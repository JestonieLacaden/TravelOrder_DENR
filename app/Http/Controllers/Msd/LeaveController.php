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
use App\Models\SetLeaveSignatory;
use App\Http\Controllers\Controller;
use App\Models\SetTravelOrderSignatory;
use Illuminate\Support\Facades\DB;

class LeaveController extends Controller
{
    public function index() {
      try
      {

        $this->authorize('MSDaddLeave', \App\Models\Leave::class);

        $Employees = Employee::where('empstatus','=', 'PERMANENT')->orderBy('lastname', 'asc')->get();
        $Leave_Types = Leave_Type::orderBy ('id', 'asc')->get();
        $Leaves = Leave::with('Employee')->with('Leave_type')->orderBy('created_at','asc')->get();
    
      
        // $Events = Event::orderBy('date', 'asc')->with('User')->get();
        return view('msd-panel.leave.index', compact('Employees', 'Leave_Types','Leaves'));
        }
        catch (Throwable $e) {
          report($e);
        
          return false;
        }
    }

    
    
    public function store(Request $request) {
        
        $this->authorize('create', \App\Models\Leave::class);

        $formfields = $request->validate([
             
            'employeeid' => 'required',
            'leaveid' => 'required',
            'daterange' => 'required',
           
        ]);

        $data = $request->daterange;
        list($startDate, $endDate) = explode(" -", $data);
        $date1 = Carbon::createFromDate($startDate)->format('Y');
        $date2 = Carbon::createFromDate($endDate)->format('Y');
        $formfields['userid'] = auth()->user()->id;
        if ($date1 == $date2)
        {
            $formfields['yearapplied'] = $date1;
      
            $Employee = Employee::where('id','=', $request->employeeid)->get()->first();
            $SetLeaveSignatory = SetLeaveSignatory::where('sectionid','=',$Employee->sectionid)->get()->first();
    
            if(!empty($SetLeaveSignatory))
            {
              $formfields['userid'] = auth()->user()->id;
              Leave::create($formfields);
      
              return back()->with('message', 'Travel Order Added Successfully');
            }
            else
            {
              return back()->with('SignatoryError', 'Error!');
            }
            
//
          
        }
        else
        {
            return back()->with('DateError1', 'Error');
        } 

    }

    public function destroy(Request $request,$Leave) {

      
      $Leave = Leave::where('id','=',$Leave)->get()->first();
      $this->authorize('delete', $Leave);


          $Leave->delete();

          return back()->with('message', 'Leave Deleted Successfully');


  }

  public function print(\Illuminate\Http\Request $request, \App\Models\Leave $Leave)
  {
    $this->authorize('print', $Leave);

    $Leave_types = \App\Models\Leave_Type::get();
    $Employee    = \App\Models\Employee::with('Office')->findOrFail($Leave->employeeid);

    // count days
    [$startDate, $endDate] = explode(' - ', $Leave->daterange);
    $date1  = new \Carbon\Carbon($startDate);
    $date2  = new \Carbon\Carbon($endDate);
    $Count  = $date1->diffInDays($date2) + 1;

    $preview = $request->boolean('preview');

    // load snapshots
    $Leave->load('approvals');

    // current signatory (fallback only)
    $set       = \App\Models\SetLeaveSignatory::where('sectionid', $Employee->sectionid)->first();
    $signatory = $set
      ? \App\Models\LeaveSignatory::with('Employee1', 'Employee2', 'Employee3')->find($set->leavesignatoryid)
      : null;

    return view('mails.leave.print', compact('Leave_types', 'Employee', 'Leave', 'Count', 'preview', 'signatory'));
  }



