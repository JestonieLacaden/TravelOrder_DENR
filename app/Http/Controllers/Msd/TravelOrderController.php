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

class TravelOrderController extends Controller
{
    public function index() 
    {

        $this->authorize('MsdCreate', \App\Models\TravelOrder::class);
      
        $Employees = Employee::orderby('lastname','asc')->get();
        $TravelOrders = TravelOrder::orderby('created_at','asc')->where('is_approve1','=',false)->where('is_approve2','=',false)->with('user')->get();
        // $Sections = Section::with('office')->get();
        // $SetLeaveSignatories = SetLeaveSignatory::with('section','Office')->get();
        // $Offices = Office::with('section')->get();
        // $LeaveSignatories = LeaveSignatory::get();
        // $ApprovedTravelOrders = TravelOrderApproved::get();
        // return view('msd-panel.travel-order.index',compact('Employees','TravelOrders','ApprovedTravelOrders'));
        return view('msd-panel.travel-order.index',compact('Employees','TravelOrders'));
    }   
    
    public function store(Request $request) {
        
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

        $Employee = Employee::where('id','=', $request->employeeid)->get()->first();
        $SetTravelOrderSignatory = SetTravelOrderSignatory::where('sectionid','=',$Employee->sectionid)->get()->first();

        if(!empty($SetTravelOrderSignatory))
        {
          $formfields['userid'] = auth()->user()->id;
          TravelOrder::create($formfields);
  
          return back()->with('message', 'Travel Order Added Successfully');
        }
        else
        {
          return back()->with('SignatoryError', 'Error!');
        }
       
      
       
     
     

    }

    public function update(Request $request, TravelOrder $TravelOrder)
{
    // If you wired a policy for update(), keep this line; otherwise you can comment it out.
    // $this->authorize('update', $TravelOrder);

    // Only the date range is editable
    $request->validate([
        'daterange' => 'required|string',
    ]);

    // If nothing changed, short-circuit
    if (trim($request->daterange) === trim($TravelOrder->daterange)) {
        return back()->with('message', 'No changes to save.');
    }

    // Parse the incoming range (accept MM/DD/YYYY or YYYY-MM-DD)
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

    // Enforce "same year" rule you already use elsewhere
    if ($start->year !== $end->year) {
        return back()->with('DateError1', true)->withInput();
    }

    // Normalize to MM/DD/YYYY - MM/DD/YYYY (matches your table display)
    $normalized = $start->format('m/d/Y') . ' - ' . $end->format('m/d/Y');

    // If normalized equals stored, also treat as no-op
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




    public function destroy(Request $request,$TravelOrder) {
   
        $Travelorder = TravelOrder::where('id','=',$TravelOrder)->get()->first();
        $this->authorize('delete', $Travelorder);
            $Travelorder->delete();
            return back()->with('message', 'Travel Order Deleted Successfully');

    }

    public function accept(\App\Models\TravelOrder $TravelOrder)
    {
        try {
            $this->authorize('accept', $TravelOrder);

            $current = \App\Models\Employee::where('email', auth()->user()->email)->firstOrFail();
            $sig = \App\Models\TravelOrderSignatory::findOrFail($TravelOrder->travelordersignatoryid);

            // Approver 1 path
            if ($sig->approver1 == $current->id) {
                if ($TravelOrder->is_approve1 || $TravelOrder->is_rejected1) {
                    return back()->with('message', 'Already processed by Approver 1.');
                }
                $TravelOrder->update(['is_approve1' => true]);
                return back()->with('message', 'Travel Order Successfully Approved!');
            }

            // Approver 2 path
            if ($sig->approver2 == $current->id) {
                if (!$TravelOrder->is_approve1) {
                    return back()->with('SignatoryError', 'Approver 1 must approve first.');
                }
                if ($TravelOrder->is_approve2 || $TravelOrder->is_rejected2) {
                    return back()->with('message', 'Already processed by Approver 2.');
                }

                $TravelOrder->update(['is_approve2' => true]);

                // Keep your TO number issuance (if any) here...
                // (TravelOrderApproved::create([...]) etc.)

                return back()->with('message', 'Travel Order Successfully Approved!');
            }

            return back()->with('SignatoryError', 'You are not assigned for this Travel Order.');
        } catch (\Throwable $e) {
            report($e);
            return back()->with('SignatoryError', 'Unexpected error.');
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
        return back()->with('message', 'Travel Order Successfully Rejected!');
    }

    if ($sig->approver2 == $current->id) {
        $TravelOrder->update(['is_rejected2' => true]);
        return back()->with('message', 'Travel Order Successfully Rejected!');
    }

    return back()->with('SignatoryError', 'You are not the assigned approver for this Travel Order.');
}


    public function userindex() 
    {
      
    $this->authorize('viewTravelOrderIndex', \App\Models\TravelOrder::class);
    $Employee = Employee::where('email', auth()->user()->email)->first();
    $TravelOrders = TravelOrder::where('employeeid', $Employee->id)->with('Employee')->orderBy('created_at','asc')->get();
    $ApprovedTravelOrders = TravelOrderApproved::get();

    $SignatoryOptions = SetTravelOrderSignatory::where('sectionid', $Employee->sectionid)
                          ->with('TravelOrderSignatory') // so we can show names
                          ->get();

    return view('user.travel-order.index', compact('Employee','TravelOrders','ApprovedTravelOrders','SignatoryOptions'));
      
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
        'destinationoffice'=> 'required',
        'purpose'          => 'required',
        'perdime'          => 'required',
        'appropriation'    => 'required',
        'remarks'          => 'required',
    ]);

