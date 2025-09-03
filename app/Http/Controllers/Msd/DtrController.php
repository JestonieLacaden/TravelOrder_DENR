<?php

namespace App\Http\Controllers\Msd;

use App\Models\Dtr;
use App\Models\Leave;
use App\Models\Office;
use App\Models\Employee;
use Carbon\CarbonPeriod;
use App\Models\Dtr_History;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use PhpParser\Node\Stmt\Else_;
use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\TravelOrder;
use App\Models\TravelOrderApproved;

class DtrController extends Controller
{
   public function index() {

    $this->authorize('viewany', \App\Models\Dtr_History::class);

    $Employees = Employee::with('Office')->with('Section')->with('Unit')->with('Dtr_History')->get(); 
    $Dtrs = Dtr_History::with('Employee')->with('User')->orderby('created_at', 'desc')->get();
        
    return view('msd-panel.dtr.index', compact('Employees','Dtrs'));
    }

    // public function print() {
    //   $Employees = Employee::with('Office')->with('Section')->with('Unit')->orderBy('firstname')->get(); 

     
    //   return view('msd-panel.dtr.dtr-table',compact('Employees'));
    //   }

   
  
      // public function print() {
      //   $Employees = Employee::with('Office')->with('Section')->with('Unit')->orderBy('firstname')->get(); 
  
       
      //   return view('msd-panel.dtr.dtr-table',compact('Employees'));
      //   }
   
