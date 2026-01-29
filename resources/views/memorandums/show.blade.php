@extends('layouts.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Memorandum Details</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('memorandums.index') }}">Memorandum</a></li>
                        <li class="breadcrumb-item active">{{ $memorandum->memorandum_number }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ $memorandum->memorandum_number }}</h3>
                            <div class="card-tools">
                                @php
                                $backUrl = session('memorandum_back_url', route('memorandums.index'));
                                // If coming from inbox
                                if (request()->query('from') === 'inbox' || str_contains(url()->previous(), '/inbox')) {
                                $backUrl = route('memorandums.inbox');
                                }
                                @endphp
                                <a href="{{ $backUrl }}" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-arrow-left"></i> Back to List
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert">
                                    <span>&times;</span>
                                </button>
                            </div>
                            @endif

                            @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="close" data-dismiss="alert">
                                    <span>&times;</span>
                                </button>
                            </div>
                            @endif

                            <!-- Status Badge -->
                            <div class="mb-3">
                                <strong>Status:</strong>
                                @if($memorandum->status === 'Draft')
                                <span class="badge badge-secondary badge-lg">Draft</span>
                                @elseif($memorandum->status === 'Previewed')
                                <span class="badge badge-info badge-lg">Previewed</span>
                                @elseif($memorandum->status === 'For Review')
                                <span class="badge badge-warning badge-lg">For Review</span>
                                @elseif($memorandum->status === 'Returned')
                                <span class="badge badge-danger badge-lg">Returned</span>
                                @elseif($memorandum->status === 'Approved')
                                <span class="badge badge-success badge-lg">Approved</span>
                                @elseif($memorandum->status === 'Generated')
                                <span class="badge badge-success badge-lg">Generated</span>
                                @elseif($memorandum->status === 'Forwarded')
                                <span class="badge badge-primary badge-lg">Forwarded</span>
                                @elseif($memorandum->status === 'Signed')
                                <span class="badge badge-dark badge-lg">Signed</span>
                                @endif
                            </div>

                            <!-- Memorandum Info -->
                            <table class="table table-bordered">
                                <tr>
                                    <th width="200">Memorandum Number</th>
                                    <td>{{ $memorandum->memorandum_number }}</td>
                                </tr>
                                <tr>
                                    <th>Date</th>
                                    <td>{{ $memorandum->memorandum_date ? $memorandum->memorandum_date->format('F d, Y') : '(No date specified)' }}</td>
                                </tr>
                                <tr>
                                    <th>Template</th>
                                    <td>{{ $memorandum->template->name }} (v{{ $memorandum->template->version }})</td>
                                </tr>
                                <tr>
                                    <th>Recipient Type</th>
                                    <td><span class="badge badge-info">{{ $memorandum->recipient_type }}</span></td>
                                </tr>
                                @if(in_array($memorandum->recipient_type, ['FOR', 'BOTH']))
                                <tr>
                                    <th>FOR</th>
                                    <td>{{ is_array($memorandum->recipient_for) ? implode(', ', $memorandum->recipient_for) : $memorandum->recipient_for }}</td>
                                </tr>
                                @endif
                                @if(in_array($memorandum->recipient_type, ['TO', 'BOTH']))
                                <tr>
                                    <th>TO</th>
                                    <td>{{ is_array($memorandum->recipient_to) ? implode(', ', $memorandum->recipient_to) : $memorandum->recipient_to }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <th>FROM</th>
                                    <td>{{ $memorandum->from_text }}</td>
                                </tr>
                                <tr>
                                    <th>SUBJECT</th>
                                    <td><strong>{{ strtoupper($memorandum->subject) }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Body</th>
                                    <td>
                                        @foreach($memorandum->getParagraphsArray() as $paragraph)
                                        <p style="text-align: justify; text-indent: 36px;">{{ $paragraph }}</p>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>E-Signature</th>
                                    <td>{{ $memorandum->use_esignature ? 'Yes' : 'No (Wet signature)' }}</td>
                                </tr>
                                <tr>
                                    <th>Created By</th>
                                    <td>
                                        @if($memorandum->creator && $memorandum->creator->Employee)
                                        {{ $memorandum->creator->Employee->fullname }}
                                        @else
                                        {{ $memorandum->creator->username ?? 'N/A' }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Created At</th>
                                    <td>{{ $memorandum->created_at->format('F d, Y h:i A') }}</td>
                                </tr>
                                @if($memorandum->generated_at)
                                <tr>
                                    <th>Generated At</th>
                                    <td>{{ $memorandum->generated_at->format('F d, Y h:i A') }}</td>
                                </tr>
                                @endif
                            </table>

                            @php
                            // Define variables at the top for use throughout the view
                            $isOwner = $memorandum->created_by === Auth::id();
                            $isCurrentHolder = $memorandum->current_holder_id === Auth::id();

                            // Check reviewer status using fresh DB query
                            $isReviewer = \App\Models\MemorandumWorkflowHistory::where('memorandum_id', $memorandum->id)
                            ->where('to_user_id', Auth::id())
                            ->where('action', 'forwarded')
                            ->exists();

                            // Use fresh query to get latest reviewer action
                            $reviewerAction = \App\Models\MemorandumWorkflowHistory::where('memorandum_id', $memorandum->id)
                            ->where('from_user_id', Auth::id())
                            ->whereIn('action', ['received', 'approved', 'returned', 'revised'])
                            ->latest('created_at')
                            ->first();

                            // Check if there's a new forward to this reviewer after their last action
                            $latestForwardToReviewer = \App\Models\MemorandumWorkflowHistory::where('memorandum_id', $memorandum->id)
                            ->where('to_user_id', Auth::id())
                            ->where('action', 'forwarded')
                            ->latest('created_at')
                            ->first();

                            // Reviewer has NOT completed if:
                            // 1. No action yet, OR
                            // 2. There's a new forward after their last action
                            $reviewerHasCompleted = $reviewerAction !== null &&
                            (!$latestForwardToReviewer || $reviewerAction->created_at > $latestForwardToReviewer->created_at);

                            $hasPendingAction = $isReviewer && !$reviewerHasCompleted && $memorandum->status === 'For Review';

                            // Filtered history for reviewers
                            $filteredHistory = $isOwner
                            ? $memorandum->workflowHistory
                            : $memorandum->workflowHistory->filter(function ($history) {
                            return $history->to_user_id === Auth::id() || $history->from_user_id === Auth::id();
                            })->values();

                            // Check edit permission - use the LATEST forward only
                            $userHasEditPermission = $latestForwardToReviewer && $latestForwardToReviewer->can_edit;
                            @endphp

                            <!-- Action Buttons -->
                            <div class="mt-3">
                                @if($memorandum->status === 'Draft' && $isOwner)
                                <a href="{{ route('memorandums.edit', $memorandum->id) }}" class="btn btn-warning">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                @endif

                                @if($memorandum->status === 'Returned' && $memorandum->current_holder_id === Auth::id())
                                <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#reviseModal">
                                    <i class="fas fa-edit"></i> Revise Memorandum
                                </button>
                                @endif

                                @php
                                // Check if creator has comments from reviewers
                                $hasReviewerComments = $isOwner && $memorandum->status === 'For Review' &&
                                \App\Models\MemorandumComment::where('memorandum_id', $memorandum->id)
                                ->where('user_id', '!=', Auth::id())
                                ->exists();
                                @endphp

                                @if($memorandum->status === 'For Review' && $isOwner && $hasReviewerComments)
                                <a href="{{ route('memorandums.edit', $memorandum->id) }}" class="btn btn-warning">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                @endif

                                @if($memorandum->status === 'For Review' && $userHasEditPermission && $hasPendingAction)
                                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#editAsReviewerModal">
                                    <i class="fas fa-edit"></i> Edit Memorandum
                                </button>
                                @endif

                                <a href="{{ route('memorandums.preview', $memorandum->id) }}" class="btn btn-info" target="_blank">
                                    <i class="fas fa-eye"></i> Preview
                                </a>

                                @if(in_array($memorandum->status, ['Draft', 'Previewed', 'Returned']) && ($memorandum->created_by === Auth::id() || $memorandum->current_holder_id === Auth::id()))
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#submitReviewModal">
                                    <i class="fas fa-paper-plane"></i> Submit for Review
                                </button>
                                @endif

                                @if($memorandum->status === 'For Review' && $isCurrentHolder && !$isOwner)
                                <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#returnModal">
                                    <i class="fas fa-undo"></i> Return with Comments
                                </button>
                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#approveModal">
                                    <i class="fas fa-check-circle"></i> Approve & Forward
                                </button>
                                @endif

                                @if($memorandum->docx_path)
                                <a href="{{ route('memorandums.download-docx', $memorandum->id) }}" class="btn btn-primary">
                                    <i class="fas fa-download"></i> Download DOCX
                                </a>
                                @endif

                                @if($memorandum->pdf_path)
                                <a href="{{ route('memorandums.download-pdf', $memorandum->id) }}" class="btn btn-primary">
                                    <i class="fas fa-download"></i> Download PDF
                                </a>
                                @endif

                                @if($memorandum->created_by === Auth::id() && $memorandum->status === 'For Review')
                                <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#managePermissionsModal">
                                    <i class="fas fa-user-lock"></i> Manage Edit Permissions
                                </button>
                                @endif

                                @if($isOwner || (!$reviewerHasCompleted && $isReviewer))
                                <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#commentModal">
                                    <i class="fas fa-comment"></i> Add Comment
                                </button>
                                @endif

                                @if($isOwner)
                                <form action="{{ route('memorandums.destroy', $memorandum->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this memorandum?')">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                                @endif
                            </div>

                            <!-- Workflow Information (only show for owner) -->
                            @if($isOwner && $memorandum->revision_count > 0)
                            <div class="mt-4 alert alert-info">
                                <h6><i class="fas fa-info-circle"></i> Workflow Status</h6>
                                <p class="mb-0"><strong>Revision Count:</strong> {{ $memorandum->revision_count }}</p>
                            </div>
                            @endif

                            <!-- Attachments Section -->
                            <div class="mt-4">
                                <h5><i class="fas fa-paperclip"></i> Attachments</h5>

                                @php
                                $allowedStatuses = ['Draft', 'For Review', 'Returned', 'Revised', 'Forwarded'];
                                $canManageAttachments = $isOwner && in_array($memorandum->status, $allowedStatuses);
                                @endphp

                                @if($canManageAttachments)
                                <div class="card mb-3">
                                    <div class="card-header bg-light">
                                        <strong>Add New Attachment</strong>
                                    </div>
                                    <div class="card-body">
                                        <form action="{{ route('memorandums.attachments.upload', $memorandum->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-row">
                                                <div class="col-md-9">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" id="attachment" name="attachment" accept=".pdf,.jpg,.jpeg,.png,.docx" required>
                                                        <label class="custom-file-label" for="attachment">Choose file...</label>
                                                    </div>
                                                    <small class="form-text text-muted">PDF, Images, DOCX (Max 10MB)</small>
                                                </div>
                                                <div class="col-md-3">
                                                    <button type="submit" class="btn btn-primary btn-block">
                                                        <i class="fas fa-upload"></i> Upload
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                @endif

                                @if($memorandum->attachments->count() > 0)
                                <div class="list-group">
                                    @foreach($memorandum->attachments as $attachment)
                                    <div class="list-group-item">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="flex-grow-1">
                                                <i class="{{ $attachment->file_icon }}"></i>
                                                <strong>{{ $attachment->original_filename }}</strong>
                                                <br>
                                                <small class="text-muted">
                                                    {{ $attachment->formatted_file_size }} •
                                                    Uploaded by {{ $attachment->uploader->name ?? 'Unknown' }} •
                                                    {{ $attachment->created_at->format('M d, Y h:i A') }}
                                                </small>
                                            </div>
                                            <div class="btn-group">
                                                @if($attachment->isPreviewable())
                                                <a href="{{ route('memorandums.attachments.preview', [$memorandum->id, $attachment->id]) }}" class="btn btn-sm btn-info" target="_blank" title="Preview">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @endif
                                                <a href="{{ route('memorandums.attachments.download', [$memorandum->id, $attachment->id]) }}" class="btn btn-sm btn-success" title="Download">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                                @if($canManageAttachments)
                                                <form action="{{ route('memorandums.attachments.delete', [$memorandum->id, $attachment->id]) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this attachment?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @else
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> No attachments yet.
                                </div>
                                @endif

                                @if(!$canManageAttachments && $memorandum->attachments->count() > 0)
                                <small class="text-muted d-block mt-2">
                                    <i class="fas fa-lock"></i> Attachments are read-only for this memorandum.
                                </small>
                                @endif
                            </div>

                            <!-- Comments Section -->
                            @if($memorandum->comments->count() > 0)
                            <div class="mt-4">
                                <h5><i class="fas fa-comments"></i> Review Comments</h5>
                                @foreach($memorandum->comments as $comment)
                                <div class="card mb-2 {{ $comment->action_type === 'return' ? 'border-warning' : '' }}">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <strong>
                                                    @if($comment->user && $comment->user->Employee)
                                                    {{ $comment->user->Employee->fullname }}
                                                    @else
                                                    {{ $comment->user->username ?? 'Unknown' }}
                                                    @endif
                                                </strong>
                                                @if($comment->action_type === 'return')
                                                <span class="badge badge-warning ml-2">Returned</span>
                                                @endif
                                                <span class="badge badge-secondary ml-1">{{ ucfirst($comment->section) }}</span>
                                            </div>
                                            <small class="text-muted">{{ $comment->created_at->format('M d, Y h:i A') }}</small>
                                        </div>
                                        <p class="mt-2 mb-0">{{ $comment->comment }}</p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @endif

                            <!-- Workflow History -->
                            @if($filteredHistory->count() > 0)
                            <div class="mt-4">
                                <h5><i class="fas fa-history"></i> Workflow History</h5>
                                <div class="timeline">
                                    @foreach($filteredHistory as $history)
                                    <div class="mb-3 pb-3" style="border-bottom: 1px solid #dee2e6;">
                                        <div class="d-flex align-items-start">
                                            <div class="mr-3" style="min-width: 25px;">
                                                @if($history->action === 'forwarded')
                                                <i class="fas fa-arrow-right text-primary"></i>
                                                @elseif($history->action === 'returned')
                                                <i class="fas fa-undo text-warning"></i>
                                                @elseif($history->action === 'approved')
                                                <i class="fas fa-check text-success"></i>
                                                @elseif($history->action === 'received')
                                                <i class="fas fa-inbox text-info"></i>
                                                @elseif($history->action === 'revised')
                                                <i class="fas fa-edit text-info"></i>
                                                @else
                                                <i class="fas fa-circle text-secondary"></i>
                                                @endif
                                            </div>
                                            <div class="flex-grow-1" style="min-width: 0;">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <strong style="font-size: 14px;">{{ ucfirst($history->action) }}</strong>
                                                    <small class="text-muted" style="white-space: nowrap;">{{ $history->created_at->format('M d, Y h:i A') }}</small>
                                                </div>
                                                <p class="mb-1" style="line-height: 1.5;">
                                                    <strong>By:</strong>
                                                    @if($history->fromUser && $history->fromUser->Employee)
                                                    {{ $history->fromUser->Employee->fullname }}
                                                    @else
                                                    {{ $history->fromUser->username ?? 'Unknown' }}
                                                    @endif
                                                </p>
                                                @if($history->to_user_id)
                                                <p class="mb-1" style="line-height: 1.5;">
                                                    <strong>To:</strong>
                                                    @if($history->toUser && $history->toUser->Employee)
                                                    {{ $history->toUser->Employee->fullname }}
                                                    @else
                                                    {{ $history->toUser->username ?? 'Unknown' }}
                                                    @endif
                                                    @if($history->action === 'forwarded')
                                                    @if($history->can_edit)
                                                    <span class="badge badge-success ml-1" style="font-size: 10px;">Can Edit</span>
                                                    @else
                                                    <span class="badge badge-secondary ml-1" style="font-size: 10px;">View Only</span>
                                                    @endif
                                                    @endif
                                                </p>
                                                @endif
                                                <p class="mb-1"><small class="text-muted">{{ $history->previous_status }} → {{ $history->new_status }}</small></p>
                                                @if($history->remarks)
                                                <p class="mb-0 text-muted" style="font-style: italic; font-size: 13px;">{{ $history->remarks }}</p>
                                                @endif
                                                @if($history->action === 'revised' && $history->old_content)
                                                <button type="button" class="btn btn-sm btn-outline-info mt-2" data-toggle="modal" data-target="#changesModal{{ $history->id }}">
                                                    <i class="fas fa-eye"></i> View Changes
                                                </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <!-- Change Tracking Modals -->
                            @if(isset($filteredHistory))
                            @foreach($filteredHistory as $history)
                            @if($history->action === 'revised' && $history->old_content)
                            @php
                            $oldContent = $history->old_content;
                            @endphp
                            <div class="modal fade" id="changesModal{{ $history->id }}" tabindex="-1">
                                <div class="modal-dialog modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header bg-info">
                                            <h5 class="modal-title">
                                                <i class="fas fa-history"></i> Changes Made by {{ $history->byUser->employee->firstname ?? 'Unknown' }} {{ $history->byUser->employee->lastname ?? '' }}
                                            </h5>
                                            <button type="button" class="close text-white" data-dismiss="modal">
                                                <span>&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h6 class="text-danger"><i class="fas fa-minus-circle"></i> Before</h6>
                                                    <hr>

                                                    @php
                                                    $hasChanges = false;
                                                    $fields = [
                                                    'subject' => 'Subject',
                                                    'body' => 'Body',
                                                    'recipient_for' => 'For',
                                                    'recipient_to' => 'To',
                                                    'through' => 'Through',
                                                    'attn' => 'Attn',
                                                    'from_text' => 'From'
                                                    ];
                                                    @endphp

                                                    @foreach($fields as $field => $label)
                                                    @php
                                                    $oldValue = $oldContent[$field] ?? '';
                                                    $currentValue = $memorandum->$field ?? '';
                                                    $isChanged = $oldValue !== $currentValue;
                                                    if($isChanged) $hasChanges = true;
                                                    @endphp

                                                    <div class="mb-3 @if($isChanged) border border-danger rounded p-2 bg-light @endif">
                                                        <strong>{{ $label }}:</strong>
                                                        <div class="mt-1" style="white-space: pre-wrap;">{{ $oldValue ?: '(empty)' }}</div>
                                                    </div>
                                                    @endforeach
                                                </div>

                                                <div class="col-md-6">
                                                    <h6 class="text-success"><i class="fas fa-plus-circle"></i> After</h6>
                                                    <hr>

                                                    @foreach($fields as $field => $label)
                                                    @php
                                                    $oldValue = $oldContent[$field] ?? '';
                                                    $currentValue = $memorandum->$field ?? '';
                                                    $isChanged = $oldValue !== $currentValue;
                                                    @endphp

                                                    <div class="mb-3 @if($isChanged) border border-success rounded p-2 bg-light @endif">
                                                        <strong>{{ $label }}:</strong>
                                                        <div class="mt-1" style="white-space: pre-wrap;">{{ $currentValue ?: '(empty)' }}</div>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>

                                            @if(!$hasChanges)
                                            <div class="alert alert-info">
                                                <i class="fas fa-info-circle"></i> No changes detected between versions.
                                            </div>
                                            @endif
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Manage Edit Permissions Modal -->
        <div class="modal fade" id="managePermissionsModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form action="{{ route('memorandums.update-edit-permissions', $memorandum->id) }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Manage Edit Permissions</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p class="text-muted">Select which reviewers can edit the memorandum form:</p>

                            @php
                            $reviewers = $memorandum->workflowHistory->where('action', 'forwarded')->where('to_user_id', '!=', null);
                            @endphp

                            @if($reviewers->count() > 0)
                            <div class="list-group">
                                @foreach($reviewers->unique('to_user_id') as $history)
                                @if($history->toUser)
                                <div class="list-group-item">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="perm_{{ $history->id }}" name="edit_permissions[{{ $history->id }}]" value="1" {{ $history->can_edit ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="perm_{{ $history->id }}">
                                            <strong>
                                                @if($history->toUser->Employee)
                                                {{ $history->toUser->Employee->fullname }}
                                                @else
                                                {{ $history->toUser->username }}
                                                @endif
                                            </strong>
                                            <br>
                                            <small class="text-muted">{{ $history->toUser->email }}</small>
                                            @if($history->can_edit)
                                            <span class="badge badge-success ml-2">Can Edit</span>
                                            @else
                                            <span class="badge badge-secondary ml-2">View Only</span>
                                            @endif
                                        </label>
                                    </div>
                                </div>
                                @endif
                                @endforeach
                            </div>
                            @else
                            <p class="text-center text-muted">No reviewers have been assigned yet.</p>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Permissions
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Submit for Review Modal -->
        <div class="modal fade" id="submitReviewModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('memorandums.submit-for-review', $memorandum->id) }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Submit for Review</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Select Reviewers <span class="text-danger">*</span></label>
                                <select name="reviewer_ids[]" id="reviewerSelect" class="form-control" multiple="multiple" required style="height: 150px;">
                                    @foreach(\App\Models\User::where('id', '!=', Auth::id())->get() as $user)
                                    <option value="{{ $user->id }}">
                                        @if($user->Employee)
                                        {{ $user->Employee->fullname }} ({{ $user->email }})
                                        @else
                                        {{ $user->username }} ({{ $user->email }})
                                        @endif
                                    </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">(Hold Ctrl to select multiple)</small>
                            </div>

                            <div class="form-group">
                                <label><strong>Edit Permissions</strong></label>
                                <small class="text-muted d-block mb-2">Select which reviewers can edit the memorandum form:</small>
                                <div id="editPermissionsContainer" class="p-3 border rounded" style="display: none; background-color: #f9f9f9; max-height: 200px; overflow-y: auto;">
                                    <!-- Checkboxes will appear here -->
                                </div>
                                <small class="text-muted" id="noReviewersMsg"><em>Select reviewers above first...</em></small>
                            </div>

                            <div class="form-group">
                                <label>Remarks (Optional)</label>
                                <textarea name="remarks" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i> Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Return with Comments Modal -->
        <div class="modal fade" id="returnModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form action="{{ route('memorandums.return-with-comments', $memorandum->id) }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Return with Comments</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div id="commentsContainer">
                                <div class="comment-item mb-3 p-3 border rounded">
                                    <div class="form-group">
                                        <label>Section</label>
                                        <select name="comments[0][section]" class="form-control">
                                            <option value="general">General</option>
                                            <option value="subject">Subject</option>
                                            <option value="body">Body</option>
                                            <option value="recipients">Recipients</option>
                                            <option value="formatting">Formatting</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Comment <span class="text-danger">*</span></label>
                                        <textarea name="comments[0][comment]" class="form-control" rows="3" required></textarea>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-secondary" id="addCommentBtn">
                                <i class="fas fa-plus"></i> Add Another Comment
                            </button>
                            <div class="form-group mt-3">
                                <label>Overall Remarks</label>
                                <textarea name="return_remarks" class="form-control" rows="2"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-undo"></i> Return to Sender
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Approve and Forward Modal -->
        <div class="modal fade" id="approveModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('memorandums.approve-and-forward', $memorandum->id) }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Approve & Forward</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="finalize" name="finalize" value="1">
                                    <label class="custom-control-label" for="finalize">
                                        <strong>Final Approval</strong> (No more reviewers)
                                    </label>
                                </div>
                            </div>
                            <div class="form-group" id="nextReviewersGroup">
                                <label>Forward to Next Reviewers</label>
                                <select name="next_reviewer_ids[]" class="form-control select2-approve" multiple>
                                    @foreach(\App\Models\User::where('id', '!=', Auth::id())->get() as $user)
                                    <option value="{{ $user->id }}">
                                        @if($user->Employee)
                                        {{ $user->Employee->fullname }} ({{ $user->email }})
                                        @else
                                        {{ $user->username }} ({{ $user->email }})
                                        @endif
                                    </option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Leave empty if this is final approval</small>
                            </div>
                            <div class="form-group">
                                <label>Approval Remarks</label>
                                <textarea name="approval_remarks" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-check-circle"></i> Approve
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Revise Memorandum Modal -->
        <div class="modal fade" id="reviseModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form action="{{ route('memorandums.revise-after-return', $memorandum->id) }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Revise Memorandum</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Subject <span class="text-danger">*</span></label>
                                <input type="text" name="subject" class="form-control" value="{{ $memorandum->subject }}" required>
                            </div>
                            <div class="form-group">
                                <label>Body <span class="text-danger">*</span></label>
                                <textarea name="body" class="form-control" rows="8" required>{{ $memorandum->body }}</textarea>
                            </div>
                            <div class="form-group">
                                <label>Recipients FOR</label>
                                <textarea name="recipient_for" class="form-control" rows="3">{{ $memorandum->recipient_for }}</textarea>
                            </div>
                            <div class="form-group">
                                <label>Recipients TO</label>
                                <textarea name="recipient_to" class="form-control" rows="3">{{ $memorandum->recipient_to }}</textarea>
                            </div>
                            <div class="form-group">
                                <label>ATTN</label>
                                <input type="text" name="attn" class="form-control" value="{{ $memorandum->attn }}">
                            </div>
                            <div class="form-group">
                                <label>FROM <span class="text-danger">*</span></label>
                                <input type="text" name="from_text" class="form-control" value="{{ $memorandum->from_text }}" required>
                            </div>
                            <div class="form-group">
                                <label>Revision Notes</label>
                                <textarea name="revision_notes" class="form-control" rows="2" placeholder="Describe what was changed..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save"></i> Save Revisions
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit As Reviewer Modal -->
        <div class="modal fade" id="editAsReviewerModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form action="{{ route('memorandums.edit-as-reviewer', $memorandum->id) }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Memorandum (Reviewer)</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> You have permission to edit this memorandum. Changes will be saved and the creator will be notified.
                            </div>
                            <div class="form-group">
                                <label>Subject <span class="text-danger">*</span></label>
                                <input type="text" name="subject" class="form-control" value="{{ $memorandum->subject }}" required>
                            </div>
                            <div class="form-group">
                                <label>Body <span class="text-danger">*</span></label>
                                <textarea name="body" class="form-control" rows="8" required>{{ $memorandum->body }}</textarea>
                            </div>
                            <div class="form-group">
                                <label>Recipients FOR</label>
                                <textarea name="recipient_for" class="form-control" rows="3">{{ $memorandum->recipient_for }}</textarea>
                            </div>
                            <div class="form-group">
                                <label>Recipients TO</label>
                                <textarea name="recipient_to" class="form-control" rows="3">{{ $memorandum->recipient_to }}</textarea>
                            </div>
                            <div class="form-group">
                                <label>THROUGH</label>
                                <input type="text" name="through" class="form-control" value="{{ $memorandum->through }}">
                            </div>
                            <div class="form-group">
                                <label>ATTN</label>
                                <input type="text" name="attn" class="form-control" value="{{ $memorandum->attn }}">
                            </div>
                            <div class="form-group">
                                <label>FROM <span class="text-danger">*</span></label>
                                <input type="text" name="from_text" class="form-control" value="{{ $memorandum->from_text }}" required>
                            </div>
                            <div class="form-group">
                                <label>Edit Notes <span class="text-danger">*</span></label>
                                <textarea name="edit_notes" class="form-control" rows="2" placeholder="Describe what you changed..." required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Add Comment Modal -->
        <div class="modal fade" id="commentModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('memorandums.add-comment', $memorandum->id) }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Add Comment</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Section</label>
                                <select name="section" class="form-control">
                                    <option value="general">General</option>
                                    <option value="subject">Subject</option>
                                    <option value="body">Body</option>
                                    <option value="recipients">Recipients</option>
                                    <option value="formatting">Formatting</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Comment <span class="text-danger">*</span></label>
                                <textarea name="comment" class="form-control" rows="4" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-comment"></i> Add Comment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

</div>
</div>
</div><!-- /.container-fluid -->
</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Update Edit Permissions checkboxes when reviewers are selected
        $('#reviewerSelect').on('change', function() {
            const selectedIds = $(this).val() || [];
            const container = $('#editPermissionsContainer');
            const noMsg = $('#noReviewersMsg');

            if (selectedIds.length === 0) {
                container.hide();
                noMsg.show();
                return;
            }

            container.show();
            noMsg.hide();

            // Build checkboxes for selected reviewers
            let html = '';
            selectedIds.forEach((userId) => {
                const option = $('#reviewerSelect option[value="' + userId + '"]');
                const userName = option.text();

                html += '<div class="custom-control custom-checkbox mb-2">' +
                    '<input type="checkbox" class="custom-control-input" id="edit_' + userId + '" name="can_edit_users[]" value="' + userId + '">' +
                    '<label class="custom-control-label" for="edit_' + userId + '">' +
                    '<small>' + userName + '</small>' +
                    '</label></div>';
            });

            container.html(html);
        });

        // Add comment functionality
        let commentIndex = 1;
        $('#addCommentBtn').click(function() {
            const commentHtml = '<div class="comment-item mb-3 p-3 border rounded">' +
                '<div class="form-group">' +
                '<label>Section</label>' +
                '<select name="comments[' + commentIndex + '][section]" class="form-control">' +
                '<option value="general">General</option>' +
                '<option value="subject">Subject</option>' +
                '<option value="body">Body</option>' +
                '<option value="recipients">Recipients</option>' +
                '<option value="formatting">Formatting</option>' +
                '</select></div>' +
                '<div class="form-group">' +
                '<label>Comment <span class="text-danger">*</span></label>' +
                '<textarea name="comments[' + commentIndex + '][comment]" class="form-control" rows="3" required></textarea>' +
                '</div>' +
                '<button type="button" class="btn btn-sm btn-danger remove-comment">' +
                '<i class="fas fa-times"></i> Remove</button></div>';
            $('#commentsContainer').append(commentHtml);
            commentIndex++;
        });

        // Remove comment
        $(document).on('click', '.remove-comment', function() {
            $(this).closest('.comment-item').remove();
        });

        // Toggle next reviewers based on finalize checkbox
        $('#finalize').change(function() {
            if ($(this).is(':checked')) {
                $('#nextReviewersGroup').hide();
            } else {
                $('#nextReviewersGroup').show();
            }
        });

        // Update attachment file input label
        $('#attachment').on('change', function() {
            var fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').html(fileName || 'Choose file...');
        });
    });

</script>
@endpush
