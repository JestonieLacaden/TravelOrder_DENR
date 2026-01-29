<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Memorandum;
use App\Models\MemorandumTemplate;
use App\Models\MemorandumForward;
use App\Models\MemorandumRecipientPreset;
use App\Models\MemorandumComment;
use App\Models\MemorandumWorkflowHistory;
use App\Models\MemorandumNotification;
use App\Models\MemorandumAttachment;
use App\Models\User;
use App\Services\MemorandumSubjectSuggestionService;
use App\Services\MemorandumDocumentGeneratorService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class MemorandumController extends Controller
{
    protected $subjectSuggestionService;

    public function __construct(MemorandumSubjectSuggestionService $subjectSuggestionService)
    {
        $this->subjectSuggestionService = $subjectSuggestionService;
    }

    /**
     * Display a listing of memorandums
     */
    public function index()
    {
        $memorandums = Memorandum::with(['template', 'fromUser', 'creator'])
            ->where('created_by', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('memorandums.index', compact('memorandums'));
    }

    /**
     * Display memorandums received by the user (Inbox)
     */
    public function inbox()
    {
        $userId = Auth::id();

        // Get all memorandums where user is in workflow_history as a reviewer
        $memorandums = Memorandum::with(['template', 'fromUser', 'creator'])
            ->whereHas('workflowHistory', function ($query) use ($userId) {
                $query->where('to_user_id', $userId)
                    ->where('action', 'forwarded');
            })
            ->whereIn('status', ['For Review', 'Approved', 'Returned'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Get user's action status for each memorandum
        $memorandums->getCollection()->transform(function ($memo) use ($userId) {
            $userAction = MemorandumWorkflowHistory::where('memorandum_id', $memo->id)
                ->where('to_user_id', $userId)
                ->where('action', 'forwarded')
                ->first();

            // Determine user's action status
            $actionStatus = MemorandumWorkflowHistory::where('memorandum_id', $memo->id)
                ->where('from_user_id', $userId)
                ->whereIn('action', ['received', 'approved', 'returned', 'revised'])
                ->latest()
                ->first();

            $memo->user_action_status = $actionStatus ? $actionStatus->action : 'pending';
            $memo->can_edit = $userAction ? $userAction->can_edit : false;

            // Get the person who forwarded/sent this to the current user
            if ($userAction && $userAction->from_user_id) {
                $fromUser = User::with('Employee')->find($userAction->from_user_id);
                if ($fromUser) {
                    if ($fromUser->Employee) {
                        $memo->forwarded_by_name = trim($fromUser->Employee->firstname . ' ' . $fromUser->Employee->middlename . ' ' . $fromUser->Employee->lastname);
                    } else {
                        $memo->forwarded_by_name = $fromUser->username ?? 'Unknown';
                    }
                } else {
                    $memo->forwarded_by_name = 'Unknown';
                }
            } else {
                // If no from_user_id, show the creator
                if ($memo->creator && $memo->creator->Employee) {
                    $memo->forwarded_by_name = trim($memo->creator->Employee->firstname . ' ' . $memo->creator->Employee->middlename . ' ' . $memo->creator->Employee->lastname);
                } else if ($memo->creator) {
                    $memo->forwarded_by_name = $memo->creator->username ?? 'Unknown';
                } else {
                    $memo->forwarded_by_name = 'Unknown';
                }
            }

            return $memo;
        });

        return view('memorandums.inbox', compact('memorandums'));
    }

    /**
     * Show the form for creating a new memorandum
     */
    public function create()
    {
        $activeTemplate = MemorandumTemplate::getActiveTemplate();

        if (!$activeTemplate) {
            return redirect()->back()->with('error', 'No active memorandum template found. Please contact administrator.');
        }

        $templates = MemorandumTemplate::all();
        $users = User::all(); // For recipient selection
        $recipientPresets = MemorandumRecipientPreset::active()->get();

        return view('memorandums.create', compact('activeTemplate', 'templates', 'users', 'recipientPresets'));
    }

    /**
     * Store a newly created memorandum
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'template_id' => 'required|exists:memorandum_templates,id',
            'memorandum_date' => 'nullable|date',
            'transaction_number' => 'nullable|string|max:255',
            'recipient_type' => 'required|in:FOR,TO,BOTH',
            'recipient_for' => 'nullable|string',
            'recipient_to' => 'nullable|string',
            'through' => 'nullable|string|max:255',
            'attn' => 'nullable|string|max:255',
            'from_text' => 'required|string',
            'from_name' => 'nullable|string',
            'from_position' => 'nullable|string',
            'show_position' => 'boolean',
            'subject' => 'required|string|max:500',
            'body' => 'required|string',
            'use_esignature' => 'boolean',
            'signature_path' => 'nullable|string',
            'attachments.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,docx|max:10240', // 10MB max per file
        ]);

        try {
            DB::beginTransaction();

            $validated['created_by'] = Auth::id();
            $validated['from_user_id'] = Auth::id(); // Always the logged-in user
            $validated['show_position'] = $request->has('show_position');
            $validated['status'] = 'Draft';

            // Handle signature override upload
            if ($request->hasFile('signature_override')) {
                $file = $request->file('signature_override');
                $filename = 'sig_override_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('signatures/overrides', $filename, 'public');
                $validated['signature_override_path'] = $path;
            }

            // Remove attachments from validated array (not a column in memorandums table)
            unset($validated['attachments']);

            $memorandum = Memorandum::create($validated);

            // Generate memorandum number
            if (!$memorandum->memorandum_number) {
                $memorandum->memorandum_number = $memorandum->generateMemorandumNumber();
                $memorandum->save();
            }

            // Handle attachments upload
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $originalFilename = $file->getClientOriginalName();
                    $extension = strtolower($file->getClientOriginalExtension());
                    $fileSize = $file->getSize();

                    // Generate unique filename
                    $storedFilename = time() . '_' . uniqid() . '.' . $extension;
                    $storedPath = $file->storeAs('memorandums/attachments/' . $memorandum->id, $storedFilename, 'local');

                    // Save to database
                    MemorandumAttachment::create([
                        'memorandum_id' => $memorandum->id,
                        'original_filename' => $originalFilename,
                        'stored_path' => $storedPath,
                        'file_type' => $extension,
                        'file_size' => $fileSize,
                        'uploaded_by' => Auth::id(),
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('memorandums.show', $memorandum->id)
                ->with('success', 'Memorandum created successfully as Draft.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Error creating memorandum: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified memorandum
     */
    public function show($id)
    {
        $memorandum = Memorandum::with([
            'template',
            'fromUser.Employee',
            'creator.Employee',
            'currentHolder.Employee',
            'previousHolder.Employee',
            'forwards.forwardedToUser.Employee',
            'comments.user.Employee',
            'workflowHistory.fromUser.Employee',
            'workflowHistory.toUser.Employee',
            'attachments.uploader'
        ])->findOrFail($id);

        // Mark all unread notifications for this memorandum as read
        MemorandumNotification::where('user_id', Auth::id())
            ->where('memorandum_id', $id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return view('memorandums.show', compact('memorandum'));
    }

    /**
     * Show the form for editing the specified memorandum
     */
    public function edit($id)
    {
        $memorandum = Memorandum::findOrFail($id);

        // Check if user is the owner
        $isOwner = $memorandum->created_by === Auth::id();

        // Allow editing if:
        // 1. Status is Draft OR
        // 2. Status is For Review AND owner AND has reviewer comments
        $hasReviewerComments = MemorandumComment::where('memorandum_id', $memorandum->id)
            ->where('user_id', '!=', Auth::id())
            ->exists();

        $canEdit = $memorandum->status === 'Draft' ||
            ($memorandum->status === 'For Review' && $isOwner && $hasReviewerComments);

        if (!$canEdit) {
            return redirect()->route('memorandums.show', $id)
                ->with('error', 'You cannot edit this memorandum at this time.');
        }

        $templates = MemorandumTemplate::all();
        $users = User::all();
        $recipientPresets = MemorandumRecipientPreset::active()->get();

        return view('memorandums.edit', compact('memorandum', 'templates', 'users', 'recipientPresets'));
    }

    /**
     * Update the specified memorandum
     */
    public function update(Request $request, $id)
    {
        $memorandum = Memorandum::findOrFail($id);

        // Check if user is the owner
        $isOwner = $memorandum->created_by === Auth::id();

        // Allow updating if:
        // 1. Status is Draft OR
        // 2. Status is For Review AND owner AND has reviewer comments
        $hasReviewerComments = MemorandumComment::where('memorandum_id', $memorandum->id)
            ->where('user_id', '!=', Auth::id())
            ->exists();

        $canUpdate = $memorandum->status === 'Draft' ||
            ($memorandum->status === 'For Review' && $isOwner && $hasReviewerComments);

        if (!$canUpdate) {
            return redirect()->route('memorandums.show', $id)
                ->with('error', 'You cannot update this memorandum at this time.');
        }

        $validated = $request->validate([
            'template_id' => 'required|exists:memorandum_templates,id',
            'memorandum_date' => 'nullable|date',
            'transaction_number' => 'nullable|string|max:255',
            'recipient_type' => 'required|in:FOR,TO,BOTH',
            'recipient_for' => 'nullable|string',
            'recipient_to' => 'nullable|string',
            'through' => 'nullable|string|max:255',
            'attn' => 'nullable|string|max:255',
            'from_text' => 'required|string',
            'from_name' => 'nullable|string',
            'from_position' => 'nullable|string',
            'subject' => 'required|string|max:500',
            'body' => 'required|string',
            'use_esignature' => 'boolean',
            'signature_path' => 'nullable|string',
            'signature_override' => 'nullable|image|max:2048',
        ]);

        // Handle signature override upload
        if ($request->hasFile('signature_override')) {
            // Delete old signature override if exists
            if ($memorandum->signature_override_path) {
                Storage::disk('public')->delete($memorandum->signature_override_path);
            }

            $file = $request->file('signature_override');
            $filename = 'sig_override_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('signatures/overrides', $filename, 'public');
            $validated['signature_override_path'] = $path;
        }

        $memorandum->update($validated);

        return redirect()->route('memorandums.show', $id)
            ->with('success', 'Memorandum updated successfully.');
    }

    /**
     * Preview the memorandum
     */
    public function preview($id)
    {
        $memorandum = Memorandum::with(['template', 'fromUser.Employee'])->findOrFail($id);

        return view('memorandums.preview', compact('memorandum'));
    }

    /**
     * Export memorandum to PDF using DOMPDF
     */
    public function exportPdf($id)
    {
        try {
            $memorandum = Memorandum::with([
                'template',
                'fromUser.Employee',
                'creator.Employee.office'
            ])->findOrFail($id);

            $pdf = Pdf::loadView(
                'memorandums.export-pdf',
                compact('memorandum')
            )
                ->setPaper('A4', 'portrait');

            // Return base64 for JavaScript blob approach (bypass IDM)
            return response()->json([
                'pdf' => base64_encode($pdf->output()),
                'filename' => 'memorandum-' . $memorandum->memorandum_number . '.pdf'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }
    /**
     * Generate DOCX and PDF files
     */
    public function generate($id)
    {
        $memorandum = Memorandum::with(['template', 'fromUser.Employee'])->findOrFail($id);

        try {
            DB::beginTransaction();

            $generator = new MemorandumDocumentGeneratorService($memorandum);

            // Generate DOCX
            $docxPath = $generator->generateDOCX();

            // Generate PDF
            $pdfPath = $generator->generatePDF();

            // Update memorandum with file paths
            $memorandum->update([
                'docx_path' => $docxPath,
                'pdf_path' => $pdfPath,
                'status' => 'Generated',
                'generated_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('memorandums.show', $id)
                ->with('success', 'Memorandum generated successfully. Files are ready for download.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('memorandums.show', $id)
                ->with('error', 'Error generating memorandum: ' . $e->getMessage());
        }
    }

    /**
     * Forward memorandum to users
     */
    public function forward(Request $request, $id)
    {
        $memorandum = Memorandum::findOrFail($id);

        $validated = $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'message' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            foreach ($validated['user_ids'] as $userId) {
                MemorandumForward::create([
                    'memorandum_id' => $memorandum->id,
                    'forwarded_by' => Auth::id(),
                    'forwarded_to' => $userId,
                    'message' => $validated['message'] ?? null,
                ]);
            }

            // Update memorandum status
            $memorandum->update(['status' => 'Forwarded']);

            DB::commit();

            return redirect()->route('memorandums.show', $id)
                ->with('success', 'Memorandum forwarded successfully to ' . count($validated['user_ids']) . ' user(s).');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('memorandums.show', $id)
                ->with('error', 'Error forwarding memorandum: ' . $e->getMessage());
        }
    }

    /**
     * AJAX: Get subject suggestions based on body content
     */
    public function suggestSubjects(Request $request)
    {
        $body = $request->input('body', '');
        $suggestions = $this->subjectSuggestionService->suggestSubjects($body);

        return response()->json([
            'suggestions' => $suggestions,
        ]);
    }

    /**
     * Download DOCX file
     */
    public function downloadDOCX($id)
    {
        $memorandum = Memorandum::findOrFail($id);

        if (!$memorandum->docx_path || !Storage::disk('public')->exists($memorandum->docx_path)) {
            return redirect()->back()->with('error', 'DOCX file not found.');
        }

        return Storage::disk('public')->download($memorandum->docx_path);
    }

    /**
     * Download PDF file
     */
    public function downloadPDF($id)
    {
        $memorandum = Memorandum::findOrFail($id);

        if (!$memorandum->pdf_path || !Storage::disk('public')->exists($memorandum->pdf_path)) {
            return redirect()->back()->with('error', 'PDF file not found.');
        }

        return Storage::disk('public')->download($memorandum->pdf_path);
    }

    /**
     * Delete memorandum
     */
    public function destroy($id)
    {
        $memorandum = Memorandum::findOrFail($id);

        // Delete associated files
        if ($memorandum->docx_path) {
            Storage::disk('public')->delete($memorandum->docx_path);
        }
        if ($memorandum->pdf_path) {
            Storage::disk('public')->delete($memorandum->pdf_path);
        }

        $memorandum->delete();

        return redirect()->route('memorandums.index')
            ->with('success', 'Memorandum deleted successfully.');
    }

    /**
     * Submit memorandum for review
     */
    public function submitForReview(Request $request, $id)
    {
        $memorandum = Memorandum::findOrFail($id);

        // Only creator or current holder can submit
        if ($memorandum->created_by !== Auth::id() && $memorandum->current_holder_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $validated = $request->validate([
            'reviewer_ids' => 'required|array|min:1',
            'reviewer_ids.*' => 'exists:users,id',
            'can_edit_users' => 'nullable|array',
            'can_edit_users.*' => 'exists:users,id',
            'remarks' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $previousStatus = $memorandum->status;
            $canEditUsers = $validated['can_edit_users'] ?? [];

            // Update memorandum status and holders
            $memorandum->update([
                'status' => 'For Review',
                'previous_holder_id' => Auth::id(),
                'current_holder_id' => $validated['reviewer_ids'][0], // First reviewer becomes current holder
            ]);

            // Log workflow history for each reviewer
            foreach ($validated['reviewer_ids'] as $reviewerId) {
                MemorandumWorkflowHistory::create([
                    'memorandum_id' => $memorandum->id,
                    'from_user_id' => Auth::id(),
                    'to_user_id' => $reviewerId,
                    'can_edit' => in_array($reviewerId, $canEditUsers),
                    'action' => 'forwarded',
                    'previous_status' => $previousStatus,
                    'new_status' => 'For Review',
                    'remarks' => $validated['remarks'] ?? null,
                ]);

                // Also create forward record (existing system)
                MemorandumForward::create([
                    'memorandum_id' => $memorandum->id,
                    'forwarded_by' => Auth::id(),
                    'forwarded_to' => $reviewerId,
                    'message' => $validated['remarks'] ?? null,
                ]);

                // Send notification to reviewer
                MemorandumNotification::createNotification(
                    $reviewerId,
                    $memorandum->id,
                    Auth::id(),
                    'forwarded',
                    'New Memorandum for Review',
                    Auth::user()->name . ' has forwarded a memorandum for your review: "' . $memorandum->subject . '"'
                );
            }

            DB::commit();

            return redirect()->route('memorandums.show', $id)
                ->with('success', 'Memorandum submitted for review successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error submitting for review: ' . $e->getMessage());
        }
    }

    /**
     * Return memorandum with comments
     */
    public function returnWithComments(Request $request, $id)
    {
        $memorandum = Memorandum::findOrFail($id);

        // Only current holder can return
        if ($memorandum->current_holder_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Only the current reviewer can return this memorandum.');
        }

        $validated = $request->validate([
            'comments' => 'required|array|min:1',
            'comments.*.comment' => 'required|string',
            'comments.*.section' => 'nullable|string',
            'return_remarks' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $previousStatus = $memorandum->status;

            // Update memorandum status and swap holders
            $memorandum->update([
                'status' => 'Returned',
                'returned_at' => now(),
                'current_holder_id' => $memorandum->previous_holder_id, // Return to previous holder
                'previous_holder_id' => Auth::id(),
                'revision_count' => $memorandum->revision_count + 1,
            ]);

            // Save comments
            foreach ($validated['comments'] as $commentData) {
                MemorandumComment::create([
                    'memorandum_id' => $memorandum->id,
                    'user_id' => Auth::id(),
                    'comment' => $commentData['comment'],
                    'section' => $commentData['section'] ?? 'general',
                    'action_type' => 'return',
                ]);
            }

            // Log workflow history
            MemorandumWorkflowHistory::create([
                'memorandum_id' => $memorandum->id,
                'from_user_id' => Auth::id(),
                'to_user_id' => $memorandum->current_holder_id,
                'action' => 'returned',
                'previous_status' => $previousStatus,
                'new_status' => 'Returned',
                'remarks' => $validated['return_remarks'] ?? 'Returned with comments',
            ]);

            // Send notification to previous holder (who will receive the return)
            MemorandumNotification::createNotification(
                $memorandum->current_holder_id,
                $memorandum->id,
                Auth::id(),
                'returned',
                'Memorandum Returned with Comments',
                Auth::user()->name . ' has returned a memorandum with comments: "' . $memorandum->subject . '"'
            );

            DB::commit();

            return redirect()->route('memorandums.show', $id)
                ->with('success', 'Memorandum returned with comments successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error returning memorandum: ' . $e->getMessage());
        }
    }

    /**
     * Approve and forward memorandum
     */
    public function approveAndForward(Request $request, $id)
    {
        $memorandum = Memorandum::findOrFail($id);

        // Only current holder can approve
        if ($memorandum->current_holder_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Only the current reviewer can approve this memorandum.');
        }

        $validated = $request->validate([
            'next_reviewer_ids' => 'nullable|array',
            'next_reviewer_ids.*' => 'exists:users,id',
            'approval_remarks' => 'nullable|string',
            'finalize' => 'nullable|boolean', // If true, mark as final approval
        ]);

        try {
            DB::beginTransaction();

            $previousStatus = $memorandum->status;
            $isFinalApproval = $request->input('finalize', false);

            if ($isFinalApproval || empty($validated['next_reviewer_ids'])) {
                // Final approval - no more reviewers
                $memorandum->update([
                    'status' => 'Approved',
                    'approved_at' => now(),
                    'current_holder_id' => null,
                    'previous_holder_id' => Auth::id(),
                ]);

                MemorandumWorkflowHistory::create([
                    'memorandum_id' => $memorandum->id,
                    'from_user_id' => Auth::id(),
                    'to_user_id' => null,
                    'action' => 'approved',
                    'previous_status' => $previousStatus,
                    'new_status' => 'Approved',
                    'remarks' => $validated['approval_remarks'] ?? 'Final approval',
                ]);

                // Notify creator that their memorandum was approved
                MemorandumNotification::createNotification(
                    $memorandum->created_by,
                    $memorandum->id,
                    Auth::id(),
                    'approved',
                    'Memorandum Approved',
                    Auth::user()->name . ' has approved your memorandum: "' . $memorandum->subject . '"'
                );
            } else {
                // Approve and forward to next reviewer(s)
                $memorandum->update([
                    'status' => 'For Review',
                    'approved_at' => now(),
                    'previous_holder_id' => Auth::id(),
                    'current_holder_id' => $validated['next_reviewer_ids'][0],
                ]);

                // Log approval
                MemorandumWorkflowHistory::create([
                    'memorandum_id' => $memorandum->id,
                    'from_user_id' => Auth::id(),
                    'to_user_id' => null,
                    'action' => 'approved',
                    'previous_status' => $previousStatus,
                    'new_status' => 'Approved',
                    'remarks' => $validated['approval_remarks'] ?? 'Approved',
                ]);

                // Log forwards
                foreach ($validated['next_reviewer_ids'] as $nextReviewerId) {
                    MemorandumWorkflowHistory::create([
                        'memorandum_id' => $memorandum->id,
                        'from_user_id' => Auth::id(),
                        'to_user_id' => $nextReviewerId,
                        'action' => 'forwarded',
                        'previous_status' => 'Approved',
                        'new_status' => 'For Review',
                        'remarks' => 'Forwarded after approval',
                    ]);

                    MemorandumForward::create([
                        'memorandum_id' => $memorandum->id,
                        'forwarded_by' => Auth::id(),
                        'forwarded_to' => $nextReviewerId,
                        'message' => $validated['approval_remarks'] ?? null,
                    ]);

                    // Notify next reviewer
                    MemorandumNotification::createNotification(
                        $nextReviewerId,
                        $memorandum->id,
                        Auth::id(),
                        'forwarded',
                        'Memorandum for Review',
                        Auth::user()->name . ' has forwarded an approved memorandum for your review: "' . $memorandum->subject . '"'
                    );
                }

                // Also notify creator that their memorandum was approved
                MemorandumNotification::createNotification(
                    $memorandum->created_by,
                    $memorandum->id,
                    Auth::id(),
                    'approved',
                    'Memorandum Approved',
                    Auth::user()->name . ' has approved your memorandum: "' . $memorandum->subject . '"'
                );
            }

            DB::commit();

            return redirect()->route('memorandums.show', $id)
                ->with('success', $isFinalApproval ? 'Memorandum approved successfully.' : 'Memorandum approved and forwarded successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error approving memorandum: ' . $e->getMessage());
        }
    }

    /**
     * Revise memorandum after return
     */
    public function reviseAfterReturn(Request $request, $id)
    {
        $memorandum = Memorandum::findOrFail($id);

        // Only current holder can revise (should be the one who received the return)
        if ($memorandum->current_holder_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Only the assigned user can revise this memorandum.');
        }

        if ($memorandum->status !== 'Returned') {
            return redirect()->back()->with('error', 'This memorandum is not in returned status.');
        }

        $validated = $request->validate([
            'subject' => 'required|string|max:500',
            'body' => 'required|string',
            'recipient_for' => 'nullable|string',
            'recipient_to' => 'nullable|string',
            'through' => 'nullable|string|max:255',
            'attn' => 'nullable|string|max:255',
            'from_text' => 'required|string',
            'revision_notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $previousStatus = $memorandum->status;

            // Update memorandum content
            $memorandum->update([
                'subject' => $validated['subject'],
                'body' => $validated['body'],
                'recipient_for' => $validated['recipient_for'],
                'recipient_to' => $validated['recipient_to'],
                'attn' => $validated['attn'],
                'from_text' => $validated['from_text'],
                'status' => 'Draft', // Back to draft after revision
            ]);

            // Log workflow history
            MemorandumWorkflowHistory::create([
                'memorandum_id' => $memorandum->id,
                'from_user_id' => Auth::id(),
                'to_user_id' => null,
                'action' => 'revised',
                'previous_status' => $previousStatus,
                'new_status' => 'Draft',
                'remarks' => $validated['revision_notes'] ?? 'Memorandum revised based on comments',
            ]);

            DB::commit();

            return redirect()->route('memorandums.show', $id)
                ->with('success', 'Memorandum revised successfully. You can now resubmit for review.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error revising memorandum: ' . $e->getMessage());
        }
    }

    /**
     * Add comment to memorandum (general review comment)
     */
    public function addComment(Request $request, $id)
    {
        $memorandum = Memorandum::findOrFail($id);

        $validated = $request->validate([
            'comment' => 'required|string',
            'section' => 'nullable|string',
        ]);

        MemorandumComment::create([
            'memorandum_id' => $memorandum->id,
            'user_id' => Auth::id(),
            'comment' => $validated['comment'],
            'section' => $validated['section'] ?? 'general',
            'action_type' => 'review',
        ]);

        // Notify creator
        MemorandumNotification::createNotification(
            $memorandum->created_by,
            $memorandum->id,
            Auth::id(),
            'commented',
            'New Comment on Memorandum',
            Auth::user()->name . ' added a comment to your memorandum: "' . $memorandum->subject . '"'
        );

        // Notify last forwarder if different from creator
        if ($memorandum->previous_holder_id && $memorandum->previous_holder_id !== $memorandum->created_by) {
            MemorandumNotification::createNotification(
                $memorandum->previous_holder_id,
                $memorandum->id,
                Auth::id(),
                'commented',
                'New Comment on Memorandum',
                Auth::user()->name . ' added a comment to a memorandum you forwarded: "' . $memorandum->subject . '"'
            );
        }

        return redirect()->back()->with('success', 'Comment added successfully.');
    }

    /**
     * Get notifications for the current user (AJAX)
     */
    public function getNotifications(Request $request)
    {
        $notifications = MemorandumNotification::where('user_id', Auth::id())
            ->with(['memorandum', 'actor'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $unreadCount = MemorandumNotification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->count();

        return response()->json([
            'notifications' => $notifications->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'type' => $notification->type,
                    'is_read' => $notification->is_read,
                    'created_at' => $notification->created_at->diffForHumans(),
                    'memorandum_id' => $notification->memorandum_id,
                    'actor_name' => $notification->actor ? $notification->actor->name : 'System',
                ];
            }),
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * Mark a notification as read (AJAX)
     */
    public function markNotificationAsRead($id)
    {
        $notification = MemorandumNotification::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if ($notification) {
            $notification->markAsRead();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }

    /**
     * Mark all notifications as read (AJAX)
     */
    public function markAllNotificationsAsRead()
    {
        MemorandumNotification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return response()->json(['success' => true]);
    }

    /**
     * Show all notifications page
     */
    public function allNotifications()
    {
        $notifications = MemorandumNotification::where('user_id', Auth::id())
            ->with(['memorandum', 'actor'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('memorandums.notifications', compact('notifications'));
    }

    /**
     * Update edit permissions for reviewers
     */
    public function updateEditPermissions(Request $request, $id)
    {
        $memorandum = Memorandum::findOrFail($id);

        // Only creator can update permissions
        if ($memorandum->created_by !== Auth::id()) {
            return redirect()->back()->with('error', 'Only the creator can manage edit permissions.');
        }

        $validated = $request->validate([
            'edit_permissions' => 'nullable|array',
        ]);

        try {
            DB::beginTransaction();

            $permissions = $validated['edit_permissions'] ?? [];

            // Update all workflow history records for this memorandum
            foreach ($permissions as $historyId => $value) {
                MemorandumWorkflowHistory::where('id', $historyId)
                    ->where('memorandum_id', $memorandum->id)
                    ->update(['can_edit' => ($value == 1)]);
            }

            // Also set can_edit to false for those not in the list
            MemorandumWorkflowHistory::where('memorandum_id', $memorandum->id)
                ->where('action', 'forwarded')
                ->whereNotIn('id', array_keys($permissions))
                ->update(['can_edit' => false]);

            DB::commit();

            return redirect()->route('memorandums.show', $id)
                ->with('success', 'Edit permissions updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error updating permissions: ' . $e->getMessage());
        }
    }

    /**
     * Edit memorandum as reviewer (with permission)
     */
    public function editAsReviewer(Request $request, $id)
    {
        $memorandum = Memorandum::findOrFail($id);

        // Check if user has edit permission
        $hasPermission = MemorandumWorkflowHistory::where('memorandum_id', $memorandum->id)
            ->where('to_user_id', Auth::id())
            ->where('can_edit', true)
            ->where('action', 'forwarded')
            ->exists();

        if (!$hasPermission) {
            return redirect()->back()->with('error', 'You do not have permission to edit this memorandum.');
        }

        if ($memorandum->status !== 'For Review') {
            return redirect()->back()->with('error', 'This memorandum is not in review status.');
        }

        $validated = $request->validate([
            'subject' => 'required|string|max:500',
            'body' => 'required|string',
            'recipient_for' => 'nullable|string',
            'recipient_to' => 'nullable|string',
            'through' => 'nullable|string|max:255',
            'attn' => 'nullable|string|max:255',
            'from_text' => 'required|string',
            'edit_notes' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            // Store old content before updating
            $oldContent = [
                'subject' => $memorandum->subject,
                'body' => $memorandum->body,
                'recipient_for' => $memorandum->recipient_for,
                'recipient_to' => $memorandum->recipient_to,
                'through' => $memorandum->through,
                'attn' => $memorandum->attn,
                'from_text' => $memorandum->from_text,
            ];

            $memorandum->update([
                'subject' => $validated['subject'],
                'body' => $validated['body'],
                'recipient_for' => $validated['recipient_for'],
                'recipient_to' => $validated['recipient_to'],
                'through' => $validated['through'],
                'attn' => $validated['attn'],
                'from_text' => $validated['from_text'],
            ]);

            MemorandumWorkflowHistory::create([
                'memorandum_id' => $memorandum->id,
                'from_user_id' => Auth::id(),
                'to_user_id' => null,
                'action' => 'revised',
                'previous_status' => 'For Review',
                'new_status' => 'For Review',
                'remarks' => 'Edited by reviewer: ' . $validated['edit_notes'],
                'old_content' => $oldContent,
            ]);

            MemorandumNotification::createNotification(
                $memorandum->created_by,
                $memorandum->id,
                Auth::id(),
                'revised',
                'Memorandum Edited by Reviewer',
                Auth::user()->name . ' has edited your memorandum: "' . $memorandum->subject . '"'
            );

            DB::commit();

            return redirect()->route('memorandums.show', $id)
                ->with('success', 'Memorandum updated successfully. Creator has been notified.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error updating memorandum: ' . $e->getMessage());
        }
    }

    /**
     * Upload attachment to memorandum
     */
    public function uploadAttachment(Request $request, $id)
    {
        $memorandum = Memorandum::findOrFail($id);

        // Check if user is owner
        if ($memorandum->created_by !== Auth::id()) {
            return redirect()->back()->with('error', 'Only the memorandum creator can upload attachments.');
        }

        // Check status - attachments can only be added in certain statuses
        $allowedStatuses = ['Draft', 'For Review', 'Returned', 'Revised', 'Forwarded'];
        if (!in_array($memorandum->status, $allowedStatuses)) {
            return redirect()->back()->with('error', 'Attachments cannot be added to approved or finalized memorandums.');
        }

        $validated = $request->validate([
            'attachment' => 'required|file|mimes:pdf,jpg,jpeg,png,docx|max:10240', // 10MB max
        ]);

        try {
            DB::beginTransaction();

            $file = $request->file('attachment');
            $originalFilename = $file->getClientOriginalName();
            $extension = strtolower($file->getClientOriginalExtension());
            $fileSize = $file->getSize();

            // Generate unique filename
            $storedFilename = time() . '_' . uniqid() . '.' . $extension;
            $storedPath = $file->storeAs('memorandums/attachments/' . $memorandum->id, $storedFilename, 'local');

            // Save to database
            MemorandumAttachment::create([
                'memorandum_id' => $memorandum->id,
                'original_filename' => $originalFilename,
                'stored_path' => $storedPath,
                'file_type' => $extension,
                'file_size' => $fileSize,
                'uploaded_by' => Auth::id(),
            ]);

            // Notify current reviewers
            $currentReviewers = MemorandumWorkflowHistory::where('memorandum_id', $memorandum->id)
                ->where('action', 'forwarded')
                ->whereDoesntHave('memorandum.workflowHistory', function ($q) {
                    $q->where('from_user_id', Auth::id())
                        ->whereIn('action', ['approved', 'returned', 'revised']);
                })
                ->pluck('to_user_id')
                ->unique();

            foreach ($currentReviewers as $reviewerId) {
                MemorandumNotification::createNotification(
                    $reviewerId,
                    $memorandum->id,
                    Auth::id(),
                    'attachment_uploaded',
                    'New Attachment Added',
                    Auth::user()->name . ' added a new attachment to: "' . $memorandum->subject . '"'
                );
            }

            DB::commit();

            return redirect()->back()->with('success', 'Attachment uploaded successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error uploading attachment: ' . $e->getMessage());
        }
    }

    /**
     * Delete attachment
     */
    public function deleteAttachment($memorandumId, $attachmentId)
    {
        $memorandum = Memorandum::findOrFail($memorandumId);
        $attachment = MemorandumAttachment::findOrFail($attachmentId);

        // Check if user is owner
        if ($memorandum->created_by !== Auth::id()) {
            return redirect()->back()->with('error', 'Only the memorandum creator can delete attachments.');
        }

        // Check status
        $allowedStatuses = ['Draft', 'For Review', 'Returned', 'Revised', 'Forwarded'];
        if (!in_array($memorandum->status, $allowedStatuses)) {
            return redirect()->back()->with('error', 'Attachments cannot be removed from approved or finalized memorandums.');
        }

        try {
            DB::beginTransaction();

            // Delete file from storage
            if (Storage::disk('local')->exists($attachment->stored_path)) {
                Storage::disk('local')->delete($attachment->stored_path);
            }

            // Soft delete from database
            $attachment->delete();

            // Notify current reviewers
            $currentReviewers = MemorandumWorkflowHistory::where('memorandum_id', $memorandum->id)
                ->where('action', 'forwarded')
                ->whereDoesntHave('memorandum.workflowHistory', function ($q) {
                    $q->where('from_user_id', Auth::id())
                        ->whereIn('action', ['approved', 'returned', 'revised']);
                })
                ->pluck('to_user_id')
                ->unique();

            foreach ($currentReviewers as $reviewerId) {
                MemorandumNotification::createNotification(
                    $reviewerId,
                    $memorandum->id,
                    Auth::id(),
                    'attachment_removed',
                    'Attachment Removed',
                    Auth::user()->name . ' removed an attachment from: "' . $memorandum->subject . '"'
                );
            }

            DB::commit();

            return redirect()->back()->with('success', 'Attachment deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error deleting attachment: ' . $e->getMessage());
        }
    }

    /**
     * Download attachment
     */
    public function downloadAttachment($memorandumId, $attachmentId)
    {
        $memorandum = Memorandum::findOrFail($memorandumId);
        $attachment = MemorandumAttachment::findOrFail($attachmentId);

        // Check if user has access (owner or reviewer)
        $isOwner = $memorandum->created_by === Auth::id();
        $isReviewer = MemorandumWorkflowHistory::where('memorandum_id', $memorandum->id)
            ->where('to_user_id', Auth::id())
            ->exists();

        if (!$isOwner && !$isReviewer) {
            abort(403, 'Unauthorized access to attachment.');
        }

        if (!Storage::disk('local')->exists($attachment->stored_path)) {
            abort(404, 'Attachment file not found.');
        }

        return Storage::disk('local')->download($attachment->stored_path, $attachment->original_filename);
    }

    /**
     * Preview attachment (for PDF and images)
     */
    public function previewAttachment($memorandumId, $attachmentId)
    {
        $memorandum = Memorandum::findOrFail($memorandumId);
        $attachment = MemorandumAttachment::findOrFail($attachmentId);

        // Check if user has access
        $isOwner = $memorandum->created_by === Auth::id();
        $isReviewer = MemorandumWorkflowHistory::where('memorandum_id', $memorandum->id)
            ->where('to_user_id', Auth::id())
            ->exists();

        if (!$isOwner && !$isReviewer) {
            abort(403, 'Unauthorized access to attachment.');
        }

        if (!$attachment->isPreviewable()) {
            return redirect()->back()->with('error', 'This file type cannot be previewed. Please download it instead.');
        }

        if (!Storage::disk('local')->exists($attachment->stored_path)) {
            abort(404, 'Attachment file not found.');
        }

        $filePath = Storage::disk('local')->path($attachment->stored_path);
        $mimeType = mime_content_type($filePath);

        return response()->file($filePath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $attachment->original_filename . '"',
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'public, must-revalidate, max-age=0',
        ]);
    }
}