  public function print(Request $request) {



        $this->authorize('print', \App\Models\Dtr_History::class);

        $request->validate([              
          'employeeid' => 'required',
          'fromdate' => 'required',
          'todate' => 'required',
            ]);

          if($request->fromdate > $request->todate)
            {
              return back()->with('DtrError', 'Error!');
    
          }
      
        $FinalDateRage = $request->fromdate .' to '. $request->todate;
        $Employee = Employee::where('id','=',$request->employeeid)->with('Office')->with('Section')->with('Unit')->get()->first(); 
        $Dtrs = Dtr_History::where('employeeid','=', $request->employeeid)->whereBetween('date',[$request->fromdate, $request->todate])->with('Employee')->with('User')->orderby('time', 'asc')->get();
      
        $startDate = new Carbon($request->fromdate);
        $endDate = new Carbon($request->todate);
        $all_dates = array();
        $all_dtr = array();
        $latemorning = intval('0');
        $lateafternoon = intval('0');
        $utmorning = intval('0');
        $utafternoon = intval('0');
        $totallate = intval('0');
        $totalut = intval('0');
        $morningout = intval('0');
        $afternoonin = intval('0');
        $absent = intval('0');
        $MAUt = intval('0');
        $lates = array();
        $uts = array();
        $FinalLeaves = array();
        $FinalTravelOrders = array();

      
 


        while ($startDate->lte($endDate)){
         
          $all_dates[] = Carbon::createFromDate($startDate)->format('m/d/Y');
          if(Carbon::createFromDate($startDate)->format('D') == 'Sat')
          {
            $all_dtr[] = array(Carbon::createFromDate($startDate)->format('m/d/Y'), 'SATURDAY',Carbon::createFromTimeString('00:00:00 ')->format('g:i A') );

          }
          if(Carbon::createFromDate($startDate)->format('D') == 'Sun')
          {
            $all_dtr[] = array(Carbon::createFromDate($startDate)->format('m/d/Y'), 'SUNDAY',Carbon::createFromTimeString('00:00:00 ')->format('g:i A') );
      
          }
          //
          $Events = Event::whereBetween('date',[$request->fromdate, $request->todate])->get();
          {
            $FinalEvent = array();
            foreach($Events as $Event)
            {
              $NewEventDate = carbon::createFromDate($Event->date)->format('m/d/Y');
              $FinalEvent[] = array($NewEventDate,$Event->schedule, $Event->subject, $Event->remarks);
            } 

          }

          $Leaves = Leave::where('employeeid','=', $request->employeeid)->with('Employee','Leave_Type')->get();
          {

            if(!empty($Leaves))
            {
              foreach($Leaves as $Leave)
              {
                list($LeaveStartDate, $LeaveEndDate) = explode(" - ", $Leave->daterange);

                $NewLeaveStartDate = new Carbon($LeaveStartDate);
                $NewLeaveEndDate = new Carbon($LeaveEndDate);

                while ($NewLeaveStartDate->lte($NewLeaveEndDate))
                {
          
                  $FinalLeaves[] = array(Carbon::createFromDate($NewLeaveStartDate)->format('m/d/Y'), $Leave->employeeid,$Leave->leave_type->leave_type);
                  $NewLeaveStartDate->addDay(); 
                }
             }
            }
         }

         $TravelOrders = TravelOrder::where('employeeid','=', $request->employeeid)->with('Employee')->get();
          {

            if(!empty($TravelOrders))
            {
              foreach($TravelOrders as $TravelOrder)
              {
                list($TravelOrderStartDate, $TravelOrderEndDate) = explode(" - ", $TravelOrder->daterange);

                $NewTravelOrderStartDate = new Carbon($TravelOrderStartDate);
                $NewTravelOrderEndDate = new Carbon($TravelOrderEndDate);

                while ($NewTravelOrderStartDate->lte($NewTravelOrderEndDate))
                {
                  $TravelOrderApproved = TravelOrderApproved::where('employeeid','=',$TravelOrder->id)->get()->first();
                  if(!empty($TravelOrderApproved))
                  {
                    $FinalTravelOrders[] = array(Carbon::createFromDate($NewTravelOrderStartDate)->format('m/d/Y'), $TravelOrder->employeeid,$TravelOrderApproved->travelorderid);
                    $NewTravelOrderStartDate->addDay(); 
                  }
                
                }
             }
            }
         }

          $times = Dtr_History::where('employeeid','=', $request->employeeid)->where('date',$startDate)->with('Employee')->with('User')->orderby('time', 'asc')->get();
          foreach($times as $time)
          {
            $all_dtr[] = array(Carbon::createFromDate($startDate)->format('m/d/Y'), $time->schedule,Carbon::createFromTimeString($time->time)->format('g:i A'), $time->remarks);

           
            if($time->schedule == 'TIME IN - MORNING' &&  strtotime($time->time) > strtotime("08:00:00")){
              $late = Carbon::make($time->time)->timestamp;
              $latemorning = $late - 28800; 
              $latemorning = date("H:i", $latemorning);
            }
            if($time->schedule == 'TIME IN - AFTERNOON' &&  strtotime($time->time) > strtotime("13:00:00")){
              $late = Carbon::make($time->time)->timestamp;
              $lateafternoon = $late - 46800; 
              $lateafternoon = date("H:i", $lateafternoon);
            }
            if($time->schedule == 'TIME OUT - AFTERNOON' &&  strtotime($time->time) < strtotime("17:00:00")){
              $undertime = Carbon::make($time->time)->timestamp;
              $utafternoon =  61200 - $undertime - 57600; 
              $utafternoon = date("H:i", $utafternoon);
            }
            if($time->schedule == 'TIME OUT - MORNING' &&  strtotime($time->time) < strtotime("12:00:00")){
              $undertime = Carbon::make($time->time)->timestamp;
              $utmorning =  43200 - $undertime - 57600; 
              $utmorning = date("H:i", $utmorning);
            }
            if($time->schedule == 'TIME OUT - MORNING'){
              $morningout = Carbon::make($time->time)->timestamp;
            }
            if($time->schedule == 'TIME IN - AFTERNOON'){
              $afternoonin = Carbon::make($time->time)->timestamp;
            }
            if($time->schedule == 'MORNING' && $time->remarks == 'ABSENT') {
                $absent = Carbon::make('04:00:00')->timestamp;
                $absent = date("H:i", $absent);
            }
            if($time->schedule == 'AFTERNOON' && $time->remarks == 'ABSENT') {
              $absent = Carbon::make('04:00:00')->timestamp;
              $absent = date("H:i", $absent);
            }
            if($time->schedule == 'WHOLE DAY' && $time->remarks == 'ABSENT') {
              $absent = Carbon::make('08:00:00')->timestamp;
              $absent = date("H:i", $absent);
            }

          }
          

          if (!empty($latemorning) && (!empty($lateafternoon)))
          {
          $totallate = Carbon::make($latemorning)->timestamp + Carbon::make($lateafternoon)->timestamp - 57600;
          $totallate = date("H:i", $totallate);
          }
          if(empty($latemorning) && empty($lateafternoon)) {
            
          }

          if(!empty($latemorning) && empty($lateafternoon)) {
            $totallate = Carbon::make($latemorning)->timestamp;
          $totallate = date("H:i", $totallate);
          }
          if(empty($latemorning) && !empty($lateafternoon)) {
            $totallate = Carbon::make($lateafternoon)->timestamp;
          $totallate = date("H:i", $totallate);
          }
      
          if (!empty($utmorning) && (!empty($utafternoon)))
          {
          $totalut = Carbon::make($utmorning)->timestamp + Carbon::make($utafternoon)->timestamp - 57600;
          $totalut = date("H:i", $totalut);
          }
          if(empty($utafternoon) && empty($utmorning)) {
            
          }
          if(empty($utmorning) && !empty($utafternoon)) {
            $totalut = Carbon::make($utafternoon)->timestamp;
          $totalut = date("H:i", $totalut);
          }

          if(!empty($utmorning) && empty($utafternoon)) {
            $totalut = Carbon::make($utmorning)->timestamp;
          $totalut = date("H:i", $totalut);
          }

        if (empty($morningout) &&  empty($afternoonin)) 
        {
          $MAUt = 1800 - intval('0');
        }
        if (!empty($morningout) &&  empty($afternoonin)) 
        {
          $MAUt = 1800 - intval('0')  ;
        }
        if (empty($morningout) &&  !empty($afternoonin)) 
        {
            $MAUt = 1800 - intval('0');
        }
       if (!empty($morningout) &&  !empty($afternoonin)) 
       {
        $MAUt = $afternoonin - $morningout;     
       } 

        if($MAUt < 1800) {
          $MAUt = date("H:i", $MAUt);
          $MAUt =  1800 - Carbon::make($MAUt)->timestamp - 28800;
        }
        else{
          $MAUt = intval('0') - 28800;
        }
       $MAUt = date("H:i", $MAUt);

        if (!empty($totalut) && (!empty($MAUt)))
        {
        $totalut = Carbon::make($totalut)->timestamp + Carbon::make($MAUt)->timestamp - 57600;
        $totalut = date("H:i", $totalut);
        }
        if(empty($totalut) && empty($MAUt)) {
          
        }
        if(empty($totalut) && !empty($MAUt)) {
          
          $totalut = Carbon::make($MAUt)->timestamp;
          $totalut = date("H:i", $totalut);
        }

        if(!empty($totalut) && empty($MAUt)) {
          $totalut = Carbon::make($totalut)->timestamp;
        $totalut = date("H:i", $totalut);
        }

//
        if (!empty($totallate) && (!empty($absent)))
        {
        $totallate = Carbon::make($totallate)->timestamp + Carbon::make($absent)->timestamp - 57600;
        $totallate = date("H:i", $totallate);
        }
        if(empty($totallate) && empty($absent)) {
          
        }
        if(empty($totallate) && !empty($absent)) {
          
          $totallate = Carbon::make($absent)->timestamp;
          $totallate = date("H:i", $totallate);
        }

        if(!empty($totallate) && empty($absent)) {
          $totallate = Carbon::make($totallate)->timestamp;
        $totallate = date("H:i", $totallate);
        }
       

      
          $lates[] = array(Carbon::createFromDate($startDate)->format('m/d/Y'), $totallate);
          $uts[] = array(Carbon::createFromDate($startDate)->format('m/d/Y'), $totalut);
          $MAUt = intval('0');
          $totallate = intval('0');
          $totalut = intval('0');
          $afternoonin = intval('0');
          $absent = intval('0');
          $morningout = intval('0');
          $lateafternoon = intval('0');
          $latemorning = intval('0');
          $utmorning = intval('0');
          $utafternoon = intval('0');
          $startDate->addDay(); 
       
        
        }

        return view('msd-panel.dtr.dtr-table',compact('Employee','Dtrs','all_dates','all_dtr','lates','uts','FinalLeaves','FinalTravelOrders','FinalDateRage','FinalEvent'));
        }
     

