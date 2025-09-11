<?php

namespace App\Http\Controllers\User;

use Throwable;
use App\Models\Mail;
use App\Models\Unit;
use App\Models\User;
use App\Models\Leave;
use App\Models\Route;
use App\Models\Office;
use App\Models\Section;
use App\Models\Document;
use App\Models\Employee;
use App\Models\UserRole;
use App\Models\Leave_Type;
use App\Models\TravelOrder;
use Illuminate\Http\Request;
use App\Models\LeaveSignatory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\TravelOrderSignatory;
use App\Models\SetTravelOrderSignatory;

class MailController extends Controller
{


    public function getcount() {

        $Employee = Employee:: where('email', auth()->user()->email )->get()->first();

        $Routes = Route::orderBy('created_at', 'DESC')->get()->unique('documentid')->where('unitid','=',$Employee->unitid);

        $AcceptedCount = 0;
        $RejectedCount = 0;
        $IncomingCount = 0;
        $OutgoingCount = 0;
        $ClosedCount = 0;
        $CreatedCount = 0;
        $Count =array();

        foreach ($Routes as $Route)
        {
            if ($Route->is_forwarded ==1)
            {
                $IncomingCount = $IncomingCount + 1;
            }
            if ($Route->is_accepted ==1)
            {
                $AcceptedCount = $AcceptedCount + 1;
            }
            if ($Route->is_rejected ==1)
            {
                $RejectedCount = $RejectedCount + 1;
            } 
            if ( $Route->action == 'CLOSED')
            {
                $ClosedCount = $ClosedCount + 1;
            } 
            if ( $Route->is_rejected == 0 && $Route->is_accepted ==0 && $Route->is_forwarded ==0 && $Route->action != 'CLOSED' )
            {
                $CreatedCount = $CreatedCount + 1;
            } 

        }
        $Outgoing = Route::orderBy('created_at', 'DESC')->get()->unique('documentid')->where('is_forwarded','=',true)->where('userunitid','=',$Employee->unitid);
       
        foreach ($Outgoing as $Outgoing)
        {
            $OutgoingCount = $OutgoingCount + 1;
        }

        $Count = array($IncomingCount,$AcceptedCount,$RejectedCount,$OutgoingCount,$ClosedCount,$CreatedCount);
        return($Count);
    }
    //

    public function index() { 

      $this->authorize('viewany', \App\Models\Mail::class);
      $Count = $this->getcount();
      return view('mails.index',compact('Count')); 
    }
      
    public function documentcreated() {
        
      $this->authorize('create', \App\Models\Document::class);
  
      $Employee = Employee:: where('email', auth()->user()->email )->get()->first();
  
      $Count = $this->getcount();

      $Routes = Route::orderBy('created_at', 'DESC')->with('document')
      ->get()->unique('documentid')->where('is_accepted','=','0')->where('is_rejected','=','0')->where('is_forwarded','=','0')->where('action','!=','CLOSED')->where('unitid','=',$Employee->unitid);


      return view('mails.processing.records.index',compact('Routes','Count','Employee'));

    }

     
      public function incoming() {
        
        $this->authorize('viewany', \App\Models\Mail::class);
    
        $Employee = Employee:: where('email', auth()->user()->email )->get()->first();
        $Routes = Route::orderBy('created_at', 'DESC')->with('document')
                    ->get()->unique('documentid')->where('is_forwarded','=','1')->where('unitid','=',$Employee->unitid);
        $Count = $this->getcount();
        return view('mails.incoming.index',compact('Routes','Count'));

      }

      public function processing() {
        
        $this->authorize('viewany', \App\Models\Mail::class);

        $Offices = Office::orderby('id')->get();
        $Sections = Section::orderby('section')->get();
        $Units = Unit::orderby('unit')->get();
    
        $Employee = Employee:: where('email', auth()->user()->email )->get()->first();
        $Routes = Route::orderBy('created_at', 'DESC')->with('document')
                    ->get()->unique('documentid')->where('is_accepted','=','1')->where('unitid','=',$Employee->unitid);
        $Count = $this->getcount();

        // $Routes1 = Route::orderBy('created_at', 'DESC')->with('document')
        // ->get()->unique('documentid')->where('is_accepted','=','0')->where('is_rejected','=','0')->where('is_forwarded','=','0')->where('action','!=','CLOSED')->where('unitid','=',$Employee->unitid);


        return view('mails.processing.index',compact('Routes','Offices','Sections','Units','Count','Employee'));

      }

