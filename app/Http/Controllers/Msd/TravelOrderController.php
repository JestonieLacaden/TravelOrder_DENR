<?php

namespace App\Http\Controllers\Msd;

use Illuminate\Support\Facades\Log;
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
        $TravelOrders = TravelOrder::orderby('created_at', 'desc')->with(['user', 'employee'])->get();
        $ApprovedTravelOrders = TravelOrderApproved::get();
        // $Sections = Section::with('office')->get();
        // $SetLeaveSignatories = SetLeaveSignatory::with('section','Office')->get();
        // $Offices = Office::with('section')->get();
        // $LeaveSignatories = LeaveSignatory::get();
        return view('msd-panel.travel-order.index', compact('Employees', 'TravelOrders', 'ApprovedTravelOrders'));
    }
    public function downloadPDF(TravelOrder $TravelOrder)
    {
        ini_set('max_execution_time', '120');
        ini_set('memory_limit', '256M');

        $this->authorize('print', $TravelOrder);

        $Employee = Employee::with('Office', 'Unit')->findOrFail($TravelOrder->employeeid);

        [$startDate, $endDate] = explode(' - ', $TravelOrder->daterange);
        $fmt = str_contains($startDate, '/') ? 'm/d/Y' : 'Y-m-d';
        $date1 = \Carbon\Carbon::createFromFormat($fmt, trim($startDate))->format('F j, Y');
        $date2 = \Carbon\Carbon::createFromFormat($fmt, trim($endDate))->format('F j, Y');

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

        $TravelOrdernumber = null;
        if (Schema::hasColumn('travel_order_approved', 'request_id')) {
            $TravelOrdernumber = \App\Models\TravelOrderApproved::where('request_id', $TravelOrder->id)->first();
        }

        if (!$TravelOrdernumber) {
            $TravelOrdernumber = \App\Models\TravelOrderApproved::where('employeeid', $TravelOrder->employeeid)
                ->orderByRaw('CASE WHEN created_at IS NULL THEN 1 ELSE 0 END, created_at DESC')
                ->orderByDesc('id')
                ->first();
        }

        if (!$TravelOrdernumber) {
            return back()->with('error', 'No Travel Order number issued yet.');
        }

        $officeHeader = match ((int) $Employee->officeid) {
            1 => 'PROVINCIAL ENVIRONMENT AND NATURAL RESOURCES OFFICE',
            2, 3 => 'COMMUNITY ENVIRONMENT AND NATURAL RESOURCES OFFICE',
            default => '',
        };

        $SetTravelOrderSignatory = $sig;
        $approver1Emp = $TravelOrder->approve1_by ? Employee::find($TravelOrder->approve1_by) : optional($sig)->Employee1;
        $approver2Emp = $TravelOrder->approve2_by ? Employee::find($TravelOrder->approve2_by) : optional($sig)->Employee2;
        $approver3Emp = $TravelOrder->approve3_by ? Employee::find($TravelOrder->approve3_by) : optional($sig)->Employee3;

        $approver1Name = $approver1Emp ? trim("{$approver1Emp->firstname} {$approver1Emp->middlename} {$approver1Emp->lastname}") : '';
        $approver2Name = $approver2Emp ? trim("{$approver2Emp->firstname} {$approver2Emp->middlename} {$approver2Emp->lastname}") : '';
        $approver3Name = $approver3Emp ? trim("{$approver3Emp->firstname} {$approver3Emp->middlename} {$approver3Emp->lastname}") : '';

        $approver1Pos = $approver1Emp->position ?? '';
        $approver2Pos = $approver2Emp->position ?? '';
        $approver3Pos = $approver3Emp->position ?? '';

        $ref = $TravelOrder->created_at ?: $TravelOrder->updated_at ?: now();
        $docDate = \Carbon\Carbon::parse($ref)->timezone(config('app.timezone', 'Asia/Manila'))->format('F d, Y');

        $pdf = \PDF::loadView('msd-panel.travel-order.print', compact(
            'TravelOrder', 'Employee', 'SetTravelOrderSignatory', 'TravelOrdernumber',
            'date1', 'date2', 'docDate', 'approver1Name', 'approver2Name', 'approver3Name',
            'approver1Pos', 'approver2Pos', 'approver3Pos', 'approver1Emp', 'approver2Emp', 'approver3Emp', 'officeHeader'
        ))->setPaper('a4')->setWarnings(false);

        return $pdf->download('TravelOrder_' . $Employee->lastname . '_' . date('Ymd') . '.pdf');
    }
    public function store(Request $request)
    {
        Log::info('TO SUBMIT: employeeid=' . $request->employeeid);
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

        // Debug info
        $debugMsg = '';

        // Determine signatory based on employee position
        $approver1 = null;
        $approver2 = null;
        $approver3 = $this->getPENROId(); // PENRO is always final approver

        // PENRO creates own TO - auto-approve
        if ($this->isPENRO($employee)) {
            $approver1 = null;
            $approver2 = null;
            $approver3 = $employee->id; // Self-sign
            $debugMsg = 'PENRO self-create flow.';
            Log::info('TO FLOW: PENRO self-create', ['approver1' => $approver1, 'approver2' => $approver2, 'approver3' => $approver3]);
        }
        // Division Chief (MSD/TSD) creates own TO - skip to PENRO
        elseif ($this->isDivisionChief($employee)) {
            $approver1 = null;
            $approver2 = null;
            $approver3 = $this->getPENROId();
            $debugMsg = 'Division Chief flow.';
            Log::info('TO FLOW: Division Chief', ['approver1' => $approver1, 'approver2' => $approver2, 'approver3' => $approver3]);
        }
        // Section Chief creates own TO - skip section chief level
        elseif ($this->isSectionChief($employee)) {
            $approver1 = null;
            $approver2 = $this->getDivisionChiefId($employee->sectionid);
            $approver3 = $this->getPENROId();
            $debugMsg = 'Section Chief flow.';
            Log::info('TO FLOW: Section Chief', ['approver1' => $approver1, 'approver2' => $approver2, 'approver3' => $approver3]);
        }
        // Regular Employee - full 3-level approval
        else {
            $approver1 = $this->getSectionChiefId($employee->unitid);
            $approver2 = $this->getDivisionChiefId($employee->sectionid);
            $approver3 = $this->getPENROId();

            // Section Chief is optional - if not set, skip that approval level
            if (!$approver1) {
                Log::info('TO INFO: No Section Chief assigned, skipping approver1 level', ['unit' => $employee->Unit->unit ?? 'Unknown', 'employeeid' => $employee->id]);
            }
            $debugMsg = 'Regular employee flow.';
            Log::info('TO FLOW: Regular employee', ['approver1' => $approver1, 'approver2' => $approver2, 'approver3' => $approver3]);
        }

        // Validate required approvers exist
        if (!$approver3) {
            Log::warning('TO ERROR: Walang PENRO', ['employeeid' => $employee->id]);
            return back()->with('SignatoryError', 'Walang PENRO na naka-set sa system! [Debug: No PENRO]');
        }

        if ($approver2 && !$this->isPENRO($employee) && !$this->isDivisionChief($employee)) {
            if (!Employee::find($approver2)) {
                Log::warning('TO ERROR: Walang Division Chief', ['employeeid' => $employee->id, 'approver2' => $approver2]);
                return back()->with('SignatoryError', 'Walang Division Chief na nahanap! [Debug: No Division Chief]');
            }
        }

        // Create or get signatory record
        $signatory = TravelOrderSignatory::firstOrCreate([
            'approver1' => $approver1,
            'approver2' => $approver2,
            'approver3' => $approver3,
        ]);
        Log::info('TO SIGNATORY: created or fetched', ['signatory_id' => $signatory->id, 'approver1' => $approver1, 'approver2' => $approver2, 'approver3' => $approver3]);

        $formfields['userid'] = auth()->user()->id;
        $formfields['travelordersignatoryid'] = $signatory->id;

        // If no section chief, auto-forward to approver2
        if (!$approver1) {
            $formfields['is_approve1'] = true;
            $formfields['approve1_by'] = null;
            $formfields['approve1_at'] = now();
            Log::info('TO AUTO-FORWARD: No section chief, skipping to approver2', ['signatory_id' => $signatory->id]);
        }

        Log::info('TO FINAL FIELDS', $formfields);

        // If PENRO self-creates, auto-approve
        if ($this->isPENRO($employee) && $employee->id == $approver3) {
            $formfields['is_approve1'] = false;
            $formfields['is_approve2'] = false;
            $formfields['is_approve3'] = true;
            $formfields['approve3_by'] = $employee->id;
            $formfields['approve3_at'] = now();

            $travelOrder = TravelOrder::create($formfields);
            Log::info('TO CREATED: PENRO auto-approve', ['travelorder_id' => $travelOrder->id]);

            // Generate TO Number immediately
            $this->generateTONumber($travelOrder);

            return back()->with('message', 'Travel Order Created and Auto-Approved (PENRO)! [Debug: ' . $debugMsg . ']');
        }

        $created = TravelOrder::create($formfields);
        Log::info('TO CREATED: regular', ['created' => $created ? true : false]);

        if ($created) {
            return back()->with('message', 'Travel Order Added Successfully! [Debug: ' . $debugMsg . ']');
        } else {
            Log::error('TO ERROR: Hindi naisave ang Travel Order', $formfields);
            return back()->with('SignatoryError', 'Hindi naisave ang Travel Order. [Debug: DB insert failed]');
        }
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
            if ($sig->approver1 && $sig->approver1 == $current->id) {
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
                // Only require Approver 1 approval if approver1 is set
                if ($sig->approver1 && !$TravelOrder->is_approve1) {
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
                // Only require Approver 1 approval if approver1 is set
                if ($sig->approver1 && !$TravelOrder->is_approve1) {
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






    public function reject(Request $request, TravelOrder $TravelOrder)
    {
        $this->authorize('reject', $TravelOrder);

        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

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

        $rejectionReason = $request->rejection_reason;

        if ($sig->approver1 == $current->id) {
            $TravelOrder->update([
                'is_rejected1' => true,
                'rejected1_reason' => $rejectionReason,
            ]);
            event(new \App\Events\TravelOrderStatusChanged($TravelOrder));
            return back()->with('message', 'Travel Order Successfully Rejected!');
        }

        if ($sig->approver2 == $current->id) {
            $TravelOrder->update([
                'is_rejected2' => true,
                'rejected2_reason' => $rejectionReason,
            ]);
            event(new \App\Events\TravelOrderStatusChanged($TravelOrder));
            return back()->with('message', 'Travel Order Successfully Rejected!');
        }

        if ($sig->approver3 == $current->id) {
            $TravelOrder->update([
                'is_rejected3' => true,
                'rejected3_reason' => $rejectionReason,
            ]);
            event(new \App\Events\TravelOrderStatusChanged($TravelOrder));
            return back()->with('message', 'Travel Order Successfully Rejected!');
        }

        return back()->with('SignatoryError', 'You are not the assigned approver for this Travel Order.');
    }

    /**
     * Return Travel Order to user for revision
     */
    public function returnToUser(Request $request, TravelOrder $TravelOrder)
    {
        $this->authorize('reject', $TravelOrder);

        $request->validate([
            'return_reason' => 'required|string|max:1000',
        ]);

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

        $returnReason = $request->return_reason;

        // Reset all previous approvals and set as returned by current approver
        if ($sig->approver1 == $current->id) {
            $TravelOrder->update([
                'is_approve1' => false,
                'is_returned1' => true,
                'returned1_reason' => $returnReason,
                'approve1_at' => null,
                'approve1_by' => null,
            ]);
            event(new \App\Events\TravelOrderStatusChanged($TravelOrder));
            return back()->with('message', 'Travel Order returned to user for revision!');
        }

        if ($sig->approver2 == $current->id) {
            $TravelOrder->update([
                'is_approve2' => false,
                'is_returned2' => true,
                'returned2_reason' => $returnReason,
                'approve2_at' => null,
                'approve2_by' => null,
                // Keep approver 1 approval - do NOT reset approve1_by and approve1_at
            ]);
            event(new \App\Events\TravelOrderStatusChanged($TravelOrder));
            return back()->with('message', 'Travel Order returned to user for revision!');
        }

        if ($sig->approver3 == $current->id) {
            $TravelOrder->update([
                'is_approve3' => false,
                'is_returned3' => true,
                'returned3_reason' => $returnReason,
                'approve3_at' => null,
                'approve3_by' => null,
                // Keep approver 1 and 2 approvals - do NOT reset their approve_by and approve_at
            ]);
            event(new \App\Events\TravelOrderStatusChanged($TravelOrder));
            return back()->with('message', 'Travel Order returned to user for revision!');
        }

        return back()->with('SignatoryError', 'You are not the assigned approver for this Travel Order.');
    }


    public function userindex()
    {

        $this->authorize('viewTravelOrderIndex', \App\Models\TravelOrder::class);
        $Employee = Employee::where('email', auth()->user()->email)->with('section')->first();
        $TravelOrders = TravelOrder::where('employeeid', $Employee->id)
            ->with(['Employee', 'approved', 'selectedSignatory']) // load approved relation and signatory
            ->orderBy('created_at', 'asc')
            ->get();

        // Extract division name from user's section name (e.g., "MANAGEMENT SERVICES DIVISION" from "ACCOUNTING SECTION")
        $userSectionName = $Employee->section->section ?? '';

        // Determine user's division based on section name pattern
        $userDivision = null;
        if (stripos($userSectionName, 'MANAGEMENT SERVICES DIVISION') !== false) {
            $userDivision = 'MANAGEMENT SERVICES DIVISION';
        } elseif (stripos($userSectionName, 'TECHNICAL SERVICES DIVISION') !== false) {
            $userDivision = 'TECHNICAL SERVICES DIVISION';
        } elseif (stripos($userSectionName, 'OFFICE OF THE PENRO') !== false) {
            $userDivision = 'OFFICE OF THE PENRO';
        } else {
            // Fallback: extract "SERVICES DIVISION" or "OFFICE OF" pattern
            if (preg_match('/(.*SERVICES DIVISION|OFFICE OF.*)/i', $userSectionName, $matches)) {
                $userDivision = trim($matches[1]);
            }
        }

        // Get eligible employees for each signatory role - FILTERED by user's unit/section
        // Section Chiefs: Same unit as current employee (immediate supervisor within same unit/section)
        $SectionChiefs = Employee::whereHas('eligibleSignatory', function($query) {
            $query->where('role', 'section_chief');
        })
        ->where('unitid', $Employee->unitid) // Filter by same UNIT (e.g., PLANNING SECTION)
        ->where('id', '!=', $Employee->id) // Exclude self
        ->orderBy('lastname', 'asc')
        ->get();

        // Division Chiefs: Get ALL Division Chiefs within the SAME DIVISION (e.g., MANAGEMENT SERVICES DIVISION)
        // This shows all possible Division Chiefs that could be in charge of the user's division
        $DivisionChiefs = Employee::whereHas('eligibleSignatory', function($query) {
            $query->where('role', 'division_chief');
        })
        ->where('sectionid', $Employee->sectionid) // Filter by same DIVISION (shows all units within division)
        ->with(['section.office']) // Eager load section and office for display
        ->where('id', '!=', $Employee->id)
        ->orderBy('lastname', 'asc')
        ->get();

        // PENROs: Final approvers - ALL eligible PENROs (not filtered by division)
        $PENROs = Employee::whereHas('eligibleSignatory', function($query) {
            $query->where('role', 'penro');
        })
        ->with(['section.office']) // Eager load section and office for display
        ->where('id', '!=', $Employee->id)
        ->orderBy('lastname', 'asc')
        ->get();

        $SignatoryOptions = SetTravelOrderSignatory::where('sectionid', $Employee->sectionid)
            ->with('TravelOrderSignatory') // so we can show names
            ->get();

        return view('user.travel-order.index', compact('Employee', 'TravelOrders', 'SignatoryOptions', 'SectionChiefs', 'DivisionChiefs', 'PENROs'));

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
            'daterange'         => 'required',
            'destinationoffice' => 'required',
            'purpose'           => 'required',
            'perdime'           => 'required',
            'appropriation'     => 'required',
            'other_appropriation' => 'required_if:appropriation,other',
            'remarks'           => 'required',
            'is_prepayment'     => 'nullable|boolean',
            'approver1'         => 'nullable|exists:employee,id',
            'approver2'         => 'nullable|exists:employee,id',
            'approver3'         => 'required|exists:employee,id',
        ]);

        $employee = \App\Models\Employee::where('email', auth()->user()->email)->first();

        // Handle "other" appropriation
        if ($request->appropriation === 'other' && $request->other_appropriation) {
            $formfields['appropriation'] = $request->other_appropriation;
        }
        unset($formfields['other_appropriation']);

        // Validate signature if pre-payment is checked
        if ($request->has('is_prepayment') && $request->is_prepayment) {
            if (!$employee || !$employee->signature_path) {
                return back()->withErrors(['is_prepayment' => 'Please upload your signature in your profile first before creating a pre-payment travel order.'])->withInput();
            }
        }

        Log::info('User TO submit (storeUserTravelOrder) by employee', [
            'employee_id' => optional($employee)->id,
            'email' => auth()->user()->email,
            'approver1' => $request->approver1,
            'approver2' => $request->approver2,
            'approver3' => $request->approver3,
            'is_prepayment' => $request->has('is_prepayment'),
        ]);

        // Get user-selected approvers
        $approver1 = $request->approver1 ?: null;
        $approver2 = $request->approver2;
        $approver3 = $request->approver3;

        // Create or get signatory record with user-selected approvers
        $signatory = \App\Models\TravelOrderSignatory::firstOrCreate([
            'approver1' => $approver1,
            'approver2' => $approver2,
            'approver3' => $approver3,
        ], [
            'name' => 'User Selected - ' . now()->format('Y-m-d H:i:s')
        ]);

        Log::info('User TO: Using signatory', ['signatory_id' => $signatory->id, 'approver1' => $approver1, 'approver2' => $approver2, 'approver3' => $approver3]);

        // Setup form fields
        $formfields['userid']                  = auth()->id();
        $formfields['employeeid']              = $employee->id;
        $formfields['travelordersignatoryid']  = $signatory->id;
        $formfields['is_prepayment']           = $request->has('is_prepayment') ? true : false;

        // Use employee's signature from profile for pre-payment
        if ($formfields['is_prepayment']) {
            $formfields['employee_signature'] = $employee->signature_path;
        } else {
            $formfields['employee_signature'] = null;
        }

        // Auto-forward logic based on selected approvers
        if (!$approver1 && !$approver2) {
            // Only PENRO selected - skip both approver1 and approver2
            $formfields['is_approve1'] = true;
            $formfields['approve1_by'] = null;
            $formfields['approve1_at'] = now();
            $formfields['is_approve2'] = true;
            $formfields['approve2_by'] = null;
            $formfields['approve2_at'] = now();
            Log::info('User TO AUTO-FORWARD: Only PENRO selected, skipping to PENRO approval', ['employee_id' => $employee->id]);
        } elseif (!$approver1) {
            // No section chief selected, auto-forward to approver2 (Division Chief)
            $formfields['is_approve1'] = true;
            $formfields['approve1_by'] = null;
            $formfields['approve1_at'] = now();
            Log::info('User TO AUTO-FORWARD: No section chief selected, skipping to approver2', ['employee_id' => $employee->id]);
        }

        $created = \App\Models\TravelOrder::create($formfields);
        if ($created) {
            Log::info('User TO created', ['travelorder_id' => $created->id, 'employee_id' => $employee->id, 'is_prepayment' => $formfields['is_prepayment']]);
            return back()->with('success', 'Travel Order Added Successfully');
        }
        Log::error('User TO create failed', ['payload' => $formfields]);
        return back()->with('error', 'Hindi naisave ang Travel Order.');
    }

    public function updateUserTravelOrder(Request $request, TravelOrder $TravelOrder)
    {
        $this->authorize('update', $TravelOrder);

        $formfields = $request->validate([
            'daterange'         => 'required',
            'destinationoffice' => 'required',
            'purpose'           => 'required',
            'perdime'           => 'required',
            'appropriation'     => 'required',
            'other_appropriation' => 'required_if:appropriation,other',
            'remarks'           => 'required',
            'is_prepayment'     => 'nullable|boolean',
            'approver1'         => 'nullable|exists:employee,id',
            'approver2'         => 'nullable|exists:employee,id',
            'approver3'         => 'required|exists:employee,id',
        ]);

        $employee = \App\Models\Employee::where('email', auth()->user()->email)->first();

        // Handle "other" appropriation
        if ($request->appropriation === 'other' && $request->other_appropriation) {
            $formfields['appropriation'] = $request->other_appropriation;
        }
        unset($formfields['other_appropriation']);

        // Validate signature if pre-payment is checked
        if ($request->has('is_prepayment') && $request->is_prepayment) {
            if (!$employee || !$employee->signature_path) {
                return back()->withErrors(['is_prepayment' => 'Please upload your signature in your profile first before creating a pre-payment travel order.'])->withInput();
            }
        }

        Log::info('User TO update by employee', [
            'travelorder_id' => $TravelOrder->id,
            'employee_id' => optional($employee)->id,
            'email' => auth()->user()->email,
            'approver1' => $request->approver1,
            'approver2' => $request->approver2,
            'approver3' => $request->approver3,
            'is_prepayment' => $request->has('is_prepayment'),
        ]);

        // Get user-selected approvers
        $approver1 = $request->approver1 ?: null;
        $approver2 = $request->approver2;
        $approver3 = $request->approver3;

        // Create or get signatory record with user-selected approvers
        $signatory = \App\Models\TravelOrderSignatory::firstOrCreate([
            'approver1' => $approver1,
            'approver2' => $approver2,
            'approver3' => $approver3,
        ], [
            'name' => 'User Selected - ' . now()->format('Y-m-d H:i:s')
        ]);

        Log::info('User TO: Updating with signatory', ['signatory_id' => $signatory->id, 'approver1' => $approver1, 'approver2' => $approver2, 'approver3' => $approver3]);

        // Setup form fields
        $formfields['travelordersignatoryid']  = $signatory->id;
        $formfields['is_prepayment']           = $request->has('is_prepayment') ? true : false;

        // Use employee's signature from profile for pre-payment
        if ($formfields['is_prepayment']) {
            $formfields['employee_signature'] = $employee->signature_path;
        } else {
            $formfields['employee_signature'] = null;
        }

        // Check if this is a returned request (being resubmitted after revision)
        $wasReturned1 = $TravelOrder->is_returned1;
        $wasReturned2 = $TravelOrder->is_returned2;
        $wasReturned3 = $TravelOrder->is_returned3;

        // Clear return flags when user resubmits
        if ($wasReturned1 || $wasReturned2 || $wasReturned3) {
            $formfields['is_returned1'] = false;
            $formfields['returned1_reason'] = null;
            $formfields['is_returned2'] = false;
            $formfields['returned2_reason'] = null;
            $formfields['is_returned3'] = false;
            $formfields['returned3_reason'] = null;

            // If returned by approver3, reset to approver3's turn (keep approver1 and approver2 approved)
            if ($wasReturned3) {
                // Keep approver1 and approver2 approvals WITH their approval records
                $formfields['is_approve1'] = true;
                $formfields['approve1_by'] = $TravelOrder->approve1_by; // Explicitly preserve
                $formfields['approve1_at'] = $TravelOrder->approve1_at; // Explicitly preserve
                $formfields['is_approve2'] = true;
                $formfields['approve2_by'] = $TravelOrder->approve2_by; // Explicitly preserve
                $formfields['approve2_at'] = $TravelOrder->approve2_at; // Explicitly preserve
                $formfields['is_approve3'] = false;
                $formfields['approve3_by'] = null;
                $formfields['approve3_at'] = null;
                Log::info('User TO UPDATE: Returned by approver3, preserving approvals', [
                    'travelorder_id' => $TravelOrder->id,
                    'approve1_by' => $formfields['approve1_by'],
                    'approve2_by' => $formfields['approve2_by']
                ]);
            }
            // If returned by approver2, reset to approver2's turn (keep approver1 approved)
            elseif ($wasReturned2) {
                // Keep approver1 approval WITH approval record
                $formfields['is_approve1'] = true;
                $formfields['approve1_by'] = $TravelOrder->approve1_by; // Explicitly preserve
                $formfields['approve1_at'] = $TravelOrder->approve1_at; // Explicitly preserve
                $formfields['is_approve2'] = false;
                $formfields['approve2_by'] = null;
                $formfields['approve2_at'] = null;
                $formfields['is_approve3'] = false;
                $formfields['approve3_by'] = null;
                $formfields['approve3_at'] = null;
                Log::info('User TO UPDATE: Returned by approver2, preserving approve1', [
                    'travelorder_id' => $TravelOrder->id,
                    'approve1_by' => $formfields['approve1_by'],
                    'approve1_at' => $formfields['approve1_at']
                ]);
            }
            // If returned by approver1, reset to approver1's turn
            elseif ($wasReturned1) {
                // Reset all approvals
                $formfields['is_approve1'] = false;
                $formfields['approve1_by'] = null;
                $formfields['approve1_at'] = null;
                $formfields['is_approve2'] = false;
                $formfields['approve2_by'] = null;
                $formfields['approve2_at'] = null;
                $formfields['is_approve3'] = false;
                $formfields['approve3_by'] = null;
                $formfields['approve3_at'] = null;
                Log::info('User TO UPDATE: Returned by approver1, sending back to approver1', ['travelorder_id' => $TravelOrder->id]);
            }
        } else {
            // Normal update (not a returned request) - apply auto-forward logic
            // Auto-forward logic based on selected approvers
            if (!$approver1 && !$approver2) {
                // Only PENRO selected - skip both approver1 and approver2
                $formfields['is_approve1'] = true;
                $formfields['approve1_by'] = null;
                $formfields['approve1_at'] = now();
                $formfields['is_approve2'] = true;
                $formfields['approve2_by'] = null;
                $formfields['approve2_at'] = now();
                Log::info('User TO UPDATE AUTO-FORWARD: Only PENRO selected, skipping to PENRO approval', ['travelorder_id' => $TravelOrder->id]);
            } elseif (!$approver1) {
                // No section chief selected, auto-forward to approver2 (Division Chief)
                $formfields['is_approve1'] = true;
                $formfields['approve1_by'] = null;
                $formfields['approve1_at'] = now();
                Log::info('User TO UPDATE AUTO-FORWARD: No section chief selected, skipping to approver2', ['travelorder_id' => $TravelOrder->id]);
            } else {
                // Reset approval if changing from no approver1 to having one
                $formfields['is_approve1'] = false;
                $formfields['approve1_by'] = null;
                $formfields['approve1_at'] = null;
            }
        }

        $updated = $TravelOrder->update($formfields);
        if ($updated) {
            // Reload to verify what was actually saved
            $TravelOrder->refresh();
            Log::info('User TO updated successfully', [
                'travelorder_id' => $TravelOrder->id,
                'employee_id' => $employee->id,
                'approve1_by_after_update' => $TravelOrder->approve1_by,
                'approve2_by_after_update' => $TravelOrder->approve2_by,
                'is_approve1' => $TravelOrder->is_approve1,
                'is_approve2' => $TravelOrder->is_approve2
            ]);
            return back()->with('message', 'Travel Order Updated Successfully');
        }
        Log::error('User TO update failed', ['travelorder_id' => $TravelOrder->id, 'payload' => $formfields]);
        return back()->with('error', 'Hindi nai-update ang Travel Order.');
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

    public function download(TravelOrder $TravelOrder)
    {
        // Increase timeout and memory for PDF generation
        set_time_limit(300);
        ini_set('memory_limit', '512M');

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

        // Prefer exact link by request_id
        $TravelOrdernumber = null;
        if (Schema::hasColumn('travel_order_approved', 'request_id')) {
            $TravelOrdernumber = \App\Models\TravelOrderApproved::where('request_id', $TravelOrder->id)->first();
        }

        if (!$TravelOrdernumber) {
            $TravelOrdernumber = \App\Models\TravelOrderApproved::where('employeeid', $TravelOrder->employeeid)
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

        $approver1Name = $approver1Emp ? trim("{$approver1Emp->firstname} {$approver1Emp->middlename} {$approver1Emp->lastname}") : '';
        $approver2Name = $approver2Emp ? trim("{$approver2Emp->firstname} {$approver2Emp->middlename} {$approver2Emp->lastname}") : '';
        $approver3Name = $approver3Emp ? trim("{$approver3Emp->firstname} {$approver3Emp->middlename} {$approver3Emp->lastname}") : '';

        $approver1Pos  = $approver1Emp->position ?? '';
        $approver2Pos  = $approver2Emp->position ?? '';
        $approver3Pos  = $approver3Emp->position ?? '';

        $ref      = $TravelOrder->created_at ?: $TravelOrder->updated_at ?: now();
        $docDate  = \Carbon\Carbon::parse($ref)
            ->timezone(config('app.timezone', 'Asia/Manila'))
            ->format('F d, Y');

        try {
            // Generate PDF with optimized settings
            $pdf = \PDF::loadView('msd-panel.travel-order.print', compact(
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

            $pdf->setPaper('a4', 'portrait');
            $pdf->setWarnings(false);

            $filename = 'TravelOrder_' . $Employee->lastname . '_' . date('Ymd') . '.pdf';

            return $pdf->download($filename);
        } catch (\Exception $e) {
            \Log::error('PDF Generation Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to generate PDF. Please try print instead.');
        }
    }



    public function advance(Request $request)
    {
        $this->authorize('MsdCreate', \App\Models\TravelOrder::class);
        $Employees = Employee::orderby('lastname', 'asc')->get();
        $TravelOrders = TravelOrder::orderby('created_at', 'asc')->where('employeeid', '=', $request->employeeid)->with(['user', 'employee'])->get();
        $ApprovedTravelOrders = TravelOrderApproved::get();
        return view('msd-panel.travel-order.advancesearch', compact('Employees', 'TravelOrders', 'ApprovedTravelOrders'));
    }
}