    public function create() {

      $this->authorize('create', \App\Models\Dtr_History::class);

        $Employees = Employee::with('Office')->with('Section')->with('Unit')->orderBy('firstname')->get(); 
       
        return view('msd-panel.dtr.create',compact('Employees'));
        }

    public function store(Request $request) {

    $this->authorize('create', \App\Models\Dtr_History::class);
      $formfields = $request->validate([              
        'date' => 'required',
        'employeeid' => 'required',
        'schedule' => 'required',
        'time' => 'required',

      ]);

      if(Carbon::createFromDate($request->date)->format('D') == 'Sat')
      {
        return back()->with('DTRError2', 'Date is Saturday!')->withInput();
      }

      if(Carbon::createFromDate($request->date)->format('D') == 'Sun')
      {
        return back()->with('DTRError3', 'Date is Sunday!')->withInput();
      }

   
       
      if ($request->schedule == 'TIME IN - MORNING')
      {
        $check = dtr_history::where('employeeid','=', $request->employeeid)->where('date','=', $request->date)->where('schedule','=', 'TIME IN - MORNING')->get()->first();
        if ($check) {
          return back()->with('DTRError4','Error')->withInput();
        } 
        $check = dtr_history::where('employeeid','=', $request->employeeid)->where('date','=', $request->date)->where('schedule','=', 'MORNING')->get()->first();
        if ($check) {
          return back()->with('DTRError4','Error')->withInput();
        }
        $check = dtr_history::where('employeeid','=', $request->employeeid)->where('date','=', $request->date)->where('schedule','=', 'EVENT - MORNING')->get()->first();
        if ($check) {
          return back()->with('DTRError4','Error')->withInput();
        }
        $check = dtr_history::where('employeeid','=', $request->employeeid)->where('date','=', $request->date)->where('schedule','=', 'EVENT - WHOLE DAY')->get()->first();
        if ($check) {
          return back()->with('DTRError4','Error')->withInput();
        }
        $check = dtr_history::where('employeeid','=', $request->employeeid)->where('date','=', $request->date)->where('schedule','=', 'WHOLE DAY')->get()->first();
        if ($check) {
          return back()->with('DTRError4','Error')->withInput();
        }
      }
       
  
      if ($request->schedule == 'TIME IN - AFTERNOON')
      {
        $check = dtr_history::where('employeeid','=', $request->employeeid)->where('date','=', $request->date)->where('schedule','=', 'TIME IN - AFTERNOON')->get()->first();
        if ($check) {
          return back()->with('DTRError4','Error')->withInput();
        } 
        $check = dtr_history::where('employeeid','=', $request->employeeid)->where('date','=', $request->date)->where('schedule','=', 'AFTERNOON')->get()->first();
        if ($check) {
          return back()->with('DTRError4','Error')->withInput();
        }
        $check = dtr_history::where('employeeid','=', $request->employeeid)->where('date','=', $request->date)->where('schedule','=', 'EVENT - AFTERNOON')->get()->first();
        if ($check) {
          return back()->with('DTRError4','Error')->withInput();
        }
        $check = dtr_history::where('employeeid','=', $request->employeeid)->where('date','=', $request->date)->where('schedule','=', 'EVENT - WHOLE DAY')->get()->first();
        if ($check) {
          return back()->with('DTRError4','Error')->withInput();
        }
        $check = dtr_history::where('employeeid','=', $request->employeeid)->where('date','=', $request->date)->where('schedule','=', 'WHOLE DAY')->get()->first();
        if ($check) {
          return back()->with('DTRError4','Error')->withInput();
        }
      }
      if ($request->schedule == 'TIME OUT - MORNING')
      {
        $check = dtr_history::where('employeeid','=', $request->employeeid)->where('date','=', $request->date)->where('schedule','=', 'TIME OUT - MORNING')->get()->first();
        if ($check) {
          return back()->with('DTRError4','Error')->withInput();
        } 
        $check = dtr_history::where('employeeid','=', $request->employeeid)->where('date','=', $request->date)->where('schedule','=', 'MORNING')->get()->first();
        if ($check) {
          return back()->with('DTRError4','Error')->withInput();
        }
        $check = dtr_history::where('employeeid','=', $request->employeeid)->where('date','=', $request->date)->where('schedule','=', 'EVENT - MORNING')->get()->first();
        if ($check) {
          return back()->with('DTRError4','Error')->withInput();
        }
        $check = dtr_history::where('employeeid','=', $request->employeeid)->where('date','=', $request->date)->where('schedule','=', 'EVENT - WHOLE DAY')->get()->first();
        if ($check) {
          return back()->with('DTRError4','Error')->withInput();
        }
        $check = dtr_history::where('employeeid','=', $request->employeeid)->where('date','=', $request->date)->where('schedule','=', 'WHOLE DAY')->get()->first();
        if ($check) {
          return back()->with('DTRError4','Error')->withInput();
        }
      }
      if ($request->schedule == 'TIME OUT - AFTERNOON')
      {
        $check = dtr_history::where('employeeid','=', $request->employeeid)->where('date','=', $request->date)->where('schedule','=', 'TIME OUT - AFTERNOON')->get()->first();
        if ($check) {
          return back()->with('DTRError4','Error')->withInput();
        } 
        $check = dtr_history::where('employeeid','=', $request->employeeid)->where('date','=', $request->date)->where('schedule','=', 'AFTERNOON')->get()->first();
        if ($check) {
          return back()->with('DTRError4','Error')->withInput();
        }
        $check = dtr_history::where('employeeid','=', $request->employeeid)->where('date','=', $request->date)->where('schedule','=', 'EVENT - AFTERNOON')->get()->first();
        if ($check) {
          return back()->with('DTRError4','Error')->withInput();
        }
        $check = dtr_history::where('employeeid','=', $request->employeeid)->where('date','=', $request->date)->where('schedule','=', 'EVENT - WHOLE DAY')->get()->first();
        if ($check) {
          return back()->with('DTRError4','Error')->withInput();
        }
        $check = dtr_history::where('employeeid','=', $request->employeeid)->where('date','=', $request->date)->where('schedule','=', 'WHOLE DAY')->get()->first();
        if ($check) {
          return back()->with('DTRError4','Error')->withInput();
        }
      }

      $date = $request->date;
      $schedule = $request->schedule;
      $employeeid = $request->employeeid;
      $check = dtr_history::where('employeeid','=', $employeeid)->where('date','=', $date)->where('schedule','=', $schedule)->get()->first();

      if ($check) {
        return back()->with('DTRError1', 'Duplicate DTR Entry!')->withInput();
      }        
               
                $Employeename = Employee::where('id','=', $request->employeeid)->get()->first();
                $formfields['userid'] = auth()->user()->id;
                $formfields['remarks'] = $request->remarks;

                Dtr_History::create($formfields) ; 
                return back()->with('message', 'DTR of'. " " . $Employeename->firstname . " " . $Employeename->lastname . " dated :" . $request->date . " " . 'Added Succesfully!')->withInput();
              }      