      public function rejected() {
        
        $this->authorize('viewany', \App\Models\Mail::class);

        $Offices = Office::orderby('id')->get();
        $Sections = Section::orderby('section')->get();
        $Units = Unit::orderby('unit')->get();
    
        $Employee = Employee:: where('email', auth()->user()->email )->get()->first();
        $Routes = Route::orderBy('created_at', 'DESC')->with('document')
                    ->get()->unique('documentid')->where('is_rejected','=','1')->where('unitid','=',$Employee->unitid);
        $Count = $this->getcount();
         return view('mails.rejected.index',compact('Routes','Offices','Sections','Units','Count'));

      }

      public function outgoing() {
        
        $this->authorize('viewany', \App\Models\Mail::class);

        $Offices = Office::orderby('id')->get();
        $Sections = Section::orderby('section')->get();
        $Units = Unit::orderby('unit')->get();
    
        $Employee = Employee:: where('email', auth()->user()->email )->get()->first();
            $Routes = Route::orderBy('created_at', 'DESC')->with('document','Unit','Office','Section')
                    ->get()->unique('documentid')->where('is_forwarded','=','1')->where('userunitid','=',$Employee->unitid);
        $Count = $this->getcount();
         return view('mails.outgoing.index',compact('Routes','Offices','Sections','Units','Count'));

      }

      public function closed() {
        
        $this->authorize('viewany', \App\Models\Mail::class);

        $Employee = Employee:: where('email', auth()->user()->email )->get()->first();
        $Routes = Route::orderBy('created_at', 'DESC')->with('document','Unit','Office','Section','user')
                    ->get()->unique('documentid')->where('is_active','=','0')->where('userunitid','=',$Employee->unitid);
        $Count = $this->getcount();
         return view('mails.closed.index',compact('Routes','Count'));

      }

      public function leaverequest() {
        
        $this->authorize('viewany', \App\Models\Leave::class);

        $Leave_types = Leave_Type::get();
        $Leaves = Leave::orderby('created_at', 'desc')->with('Employee')->with('Leave_Type')->get();
        $Roles = UserRole::where('userid','=', auth()->user()->id)->get();
        $Employees = Employee::get();
        $LeaveSignatories = LeaveSignatory::get();
        $UserEmployee = Employee::where('email','=',auth()->user()->email)->get()->first();
        $EmployeeLeaveCount = $this->getEmployeeLeaveCount();

          $LeaveYear = $this->getLeaveYearPending();

          $Count = $this->getcountrequrest();

          return view('mails.leave.index',compact('LeaveSignatories','UserEmployee','LeaveYear','EmployeeLeaveCount','Employees','Leave_types','Leaves','Roles','Count'));
   
      }



  public function travelorderequest()
  {
    $this->authorize('acceptrequest', \App\Models\TravelOrder::class);

    $me = \App\Models\Employee::where('email', auth()->user()->email)->firstOrFail();

    // IDs ng signatories na ako ang approver1 o approver2
    $mySigIds = \App\Models\TravelOrderSignatory::where('approver1', $me->id)
      ->orWhere('approver2', $me->id)
      ->pluck('id');

    // Mga TO na naka-assign sa alinman sa signatories ko at nasa correct stage
    $TravelOrders = \App\Models\TravelOrder::with('Employee')
      ->whereIn('travelordersignatoryid', $mySigIds)
      ->where(function ($q) use ($me) {
        $q->where('is_approve1', false) // Approver1 stage
          ->orWhere(function ($qq) {
            $qq->where('is_approve1', true)
              ->where('is_approve2', false); // Approver2 stage
          });
      })
      ->orderBy('created_at', 'desc')
      ->get();

    $Roles = \App\Models\UserRole::where('userid', auth()->id())->get();
    $Employees = \App\Models\Employee::get();
    $TravelOrderSignatories = \App\Models\TravelOrderSignatory::get();

    // optional: badge counts for sidebar (see Step 6)
    $Count = $this->getcountrequrest();

    return view('mails.travelorder.index', compact(
      'TravelOrderSignatories',
      'TravelOrders',
      'Roles',
      'Employees',
      'Count'
    ));
  }





