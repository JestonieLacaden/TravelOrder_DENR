<?php

namespace App\Http\Controllers\Msd;

use Throwable;
use App\Models\Employee;
use App\Models\TravelOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Models\TravelOrderSignatory;
use App\Models\SetTravelOrderSignatory;
use App\Models\TravelOrderApproved;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\SectionChief;
use App\Models\Unit;

class TravelOrderController extends Controller
{
    /**
     * Helper: Detect if employee is PENRO
     */
    private function isPENRO($employee)
    {
        return $employee->unitid == 1; // OFFICE OF THE PENRO
    }

    /**
     * Helper: Detect if employee is MSD Chief
     */
    private function isMSDChief($employee)
    {
        return $employee->unitid == 6; // OFFICE OF THE MSD CHIEF
    }

    /**
     * Helper: Detect if employee is TSD Chief
     */
    private function isTSDChief($employee)
    {
        return $employee->unitid == 14; // OFFICE OF THE TSD CHIEF
    }

    /**
     * Helper: Detect if employee is a Division Chief (MSD or TSD)
     */
    private function isDivisionChief($employee)
    {
        return $this->isMSDChief($employee) || $this->isTSDChief($employee);
    }

    /**
     * Helper: Check if employee is Section Chief for their unit
     */
    private function isSectionChief($employee)
    {
        $sectionChief = SectionChief::where('unitid', $employee->unitid)
            ->where('employeeid', $employee->id)
            ->first();
        return !empty($sectionChief);
    }

    /**
     * Helper: Get PENRO employee ID
     */
    private function getPENROId()
    {
        return Employee::where('unitid', 1)->value('id');
    }

    /**
     * Helper: Get Division Chief based on section
     */
    private function getDivisionChiefId($sectionid)
    {
        // sectionid = 2 (MSD) → unitid = 6
        // sectionid = 3 (TSD) → unitid = 14
        if ($sectionid == 2) {
            return Employee::where('unitid', 6)->value('id'); // MSD CHIEF
        } elseif ($sectionid == 3) {
            return Employee::where('unitid', 14)->value('id'); // TSD CHIEF
        }
        return null;
    }

    /**
     * Helper: Get Section Chief for a unit
     */
    private function getSectionChiefId($unitid)
    {
        $sectionChief = SectionChief::where('unitid', $unitid)->first();
        return $sectionChief ? $sectionChief->employeeid : null;
    }

