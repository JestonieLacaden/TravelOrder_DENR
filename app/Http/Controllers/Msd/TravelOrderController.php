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

    public function accept(TravelOrder $TravelOrder) {
        try
        {
          $this->authorize('accept', $TravelOrder);
      
          $TravelOrderofEmployee = Employee::where('id','=', $TravelOrder->employeeid)->get()->first();
          $SetTravelOrderSignatory = SetTravelOrderSignatory::where('sectionid','=',$TravelOrderofEmployee->sectionid)->get()->first();
          $TravelOrderSignatory = TravelOrderSignatory::where('id','=',$SetTravelOrderSignatory->travelordersignatoryid)->get()->first();
          $Employee = Employee::where('email','=',auth()->user()->email)->get()->first();
        
            if($TravelOrderSignatory->approver1 == $Employee->id && auth()->check()) 
            {
              $formfields['is_approve1'] = true;
              $TravelOrder->update($formfields);
              return back()->with('message', 'Travel Order Successfully Approved!');
            } 
            if($TravelOrderSignatory->approver2 == $Employee->id && auth()->check()) 
            {
              $formfields['is_approve2'] = true;
              $TravelOrder->update($formfields);
                //
              $data['employeeid'] = $TravelOrder->id;
              $project = TravelOrderApproved::where('travelorderid', 'LIKE', '%' . date('Y') . '%')->latest()->first();
        
             if($project != null) 
              {
                $newproject = $project->id;
                $projectcount = (int)$newproject + 1;
              }
              else
                {
                  $projectcount = 1;
                }

             $data['travelorderid'] = 'TO'.'-'.date('Y') .'-'. str_pad($projectcount, 5, '0', STR_PAD_LEFT) ;

             TravelOrderApproved::create($data);

                
              return back()->with('message', 'Travel Order Successfully Approved!');
            } 
           
        
      }    
      catch (Throwable $e) {
        report($e);
      
        return false;
      }
      } 
      
      public function reject(TravelOrder $TravelOrder) {
        $this->authorize('reject', $TravelOrder);
        $TravelOrderSignatories = TravelOrderSignatory::get();
        $Employee = Employee::where('email','=',auth()->user()->email)->get()->first();
       
        foreach($TravelOrderSignatories as $TravelOrderSignatory)
        {
          if($TravelOrderSignatory->approver1 == $Employee->id && auth()->check()) 
          {
            $formfields['is_rejected1'] = true;
            $TravelOrder->update($formfields);
            return back()->with('message', 'Travel Order Successfully Rejected!');
          } 
          if($TravelOrderSignatory->approver2 == $Employee->id && auth()->check()) 
          {
            $formfields['is_rejected2'] = true;
            $TravelOrder->update($formfields);
            return back()->with('message', 'Travel Order Successfully Rejected!');
          } 
          
        }
    }

    public function userindex() 
    {
      $this->authorize('viewTravelOrderIndex', \App\Models\TravelOrder::class);
        $Employee = Employee::where('email','=', auth()->user()->email)->get()->first();
    
        $TravelOrders = TravelOrder::where('employeeid','=',$Employee->id)->with('Employee')->orderBy('created_at','asc')->get();
  
        $ApprovedTravelOrders = TravelOrderApproved::get();


        return view('user.travel-order.index', compact('Employee','TravelOrders','ApprovedTravelOrders'));
    }

    public function storeUserTravelOrder(Request $request) {
        
      $this->authorize('AddUserTravelOrder', \App\Models\TravelOrder::class);

      $formfields = $request->validate([
           
            'daterange' => 'required',
            'destinationoffice' => 'required',
            'purpose' => 'required',
            'perdime' => 'required',
            'appropriation' => 'required',
            'remarks' => 'required',
         
      ]);


      $Employee = Employee::where('email','=', auth()->user()->email)->get()->first();
      $SetTravelOrderSignatory = SetTravelOrderSignatory::where('sectionid','=',$Employee->sectionid)->get()->first();

      if(!empty($SetTravelOrderSignatory))
      {
        $formfields['userid'] = auth()->user()->id;
        $formfields['employeeid'] = $Employee->id;
        TravelOrder::create($formfields);

        return back()->with('message', 'Travel Order Added Successfully');
      }
      else
      {
        return back()->with('SignatoryError', 'Error!');
      }

  }

  public function print(TravelOrder $TravelOrder) {

 
    $this->authorize('print', $TravelOrder);

          $Employee = Employee::where('id','=',$TravelOrder->employeeid)->with('Office','Unit')->get()->first();
          $GetTravelOrderSignatory = SetTravelOrderSignatory::where('sectionid','=', $Employee->sectionid)->get()->first();

          if(!empty($GetTravelOrderSignatory)) {
            $TravelOrdernumber = TravelOrderApproved::where('employeeid','=',$TravelOrder->id)->get()->first();
            $data = $TravelOrder->daterange;
            list($startDate, $endDate) = explode(" - ", $data);
            // Detect how the daterange is stored, e.g. "07/08/2025 - 07/11/2025" or "2025-07-08 - 2025-07-11"
            $inputFmt = str_contains($startDate, '/') ? 'm/d/Y' : 'Y-m-d';

            // Full month name (F). Use 'j' for day without leading zero.
            $date1 = \Carbon\Carbon::createFromFormat($inputFmt, trim($startDate))->format('F j, Y'); // January 1, 2025
            $date2 = \Carbon\Carbon::createFromFormat($inputFmt, trim($endDate))->format('F j, Y');   // January 5, 2025

            $SetTravelOrderSignatory = TravelOrderSignatory::where('id', '=', $GetTravelOrderSignatory->travelordersignatoryid)->with('Employee1','Employee2')->get()->first();

            return view('msd-panel.travel-order.print',compact('TravelOrder','Employee','SetTravelOrderSignatory','TravelOrdernumber','date1','date2'));
          }
          else
          {
            return back()->with('SignatoryError', 'Error');
          }
     
  }

  public function advance(Request $request) {
    $this->authorize('MsdCreate', \App\Models\TravelOrder::class);
    $Employees = Employee::orderby('lastname','asc')->get();
    $TravelOrders = TravelOrder::orderby('created_at','asc')->where('employeeid','=',$request->employeeid)->with('user')->get();
     $ApprovedTravelOrders = TravelOrderApproved::get();
    return view('msd-panel.travel-order.advancesearch',compact('Employees','TravelOrders','ApprovedTravelOrders'));
    }

    
}