    public function storeabsent(Request $request) {

      $this->authorize('create', \App\Models\Dtr_History::class);

      $formfields = $request->validate([              
        'date' => 'required',
        'employeeid' => 'required',
        'schedule' => 'required',
        'time' => 'required',

      ]);

      if(Carbon::createFromDate($request->date)->format('D') == 'Sat')
      {
        return back()->with('DTRError2', 'Date is Saturday!')->withInput();
      }

      if(Carbon::createFromDate($request->date)->format('D') == 'Sun')
      {
        return back()->with('DTRError3', 'Date is Sunday!')->withInput();
      }
    

      $date = $request->date;
      $schedule = $request->schedule;
      $employeeid = $request->employeeid;

      if ($request->schedule == 'MORNING')
      {
        $check = dtr_history::where('employeeid','=', $employeeid)->where('date','=', $date)->where('schedule','=', 'TIME IN - MORNING')->get()->first();
        if ($check) {
          return back()->with('DTRError4','Error')->withInput();
        } 
        $check = dtr_history::where('employeeid','=', $employeeid)->where('date','=', $date)->where('schedule','=', 'TIME OUT - MORNING')->get()->first();
        if ($check) {
          return back()->with('DTRError4','Error')->withInput();
        } 
        $check = dtr_history::where('employeeid','=', $employeeid)->where('date','=', $date)->where('schedule','=', 'EVENT - MORNING')->get()->first();
        if ($check) {
          return back()->with('DTRError4','Error')->withInput();
        } 
        $check = dtr_history::where('employeeid','=', $employeeid)->where('date','=', $date)->where('schedule','=', 'EVENT - WHOLE DAY')->get()->first();
        if ($check) {
          return back()->with('DTRError4','Error')->withInput();
        } 
        $check = dtr_history::where('employeeid','=', $employeeid)->where('date','=', $date)->where('schedule','=', 'MORNING')->get()->first();
        if ($check) {
          return back()->with('DTRError4','Error')->withInput();
        } 
      }

      if ($request->schedule == 'AFTERNOON')
      {
        $check = dtr_history::where('employeeid','=', $employeeid)->where('date','=', $date)->where('schedule','=', 'TIME IN - AFTERNOON')->get()->first();
        if ($check) {
          return back()->with('DTRError4','Error')->withInput();
        } 
        $check = dtr_history::where('employeeid','=', $employeeid)->where('date','=', $date)->where('schedule','=', 'TIME OUT - AFTERNOON')->get()->first();
        if ($check) {
          return back()->with('DTRError4','Error')->withInput();
        } 
        $check = dtr_history::where('employeeid','=', $employeeid)->where('date','=', $date)->where('schedule','=', 'EVENT - AFTERNOON')->get()->first();
        if ($check) {
          return back()->with('DTRError4','Error')->withInput();
        } 
        $check = dtr_history::where('employeeid','=', $employeeid)->where('date','=', $date)->where('schedule','=', 'EVENT - WHOLE DAY')->get()->first();
        if ($check) {
          return back()->with('DTRError4','Error')->withInput();
        } 
        $check = dtr_history::where('employeeid','=', $employeeid)->where('date','=', $date)->where('schedule','=', 'AFTERNOON')->get()->first();
        if ($check) {
          return back()->with('DTRError4','Error')->withInput();
        }  
      }


      if ($request->schedule == 'WHOLEDAY')
      {
        $check = dtr_history::where('employeeid','=', $employeeid)->where('date','=', $date)->where('schedule','=', 'TIME IN - AFTERNOON')->get()->first();
        if ($check) {
          return back()->with('DTRError4','Error')->withInput();
        } 
        $check = dtr_history::where('employeeid','=', $employeeid)->where('date','=', $date)->where('schedule','=', 'TIME OUT - AFTERNOON')->get()->first();
        if ($check) {
          return back()->with('DTRError4','Error')->withInput();
        } 
        $check = dtr_history::where('employeeid','=', $employeeid)->where('date','=', $date)->where('schedule','=', 'TIME IN - MORNING')->get()->first();
        if ($check) {
          return back()->with('DTRError4','Error')->withInput();
        } 
        $check = dtr_history::where('employeeid','=', $employeeid)->where('date','=', $date)->where('schedule','=', 'TIME OUT - MORNING')->get()->first();
        if ($check) {
          return back()->with('DTRError4','Error')->withInput();
        } 
        $check = dtr_history::where('employeeid','=', $employeeid)->where('date','=', $date)->where('schedule','=', 'EVENT - AFTERNOON')->get()->first();
        if ($check) {
          return back()->with('DTRError4','Error')->withInput();
        } 
        $check = dtr_history::where('employeeid','=', $employeeid)->where('date','=', $date)->where('schedule','=', 'EVENT - MORNING')->get()->first();
        if ($check) {
          return back()->with('DTRError4','Error')->withInput();
        } 
        $check = dtr_history::where('employeeid','=', $employeeid)->where('date','=', $date)->where('schedule','=', 'EVENT - WHOLE DAY')->get()->first();
        if ($check) {
          return back()->with('DTRError4','Error')->withInput();
        } 
        $check = dtr_history::where('employeeid','=', $employeeid)->where('date','=', $date)->where('schedule','=', 'AFTERNOON')->get()->first();
        if ($check) {
          return back()->with('DTRError4','Error')->withInput();
        }  
        $check = dtr_history::where('employeeid','=', $employeeid)->where('date','=', $date)->where('schedule','=', 'MORNING')->get()->first();
        if ($check) {
          return back()->with('DTRError4','Error')->withInput();
        }
      }
    
                $Employeename = Employee::where('id','=', $request->employeeid)->get()->first();
                $formfields['userid'] = auth()->user()->id;
                $formfields['remarks'] = 'ABSENT';

                Dtr_History::create($formfields) ; 
                return back()->with('message', 'DTR of'. " " . $Employeename->firstname . " " . $Employeename->lastname . " dated :" . $request->date . " " . 'Added Succesfully!')->withInput();
              }