  public function accept(Leave $Leave)
  {
    try {
      $this->authorize('accept', $Leave);

      $approver = Employee::where('email', auth()->user()->email)->firstOrFail();

      $applicant   = Employee::findOrFail($Leave->employeeid);
      $set         = SetLeaveSignatory::where('sectionid', $applicant->sectionid)->first();
      if (!$set)  return back()->with('message', 'No signatory set found.');

      $signatory   = LeaveSignatory::find($set->leavesignatoryid);
      if (!$signatory) return back()->with('message', 'Signatory record missing.');

      $step = null;
      if ($signatory->approver1 == $approver->id && !$Leave->is_approve1 && !$Leave->is_rejected1) {
        $step = 1;
        $Leave->update(['is_approve1' => true]);
      } elseif ($signatory->approver2 == $approver->id && $Leave->is_approve1 && !$Leave->is_approve2 && !$Leave->is_rejected2) {
        $step = 2;
        $Leave->update(['is_approve2' => true]);
      } elseif ($signatory->approver3 == $approver->id && $Leave->is_approve1 && $Leave->is_approve2 && !$Leave->is_approve3 && !$Leave->is_rejected3) {
        $step = 3;
        $Leave->update(['is_approve3' => true]);
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
      return back()->with('message', 'Error approving leave.');
    }
  }





  public function reject(Leave $Leave) {
        $this->authorize('reject', $Leave);
        $LeaveSignatories = LeaveSignatory::get();
        $Employee = Employee::where('email','=',auth()->user()->email)->get()->first();
        foreach($LeaveSignatories as $LeaveSignatory)
        {
          if($LeaveSignatory->approver1 == $Employee->id && auth()->check()) 
          {
            $formfields['is_rejected1'] = true;
            $Leave->update($formfields);
            return back()->with('message', 'Leave Successfully Rejected!');
          } 
          if($LeaveSignatory->approver2 == $Employee->id && auth()->check()) 
          {
            $formfields['is_rejected2'] = true;
            $Leave->update($formfields);
            return back()->with('message', 'Leave Successfully Rejected!');
          } 
          if($LeaveSignatory->approver3 == $Employee->id && auth()->check()) 
          {
            $formfields['is_rejected3'] = true;
            $Leave->update($formfields);
            return back()->with('message', 'Leave Successfully Rejected!');
          } 
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


  public function storeUserLeave(Request $request) {
        
      $this->authorize('AddUserLeave', \App\Models\Leave::class);

      $formfields = $request->validate([
           
          'leaveid' => 'required',
          'daterange' => 'required',
         
      ]);

      $data = $request->daterange;
      list($startDate, $endDate) = explode(" -", $data);
      $date1 = Carbon::createFromDate($startDate)->format('Y');
      $date2 = Carbon::createFromDate($endDate)->format('Y');
      $formfields['userid'] = auth()->user()->id;
      if ($date1 == $date2)
      {
          $formfields['yearapplied'] = $date1;
          $Employeeid = Employee::where('email','=',auth()->user()->email)->get()->first();
          $formfields['employeeid'] = $Employeeid->id;
          Leave::create($formfields);

          return back()->with('message', 'Leave Added Successfully');
      }
      else
      {
          return back()->with('DateError1', 'Error');
      } 

  }

  public function getLeaveYearApproved() {
        
  
        $Leaves = Leave::orderby('created_at', 'desc')->where('is_approve3', true)->with('Employee')->with('Leave_Type')->get();
        $Leave_types = Leave_Type::get();
        $LeaveYear = array();
        $LeaveYear = [];
    
        foreach($Leaves as $Leave)
        {
          foreach($Leave_types as $Leave_type)
          {
            if($Leave->leaveid == $Leave_type->id)
            {
  
              
            }
  
          }
          $data = $Leave->daterange;
          list($startDate, $endDate) = explode(" - ", $data);
          $date1 =  Carbon::createFromDate($startDate)->format('Y');
  
          $date2 =  Carbon::createFromDate($endDate)->format('Y');
  
        if($date2 == $date1)
          {
            $LeaveYear[] = array($Leave->id, $date1,$Leave->leaveid,$Leave->employeeid,$data);
          }      
        }

      return $LeaveYear;

  }

  public function getEmployeeLeaveCount() {
    
 
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
    
    foreach($Employees as $Employee) 
    {
      foreach($LeaveYear as $Leave)
      {
        if($Leave['3'] == $Employee->id)
        {
       
          foreach($Leave_types as $Leave_type)
          {
                if($Leave['2'] == $Leave_type->id && $Leave['3'] == $Employee->id)
                {    
                  $data = $Leave['4'];
                  list($startDate, $endDate) = explode(" - ", $data);
                  $date1 = new carbon($startDate);  
                  $date2 = new carbon($endDate);  
                  $date3 =  Carbon::createFromDate($startDate)->format('Y');
                 

                  if($date3 == $Leave['1'])
                  {
                    while ($date1->lte($date2))
                    {
                      $Count =  $Count + 1;      
                      $date1->addDay();       
                    }
                  }
                  if($Count != 0)
                  {
                   $LeaveCount1[] = array($Employee->id, $Leave_type->id, $Count, $Leave['1']);
                   $Count = 0;  
                  }
              }
          }
        }
      }
    }


    for($i = 2021; $i <= 2050; $i++)
    {
          foreach($Leave_types as $Leave_type)
          {
                foreach($Employees as $Employee)
                {
                 
                    foreach($LeaveCount1 as $LeaveCount)
                    {
                      if($LeaveCount['3'] == $i && $Leave_type->id == $LeaveCount['1'] && $Employee->id == $LeaveCount['0'])
                      {
                        $Count = $Count + $LeaveCount['2'];  
                      }
                    }
                    if($Count != 0)
                    {
                    $Final[] = array($Employee->id, $Leave_type->id, $Count, $i);
                    $Count = 0;  
                    }
                
                }

              }
            }
return $Final;

}

public function summary() 
{
  $this->authorize('summary', \App\Models\Leave::class);
    $Employee = Employee::where('email','=', auth()->user()->email)->get()->first();
    $Leave_Types = Leave_Type::orderBy ('id', 'asc')->get();
    $YearNow = Carbon::createFromDate(now())->format('Y');

    $Leaves = Leave::where('employeeid','=',$Employee->id)->where('yearapplied','=',$YearNow)->with('Employee')->with('Leave_type')->orderBy('created_at','asc')->get();

    $EmployeeLeaveCount = $this->getEmployeeLeaveCount();


    // $Events = Event::orderBy('date', 'asc')->with('User')->get();
    return view('user.leave.leave_summary', compact('YearNow','Employee','EmployeeLeaveCount','Leave_Types','Leaves'));
}

public function summaryfilter(Request $request) 
{
 
  $this->authorize('summary', \App\Models\Leave::class);
    $Employee = Employee::where('email','=', auth()->user()->email)->get()->first();
    $Leave_Types = Leave_Type::orderBy ('id', 'asc')->get();
    $YearNow = Carbon::createFromDate(now())->format('Y');
    $FilteredYear = $request->year;

    $Leaves = Leave::where('employeeid','=',$Employee->id)->where('yearapplied','=',$YearNow)->with('Employee')->with('Leave_type')->orderBy('created_at','asc')->get();

    $EmployeeLeaveCount = $this->getEmployeeLeaveCount();
  

    // $Events = Event::orderBy('date', 'asc')->with('User')->get();
    return view('user.leave.leave_summary_filtered', compact('FilteredYear','YearNow','Employee','EmployeeLeaveCount','Leave_Types','Leaves'));
}

public function advance(Request $request) {
    try
    {

      $this->authorize('MSDaddLeave', \App\Models\Leave::class);

      $Employees = Employee::where('empstatus','=', 'PERMANENT')->get();
      $Leave_Types = Leave_Type::orderBy ('id', 'asc')->get();
      $Leaves = Leave::with('Employee')->with('Leave_type')->where('employeeid','=',$request->employeeid)->orderBy('created_at','asc')->get();

    
      // $Events = Event::orderBy('date', 'asc')->with('User')->get();
      return view('msd-panel.leave.advancesearch', compact('Employees', 'Leave_Types','Leaves'));
      }
      catch (Throwable $e) {
        report($e);
      
        return false;
    }
  }

}