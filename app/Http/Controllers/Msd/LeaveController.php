<?php

namespace App\Http\Controllers\Msd;

use Throwable;
use App\Models\Leave;
use App\Models\Employee;
use App\Models\UserRole;
use App\Models\Leave_Type;
use Illuminate\Http\Request;
use App\Models\LeaveSignatory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use App\Models\SetLeaveSignatory;
use App\Http\Controllers\Controller;
use App\Models\SetTravelOrderSignatory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class LeaveController extends Controller
{
  public function index()
  {
    try {

      $this->authorize('MSDaddLeave', \App\Models\Leave::class);

      $Employees = Employee::where('empstatus', '=', 'PERMANENT')->orderBy('lastname', 'asc')->get();
      $Leave_Types = Leave_Type::orderBy('id', 'asc')->get();
      $Leaves = Leave::with('Employee')->with('Leave_type')->orderBy('created_at', 'asc')->get();


      // $Events = Event::orderBy('date', 'asc')->with('User')->get();
      return view('msd-panel.leave.index', compact('Employees', 'Leave_Types', 'Leaves'));
    } catch (Throwable $e) {
      report($e);

      return false;
    }
  }



  public function store(Request $request)
  {

    $this->authorize('create', \App\Models\Leave::class);

    $base = $request->validate([
      'employeeid' => 'required|exists:employee,id',
      'leaveid' => 'required|exists:leave_type,id',
      'daterange' => 'required',
    ]);

    $leaveType = Leave_Type::findOrFail($base['leaveid']);
    $typeText  = Str::lower(trim($leaveType->leave_type ?? ''));

    $data = $base['daterange'];
    list($startDate, $endDate) = explode(" -", $data);
    $date1 = Carbon::createFromDate($startDate)->format('Y');
    $date2 = Carbon::createFromDate($endDate)->format('Y');

    if ($date1 != $date2) {
      return back()->with('DateError1', 'Error');
    }

    $Employee = Employee::where('id', '=', $base['employeeid'])->get()->first();
    $SetLeaveSignatory = SetLeaveSignatory::where('sectionid', '=', $Employee->sectionid)->get()->first();

    if (empty($SetLeaveSignatory)) {
      return back()->with('SignatoryError', 'Error!');
    }

    // Prepare payload with 6.B Details fields
    $payload = [
      'leaveid'               => $base['leaveid'],
      'employeeid'            => $base['employeeid'],
      'daterange'             => $base['daterange'],
      'yearapplied'           => $date1,
      'userid'                => auth()->id(),
      'is_half_day'           => $request->has('is_half_day') ? true : false,
      'location_within_ph'    => null,
      'location_abroad'       => null,
      'hospital_specify'      => null,
      'outpatient_specify'    => null,
      'study_masters_degree'  => 0,
      'study_bar_board'       => 0,
      'other_monetization'    => 0,
      'other_terminal_leave'  => 0,
      'commutation'           => 'not_requested',
    ];

    // Conditional validation + assignment per leave type
    if (Str::contains($typeText, ['vacation', 'mandatory', 'forced', 'special privilege'])) {
      $extra = $request->validate([
        'location_choice'     => 'required|in:within_ph,abroad',
        'location_within_ph'  => 'required_if:location_choice,within_ph|nullable|string',
        'location_abroad'     => 'required_if:location_choice,abroad|nullable|string',
      ]);
      if ($extra['location_choice'] === 'within_ph') {
        $payload['location_within_ph'] = $extra['location_within_ph'];
      } else {
        $payload['location_abroad'] = $extra['location_abroad'];
      }
    } elseif (Str::contains($typeText, 'sick')) {
      $extra = $request->validate([
        'sick_choice'        => 'required|in:hospital,outpatient',
        'hospital_specify'   => 'required_if:sick_choice,hospital|nullable|string',
        'outpatient_specify' => 'required_if:sick_choice,outpatient|nullable|string',
      ]);
      if ($extra['sick_choice'] === 'hospital') {
        $payload['hospital_specify'] = $extra['hospital_specify'];
      } else {
        $payload['outpatient_specify'] = $extra['outpatient_specify'];
      }
    } elseif (Str::contains($typeText, 'study')) {
      $extra = $request->validate([
        'study_choice' => 'required|in:masters,bar_board',
      ]);
      $payload['study_masters_degree'] = $extra['study_choice'] === 'masters' ? 1 : 0;
      $payload['study_bar_board']      = $extra['study_choice'] === 'bar_board' ? 1 : 0;
    } elseif (Str::contains($typeText, ['others', 'other'])) {
      $extra = $request->validate([
        'others_choice' => 'required|in:monetization,terminal',
      ]);
      $payload['other_monetization']   = $extra['others_choice'] === 'monetization' ? 1 : 0;
      $payload['other_terminal_leave'] = $extra['others_choice'] === 'terminal' ? 1 : 0;
      $payload['commutation']          = 'requested';
    }

    Leave::create($payload);

    return back()->with('message', 'Leave Added Successfully');
  }

  public function destroy(Request $request, $Leave)
  {


    $Leave = Leave::where('id', '=', $Leave)->get()->first();
    $this->authorize('delete', $Leave);


    $Leave->delete();

    return back()->with('message', 'Leave Deleted Successfully');
  }

  public function print(\Illuminate\Http\Request $request, \App\Models\Leave $Leave)
  {
    $this->authorize('print', $Leave);

    $Leave_types = \App\Models\Leave_Type::get();
    $Employee    = \App\Models\Employee::with('Office')->findOrFail($Leave->employeeid);

    // determine application year (stored yearapplied or derived from range start)
    $yearApplied = $Leave->yearapplied ?? Carbon::parse(explode(' - ', $Leave->daterange)[0])->year;

    // count days (check if half-day)
    [$startDate, $endDate] = explode(' - ', $Leave->daterange);
    $date1  = new \Carbon\Carbon($startDate);
    $date2  = new \Carbon\Carbon($endDate);
    $Count  = $Leave->is_half_day ? 0.5 : ($date1->diffInDays($date2) + 1);

    $preview = $request->boolean('preview');

    // load snapshots with approver relation for robust fallbacks
    $Leave->load(['approvals', 'approvals.approver']);

    // current signatory (fallback only)
    $set       = \App\Models\SetLeaveSignatory::where('sectionid', $Employee->sectionid)->first();
    $signatory = $set
      ? \App\Models\LeaveSignatory::with('Employee1', 'Employee2', 'Employee3')->find($set->leavesignatoryid)
      : null;

    // compute leave credit snapshot (Vacation/Sick) for this employee/year
    $leaveCredits = $this->buildLeaveCredits($Employee->id, $yearApplied, $Leave_types, $Leave->leaveid, $Count);

    return view('mails.leave.print', compact('Leave_types', 'Employee', 'Leave', 'Count', 'preview', 'signatory', 'leaveCredits'));
  }

  public function download(\Illuminate\Http\Request $request, \App\Models\Leave $Leave)
  {
    return $this->downloadPDF($request, $Leave);
  }

  public function downloadPDF(\Illuminate\Http\Request $request, \App\Models\Leave $Leave)
  {
    ini_set('max_execution_time', '120');
    ini_set('memory_limit', '256M');

    $this->authorize('print', $Leave);

    $Leave_types = \App\Models\Leave_Type::get();
    $Employee = \App\Models\Employee::with('Office')->findOrFail($Leave->employeeid);

    $yearApplied = $Leave->yearapplied ?? Carbon::parse(explode(' - ', $Leave->daterange)[0])->year;

    [$startDate, $endDate] = explode(' - ', $Leave->daterange);
    $date1 = new \Carbon\Carbon($startDate);
    $date2 = new \Carbon\Carbon($endDate);
    $Count = $Leave->is_half_day ? 0.5 : ($date1->diffInDays($date2) + 1);

    $preview = $request->boolean('preview');
    $Leave->load(['approvals', 'approvals.approver']);

    $set = \App\Models\SetLeaveSignatory::where('sectionid', $Employee->sectionid)->first();
    $signatory = $set ? \App\Models\LeaveSignatory::with('Employee1', 'Employee2', 'Employee3')->find($set->leavesignatoryid) : null;

    $leaveCredits = $this->buildLeaveCredits($Employee->id, $yearApplied, $Leave_types, $Leave->leaveid, $Count);

    $pdf = \PDF::loadView('mails.leave.print', compact('Leave_types', 'Employee', 'Leave', 'Count', 'preview', 'signatory', 'leaveCredits'))
      ->setPaper('a4')->setWarnings(false);

    return $pdf->download('Leave_' . $Employee->lastname . '_' . date('Ymd') . '.pdf');
  }



  public function accept(Leave $Leave)
  {
    try {
      $this->authorize('accept', $Leave);

      // Eager load relationships
      $Leave->load(['Employee', 'Leave_Type']);

      $approver = Employee::where('email', auth()->user()->email)->firstOrFail();

      $applicant   = Employee::findOrFail($Leave->employeeid);
      $set         = SetLeaveSignatory::where('sectionid', $applicant->sectionid)->first();
      if (!$set)  return back()->with('message', 'No signatory set found.');

      $signatory   = LeaveSignatory::find($set->leavesignatoryid);
      if (!$signatory) return back()->with('message', 'Signatory record missing.');

      $step = null;
      if ($signatory->approver1 == $approver->id && !$Leave->is_approve1 && !$Leave->is_rejected1) {
        $step = 1;
        $Leave->update([
            'is_approve1' => true,
            'approve1_at' => now()
        ]);
      } elseif ($signatory->approver2 == $approver->id && $Leave->is_approve1 && !$Leave->is_approve2 && !$Leave->is_rejected2) {
        $step = 2;
        $updateFields = [
            'is_approve2' => true,
            'approve2_at' => now()
        ];
        // If recommendation is not 'for_disapproval', set to 'for_approval'
        if ($Leave->recommendation !== 'for_disapproval') {
          $updateFields['recommendation'] = 'for_approval';
        }
        $Leave->update($updateFields);
      } elseif ($signatory->approver3 == $approver->id && $Leave->is_approve1 && $Leave->is_approve2 && !$Leave->is_approve3 && !$Leave->is_rejected3) {
        $step = 3;
        $Leave->update([
            'is_approve3' => true,
            'approve3_at' => now()
        ]);

        // Update employee's leave balance based on the approved Leave record
        $employee = $Leave->Employee;
        $leaveTypeObj = $Leave->Leave_Type;
        $leaveType = strtolower($leaveTypeObj->leave_type ?? '');

        \Log::info("Approver 3 approval - Leave ID: {$Leave->id}, Leave Type: {$leaveType}");
        \Log::info("Leave Type Object: " . json_encode($leaveTypeObj));
        \Log::info("Vacation Balance in Leave record: {$Leave->vacation_balance}");
        \Log::info("Sick Balance in Leave record: {$Leave->sick_balance}");

        if ($employee) {
          // Calculate days from daterange for fallback
          [$startDate, $endDate] = explode(' - ', $Leave->daterange);
          $start = Carbon::parse($startDate);
          $end = Carbon::parse($endDate);
          $daysToDeduct = $Leave->is_half_day ? 0.5 : ($start->diffInDays($end) + 1);

          // Use the balance calculated by Approver 1 (saved in vacation_balance/sick_balance)
          // If not set by Approver 1, auto-calculate
          if (str_contains($leaveType, 'vacation')) {
            $oldBalance = $employee->vacation_leave_balance ?? 0;
            // Treat 0 as null (ignore if Approver 1 accidentally set 0)
            $newBalance = ($Leave->vacation_balance !== null && $Leave->vacation_balance > 0)
              ? $Leave->vacation_balance
              : max(0, $oldBalance - $daysToDeduct);
            $employee->update(['vacation_leave_balance' => $newBalance]);
            \Log::info("Vacation leave balance updated: Employee {$employee->id}, Old: {$oldBalance}, New: {$newBalance}, Source: " . (($Leave->vacation_balance !== null && $Leave->vacation_balance > 0) ? 'Approver1-edited' : 'Auto-calculated'));
          } elseif (str_contains($leaveType, 'sick')) {
            $oldBalance = $employee->sick_leave_balance ?? 0;
            // Treat 0 as null (ignore if Approver 1 accidentally set 0)
            $newBalance = ($Leave->sick_balance !== null && $Leave->sick_balance > 0)
              ? $Leave->sick_balance
              : max(0, $oldBalance - $daysToDeduct);
            $employee->update(['sick_leave_balance' => $newBalance]);
            \Log::info("Sick leave balance updated: Employee {$employee->id}, Old: {$oldBalance}, New: {$newBalance}, Source: " . (($Leave->sick_balance !== null && $Leave->sick_balance > 0) ? 'Approver1-edited' : 'Auto-calculated'));
          } elseif (str_contains($leaveType, 'forced') || str_contains($leaveType, 'mandatory')) {
            $oldBalance = $employee->force_leave_balance ?? 0;
            $newBalance = max(0, $oldBalance - $daysToDeduct);
            $employee->update(['force_leave_balance' => $newBalance]);
            \Log::info("Force leave balance updated: Employee {$employee->id}, Old: {$oldBalance}, New: {$newBalance}, Days: {$daysToDeduct}");
          } elseif (str_contains($leaveType, 'special privilege')) {
            $oldBalance = $employee->special_privilege_leave_balance ?? 0;
            $newBalance = max(0, $oldBalance - $daysToDeduct);
            $employee->update(['special_privilege_leave_balance' => $newBalance]);
            \Log::info("Special privilege leave balance updated: Employee {$employee->id}, Old: {$oldBalance}, New: {$newBalance}, Days: {$daysToDeduct}");
          } elseif (str_contains($leaveType, 'solo parent')) {
            $oldBalance = $employee->solo_parent_leave_balance ?? 0;
            $newBalance = max(0, $oldBalance - $daysToDeduct);
            $employee->update(['solo_parent_leave_balance' => $newBalance]);
            \Log::info("Solo parent leave balance updated: Employee {$employee->id}, Old: {$oldBalance}, New: {$newBalance}, Days: {$daysToDeduct}");
          } elseif (str_contains($leaveType, 'wellness')) {
            $oldBalance = $employee->wellness_leave_balance ?? 0;
            $newBalance = max(0, $oldBalance - $daysToDeduct);
            $employee->update(['wellness_leave_balance' => $newBalance]);
            \Log::info("Wellness leave balance updated: Employee {$employee->id}, Old: {$oldBalance}, New: {$newBalance}, Days: {$daysToDeduct}");
          }
        }
      }

      if (!$step) return back()->with('message', 'You are not the active approver for this request.');

      // --- pick a signature path
      $sigField       = "signature{$step}_path";
      $signature_path = $signatory->{$sigField};                 // prefer per-signatory upload

      // if blank, try the employee's saved signature
      if (!$signature_path) {
        $approverId = [1 => $signatory->approver1, 2 => $signatory->approver2, 3 => $signatory->approver3][$step] ?? null;
        $fallback   = optional(Employee::find($approverId))->signature_path; // might be "signatures/<file>.png"
        if ($fallback && Storage::disk('public')->exists($fallback)) {
          $signature_path = $fallback;
        }
      }

      // snapshot fields
      $Leave->approvals()->updateOrCreate(
        ['step' => $step],
        [
          'approver_employee_id' => $approver->id,
          'approver_name'        => trim($approver->firstname . ' ' . $approver->middlename . ' ' . $approver->lastname),
          'approver_position'    => $approver->position,
          'signature_path'       => $signature_path, // can be null if really none
          'approved_at'          => now(),
        ]
      );

      return back()->with('message', 'Leave Successfully Approved!');
    } catch (\Throwable $e) {
      report($e);
      \Log::error('Leave approval error: ' . $e->getMessage(), [
        'leave_id' => $Leave->id,
        'trace' => $e->getTraceAsString()
      ]);
      return back()->with('error', 'Error approving leave: ' . $e->getMessage());
    }
  }





  public function reject(Request $request, Leave $Leave)
  {
    $this->authorize('reject', $Leave);

    $request->validate([
        'rejection_reason' => 'required|string|max:1000',
    ]);

    $LeaveSignatories = LeaveSignatory::get();
    $Employee = Employee::where('email', '=', auth()->user()->email)->get()->first();
    $rejectionReason = $request->rejection_reason;

    foreach ($LeaveSignatories as $LeaveSignatory) {
      if ($LeaveSignatory->approver1 == $Employee->id && auth()->check()) {
        $formfields['is_rejected1'] = true;
        $formfields['rejected1_reason'] = $rejectionReason;
        $Leave->update($formfields);
        return back()->with('message', 'Leave Successfully Rejected!');
      }
      if ($LeaveSignatory->approver2 == $Employee->id && auth()->check()) {
        $formfields['is_rejected2'] = true;
        $formfields['rejected2_reason'] = $rejectionReason;
        $Leave->update($formfields);
        return back()->with('message', 'Leave Successfully Rejected!');
      }
      if ($LeaveSignatory->approver3 == $Employee->id && auth()->check()) {
        $formfields['is_rejected3'] = true;
        $formfields['rejected3_reason'] = $rejectionReason;
        $Leave->update($formfields);
        return back()->with('message', 'Leave Successfully Rejected!');
      }
    }
  }

  /**
   * Return Leave to user for revision
   */
  public function returnToUser(Request $request, Leave $Leave)
  {
    $this->authorize('reject', $Leave);

    $request->validate([
        'return_reason' => 'required|string|max:1000',
    ]);

    $applicant = Employee::findOrFail($Leave->employeeid);
    $set = SetLeaveSignatory::where('sectionid', $applicant->sectionid)->first();
    if (!$set) return back()->with('message', 'No signatory set found.');

    $signatory = LeaveSignatory::find($set->leavesignatoryid);
    if (!$signatory) return back()->with('message', 'Signatory record missing.');

    $Employee = Employee::where('email', '=', auth()->user()->email)->first();
    $returnReason = $request->return_reason;

    // Reset all previous approvals and set as returned by current approver
    if ($signatory->approver1 == $Employee->id && auth()->check()) {
      $Leave->update([
        'is_approve1' => false,
        'is_returned1' => true,
        'returned1_reason' => $returnReason,
        'approve1_at' => null,
      ]);
      return back()->with('message', 'Leave returned to user for revision!');
    }

    if ($signatory->approver2 == $Employee->id && auth()->check()) {
      $Leave->update([
        'is_approve2' => false,
        'is_returned2' => true,
        'returned2_reason' => $returnReason,
        'approve2_at' => null,
        // Keep approver 1 approval - do NOT reset approve1_at
      ]);
      return back()->with('message', 'Leave returned to user for revision!');
    }

    if ($signatory->approver3 == $Employee->id && auth()->check()) {
      $Leave->update([
        'is_approve3' => false,
        'is_returned3' => true,
        'returned3_reason' => $returnReason,
        'approve3_at' => null,
        // Keep approver 1 and 2 approvals - do NOT reset their approve_at
      ]);
      return back()->with('message', 'Leave returned to user for revision!');
    }

    return back()->with('message', 'You are not the assigned approver for this Leave.');
  }

  /**
   * Save edited leave credits from Approver1.
   * Stores the manually entered values for Vacation/Sick leave totals.
   */
  public function saveCredits(Request $request, Leave $Leave)
  {
    try {
      $this->authorize('accept', $Leave);

      // Approver1 can only edit: 7.A (Leave Credits) and 7.C (Approved For)
      $validated = $request->validate([
        'vacation_earned'     => 'nullable|numeric|min:0',
        'vacation_this_app'   => 'nullable|numeric|min:0',
        'vacation_balance'    => 'nullable|numeric|min:0',
        'sick_earned'         => 'nullable|numeric|min:0',
        'sick_this_app'       => 'nullable|numeric|min:0',
        'sick_balance'        => 'nullable|numeric|min:0',
        'days_with_pay'       => 'nullable|numeric|min:0',
        'days_without_pay'    => 'nullable|numeric|min:0',
        'approved_others'     => 'nullable|string|max:255',
      ]);

      // Convert empty strings to null for numeric fields
      foreach (
        [
          'vacation_earned',
          'vacation_this_app',
          'vacation_balance',
          'sick_earned',
          'sick_this_app',
          'sick_balance',
          'days_with_pay',
          'days_without_pay'
        ] as $field
      ) {
        if (isset($validated[$field]) && ($validated[$field] === '' || $validated[$field] === null)) {
          $validated[$field] = null;
        }
      }

      // Store edited values in the Leave record
      $Leave->update($validated);

      return back()->with('message', 'Leave Details Updated Successfully!');
    } catch (\Throwable $e) {
      report($e);
      return back()->with('message', 'Error saving leave details.');
    }
  }

  public function saveApprover2(Request $request, Leave $Leave)
  {
    try {
      $this->authorize('accept', $Leave);

      // Approver2 can only edit: 7.B (Recommendation)
      $validated = $request->validate([
        'recommendation'      => 'nullable|in:for_approval,for_disapproval',
        'recommendation_notes' => 'nullable|string',
      ]);

      // Always set to 'for_approval' unless 'for_disapproval' is explicitly selected
      if (empty($validated['recommendation']) || $validated['recommendation'] !== 'for_disapproval') {
        $validated['recommendation'] = 'for_approval';
      }

      $Leave->update($validated);

      return back()->with('message', 'Recommendation Updated Successfully!');
    } catch (\Throwable $e) {
      report($e);
      return back()->with('message', 'Error saving recommendation.');
    }
  }

  public function saveApprover3(Request $request, Leave $Leave)
  {
    try {
      $this->authorize('accept', $Leave);

      // Approver3 can only edit: 7.D (Disapproved Due To)
      $validated = $request->validate([
        'disapproved_reason'  => 'nullable|string',
      ]);

      $Leave->update($validated);

      return back()->with('message', 'Disapproval Reason Updated Successfully!');
    } catch (\Throwable $e) {
      report($e);
      return back()->with('message', 'Error saving disapproval reason.');
    }
  }

  public function userindex()
  {
    $this->authorize('viewLeaveindex', \App\Models\Leave::class);

    $Employee    = Employee::where('email', '=', auth()->user()->email)->first();
    $Leave_Types = Leave_Type::orderBy('id', 'asc')->get();

    // NEWEST FIRST
    $Leaves = Leave::where('employeeid', $Employee->id)
      ->with('Employee', 'Leave_type')
      ->orderBy('created_at', 'desc')   // <—
      ->get();

    $EmployeeLeaveCount = $this->getEmployeeLeaveCount();

    return view('user.leave.index', compact('Employee', 'EmployeeLeaveCount', 'Leave_Types', 'Leaves'));
  }

  public function checkUpdates(Request $request)
  {
    try {
      $lastCheck = $request->lastCheck ?
        Carbon::createFromTimestamp($request->lastCheck / 1000) :
        Carbon::now()->subMinutes(5);

      $Employee = Employee::where('email', '=', auth()->user()->email)->first();

      if (!$Employee) {
        return response()->json(['hasUpdates' => false]);
      }

      // Check if may bagong updates sa user's leaves since last check
      $hasUpdates = Leave::where('employeeid', $Employee->id)
        ->where('updated_at', '>', $lastCheck)
        ->exists();

      return response()->json(['hasUpdates' => $hasUpdates]);
    } catch (\Exception $e) {
      return response()->json(['hasUpdates' => false]);
    }
  }


  public function storeUserLeave(Request $request)
  {
    $this->authorize('AddUserLeave', \App\Models\Leave::class);

    $base = $request->validate([
      'leaveid'   => 'required|exists:leave_type,id',
      'daterange' => 'required',
    ]);

    $leaveType = Leave_Type::findOrFail($base['leaveid']);
    $typeText  = Str::lower(trim($leaveType->leave_type ?? ''));

    // Parse date range (expects "start - end")
    $data = $base['daterange'];
    [$startDate, $endDate] = array_pad(explode(' -', $data), 2, null);
    $date1 = Carbon::parse($startDate)->format('Y');
    $date2 = Carbon::parse($endDate)->format('Y');

    if ($date1 !== $date2) {
      return back()->with('DateError1', 'Error');
    }

    $employee = Employee::where('email', auth()->user()->email)->firstOrFail();

    // Defaults
    $payload = [
      'leaveid'               => $base['leaveid'],
      'daterange'             => $base['daterange'],
      'yearapplied'           => $date1,
      'userid'                => auth()->id(),
      'employeeid'            => $employee->id,
      'is_half_day'           => $request->has('is_half_day') ? true : false,
      'location_within_ph'    => null,
      'location_abroad'       => null,
      'hospital_specify'      => null,
      'outpatient_specify'    => null,
      'study_masters_degree'  => 0,
      'study_bar_board'       => 0,
      'other_monetization'    => 0,
      'other_terminal_leave'  => 0,
      'commutation'           => 'not_requested',
    ];

    // Conditional validation + assignment per leave type
    if (Str::contains($typeText, ['vacation', 'mandatory', 'forced', 'special privilege'])) {
      $extra = $request->validate([
        'location_choice'     => 'required|in:within_ph,abroad',
        'location_within_ph'  => 'required_if:location_choice,within_ph|nullable|string',
        'location_abroad'     => 'required_if:location_choice,abroad|nullable|string',
      ]);
      if ($extra['location_choice'] === 'within_ph') {
        $payload['location_within_ph'] = $extra['location_within_ph'];
      } else {
        $payload['location_abroad'] = $extra['location_abroad'];
      }
    } elseif (Str::contains($typeText, 'sick')) {
      $extra = $request->validate([
        'sick_choice'        => 'required|in:hospital,outpatient',
        'hospital_specify'   => 'required_if:sick_choice,hospital|nullable|string',
        'outpatient_specify' => 'required_if:sick_choice,outpatient|nullable|string',
      ]);
      if ($extra['sick_choice'] === 'hospital') {
        $payload['hospital_specify'] = $extra['hospital_specify'];
      } else {
        $payload['outpatient_specify'] = $extra['outpatient_specify'];
      }
    } elseif (Str::contains($typeText, 'study')) {
      $extra = $request->validate([
        'study_choice' => 'required|in:masters,bar_board',
      ]);
      $payload['study_masters_degree'] = $extra['study_choice'] === 'masters' ? 1 : 0;
      $payload['study_bar_board']      = $extra['study_choice'] === 'bar_board' ? 1 : 0;
    } elseif (Str::contains($typeText, ['others', 'other'])) {
      $extra = $request->validate([
        'others_choice' => 'required|in:monetization,terminal',
      ]);
      $payload['other_monetization']   = $extra['others_choice'] === 'monetization' ? 1 : 0;
      $payload['other_terminal_leave'] = $extra['others_choice'] === 'terminal' ? 1 : 0;
      $payload['commutation']          = 'requested';
    }

    // Calculate number of days
    [$startDate, $endDate] = array_pad(explode(' -', $base['daterange']), 2, null);
    $start = Carbon::parse($startDate);
    $end = Carbon::parse($endDate);
    $daysRequested = $payload['is_half_day'] ? 0.5 : ($start->diffInDays($end) + 1);

    // Check leave balance before creating
    if (str_contains($typeText, 'vacation')) {
      $currentBalance = $employee->vacation_leave_balance ?? 0;
      if ($daysRequested > $currentBalance) {
        return back()->with('error', "Insufficient vacation leave balance! You have {$currentBalance} days available but requesting {$daysRequested} days.");
      }
    } elseif (str_contains($typeText, 'sick')) {
      $currentBalance = $employee->sick_leave_balance ?? 0;
      if ($daysRequested > $currentBalance) {
        return back()->with('error', "Insufficient sick leave balance! You have {$currentBalance} days available but requesting {$daysRequested} days.");
      }
    }

    Leave::create($payload);

    return back()->with('message', 'Leave Added Successfully');
  }

  public function updateUserLeave(Request $request, Leave $Leave)
  {
    $this->authorize('update', $Leave);

    $base = $request->validate([
      'leaveid'   => 'required|exists:leave_type,id',
      'daterange' => 'required',
    ]);

    $leaveType = Leave_Type::findOrFail($base['leaveid']);
    $typeText  = Str::lower(trim($leaveType->leave_type ?? ''));

    // Parse date range (expects "start - end")
    $data = $base['daterange'];
    [$startDate, $endDate] = array_pad(explode(' -', $data), 2, null);
    $date1 = Carbon::parse($startDate)->format('Y');
    $date2 = Carbon::parse($endDate)->format('Y');

    if ($date1 !== $date2) {
      return back()->with('DateError1', 'Error');
    }

    // Defaults
    $payload = [
      'leaveid'               => $base['leaveid'],
      'daterange'             => $base['daterange'],
      'yearapplied'           => $date1,
      'is_half_day'           => $request->has('is_half_day') ? true : false,
      'location_within_ph'    => null,
      'location_abroad'       => null,
      'hospital_specify'      => null,
      'outpatient_specify'    => null,
      'study_masters_degree'  => 0,
      'study_bar_board'       => 0,
      'other_monetization'    => 0,
      'other_terminal_leave'  => 0,
      'commutation'           => 'not_requested',
    ];

    // Conditional validation + assignment per leave type
    if (Str::contains($typeText, ['vacation', 'mandatory', 'forced', 'special privilege'])) {
      $extra = $request->validate([
        'location_choice'     => 'required|in:within_ph,abroad',
        'location_within_ph'  => 'required_if:location_choice,within_ph|nullable|string',
        'location_abroad'     => 'required_if:location_choice,abroad|nullable|string',
      ]);
      if ($extra['location_choice'] === 'within_ph') {
        $payload['location_within_ph'] = $extra['location_within_ph'];
      } else {
        $payload['location_abroad'] = $extra['location_abroad'];
      }
    } elseif (Str::contains($typeText, 'sick')) {
      $extra = $request->validate([
        'sick_choice'        => 'required|in:hospital,outpatient',
        'hospital_specify'   => 'required_if:sick_choice,hospital|nullable|string',
        'outpatient_specify' => 'required_if:sick_choice,outpatient|nullable|string',
      ]);
      if ($extra['sick_choice'] === 'hospital') {
        $payload['hospital_specify'] = $extra['hospital_specify'];
      } else {
        $payload['outpatient_specify'] = $extra['outpatient_specify'];
      }
    } elseif (Str::contains($typeText, 'study')) {
      $extra = $request->validate([
        'study_choice' => 'required|in:masters,bar_board',
      ]);
      $payload['study_masters_degree'] = $extra['study_choice'] === 'masters' ? 1 : 0;
      $payload['study_bar_board']      = $extra['study_choice'] === 'bar_board' ? 1 : 0;
    } elseif (Str::contains($typeText, ['others', 'other'])) {
      $extra = $request->validate([
        'others_choice' => 'required|in:monetization,terminal',
      ]);
      $payload['other_monetization']   = $extra['others_choice'] === 'monetization' ? 1 : 0;
      $payload['other_terminal_leave'] = $extra['others_choice'] === 'terminal' ? 1 : 0;
      $payload['commutation']          = 'requested';
    }

    // Calculate number of days for the updated leave
    $employee = Employee::findOrFail($Leave->employeeid);
    [$startDate, $endDate] = array_pad(explode(' -', $base['daterange']), 2, null);
    $start = Carbon::parse($startDate);
    $end = Carbon::parse($endDate);
    $daysRequested = $payload['is_half_day'] ? 0.5 : ($start->diffInDays($end) + 1);

    // Check leave balance before updating (only if not already approved)
    if (!$Leave->is_approve3) {
      if (str_contains($typeText, 'vacation')) {
        $currentBalance = $employee->vacation_leave_balance ?? 0;
        if ($daysRequested > $currentBalance) {
          return back()->with('error', "Insufficient vacation leave balance! You have {$currentBalance} days available but requesting {$daysRequested} days.");
        }
      } elseif (str_contains($typeText, 'sick')) {
        $currentBalance = $employee->sick_leave_balance ?? 0;
        if ($daysRequested > $currentBalance) {
          return back()->with('error', "Insufficient sick leave balance! You have {$currentBalance} days available but requesting {$daysRequested} days.");
        }
      }
    }

    // Check if this is a returned request (being resubmitted after revision)
    $wasReturned1 = $Leave->is_returned1;
    $wasReturned2 = $Leave->is_returned2;
    $wasReturned3 = $Leave->is_returned3;

    // Clear return flags when user resubmits
    if ($wasReturned1 || $wasReturned2 || $wasReturned3) {
      $payload['is_returned1'] = false;
      $payload['returned1_reason'] = null;
      $payload['is_returned2'] = false;
      $payload['returned2_reason'] = null;
      $payload['is_returned3'] = false;
      $payload['returned3_reason'] = null;

      // If returned by approver3, reset to approver3's turn (keep approver1 and approver2 approved)
      if ($wasReturned3) {
        // Keep approver1 and approver2 approvals
        $payload['is_approve1'] = true;
        $payload['is_approve2'] = true;
        $payload['is_approve3'] = false;
      }
      // If returned by approver2, reset to approver2's turn (keep approver1 approved)
      elseif ($wasReturned2) {
        // Keep approver1 approval
        $payload['is_approve1'] = true;
        $payload['is_approve2'] = false;
        $payload['is_approve3'] = false;
      }
      // If returned by approver1, reset to approver1's turn
      elseif ($wasReturned1) {
        // Reset all approvals
        $payload['is_approve1'] = false;
        $payload['is_approve2'] = false;
        $payload['is_approve3'] = false;
      }
    }

    $Leave->update($payload);

    return back()->with('message', 'Leave Updated Successfully');
  }

  public function getLeaveYearApproved()
  {


    $Leaves = Leave::orderby('created_at', 'desc')->where('is_approve3', true)->with('Employee')->with('Leave_Type')->get();
    $Leave_types = Leave_Type::get();
    $LeaveYear = array();
    $LeaveYear = [];

    foreach ($Leaves as $Leave) {
      foreach ($Leave_types as $Leave_type) {
        if ($Leave->leaveid == $Leave_type->id) {
        }
      }
      $data = $Leave->daterange;
      list($startDate, $endDate) = explode(" - ", $data);
      $date1 =  Carbon::createFromDate($startDate)->format('Y');

      $date2 =  Carbon::createFromDate($endDate)->format('Y');

      if ($date2 == $date1) {
        $LeaveYear[] = array($Leave->id, $date1, $Leave->leaveid, $Leave->employeeid, $data);
      }
    }

    return $LeaveYear;
  }

  public function getEmployeeLeaveCount()
  {


    $Leaves = Leave::with('Employee')->where('is_approve3', true)->get();
    $Leave_types = Leave_Type::get();
    $LeaveCounts = array();
    $LeaveCount = [];
    $LeaveCount1 = array();
    $LeaveCount1 = [];
    $Final = array();
    $Final = [];
    $LeaveYear = $this->getLeaveYearApproved();


    //
    $Count = 0;
    $Employees = Employee::get();

    foreach ($Employees as $Employee) {
      foreach ($LeaveYear as $Leave) {
        if ($Leave['3'] == $Employee->id) {

          foreach ($Leave_types as $Leave_type) {
            if ($Leave['2'] == $Leave_type->id && $Leave['3'] == $Employee->id) {
              $data = $Leave['4'];
              list($startDate, $endDate) = explode(" - ", $data);
              $date1 = new carbon($startDate);
              $date2 = new carbon($endDate);
              $date3 =  Carbon::createFromDate($startDate)->format('Y');


              if ($date3 == $Leave['1']) {
                while ($date1->lte($date2)) {
                  $Count =  $Count + 1;
                  $date1->addDay();
                }
              }
              if ($Count != 0) {
                $LeaveCount1[] = array($Employee->id, $Leave_type->id, $Count, $Leave['1']);
                $Count = 0;
              }
            }
          }
        }
      }
    }


    for ($i = 2021; $i <= 2050; $i++) {
      foreach ($Leave_types as $Leave_type) {
        foreach ($Employees as $Employee) {

          foreach ($LeaveCount1 as $LeaveCount) {
            if ($LeaveCount['3'] == $i && $Leave_type->id == $LeaveCount['1'] && $Employee->id == $LeaveCount['0']) {
              $Count = $Count + $LeaveCount['2'];
            }
          }
          if ($Count != 0) {
            $Final[] = array($Employee->id, $Leave_type->id, $Count, $i);
            $Count = 0;
          }
        }
      }
    }
    return $Final;
  }

  /**
   * Build per-leave-type credit numbers for the print view.
   * - Total Earned: configured available from Leave_Type
   * - Used: total approved days in the given year (already applied/approved)
   * - Less this Application: current request days if it matches the type
   * - Balance: total - used - currentApplication
   */
  private function buildLeaveCredits(int $employeeId, int $year, $leaveTypes, int $currentLeaveTypeId, int $currentDays): array
  {
    // accumulate approved usage per leave type for the year
    $approved = Leave::where('employeeid', $employeeId)
      ->where('is_approve3', true)
      ->where('yearapplied', $year)
      ->get();

    $usedPerType = [];
    foreach ($approved as $leave) {
      [$start, $end] = explode(' - ', $leave->daterange);
      $d1 = new Carbon($start);
      $d2 = new Carbon($end);
      // Check if half-day leave
      $days = $leave->is_half_day ? 0.5 : ($d1->diffInDays($d2) + 1);
      $usedPerType[$leave->leaveid] = ($usedPerType[$leave->leaveid] ?? 0) + $days;
    }

    // identify Vacation and Sick leave type IDs by name (case-insensitive contains match)
    $vacationId = null;
    $sickId     = null;
    foreach ($leaveTypes as $lt) {
      $name = strtolower($lt->leave_type ?? '');
      if (strpos($name, 'vacation') !== false) {
        $vacationId = $lt->id;
      }
      if (strpos($name, 'sick') !== false) {
        $sickId = $lt->id;
      }
    }

    $makeRow = function ($typeId) use ($leaveTypes, $usedPerType, $currentLeaveTypeId, $currentDays) {
      $available = 0;
      foreach ($leaveTypes as $lt) {
        if ($lt->id == $typeId) {
          $available = (int) ($lt->available ?? 0);
          break;
        }
      }

      $used  = (int) ($usedPerType[$typeId] ?? 0);
      $thisApp = $currentLeaveTypeId === $typeId ? $currentDays : 0;
      $balance = $available - $used - $thisApp;
      if ($balance < 0) {
        $balance = 0; // avoid negative display
      }

      return [
        'earned'    => $available,
        'used'      => $used,
        'this_app'  => $thisApp,
        'balance'   => $balance,
      ];
    };

    return [
      'vacation' => $vacationId ? $makeRow($vacationId) : ['earned' => 0, 'used' => 0, 'this_app' => 0, 'balance' => 0],
      'sick'     => $sickId ? $makeRow($sickId) : ['earned' => 0, 'used' => 0, 'this_app' => 0, 'balance' => 0],
    ];
  }

  public function summary()
  {
    $this->authorize('summary', \App\Models\Leave::class);
    $Employee = Employee::where('email', '=', auth()->user()->email)->get()->first();
    $Leave_Types = Leave_Type::orderBy('id', 'asc')->get();
    $YearNow = Carbon::createFromDate(now())->format('Y');

    $Leaves = Leave::where('employeeid', '=', $Employee->id)->where('yearapplied', '=', $YearNow)->with('Employee')->with('Leave_type')->orderBy('created_at', 'asc')->get();

    $EmployeeLeaveCount = $this->getEmployeeLeaveCount();


    // $Events = Event::orderBy('date', 'asc')->with('User')->get();
    return view('user.leave.leave_summary', compact('YearNow', 'Employee', 'EmployeeLeaveCount', 'Leave_Types', 'Leaves'));
  }

  public function summaryfilter(Request $request)
  {

    $this->authorize('summary', \App\Models\Leave::class);
    $Employee = Employee::where('email', '=', auth()->user()->email)->get()->first();
    $Leave_Types = Leave_Type::orderBy('id', 'asc')->get();
    $YearNow = Carbon::createFromDate(now())->format('Y');
    $FilteredYear = $request->year;

    $Leaves = Leave::where('employeeid', '=', $Employee->id)->where('yearapplied', '=', $YearNow)->with('Employee')->with('Leave_type')->orderBy('created_at', 'asc')->get();

    $EmployeeLeaveCount = $this->getEmployeeLeaveCount();


    // $Events = Event::orderBy('date', 'asc')->with('User')->get();
    return view('user.leave.leave_summary_filtered', compact('FilteredYear', 'YearNow', 'Employee', 'EmployeeLeaveCount', 'Leave_Types', 'Leaves'));
  }

  public function advance(Request $request)
  {
    try {

      $this->authorize('MSDaddLeave', \App\Models\Leave::class);

      $Employees = Employee::where('empstatus', '=', 'PERMANENT')->get();
      $Leave_Types = Leave_Type::orderBy('id', 'asc')->get();
      $Leaves = Leave::with('Employee')->with('Leave_type')->where('employeeid', '=', $request->employeeid)->orderBy('created_at', 'asc')->get();


      // $Events = Event::orderBy('date', 'asc')->with('User')->get();
      return view('msd-panel.leave.advancesearch', compact('Employees', 'Leave_Types', 'Leaves'));
    } catch (Throwable $e) {
      report($e);

      return false;
    }
  }
}