  public function getLeaveYearPending() {

        $LeaveSignatories = LeaveSignatory::get();
        $Employee = Employee::where('email','=',auth()->user()->email)->get()->first();

        foreach($LeaveSignatories as $LeaveSignatory)
        {
          if($LeaveSignatory->approver1 == $Employee->id && auth()->check()) 
          {
            $Leaves = Leave::orderby('created_at', 'desc')->where('is_approve1', false)->where('is_rejected1', false)->with('Employee')->with('Leave_Type')->get();
            $LeaveYear = array();
            foreach($Leaves as $Leave)
            {
              $data = $Leave->daterange;
              list($startDate, $endDate) = explode(" -", $data);
              $date1 =  Carbon::createFromDate($startDate)->format('Y');
    
              $date2 =  Carbon::createFromDate($endDate)->format('Y');
       
             if($date2 == $date1)
              {
                $LeaveYear[] = array($Leave->id, $date1);
              }
             
            }
          return $LeaveYear;
          }
          if($LeaveSignatory->approver2 == $Employee->id && auth()->check()) 
          {
            $Leaves = Leave::orderby('created_at', 'desc')->where('is_approve1', true)->where('is_rejected1', false)->with('Employee')->with('Leave_Type')->get();
            $LeaveYear = array();
            foreach($Leaves as $Leave)
            {
              $data = $Leave->daterange;
              list($startDate, $endDate) = explode(" -", $data);
              $date1 =  Carbon::createFromDate($startDate)->format('Y');
    
              $date2 =  Carbon::createFromDate($endDate)->format('Y');
       
             if($date2 == $date1)
              {
                $LeaveYear[] = array($Leave->id, $date1);
              }
             
            }
          return $LeaveYear;
          }
          if($LeaveSignatory->approver3 == $Employee->id && auth()->check()) 
          {
            $Leaves = Leave::orderby('created_at', 'desc')->where('is_approve2', true)->where('is_rejected2', false)->with('Employee')->with('Leave_Type')->get();
            $LeaveYear = array();
            foreach($Leaves as $Leave)
            {
              $data = $Leave->daterange;
              list($startDate, $endDate) = explode(" -", $data);
              $date1 =  Carbon::createFromDate($startDate)->format('Y');
    
              $date2 =  Carbon::createFromDate($endDate)->format('Y');
       
             if($date2 == $date1)
              {
                $LeaveYear[] = array($Leave->id, $date1);
              }
             
            }
          return $LeaveYear;
          }
        }
        
       
      }

      public function IncomingCount() {
        $Documents = Document::with('attachment')->OrderBy('id')->get();
        $Routes = Route::orderby('created_at', 'desc')->get();
        $Employee = Employee::where('email', auth()->user()->email )->get()->first();


        $Count = 0;
      foreach($Documents as $Index => $Document)
      {
        foreach($Routes as $Route) 
        {
          if($Route->action == 'ACCEPTED' || $Route->action == 'REJECTED')
          {
            if(  $Document->id == $Route->documentid && $Route->userunitid == $Employee->unitid)
            {
              break;
            }
          }
          else
          {
            if($Route->action == 'FORWARD TO' &&  $Document->id == $Route->documentid &&  $Route->unitid == $Employee->unitid && $Route->is_forwarded == true)
            {
              $Count = $Count + 1;
            }
          }
        }
      }
      $IncomingCount = $Count;

      return $IncomingCount;
     
    }

    public function ProcessingCount() {
      $Documents = Document::with('attachment')->OrderBy('id')->get();
      $Routes = Route::orderby('created_at', 'desc')->get();
      $Employee = Employee::where('email', auth()->user()->email )->get()->first();


      $Count = 0;
    foreach($Documents as $Index => $Document)
    {
      foreach($Routes as $Route) 
      {
        if($Route->is_forwarded == true  || $Route->is_rejected == true || $Route->is_active == false)
        {
       
     
          if(  $Document->id == $Route->documentid && $Route->userunitid == $Employee->unitid)
          {
            break;
          }
        }
        else
        {
          if($Route->action == 'ACCEPTED' &&  $Document->id == $Route->documentid &&  $Route->userunitid == $Employee->unitid && $Route->is_accepted == true)
          {
            $Count = $Count + 1;
          }
        }
      }
    }

    return $Count;
   
  }

  public function ClosedCount() {
    $Documents = Document::with('attachment')->OrderBy('id')->get();
    $Routes = Route::orderby('created_at', 'desc')->get();
    $Employee = Employee::where('email', auth()->user()->email)->get()->first();


  $Count = 0;
  foreach($Documents as $Index => $Document)
  {
    foreach($Routes as $Route) 
    {
      if($Route->action == 'ACCEPTED' || $Route->action == 'REJECTED' || $Route->action == 'FORWARD TO')
      {
        if( $Document->id == $Route->documentid && $Route->userunitid == $Employee->unitid)
        {
         
          break;  
        
        }
      }
      else
      {
        if($Route->action == 'CLOSED' &&  $Document->id == $Route->documentid && $Route->userunitid == $Employee->unitid)
        {
        
          $Count = $Count + 1;
    
        }
      }
    }
  }
  $ClosedCount = $Count;

  return $ClosedCount;
 
}