  public function storeevent(Request $request) {

    $this->authorize('create', \App\Models\Dtr_History::class);
      $formfields = $request->validate([              
        'date' => 'required',
        'employeeid' => 'required',
        'schedule' => 'required',
        'remarks' => 'required',
        'time' => 'required',
      ]);

      if(Carbon::createFromDate($request->date)->format('D') == 'Sat')
      {
        return back()->with('DTRError2', 'Date is Saturday!')->withInput();
      }

      if(Carbon::createFromDate($request->date)->format('D') == 'Sun')
      {
        return back()->with('DTRError3', 'Date is Sunday!')->withInput();
      }

       
      $date = $request->date;
      $schedule = $request->schedule;
      $employeeid = $request->employeeid;


      if ($request->schedule == 'EVENT - MORNING')
      {
        $check = dtr_history::where('employeeid','=', $employeeid)->where('date','=', $date)->where('schedule','=', 'TIME IN - MORNING')->get()->first();
        if ($check) {
          return back()->with('DTRError4','Error')->withInput();
        } 
        $check = dtr_history::where('employeeid','=', $employeeid)->where('date','=', $date)->where('schedule','=', 'TIME OUT - MORNING')->get()->first();
        if ($check) {
          return back()->with('DTRError4','Error')->withInput();
        } 
        $check = dtr_history::where('employeeid','=', $employeeid)->where('date','=', $date)->where('schedule','=', 'MORNING')->get()->first();
        if ($check) {
          return back()->with('DTRError4','Error')->withInput();
        } 
        $check = dtr_history::where('employeeid','=', $employeeid)->where('date','=', $date)->where('schedule','=', 'WHOLE DAY')->get()->first();
        if ($check) {
          return back()->with('DTRError4','Error')->withInput();
        } 
        $check = dtr_history::where('employeeid','=', $employeeid)->where('date','=', $date)->where('schedule','=', 'EVENT - WHOLE DAY')->get()->first();
        if ($check) {
          return back()->with('DTRError4','Error')->withInput();
        } 
        $check = dtr_history::where('employeeid','=', $employeeid)->where('date','=', $date)->where('schedule','=', 'EVENT - MORNING')->get()->first();
        if ($check) {
          return back()->with('DTRError4','Error')->withInput();
        } 
     
      }
      if ($request->schedule == 'EVENT - AFTERNOON')
      {
        $check = dtr_history::where('employeeid','=', $employeeid)->where('date','=', $date)->where('schedule','=', 'TIME OUT - AFTERNOON')->get()->first();
        if ($check) {
          return back()->with('DTRError4','Error')->withInput();
        }
        $check = dtr_history::where('employeeid','=', $employeeid)->where('date','=', $date)->where('schedule','=', 'TIME IN - AFTERNOON')->get()->first();
        if ($check) {
          return back()->with('DTRError4','Error')->withInput();
        } 
        $check = dtr_history::where('employeeid','=', $employeeid)->where('date','=', $date)->where('schedule','=', 'AFTERNOON')->get()->first();
        if ($check) {
          return back()->with('DTRError4','Error')->withInput();
        } 
        $check = dtr_history::where('employeeid','=', $employeeid)->where('date','=', $date)->where('schedule','=', 'WHOLE DAY')->get()->first();
        if ($check) {
          return back()->with('DTRError4','Error')->withInput();
        } 
        $check = dtr_history::where('employeeid','=', $employeeid)->where('date','=', $date)->where('schedule','=', 'EVENT - WHOLE DAY')->get()->first();
        if ($check) {
          return back()->with('DTRError4','Error')->withInput();
        } 
        $check = dtr_history::where('employeeid','=', $employeeid)->where('date','=', $date)->where('schedule','=', 'EVENT - AFTERNOON')->get()->first();
        if ($check) {
          return back()->with('DTRError4','Error')->withInput();
        } 
      }

      if ($request->schedule == 'EVENT - WHOLEDAY')
      {
        $check = dtr_history::where('employeeid','=', $employeeid)->where('date','=', $date)->where('schedule','=', 'TIME OUT - AFTERNOON')->get()->first();
        if ($check) {
          return back()->with('DTRError4','Error')->withInput();
        }
        $check = dtr_history::where('employeeid','=', $employeeid)->where('date','=', $date)->where('schedule','=', 'TIME IN - AFTERNOON')->get()->first();
        if ($check) {
          return back()->with('DTRError4','Error')->withInput();
        } 
        $check = dtr_history::where('employeeid','=', $employeeid)->where('date','=', $date)->where('schedule','=', 'TIME IN - MORNING')->get()->first();
        if ($check) {
          return back()->with('DTRError4','Error')->withInput();
        } 
        $check = dtr_history::where('employeeid','=', $employeeid)->where('date','=', $date)->where('schedule','=', 'TIME OUT - MORNING')->get()->first();
        if ($check) {
          return back()->with('DTRError4','Error')->withInput();
        } 
        $check = dtr_history::where('employeeid','=', $employeeid)->where('date','=', $date)->where('schedule','=', 'MORNING')->get()->first();
        if ($check) {
          return back()->with('DTRError4','Error')->withInput();
        } 
        $check = dtr_history::where('employeeid','=', $employeeid)->where('date','=', $date)->where('schedule','=', 'AFTERNOON')->get()->first();
        if ($check) {
          return back()->with('DTRError4','Error')->withInput();
        } 
        $check = dtr_history::where('employeeid','=', $employeeid)->where('date','=', $date)->where('schedule','=', 'WHOLE DAY')->get()->first();
        if ($check) {
          return back()->with('DTRError4','Error')->withInput();
        } 
        $check = dtr_history::where('employeeid','=', $employeeid)->where('date','=', $date)->where('schedule','=', 'EVENT - WHOLE DAY')->get()->first();
        if ($check) {
          return back()->with('DTRError4','Error')->withInput();
        } 
        $check = dtr_history::where('employeeid','=', $employeeid)->where('date','=', $date)->where('schedule','=', 'EVENT - MORNING')->get()->first();
        if ($check) {
          return back()->with('DTRError4','Error')->withInput();
        } 
        $check = dtr_history::where('employeeid','=', $employeeid)->where('date','=', $date)->where('schedule','=', 'EVENT - AFTERNOON')->get()->first();
        if ($check) {
          return back()->with('DTRError4','Error')->withInput();
        } 
      }  


      $Employeename = Employee::where('id','=', $request->employeeid)->get()->first();
      $formfields['userid'] = auth()->user()->id;
      $formfields['remarks'] = $request->remarks;

      Dtr_History::create($formfields) ; 
      return back()->with('message', 'DTR of'. " " . $Employeename->firstname . " " . $Employeename->lastname . " dated :" . $request->date . " " . 'Added Succesfully!')->withInput();
              }    
              
  
  public function uploadDTR (Request $request) {

    if ($request->hasFile('uploadedDTR')) {

      $fileName = $request->file('uploadedDTR');
      $UploadedDTR = [];
      $FinalDTR = [];
      $fp = fopen($fileName, "r");
      if (!$fp) {
          die("Cannot load file");
      }
 
      while (($line = fgets($fp, 4096)) !== false) {
          list($word, $measure) = explode("\t", trim($line));
          $UploadedDTR[] = [$word,$measure];
      }

      foreach ($UploadedDTR as $DTR)
      {

        $Log1 =  carbon::createFromDate($DTR[1])->format('Y-m-d');
        $Log2 = carbon::createFromDate($DTR[1])->format('H:i');

        $MorningInstart = Carbon::createFromTimeString('06:00')->format('H:i');
        $MorningInend = Carbon::createFromTimeString('10:00')->format('H:i');
        $MorningOutstart = Carbon::createFromTimeString('10:01')->format('H:i');
        $MorningOutend = Carbon::createFromTimeString('12:30')->format('H:i');
        $AfternoonInstart = Carbon::createFromTimeString('12:31')->format('H:i');
        $AfternoonInend = Carbon::createFromTimeString('15:00')->format('H:i');
        $AfternoonOutstart = Carbon::createFromTimeString('15:01')->format('H:i');
        $AfternoonOutend = Carbon::createFromTimeString('22:00')->format('H:i');

        if ($Log2 >= $MorningInstart && $Log2 <= $MorningInend) {
          $FinalDTR[] = [$DTR[0], $Log1, $Log2, 'TIME IN - MORNING'];
        }
        
        if ($Log2 >= $MorningOutstart && $Log2 <= $MorningOutend) {
          $FinalDTR[] = [$DTR[0], $Log1, $Log2, 'TIME OUT - MORNING'];
        }
     
        if ($Log2 >= $AfternoonInstart && $Log2 <= $AfternoonInend) {
          $FinalDTR[] = [$DTR[0], $Log1, $Log2, 'TIME IN - AFTERNOON'];
        }

        if ($Log2 >= $AfternoonInstart && $Log2 <= $AfternoonInend) {
          $FinalDTR[] = [$DTR[0], $Log1, $Log2, 'TIME OUT - AFTERNOON'];
        }
     
      }
 
      dd($FinalDTR);
    }
    else
    {
      dd('none');

      return redirect()->back();
    }
  }

}