    public function index()
    {

        $this->authorize('MsdCreate', \App\Models\TravelOrder::class);

        $Employees = Employee::orderby('lastname', 'asc')->get();
        // Show ALL Travel Orders (pending and approved) ordered by newest first
        $TravelOrders = TravelOrder::orderby('created_at', 'desc')->with('user')->get();
        $ApprovedTravelOrders = TravelOrderApproved::get();
        // $Sections = Section::with('office')->get();
        // $SetLeaveSignatories = SetLeaveSignatory::with('section','Office')->get();
        // $Offices = Office::with('section')->get();
        // $LeaveSignatories = LeaveSignatory::get();
        return view('msd-panel.travel-order.index', compact('Employees', 'TravelOrders', 'ApprovedTravelOrders'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', \App\Models\TravelOrder::class);

        $formfields = $request->validate([
            'employeeid' => 'required',
            'daterange' => 'required',
            'destinationoffice' => 'required',
            'purpose' => 'required',
            'perdime' => 'required',
            'appropriation' => 'required',
            'remarks' => 'required',
        ]);

        $employee = Employee::findOrFail($request->employeeid);

        // Determine signatory based on employee position
        $approver1 = null;
        $approver2 = null;
        $approver3 = $this->getPENROId(); // PENRO is always final approver

        // PENRO creates own TO - auto-approve
        if ($this->isPENRO($employee)) {
            $approver1 = null;
            $approver2 = null;
            $approver3 = $employee->id; // Self-sign
        }
        // Division Chief (MSD/TSD) creates own TO - skip to PENRO
        elseif ($this->isDivisionChief($employee)) {
            $approver1 = null;
            $approver2 = null;
            $approver3 = $this->getPENROId();
        }
        // Section Chief creates own TO - skip section chief level
        elseif ($this->isSectionChief($employee)) {
            $approver1 = null;
            $approver2 = $this->getDivisionChiefId($employee->sectionid);
            $approver3 = $this->getPENROId();
        }
        // Regular Employee - full 3-level approval
        else {
            $approver1 = $this->getSectionChiefId($employee->unitid);
            $approver2 = $this->getDivisionChiefId($employee->sectionid);
            $approver3 = $this->getPENROId();

            // Validate Section Chief is assigned
            if (!$approver1) {
                return back()->with('SignatoryError', 'No Section Chief assigned for ' . $employee->Unit->unit . '!');
            }
        }

        // Validate required approvers exist
        if (!$approver3) {
            return back()->with('SignatoryError', 'PENRO not found in system!');
        }

        if ($approver2 && !$this->isPENRO($employee) && !$this->isDivisionChief($employee)) {
            if (!Employee::find($approver2)) {
                return back()->with('SignatoryError', 'Division Chief not found!');
            }
        }

        // Create or get signatory record
        $signatory = TravelOrderSignatory::firstOrCreate([
            'approver1' => $approver1,
            'approver2' => $approver2,
            'approver3' => $approver3,
        ]);

        $formfields['userid'] = auth()->user()->id;
        $formfields['travelordersignatoryid'] = $signatory->id;

        // If PENRO self-creates, auto-approve
        if ($this->isPENRO($employee) && $employee->id == $approver3) {
            $formfields['is_approve1'] = false;
            $formfields['is_approve2'] = false;
            $formfields['is_approve3'] = true;
            $formfields['approve3_by'] = $employee->id;
            $formfields['approve3_at'] = now();

            $travelOrder = TravelOrder::create($formfields);

            // Generate TO Number immediately
            $this->generateTONumber($travelOrder);

            return back()->with('message', 'Travel Order Created and Auto-Approved (PENRO)!');
        }

        TravelOrder::create($formfields);

        return back()->with('message', 'Travel Order Added Successfully');
    }

    /**
     * Generate TO Number after final approval
     */
    private function generateTONumber($travelOrder)
    {
        DB::transaction(function () use ($travelOrder) {
            // Check if already generated
            if (Schema::hasColumn('travel_order_approved', 'request_id')) {
                $existing = TravelOrderApproved::where('request_id', $travelOrder->id)->first();
                if ($existing) return;
            }

            // Generate monthly sequence
            $attempts = 0;
            while (true) {
                $attempts++;
                $now = now();
                $year = $now->format('Y');
                $month = $now->format('m');

                $last = TravelOrderApproved::whereYear('created_at', $year)
                    ->whereMonth('created_at', $month)
                    ->orderByDesc('id')
                    ->lockForUpdate()
                    ->first();

                $seq = 1;
                if ($last && preg_match('/(\d{4})$/', (string) $last->travelorderid, $m)) {
                    $seq = (int) $m[1] + 1;
                }

                $toNumber = sprintf('%s-%s-%04d', $year, $month, $seq);

                $payload = [
                    'employeeid' => $travelOrder->employeeid,
                    'travelorderid' => $toNumber,
                ];
                if (Schema::hasColumn('travel_order_approved', 'request_id')) {
                    $payload['request_id'] = $travelOrder->id;
                }

                try {
                    TravelOrderApproved::create($payload);
                    break;
                } catch (\Illuminate\Database\QueryException $e) {
                    if ($e->getCode() === '23000' && $attempts < 5) {
                        continue;
                    }
                    throw $e;
                }
            }
        });
    }

    public function update(Request $request, TravelOrder $TravelOrder)
    {
        $request->validate([
            'daterange' => 'required|string',
        ]);

        if (trim($request->daterange) === trim($TravelOrder->daterange)) {
            return back()->with('message', 'No changes to save.');
        }


        if (!str_contains($request->daterange, ' - ')) {
            return back()->with('EventError', 'Invalid date range format.')->withInput();
        }

        [$sRaw, $eRaw] = explode(' - ', $request->daterange, 2);

        $start = \Illuminate\Support\Carbon::hasFormat($sRaw, 'm/d/Y')
            ? \Illuminate\Support\Carbon::createFromFormat('m/d/Y', $sRaw)
            : (\Illuminate\Support\Carbon::hasFormat($sRaw, 'Y-m-d')
                ? \Illuminate\Support\Carbon::createFromFormat('Y-m-d', $sRaw)
                : null);

        $end = \Illuminate\Support\Carbon::hasFormat($eRaw, 'm/d/Y')
            ? \Illuminate\Support\Carbon::createFromFormat('m/d/Y', $eRaw)
            : (\Illuminate\Support\Carbon::hasFormat($eRaw, 'Y-m-d')
                ? \Illuminate\Support\Carbon::createFromFormat('Y-m-d', $eRaw)
                : null);

        if (!$start || !$end) {
            return back()->with('EventError', 'Invalid date range format.')->withInput();
        }


        if ($start->year !== $end->year) {
            return back()->with('DateError1', true)->withInput();
        }


        $normalized = $start->format('m/d/Y') . ' - ' . $end->format('m/d/Y');


        if (trim($normalized) === trim($TravelOrder->daterange)) {
            return back()->with('message', 'No changes to save.');
        }

        $TravelOrder->update(['daterange' => $normalized]);

        return back()->with('message', 'Travel Order updated successfully.');
    }


    public function updateApprove2(Request $request, TravelOrder $travel_order)
    {
        $this->authorize('updateFinal', $travel_order);

        $request->validate(['daterange' => 'required|string']);

        // Skip if unchanged
        if (trim($request->daterange) === trim($travel_order->daterange)) {
            return back()->with('message', 'No changes to save.');
        }

        if (!str_contains($request->daterange, ' - ')) {
            return back()->with('EventError', 'Invalid date range format.')->withInput();
        }

        [$sRaw, $eRaw] = explode(' - ', $request->daterange, 2);

        $start = \Illuminate\Support\Carbon::hasFormat($sRaw, 'm/d/Y')
            ? \Illuminate\Support\Carbon::createFromFormat('m/d/Y', $sRaw)
            : (\Illuminate\Support\Carbon::hasFormat($sRaw, 'Y-m-d')
                ? \Illuminate\Support\Carbon::createFromFormat('Y-m-d', $sRaw)
                : null);

        $end = \Illuminate\Support\Carbon::hasFormat($eRaw, 'm/d/Y')
            ? \Illuminate\Support\Carbon::createFromFormat('m/d/Y', $eRaw)
            : (\Illuminate\Support\Carbon::hasFormat($eRaw, 'Y-m-d')
                ? \Illuminate\Support\Carbon::createFromFormat('Y-m-d', $eRaw)
                : null);

        if (!$start || !$end) {
            return back()->with('EventError', 'Invalid date range format.')->withInput();
        }

        // Same-year rule
        if ($start->year !== $end->year) {
            return back()->with('DateError1', true)->withInput();
        }

        $normalized = $start->format('m/d/Y') . ' - ' . $end->format('m/d/Y');

        if (trim($normalized) === trim($travel_order->daterange)) {
            return back()->with('message', 'No changes to save.');
        }

        $travel_order->update(['daterange' => $normalized]);

        return back()->with('message', 'Travel Order date updated by Approver 2.');
    }




    public function destroy(Request $request, $TravelOrder)
    {

        $Travelorder = TravelOrder::where('id', '=', $TravelOrder)->get()->first();
        $this->authorize('delete', $Travelorder);
        $Travelorder->delete();
        return back()->with('message', 'Travel Order Deleted Successfully');
    }

    public function accept(\App\Models\TravelOrder $TravelOrder)
    {
        try {
            $this->authorize('accept', $TravelOrder);

            $current = \App\Models\Employee::where('email', auth()->user()->email)->firstOrFail();

            // --- Get the Travel Order's signatory set robustly ---
            // Prefer the saved signatory id on the request (new records)
            $sig = $TravelOrder->travelordersignatoryid
                ? \App\Models\TravelOrderSignatory::find($TravelOrder->travelordersignatoryid)
                : null;

            // Fallback for old requests that don't have travelordersignatoryid
            if (!$sig) {
                $owner = \App\Models\Employee::findOrFail($TravelOrder->employeeid);

                // try office+section first, then section-only
                $sigId = \App\Models\SetTravelOrderSignatory::where('officeid', $owner->officeid)
                    ->where('sectionid', $owner->sectionid)
                    ->value('travelordersignatoryid')
                    ?? \App\Models\SetTravelOrderSignatory::where('sectionid', $owner->sectionid)
                    ->value('travelordersignatoryid');

                $sig = $sigId ? \App\Models\TravelOrderSignatory::find($sigId) : null;
            }

            if (!$sig) {
                return back()->with('SignatoryError', 'No signatory configured for this Travel Order.');
            }
            // --- end robust lookup ---

            // -------------------------
            // Approver 1
            // -------------------------
            if ($sig->approver1 == $current->id) {
                if ($TravelOrder->is_approve1 || $TravelOrder->is_rejected1) {
                    return back()->with('message', 'Already processed by Approver 1.');
                }
                $TravelOrder->forceFill([
                    'is_approve1' => true,
                    'approve1_by' => $current->id,
                    'approve1_at' => now(),
                ])->save();
                return back()->with('message', 'Travel Order Successfully Approved!');
            }
            // -------------------------
            // Approver 2
            // -------------------------
            if ($sig->approver2 == $current->id) {
                if (!$TravelOrder->is_approve1) {
                    return back()->with('SignatoryError', 'Approver 1 must approve first.');
                }
                if ($TravelOrder->is_approve2 || $TravelOrder->is_rejected2) {
                    return back()->with('message', 'Already processed by Approver 2.');
                }

                // Just approve and forward to Approver 3 (PENRO)
                $TravelOrder->forceFill([
                    'is_approve2' => true,
                    'approve2_by' => $current->id,
                    'approve2_at' => now(),
                ])->save();

                return back()->with('message', 'Travel Order Successfully Approved! Forwarded to PENRO.');
            }

            // -------------------------
            // Approver 3 (PENRO - Final Approval)
            // -------------------------
            if ($sig->approver3 == $current->id) {
                if (!$TravelOrder->is_approve1) {
                    return back()->with('SignatoryError', 'Approver 1 must approve first.');
                }
                if (!$TravelOrder->is_approve2) {
                    return back()->with('SignatoryError', 'Approver 2 must approve first.');
                }
                if ($TravelOrder->is_approve3 || $TravelOrder->is_rejected3) {
                    return back()->with('message', 'Already processed by Approver 3.');
                }

                DB::transaction(function () use ($TravelOrder, $current) {
                    // 1) mark as approved by approver 3 (PENRO - Final)
                    $TravelOrder->forceFill([
                        'is_approve3' => true,
                        'approve3_by' => $current->id,
                        'approve3_at' => now(),
                    ])->save();

                    // 2) avoid duplicate approval record for the same request
                    $existing = null;
                    if (Schema::hasColumn('travel_order_approved', 'request_id')) {
                        $existing = \App\Models\TravelOrderApproved::where('request_id', $TravelOrder->id)->first();
                    }
                    if ($existing) return;

                    // 3) generate monthly sequence: YYYY-MM-#### (race-safe with retry)
                    $attempts = 0;
                    while (true) {
                        $attempts++;
                        $now   = now();
                        $year  = $now->format('Y');
                        $month = $now->format('m');

                        // lock the latest row for THIS month, then compute next seq
                        $last = \App\Models\TravelOrderApproved::whereYear('created_at', $year)
                            ->whereMonth('created_at', $month)
                            ->orderByDesc('id')
                            ->lockForUpdate()
                            ->first();

                        $seq = 1;
                        if ($last && preg_match('/(\d{4})$/', (string) $last->travelorderid, $m)) {
                            $seq = (int) $m[1] + 1;
                        }

                        $toNumber = sprintf('%s-%s-%04d', $year, $month, $seq);

                        $payload = [
                            'employeeid'    => $TravelOrder->employeeid,
                            'travelorderid' => $toNumber,
                        ];
                        if (Schema::hasColumn('travel_order_approved', 'request_id')) {
                            $payload['request_id'] = $TravelOrder->id;
                        }

                        try {
                            \App\Models\TravelOrderApproved::create($payload);
                            break; // success
                        } catch (\Illuminate\Database\QueryException $e) {
                            // duplicate key (unique index on travelorderid) → retry a few times
                            if ($e->getCode() === '23000' && $attempts < 5) {
                                // loop will re-lock & recompute $last, then try again
                                continue;
                            }
                            throw $e; // rethrow if not duplicate or too many attempts
                        }
                    }
                });


                event(new \App\Events\TravelOrderStatusChanged($TravelOrder));


                return back()->with('message', 'Travel Order Successfully Approved!');
            }

            return back()->with('SignatoryError', 'You are not assigned for this Travel Order.');
        } catch (\Throwable $e) {
            report($e);
            return back()->with('SignatoryError', 'Unexpected error: ' . $e->getMessage());
        }
    }






    public function reject(TravelOrder $TravelOrder)
    {
        $this->authorize('reject', $TravelOrder);

        $current = Employee::where('email', auth()->user()->email)->first();
        if (!$current) return back()->with('SignatoryError', 'No employee profile for current user.');

        $sig = $TravelOrder->travelordersignatoryid
            ? TravelOrderSignatory::find($TravelOrder->travelordersignatoryid)
            : null;

        if (!$sig) {
            $owner = Employee::find($TravelOrder->employeeid);
            $sigId = SetTravelOrderSignatory::where('officeid', $owner->officeid)
                ->where('sectionid', $owner->sectionid)
                ->value('travelordersignatoryid')
                ?? SetTravelOrderSignatory::where('sectionid', $owner->sectionid)->value('travelordersignatoryid');

            $sig = $sigId ? TravelOrderSignatory::find($sigId) : null;
        }

        if (!$sig) return back()->with('SignatoryError', 'No signatory configured for this Travel Order.');

        if ($sig->approver1 == $current->id) {
            $TravelOrder->update(['is_rejected1' => true]);
            event(new \App\Events\TravelOrderStatusChanged($TravelOrder));
            return back()->with('message', 'Travel Order Successfully Rejected!');
        }

        if ($sig->approver2 == $current->id) {
            $TravelOrder->update(['is_rejected2' => true]);
            event(new \App\Events\TravelOrderStatusChanged($TravelOrder));
            return back()->with('message', 'Travel Order Successfully Rejected!');
        }

        if ($sig->approver3 == $current->id) {
            $TravelOrder->update(['is_rejected3' => true]);
            event(new \App\Events\TravelOrderStatusChanged($TravelOrder));
            return back()->with('message', 'Travel Order Successfully Rejected!');
        }

        return back()->with('SignatoryError', 'You are not the assigned approver for this Travel Order.');
    }


    public function userindex()
    {

        $this->authorize('viewTravelOrderIndex', \App\Models\TravelOrder::class);
        $Employee = Employee::where('email', auth()->user()->email)->first();
        $TravelOrders = TravelOrder::where('employeeid', $Employee->id)
            ->with(['Employee', 'approved']) // load approved relation
            ->orderBy('created_at', 'asc')
            ->get();
        // $TravelOrders = TravelOrder::where('employeeid', $Employee->id)->with('Employee')->orderBy('created_at', 'asc')->get();
        // $ApprovedTravelOrders = TravelOrderApproved::get();
        // $approvedIds = TravelOrderApproved::where('employeeid', $Employee->id)
        //     ->pluck('travelorderid')
        //     ->toArray();

        $SignatoryOptions = SetTravelOrderSignatory::where('sectionid', $Employee->sectionid)
            ->with('TravelOrderSignatory') // so we can show names
            ->get();

        return view('user.travel-order.index', compact('Employee', 'TravelOrders', 'SignatoryOptions'));

        // $this->authorize('viewTravelOrderIndex', \App\Models\TravelOrder::class);
        //   $Employee = Employee::where('email','=', auth()->user()->email)->get()->first();

        //   $TravelOrders = TravelOrder::where('employeeid','=',$Employee->id)->with('Employee')->orderBy('created_at','asc')->get();

        //   $ApprovedTravelOrders = TravelOrderApproved::get();


        //   return view('user.travel-order.index', compact('Employee','TravelOrders','ApprovedTravelOrders'));
    }

    // app/Http/Controllers/Msd/TravelOrderController.php
    public function storeUserTravelOrder(Request $request)
    {
        $this->authorize('AddUserTravelOrder', \App\Models\TravelOrder::class);

        $formfields = $request->validate([
            'daterange'        => 'required',
            'destinationoffice' => 'required',
            'purpose'          => 'required',
            'perdime'          => 'required',
            'appropriation'    => 'required',
            'remarks'          => 'required',
        ]);

        $employee = \App\Models\Employee::where('email', auth()->user()->email)->first();

        // ✅ OPTION 2: Lookup approvers based on employee's unit and section

        // Get Section Chief from section_chief table (based on unitid)
        $approver1 = $this->getSectionChiefId($employee->unitid);

        // Get Division Chief based on section
        $approver2 = $this->getDivisionChiefId($employee->sectionid);

        // Get PENRO (always the same)
        $approver3 = $this->getPENROId();

        // Validate required approvers
        if (!$approver1) {
            return back()->with('SignatoryError', 'No Section Chief assigned for ' . $employee->Unit->unit . '!');
        }
        if (!$approver3) {
            return back()->with('SignatoryError', 'PENRO not found in system!');
        }

        // Create or get signatory record with these 3 approvers
        $signatory = TravelOrderSignatory::firstOrCreate([
            'approver1' => $approver1,
            'approver2' => $approver2,
            'approver3' => $approver3,
        ]);

        $formfields['userid']                  = auth()->id();
        $formfields['employeeid']              = $employee->id;
        $formfields['travelordersignatoryid']  = $signatory->id;

        \App\Models\TravelOrder::create($formfields);

        return back()->with('message', 'Travel Order Added Successfully');
    }




    public function print(TravelOrder $TravelOrder)
    {
        $this->authorize('print', $TravelOrder);

        $Employee = Employee::with('Office', 'Unit')
            ->findOrFail($TravelOrder->employeeid);

        // Dates
        [$startDate, $endDate] = explode(' - ', $TravelOrder->daterange);
        $fmt   = str_contains($startDate, '/') ? 'm/d/Y' : 'Y-m-d';
        $date1 = \Carbon\Carbon::createFromFormat($fmt, trim($startDate))->format('F j, Y');
        $date2 = \Carbon\Carbon::createFromFormat($fmt, trim($endDate))->format('F j, Y');

        // Prefer saved signatory, fallback to mapping
        $sig = $TravelOrder->travelordersignatoryid
            ? TravelOrderSignatory::with('Employee1', 'Employee2')->find($TravelOrder->travelordersignatoryid)
            : null;

        if (!$sig) {
            $sigId = SetTravelOrderSignatory::where('officeid', $Employee->officeid)
                ->where('sectionid', $Employee->sectionid)
                ->value('travelordersignatoryid')
                ?? SetTravelOrderSignatory::where('sectionid', $Employee->sectionid)->value('travelordersignatoryid');

            $sig = $sigId ? TravelOrderSignatory::with('Employee1', 'Employee2')->find($sigId) : null;
        }

        if (!$sig) {
            return back()->with('SignatoryError', 'No Travel Order signatory configured for this employee.');
        }


        // Prefer exact link by request_id (kung meron)
        $TravelOrdernumber = null;
        if (Schema::hasColumn('travel_order_approved', 'request_id')) {
            $TravelOrdernumber = \App\Models\TravelOrderApproved::where('request_id', $TravelOrder->id)->first();
        }

        if (!$TravelOrdernumber) {
            $TravelOrdernumber = \App\Models\TravelOrderApproved::where('employeeid', $TravelOrder->employeeid)
                // unahin ang may created_at (pinaka-bago)
                ->orderByRaw('CASE WHEN created_at IS NULL THEN 1 ELSE 0 END, created_at DESC')
                ->orderByDesc('id')
                ->first();
        }

        if (!$TravelOrdernumber) {
            return response()->view('msd-panel.travel-order.print-error', [
                'TravelOrder' => $TravelOrder,
                'message'     => 'No Travel Order number issued yet (Approver 2 must approve).',
            ], 409);
        }

        $officeHeader = match ((int) $Employee->officeid) {
            1 => 'PROVINCIAL ENVIRONMENT AND NATURAL RESOURCES OFFICE',
            2, 3 => 'COMMUNITY ENVIRONMENT AND NATURAL RESOURCES OFFICE',
            default => '',
        };



        $SetTravelOrderSignatory = $sig;

        $approver1Emp = $TravelOrder->approve1_by
            ? Employee::find($TravelOrder->approve1_by)
            : optional($sig)->Employee1;

        $approver2Emp = $TravelOrder->approve2_by
            ? Employee::find($TravelOrder->approve2_by)
            : optional($sig)->Employee2;

        $approver3Emp = $TravelOrder->approve3_by
            ? Employee::find($TravelOrder->approve3_by)
            : optional($sig)->Employee3;

        // Ihanda ang formatted names/positions
        $approver1Name = $approver1Emp ? trim("{$approver1Emp->firstname} {$approver1Emp->middlename} {$approver1Emp->lastname}") : '';
        $approver2Name = $approver2Emp ? trim("{$approver2Emp->firstname} {$approver2Emp->middlename} {$approver2Emp->lastname}") : '';
        $approver3Name = $approver3Emp ? trim("{$approver3Emp->firstname} {$approver3Emp->middlename} {$approver3Emp->lastname}") : '';

        $approver1Pos  = $approver1Emp->position ?? '';
        $approver2Pos  = $approver2Emp->position ?? '';
        $approver3Pos  = $approver3Emp->position ?? '';

        // Document date shown on the print (when the TO was created)
        $ref      = $TravelOrder->created_at ?: $TravelOrder->updated_at ?: now();
        $docDate  = \Carbon\Carbon::parse($ref)
            ->timezone(config('app.timezone', 'Asia/Manila'))
            ->format('F d, Y');

        return view('msd-panel.travel-order.print', compact(
            'TravelOrder',
            'Employee',
            'SetTravelOrderSignatory',
            'TravelOrdernumber',
            'date1',
            'date2',
            'docDate',
            'approver1Name',
            'approver2Name',
            'approver3Name',
            'approver1Pos',
            'approver2Pos',
            'approver3Pos',
            'approver1Emp',
            'approver2Emp',
            'approver3Emp',
            'officeHeader',
        ));
    }



    public function advance(Request $request)
    {
        $this->authorize('MsdCreate', \App\Models\TravelOrder::class);
        $Employees = Employee::orderby('lastname', 'asc')->get();
        $TravelOrders = TravelOrder::orderby('created_at', 'asc')->where('employeeid', '=', $request->employeeid)->with('user')->get();
        $ApprovedTravelOrders = TravelOrderApproved::get();
        return view('msd-panel.travel-order.advancesearch', compact('Employees', 'TravelOrders', 'ApprovedTravelOrders'));
    }
}