    $employee = \App\Models\Employee::where('email', auth()->user()->email)->first();

    // find the mapped signatory for the requester’s section
    $set = \App\Models\SetTravelOrderSignatory::where('sectionid', $employee->sectionid)->first();
    if (!$set) {
        return back()->with('SignatoryError', 'No signatory mapping for your section.');
    }

    $formfields['userid']                  = auth()->id();
    $formfields['employeeid']              = $employee->id;
    $formfields['travelordersignatoryid']  = $set->travelordersignatoryid;

    \App\Models\TravelOrder::create($formfields);

    return back()->with('message', 'Travel Order Added Successfully');
}




  public function print(TravelOrder $TravelOrder)
{
    $this->authorize('print', $TravelOrder);

    $Employee = Employee::where('id', $TravelOrder->employeeid)
        ->with('Office','Unit')
        ->firstOrFail();

    // Dates
    [$startDate, $endDate] = explode(' - ', $TravelOrder->daterange);
    $fmt   = str_contains($startDate, '/') ? 'm/d/Y' : 'Y-m-d';
    $date1 = \Carbon\Carbon::createFromFormat($fmt, trim($startDate))->format('F j, Y');
    $date2 = \Carbon\Carbon::createFromFormat($fmt, trim($endDate))->format('F j, Y');

    // Prefer saved signatory, fallback to mapping
    $sig = $TravelOrder->travelordersignatoryid
        ? TravelOrderSignatory::with('Employee1','Employee2')->find($TravelOrder->travelordersignatoryid)
        : null;

    if (!$sig) {
        $sigId = SetTravelOrderSignatory::where('officeid', $Employee->officeid)
                ->where('sectionid', $Employee->sectionid)
                ->value('travelordersignatoryid')
            ?? SetTravelOrderSignatory::where('sectionid', $Employee->sectionid)->value('travelordersignatoryid');

        $sig = $sigId ? TravelOrderSignatory::with('Employee1','Employee2')->find($sigId) : null;
    }

    if (!$sig) {
        return back()->with('SignatoryError', 'No Travel Order signatory configured for this employee.');
    }

    $TravelOrdernumber = TravelOrderApproved::where('employeeid', $TravelOrder->id)->first();

    // keep variable name used by the view
    $SetTravelOrderSignatory = $sig;

    return view('msd-panel.travel-order.print', compact(
        'TravelOrder', 'Employee', 'SetTravelOrderSignatory', 'TravelOrdernumber', 'date1', 'date2'
    ));
}


  public function advance(Request $request) {
    $this->authorize('MsdCreate', \App\Models\TravelOrder::class);
    $Employees = Employee::orderby('lastname','asc')->get();
    $TravelOrders = TravelOrder::orderby('created_at','asc')->where('employeeid','=',$request->employeeid)->with('user')->get();
     $ApprovedTravelOrders = TravelOrderApproved::get();
    return view('msd-panel.travel-order.advancesearch',compact('Employees','TravelOrders','ApprovedTravelOrders'));
    }

    
}