  public function OutgoingCount() {
    $Documents = Document::with('attachment')->OrderBy('id')->get();
    $Routes = Route::orderby('created_at', 'desc')->get();
    $Employee = Employee::where('email', auth()->user()->email)->get()->first();


  $Count = 0;
  foreach($Documents as $Document)
  {
    foreach($Routes as $Route) 
    {
      if($Route->action == 'ACCEPTED' || $Route->action == 'REJECTED')
      {
        if( $Document->id == $Route->documentid)
        {
          
          break;
        }
      }
      else
      {
        if($Route->action == 'FORWARD TO' &&  $Document->id == $Route->documentid && $Route->is_forwarded == true && $Route->userunitid == $Employee->unitid)
        {

          $Count = $Count + 1;
        
    
        }
      }
    }
  }
  $IncomingCount = $Count;

  return $IncomingCount;
 
}
public function RejectedCount() {
  $Documents = Document::with('attachment')->OrderBy('id')->get();
  $Routes = Route::orderby('created_at', 'desc')->get();
  $Employee = Employee::where('email', auth()->user()->email )->get()->first();


  $Count = 0;
foreach($Documents as $Index => $Document)
{
  foreach($Routes as $Route) 
  {
    if($Route->is_forwarded == true  || $Route->is_accepted == true || $Route->is_active == false)
    {
   
   
      if(  $Document->id == $Route->documentid && $Route->unitid == $Employee->unitid)
      {
        
        break;
      }
    }
    else
    {
      if($Route->action == 'REJECTED' &&  $Document->id == $Route->documentid &&  $Route->unitid == $Employee->unitid && $Route->is_rejected == true)
      {
        $Count = $Count + 1;
      }
    }
  }
}

return $Count;

}

public function LeaveRequestCount() {
    
      $Leaves = Leave::orderby('created_at', 'desc')->with('Employee')->get();
      $LeaveSignatories = LeaveSignatory::get();
      $Employee = Employee::where('email','=',auth()->user()->email)->get()->first();
      

      $Count = 0;
    foreach($Leaves as $Leave)
    {
      foreach($LeaveSignatories as $LeaveSignatory)
      {
        if($Leave->is_approve1 != true && $Leave->is_rejected1 != true)
          {
            if($LeaveSignatory->approver1 == $Employee->id && auth()->check())
            {
              $Count = $Count + 1;
            }
          }
          if($Leave->is_approve1 == true && $Leave->is_rejected2 != true && $Leave->is_approve2 != true && $Leave->is_rejected1 == false )
          {
            if($LeaveSignatory->approver2 == $Employee->id && auth()->check())
            {
              $Count = $Count + 1;
            }
          }

          if($Leave->is_approve2 == true && $Leave->is_rejected3 != true && $Leave->is_approve3 != true && $Leave->is_rejected2 == false )
          {
            if($LeaveSignatory->approver3 == $Employee->id && auth()->check())
            {
              $Count = $Count + 1;
            }
          }
      }
    }

    return $Count;

    }

    public function TravelOrderRequestCount()
{
    $me = \App\Models\Employee::where('email', auth()->user()->email)->first();
    if (!$me) return 0;

    $mySigIds = \App\Models\TravelOrderSignatory::where('approver1', $me->id)
        ->orWhere('approver2', $me->id)
        ->pluck('id');

    return \App\Models\TravelOrder::whereIn('travelordersignatoryid', $mySigIds)
        ->where(function ($q) {
            $q->where('is_approve1', false)
              ->orWhere(function ($qq) {
                  $qq->where('is_approve1', true)
                     ->where('is_approve2', false);
              });
        })
        ->count();
}




    public function getLeaveYearApproved() {
        
      try
      {
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
      } catch (Throwable $e) {
        report($e);
      
     
      }
    }

    public function getEmployeeLeaveCount() {
    
      try
      {

      $Leaves = Leave::with('Employee')->where('is_approve3', true)->get();
      $Leave_types = Leave_Type::get();
      $LeaveCounts = array();
      $LeaveCount = [];

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
  } catch (Throwable $e) {
    report($e);
  
  
  }
  }

  public function getcountrequrest() {

    $Employee = Employee:: where('email', auth()->user()->email )->get()->first();
   
    $LeaveRequestCount = $this->LeaveRequestCount();
    $TravelOrderRequestCount = $this->TravelOrderRequestCount();

    $Count = array($LeaveRequestCount,$TravelOrderRequestCount);
    return($Count);
}


  public function employeerequest()
   {

    $this->authorize('acceptemployee', \App\Models\TravelOrder::class);

     
    // $Employee = Employee:: where('email', auth()->user()->email )->get()->first();
    // $Routes = FinancialManagementRoute::orderBy('created_at', 'DESC')->with('Voucher','User')
    //             ->get()->unique('sequenceid')->where('is_forwarded','=','1')->where('unitid','=',$Employee->unitid);
    
    $Count = $this->getcountrequrest();

    return view('mails.employeerequest.index',compact('Count'));

  }
}