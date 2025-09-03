<?php

namespace App\Http\Controllers\FinancialManagement;

use App\Models\Unit;
use App\Models\Office;
use App\Models\Section;
use App\Models\Employee;
use App\Models\FMCharging;
use Illuminate\Http\Request;
use App\Models\FMPlanningPAP;
use App\Models\FMPlanningUACS;
use App\Models\FMPlanningActivity;
use App\Models\FinancialManagement;
use App\Http\Controllers\Controller;
use App\Models\AActivity;
use App\Models\AccountEntry;
use App\Models\AccountName;
use App\Models\AccountNumber;
use App\Models\Allocation;
use App\Models\AUACS;
use App\Models\BoxA;
use App\Models\BoxD;
use App\Models\FinancialManagementRoute;
use App\Models\FMAllocationActivitySAA;
use App\Models\FMAllocationPAP;
use App\Models\FMAllocationPAPSAA;
use App\Models\FMAllocationUACS;
use App\Models\FMAllocationUACSSAA;
use App\Models\FMCash;
use App\Models\FMDV;
use App\Models\FMORS;
use App\Models\FMReview;
use App\Models\RealignHistory;
use App\Policies\FMAllocationPAPSAAPolicy;
use Symfony\Contracts\Service\Attribute\Required;

use function PHPSTORM_META\elementType;

class FinancialManagementRouteController extends Controller
{
    public function store(Request $request, FinancialManagement $Voucher) {

        // $this->authorize('addRoute', $Document);

        $formfields = $request->validate([
            'action' => 'required',
            'officeid' => 'required',
            'actiondate' => 'required',
        ]);
        $voucher = FinancialManagement::where('sequenceid', '=', $request->sequenceid)->get()->first();
        $formfields['sequenceid'] = $voucher->sequenceid;
        $formfields['userid'] = auth()->user()->id;
        $formfields['remarks'] = $request->remarks;
        $formfields['is_active'] = true;
        
        $data = $request->officeid;     
        list($Office, $Section, $Unit) = explode(",", $data);
        $formfields['officeid'] = $Office;
        $formfields['sectionid'] = $Section;
        $formfields['unitid'] = $Unit;

        $formfields['is_forwarded'] = true;
        $employee = Employee::where('email','=',auth()->user()->email)->get()->first();
        $formfields['userunitid'] = $employee->unitid;

        $Success = FinancialManagementRoute::create($formfields);
        
        if($Success)
          {
            
        return back()->with('message', "Route Saved Successfully!");
          }
        else
          {
            return back()->with('failed', 'Error, Please contact System Administrator'); 
          }
        
      


    }



    public function storebyAda(Request $request, FinancialManagement $Voucher) {

      // $this->authorize('addRoute', $Document);

      $formfields = $request->validate([
 
          'action' => 'required',
          'officeid' => 'required',
          'actiondate' => 'required',
      ]);
    
    
      $formfields['userid'] = auth()->user()->id;
      $formfields['remarks'] = $request->remarks;
      $formfields['is_active'] = true;
      
      $data = $request->officeid;     
      list($Office, $Section, $Unit) = explode(",", $data);
      $formfields['officeid'] = $Office;
      $formfields['sectionid'] = $Section;
      $formfields['unitid'] = $Unit;

      $formfields['is_forwarded'] = true;
      $employee = Employee::where('email','=',auth()->user()->email)->get()->first();
      $formfields['userunitid'] = $employee->unitid;



      $Vouchers = FMCash::where('adano', $request->batchid)->get();

      $SuccessTotal = FMCash::where('adano', $request->batchid)->count();
      $SuccessCount = 0;
      foreach ($Vouchers as $Voucher)
      {
        $voucher = FinancialManagement::where('id', '=', $Voucher->fmid)->get()->first();
        $formfields['sequenceid'] = $voucher->sequenceid;
        $Success = FinancialManagementRoute::create($formfields);
      
        if($Success)
          {
            
            $SuccessCount = $SuccessCount + 1;
          }
      }

      return back()->with('message', "Route " . $SuccessCount . " out of " . $SuccessTotal . " Saved Successfully!");

  }
    
 

    public function boxacreate(Request $request) {

        $this->authorize('create', \App\Models\BoxA::class);

        $formfields = $request->validate([
            'certified_by' => 'required',
            'position' => 'required',
            'box' => 'required'
        ]);
     
        $check = BoxA::where([
            ['certified_by', '=', $request->certified_by],
            ['box', '=', $request->box],
          ])->first();
  
          if ($check) {
            return back()->with('failed', 'Duplicate Certified By!');
          }

        BoxA::updateorCreate($formfields);
    
        return back()->with('message', "Certified created Successfully!");
 

    }

    public function boxdupdate(Request $request, FinancialManagement $Route) {
        
       $Voucher = FinancialManagement::where('id','=',$request->fmid)->get()->first();

        

       $this->authorize('FmAccounting', \App\Models\FinancialManagement::class);

        $signatory_id['signatory_id'] = $request->signatory_id;
        

        $Voucher->update($signatory_id);

        $Check = BoxD::where('fmid','=', $Voucher->id)->get()->first();

        if($Check)
        {  $signatoryid['signatoryid'] = $request->signatory_id;
            $Check->update($signatoryid);

        }
        else
        {
            $formfields['fmid'] = $Voucher->id;
            $formfields['signatoryid'] = $request->signatory_id;
  
            BoxD::updateorcreate($formfields);
        }

 

        return back()->with('message', "Signatory D updated Successfully!");
    
    }


    public function SignatoryApprove(Request $request) {
        $this->authorize('create', \App\Models\BoxD::class);
        
        $date['date_approved'] = Date('d-m-Y', strtotime(now()));
    
        $Voucher = BoxD::where('fmid','=', $request->fmid)->get()->first();

        if($Voucher) {
  
        $data = Employee::where('email','=',auth()->user()->email)->get()->first(); 

        $formfield['actiondate'] = $date['date_approved'] ;
        $formfield['action'] = 'SIGNATORY APPROVED';
        $formfield['sequenceid'] = $request->sequenceid;
        $formfield['userid'] = auth()->user()->id;
        $formfield['remarks'] = 'Approved';
        $formfield['is_active'] = true;
        
        $data = Employee::where('email','=',auth()->user()->email)->get()->first(); 
        $formfield['officeid'] = $data->officeid;
        $formfield['sectionid'] = $data->sectionid;
        $formfield['unitid'] =  $data->unitid;
        $formfield['userunitid'] = $data->unitid;
        $formfield['is_accepted'] = true;
    
         FinancialManagementRoute::create($formfield);
         $Voucher->Update($date);
        }

        else
        {
            $Voucher = FinancialManagement::where('id','=', $request->fmid)->get()->first();

            $data = Employee::where('email','=',auth()->user()->email)->get()->first(); 

            $formfield['actiondate'] = $date['date_approved'] ;
            $formfield['action'] = 'SIGNATORY BOX A APPROVED';
            $formfield['sequenceid'] = $request->sequenceid;
            $formfield['userid'] = auth()->user()->id;
            $formfield['remarks'] = 'Approved';
            $formfield['is_active'] = true;
            
            $data = Employee::where('email','=',auth()->user()->email)->get()->first(); 
            $formfield['officeid'] = $data->officeid;
            $formfield['sectionid'] = $data->sectionid;
            $formfield['unitid'] =  $data->unitid;
            $formfield['userunitid'] = $data->unitid;
            $formfield['is_accepted'] = true;
        
             FinancialManagementRoute::create($formfield);
          
            // $Voucher->Update($date);
        }



        return back()->with('message', "Voucher Approved Successfully!");
    }




    public function boxadelete(Request $request, BoxA $BoxA) {
        
        $this->authorize('delete', $BoxA);

        $BoxA->delete();

        return back()->with('message', "Certified deleted Successfully!");
    
    }

    public function boxa() {
        $this->authorize('createBoxA', \App\Models\FinancialManagement::class);

        $BoxAs = BoxA::orderby('certified_by','DESC')->get();
        
        return view('financial-management.boxa.index',compact('BoxAs'));
    }

    public function getcount() {

        // $this->authorize('FMPlanning', \App\Models\FinancialManagement::class);

        $Employee = Employee:: where('email', auth()->user()->email )->get()->first();
       
        $IncomingCount = 0;
        $AcceptedCount = 0;
        $RejectedCount = 0;
        $ClosedCount = 0;
        $OutgoingCount = 0;
        $PendingCount = 0;
        $AllocationCount = 0;
        $AllocationUACSCount = 0;
        $AllocationPAPCount = 0;
        //  $LeaveYear[] = array($Leave->id, $date1,$Leave->leaveid,$Leave->employeeid,$data);


        $Vouchers = FinancialManagementRoute::orderBy('created_at', 'DESC')->with('Voucher','userUnit')
        ->get()->unique('sequenceid')->where('unitid','=',$Employee->unitid);

        foreach ($Vouchers as $Route)
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
                    $PendingCount = $PendingCount + 1;
                } 
            }

        $Outgoing = FinancialManagementRoute::orderBy('created_at', 'DESC')->with('Voucher','userUnit')
        ->get()->unique('sequenceid')->where('is_forwarded','=',true)->where('userunitid','=',$Employee->unitid);
       
        foreach ($Outgoing as $Outgoing)
        {
            $OutgoingCount = $OutgoingCount + 1;
        }

        $PAPCount = FMPlanningPAP::count();
        $UACSCount = FMPlanningUACS::count();
        $ActivityCount = FMPlanningActivity::count();
        $AllocationCount = Allocation::count();
        $AllocationUACSCount = FMAllocationUACS::count();
        $AllocationPAPCount = FMAllocationPAP::count();
        $AllocationPAPSAACount = FMAllocationPAPSAA::count();
        $AllocationActivitySAACount = FMAllocationActivitySAA::count();
        $AllocationUACSSAACount = FMAllocationUACSSAA::count();
        $Count = array($IncomingCount,$AcceptedCount,$RejectedCount,$OutgoingCount,$ClosedCount,$PAPCount,$UACSCount,$ActivityCount,$PendingCount,$AllocationCount,$AllocationUACSCount,$AllocationPAPCount,$AllocationPAPSAACount,$AllocationActivitySAACount,$AllocationUACSSAACount);
        return($Count);
    }

    public function getcountaccounting() {

        // $this->authorize('FMPlanning', \App\Models\FinancialManagement::class);

        $Employee = Employee:: where('email', auth()->user()->email )->get()->first();
       
        $IncomingCount = 0;
        $AcceptedCount = 0;
        $RejectedCount = 0;
        $ClosedCount = 0;
        $OutgoingCount = 0;
        $PendingCount = 0;
        //  $LeaveYear[] = array($Leave->id, $date1,$Leave->leaveid,$Leave->employeeid,$data);


        $Vouchers = FinancialManagementRoute::orderBy('created_at', 'DESC')->with('Voucher','userUnit')
        ->get()->unique('sequenceid')->where('unitid','=',$Employee->unitid);

        foreach ($Vouchers as $Route)
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
                    $PendingCount = $PendingCount + 1;
                } 
            }

        $Outgoing = FinancialManagementRoute::orderBy('created_at', 'DESC')->with('Voucher','userUnit')
        ->get()->unique('sequenceid')->where('is_forwarded','=',true)->where('userunitid','=',$Employee->unitid);
       
        foreach ($Outgoing as $Outgoing)
        {
            $OutgoingCount = $OutgoingCount + 1;
        }

        $Activity = AActivity::count();
        $UACS = AUACS::count();

        $Count = array($IncomingCount,$AcceptedCount,$RejectedCount,$OutgoingCount,$ClosedCount,$PendingCount,$Activity,$UACS );
        return($Count);
    }
    


    public function getcountcashier() {

        // $this->authorize('FMPlanning', \App\Models\FinancialManagement::class);

        $Employee = Employee:: where('email', auth()->user()->email )->get()->first();
       
        $IncomingCount = 0;
        $AcceptedCount = 0;
        $RejectedCount = 0;
        $ClosedCount = 0;
        $OutgoingCount = 0;
        $PendingCount = 0;
        //  $LeaveYear[] = array($Leave->id, $date1,$Leave->leaveid,$Leave->employeeid,$data);


        $Vouchers = FinancialManagementRoute::orderBy('created_at', 'DESC')->with('Voucher','userUnit')
        ->get()->unique('sequenceid')->where('unitid','=',$Employee->unitid);

        foreach ($Vouchers as $Route)
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
                    $PendingCount = $PendingCount + 1;
                } 
            }

        $Outgoing = FinancialManagementRoute::orderBy('created_at', 'DESC')->with('Voucher','userUnit')
        ->get()->unique('sequenceid')->where('is_forwarded','=',true)->where('unitid','!=','15')->where('userunitid','=',$Employee->unitid);
       
        foreach ($Outgoing as $Outgoing)
        {
            $OutgoingCount = $OutgoingCount + 1;
        }

        $AccountNameCount = AccountName::count();
        $AccountNumberCount = AccountNumber::count();

        $Count = array($IncomingCount,$AcceptedCount,$RejectedCount,$OutgoingCount,$ClosedCount,$PendingCount,$AccountNameCount,$AccountNumberCount );
        return($Count);
    }
    
    public function getActivity(Request $request) 
    {
      $Activities = FMPlanningActivity::where('papid','=',$request->papid)->get();
      if (count($Activities) > 0) {

        return response()->json($Activities,);

      }
    }

    public function getActivityAllocation(Request $request) 
    {
      $Activities = FMPlanningActivity::where('papid','=',$request->papid)->get();
      if (count($Activities) > 0) {

        return response()->json($Activities,);

      }
    }

    public function getActivityAllocationSAA(Request $request) 
    {
      $Activities = FMPlanningActivity::where('papid','=',$request->papid)->get();
      if (count($Activities) > 0) {

        return response()->json($Activities,);

      }
    }

    public function getRemBal(Request $request) 
    {
      $RemBal = Allocation::where('activityid','=',$request->activityid)->orderby('year','desc')->groupby('year')->get();

      
      if (count($RemBal) > 0) {
        return response()->json($RemBal,);
      }

    }

    public function getRemBalOffice(Request $request) 
    {
      $RemBal = Allocation::where('activityid','=',$request->activityid)->where('year','=',$request->year)->where('papid','=',$request->papid)->orderby('year','desc')->get();

      
      if (count($RemBal) > 0) {
        return response()->json($RemBal,);
      }

    }

    public function getRemBalyear(Request $request) 
    {

      $RemBalyear = Allocation::where('activityid','=',$request->activityid)->where('year','=',$request->year)->where('office','=',$request->office)->where('papid','=',$request->papid)->get();
      if (count($RemBalyear) > 0) {
        return response()->json($RemBalyear,);
      }

    }


    public function getRemBalAllocPAP(Request $request) 
    {
      $RemBal = FMAllocationPAP::where('papid','=',$request->papid)->orderby('year','desc')->groupby('expense_class')->get();
      if (count($RemBal) > 0) {
        return response()->json($RemBal,);
      }

    }

    public function getRemBalAllocPAPSAA(Request $request) 
    {
      $RemBal = FMAllocationPAPSAA::where('papid','=',$request->papid)->orderby('year','desc')->groupby('expense_class')->get();
      if (count($RemBal) > 0) {
        return response()->json($RemBal,);
      }

    }


    public function getRemBalAllocPAPoffice(Request $request) 
    {
      $RemBal = FMAllocationPAP::where('papid','=',$request->papid)->where('expense_class',$request->expense_class)->orderby('year','desc')->get();
      if (count($RemBal) > 0) {
        return response()->json($RemBal,);
      }

    }

    public function getRemBalAllocPAPofficeSAA(Request $request) 
    {
      $RemBal = FMAllocationPAPSAA::where('papid','=',$request->papid)->where('expense_class',$request->expense_class)->orderby('year','desc')->get();
      if (count($RemBal) > 0) {
        return response()->json($RemBal,);
      }

    }


    public function getRemBalAllocPAPyear(Request $request) 
    {
      $RemBal = FMAllocationPAP::where('papid','=',$request->papid)->where('office','=', $request->papoffice)->where('expense_class',$request->expense_class)->orderby('year','desc')->get();
      if (count($RemBal) > 0) {
        return response()->json($RemBal,);
      }

    }

    public function getRemBalAllocPAPyearSAA(Request $request) 
    {
      $RemBal = FMAllocationPAPSAA::where('papid','=',$request->papid)->where('office','=', $request->papoffice)->where('expense_class',$request->expense_class)->orderby('year','desc')->get();
      if (count($RemBal) > 0) {
        return response()->json($RemBal,);
      }

    }

    public function getRemBalyearAllocPAP(Request $request) 
    {

      $RemBalyear = FMAllocationPAP::where('papid','=',$request->papid)->where('year','=',$request->year)->where('office','=', $request->papoffice)->where('expense_class',$request->expense_class)->get();
      if (count($RemBalyear) > 0) {
        return response()->json($RemBalyear,);
      }

    }

    
    public function getRemBalyearAllocPAPSAA(Request $request) 
    {

      $RemBalyear = FMAllocationPAPSAA::where('papid','=',$request->papid)->where('year','=',$request->year)->where('office','=', $request->papoffice)->where('expense_class',$request->expense_class)->get();
      if (count($RemBalyear) > 0) {
        return response()->json($RemBalyear,);
      }

    }

    
    public function getRemBalAllocUACS(Request $request) 
    {
      $RemBal = FMAllocationPAP::where('papid','=',$request->papid)->orderby('year','desc')->groupby('expense_class')->get();
      if (count($RemBal) > 0) {
        return response()->json($RemBal,);
      }

    }

    public function getRemBalAllocUACSSAA(Request $request) 
    {
      $RemBal = FMAllocationPAPSAA::where('papid','=',$request->papid)->orderby('year','desc')->groupby('expense_class')->get();
      if (count($RemBal) > 0) {
        return response()->json($RemBal,);
      }

    }

    public function getRemBalAllocUACSoffice(Request $request) 
    {
      $RemBal = FMAllocationPAP::where('papid','=',$request->papid)->where('expense_class',$request->expense_class)->orderby('year','desc')->groupby('expense_class')->get();
      if (count($RemBal) > 0) {
        return response()->json($RemBal,);
      }

    }

    public function getRemBalAllocUACSyear(Request $request) 
    {
      $RemBal = FMAllocationPAP::where('papid','=',$request->papid)->where('office','=',$request->papoffice)->where('expense_class',$request->expense_class)->orderby('year','desc')->get();
      if (count($RemBal) > 0) {
        return response()->json($RemBal,);
      }

    }

    public function getRemBalAllocUACSyearSAA(Request $request) 
    {
      $RemBal = FMAllocationPAPSAA::where('papid','=',$request->papid)->where('office','=',$request->papoffice)->where('expense_class',$request->expense_class)->orderby('year','desc')->get();
      if (count($RemBal) > 0) {
        return response()->json($RemBal,);
      }

    }


    

    public function getRemBalrealign_year(Request $request) 
    {
      $RemBalyear = FMAllocationUACS::where('papid','=',$request->papid)->where('office','=',$request->papoffice)->where('expense_class',$request->expense_class)->groupby('uacsid')->where('year', $request->year)->orderby('year','desc')->get();
    //   $RemBalyear = FMAllocationUACS::where('papid','=',$request->papid)->where('year','=',$request->year)->with('UACS')->groupby('uacsid')->get();
      if (count($RemBalyear) > 0) {
        foreach ($RemBalyear as $Allocation)
        {
            $uacslists[] = array($Allocation->UACS->id, $Allocation->UACS->uacs,$Allocation->UACS->description );
        }
        return response()->json($uacslists,);
      }
    }

    public function getRemBalrealign_yearSAA(Request $request) 
    {
      $RemBalyear = FMAllocationUACSSAA::where('papid','=',$request->papid)->where('office','=',$request->papoffice)->where('expense_class',$request->expense_class)->groupby('uacsid')->where('year', $request->year)->orderby('year','desc')->get();
    //   $RemBalyear = FMAllocationUACS::where('papid','=',$request->papid)->where('year','=',$request->year)->with('UACS')->groupby('uacsid')->get();
      if (count($RemBalyear) > 0) {
        foreach ($RemBalyear as $Allocation)
        {
            $uacslists[] = array($Allocation->UACS->id, $Allocation->UACS->uacs,$Allocation->UACS->description );
        }
        return response()->json($uacslists,);
      }
    }



    public function getRemBalrealignyexpense(Request $request) 
    {
        $RemBal = FMAllocationPAP::where('papid','=',$request->papid)->where('expense_class',$request->expense_class)->orderby('year','desc')->get();
      if (count($RemBal) > 0) {
        return response()->json($RemBal,);
      }

    }

    
    public function getRemBalrealignyexpenseSAA(Request $request) 
    {
        $RemBal = FMAllocationPAPSAA::where('papid','=',$request->papid)->where('expense_class',$request->expense_class)->orderby('year','desc')->get();
      if (count($RemBal) > 0) {
        return response()->json($RemBal,);
      }

    }

    

    public function getRemBalrealignoffice(Request $request) 
    {
        $RemBal = FMAllocationPAP::where('papid','=',$request->papid)->where('expense_class',$request->expense_class)->where('office','=',$request->papoffice)->orderby('year','desc')->get();
      if (count($RemBal) > 0) {
        return response()->json($RemBal,);
      }

    }
    public function getRemBalrealignofficeSAA(Request $request) 
    {
        $RemBal = FMAllocationPAPSAA::where('papid','=',$request->papid)->where('expense_class',$request->expense_class)->where('office','=',$request->papoffice)->orderby('year','desc')->get();
      if (count($RemBal) > 0) {
        return response()->json($RemBal,);
      }

    }

    public function getRemBalrealignAllocUACSyear(Request $request) 
    {

      $RemBalyear = FMAllocationPAP::where('papid','=',$request->papid)->groupby('expense_class')->get();
      if (count($RemBalyear) > 0) {
        return response()->json($RemBalyear,);
      }

    }

    public function getRemBalrealignAllocUACSyearSAA(Request $request) 
    {

      $RemBalyear = FMAllocationPAPSAA::where('papid','=',$request->papid)->groupby('expense_class')->get();
      if (count($RemBalyear) > 0) {
        return response()->json($RemBalyear,);
      }

    }

    public function getRemBalrealignAllocUACSoffice(Request $request) 
    {

      $RemBalyear = FMAllocationPAP::where('papid','=',$request->papid)->where('office','=',$request->papoffice)->get();
      if (count($RemBalyear) > 0) {
        return response()->json($RemBalyear,);
      }

    }

    public function getRemBalrealignAllocUACS(Request $request) 
    {

      $RemBalyear = FMAllocationUACS::where('papid','=',$request->papid)->where('year','=',$request->year)->with('UACS')->groupby('uacsid')->get();
      if (count($RemBalyear) > 0) {
        foreach ($RemBalyear as $Allocation)
        {
            $uacslists[] = array($Allocation->UACS->id, $Allocation->UACS->uacs,$Allocation->UACS->description );
        }
        return response()->json($uacslists,);
      }

    }

    public function getRemBalrealignAllocUACSalloc(Request $request) 
    {

      $RemBalyear = FMAllocationUACS::where('uacsid','=',$request->allocuacsid)->where('papid','=',$request->papid)->where('year','=',$request->year)->where('expense_class', $request->expense_class)->where('office',$request->office)->get();
      if (count($RemBalyear) > 0) {

            return response()->json($RemBalyear,);
 
      }

    }

    public function getRemBalrealignAllocUACSallocSAA(Request $request) 
    {

      $RemBalyear = FMAllocationUACSSAA::where('uacsid','=',$request->allocuacsid)->where('papid','=',$request->papid)->where('year','=',$request->year)->where('expense_class', $request->expense_class)->where('office',$request->office)->get();
      if (count($RemBalyear) > 0) {

            return response()->json($RemBalyear,);
 
      }

    }

    
    public function getRemBalrealignAllocUACSallocOffice(Request $request) 
    {

      $RemBalyear = FMAllocationUACS::where('uacsid','=',$request->allocuacsid)->where('papid','=',$request->papid)->where('year','=',$request->year)->where('office','=',$request->office)->get();
      if (count($RemBalyear) > 0) {

            return response()->json($RemBalyear,);
 
      }

    }

    public function getRemBalrealignAllocUACSallocOfficeSAA(Request $request) 
    {

      $RemBalyear = FMAllocationUACSSAA::where('uacsid','=',$request->allocuacsid)->where('papid','=',$request->papid)->where('year','=',$request->year)->where('office','=',$request->office)->get();
      if (count($RemBalyear) > 0) {

            return response()->json($RemBalyear,);
 
      }

    }


    
    public function getRemBalyearAllocUACS(Request $request) 
    {

      $RemBalyear = FMAllocationPAP::where('papid','=',$request->papid)->where('office','=',$request->papoffice)->where('year','=',$request->year)->where('expense_class',$request->expense_class)->get();
      if (count($RemBalyear) > 0) {
        return response()->json($RemBalyear,);
      }

    }

    public function getRemBalyearAllocUACSSAA(Request $request) 
    {

      $RemBalyear = FMAllocationPAPSAA::where('papid','=',$request->papid)->where('office','=',$request->papoffice)->where('year','=',$request->year)->where('expense_class',$request->expense_class)->get();
      if (count($RemBalyear) > 0) {
        return response()->json($RemBalyear,);
      }

    }


    public function getRemBalUACS(Request $request) 
    {
      $RemBalUACS = FMAllocationUACS::where('uacsid','=',$request->uacsid)->orderby('year','desc')->groupby('year')->get();
      if (count($RemBalUACS) > 0) {
        return response()->json($RemBalUACS,);
      }

    }

    public function getRemBalUACSOffice(Request $request) 
    {
      $RemBalUACS = FMAllocationUACS::where('uacsid','=',$request->uacsid)->where('year','=',$request->year)->where('papid','=',$request->papid)->orderby('year','desc')->get();
      if (count($RemBalUACS) > 0) {
        return response()->json($RemBalUACS,);
        
      }


    }

    public function getRemBalyearUACS(Request $request) 
    {

      $RemBalyearUACS = FMAllocationUACS::where('uacsid','=',$request->uacsid)->where('year','=',$request->year)->where('office','=', $request->office)->where('papid','=', $request->papid)->get();
      if (count($RemBalyearUACS) > 0) {
        return response()->json($RemBalyearUACS,);
      }

    }

    public function getUACS(Request $request) 
    {
      $UACSs = AUACS::where('a_activity_id','=',$request->activity_id)->get();

      if (count($UACSs) > 0) {

        return response()->json($UACSs,);

      }
    }


//Charging 


public function ChargingExpenseClass(Request $request) 
{

  $Actitivities = Allocation::where('papid','=',$request->papid)->where('expense_class',$request->expense_class)->groupby('activityid')->get();
   if (count($Actitivities) > 0) {

    foreach ($Actitivities as $Activity)
    {
        $ActivitName = FMPlanningActivity::where('id', $Activity->activityid)->get()->first();
        $ActivityList[] = array($ActivitName->id,$ActivitName->activity);
    }

    return response()->json($ActivityList,);
  }

}

public function ChargingExpenseClassSAA(Request $request) 
{

  $Actitivities = FMAllocationActivitySAA::where('papid','=',$request->papid)->where('expense_class',$request->expense_class)->groupby('activityid')->get();
   if (count($Actitivities) > 0) {

    foreach ($Actitivities as $Activity)
    {
        $ActivitName = FMPlanningActivity::where('id', $Activity->activityid)->get()->first();
        $ActivityList[] = array($ActivitName->id,$ActivitName->activity);
    }

    return response()->json($ActivityList,);
  }

}





public function ChargingExpenseClassUACS(Request $request) 
{

   $UACSs = FMAllocationUACS::where('papid','=',$request->papid)->where('expense_class',$request->expense_class)->groupby('uacsid')->get();
  
  if (count($UACSs) > 0) {


    foreach ($UACSs as $UACS)
    {
        $UACSName = FMPlanningUACS::where('id', $UACS->uacsid)->get()->first();
        $UACSList[] = array($UACSName->id,$UACSName->uacs, $UACSName->description);
    }
    return response()->json($UACSList,);
  }

}

public function ChargingExpenseClassUACSSAA(Request $request) 
{

   $UACSs = FMAllocationUACSSAA::where('papid','=',$request->papid)->where('expense_class',$request->expense_class)->groupby('uacsid')->get();
  
  if (count($UACSs) > 0) {


    foreach ($UACSs as $UACS)
    {
        $UACSName = FMPlanningUACS::where('id', $UACS->uacsid)->get()->first();
        $UACSList[] = array($UACSName->id,$UACSName->uacs, $UACSName->description);
    }
    return response()->json($UACSList,);
  }

}


public function ChargingActivity(Request $request) 
{

  $Actitivities = Allocation::where('papid','=',$request->papid)->where('expense_class',$request->expense_class)->where('activityid', $request->activityid)->groupby('year')->get();

  
  if (count($Actitivities) > 0) {

    return response()->json($Actitivities,);
  }


}


public function ChargingActivitySAA(Request $request) 
{

  $Actitivities = FMAllocationActivitySAA::where('papid','=',$request->papid)->where('expense_class',$request->expense_class)->where('activityid', $request->activityid)->groupby('year')->get();

  
  if (count($Actitivities) > 0) {

    return response()->json($Actitivities,);
  }

}

public function ChargingUACS(Request $request) 
{

  $Actitivities = FMAllocationUACS::where('papid','=',$request->papid)->where('expense_class',$request->expense_class)->where('uacsid', $request->uacsid)->groupby('year')->get();

  
  if (count($Actitivities) > 0) {

    return response()->json($Actitivities,);
  }

}


public function ChargingUACSSAA(Request $request) 
{

  $Actitivities = FMAllocationUACSSAA::where('papid','=',$request->papid)->where('expense_class',$request->expense_class)->where('uacsid', $request->uacsid)->groupby('year')->get();

  
  if (count($Actitivities) > 0) {

    return response()->json($Actitivities,);
  }

}




public function ChargingYear(Request $request) 
{

  $Actitivities = Allocation::where('papid','=',$request->papid)->where('expense_class',$request->expense_class)->where('activityid', $request->activityid)->where('year', $request->year)->groupby('office')->get();

  
  if (count($Actitivities) > 0) {

    return response()->json($Actitivities,);
  }

}


public function ChargingYearSAA(Request $request) 
{

  $Actitivities = FMAllocationActivitySAA::where('papid','=',$request->papid)->where('expense_class',$request->expense_class)->where('activityid', $request->activityid)->where('year', $request->year)->groupby('office')->get();

  
  if (count($Actitivities) > 0) {

    return response()->json($Actitivities,);
  }

}



public function ChargingUACSyear(Request $request) 
{

  $Actitivities = FMAllocationUACS::where('papid','=',$request->papid)->where('expense_class',$request->expense_class)->where('uacsid', $request->uacsid)->where('year', $request->year_uacs)->groupby('office')->get();

  
  if (count($Actitivities) > 0) {

    return response()->json($Actitivities,);
  }

}


public function ChargingUACSyearSAA(Request $request) 
{

  $Actitivities = FMAllocationUACSSAA::where('papid','=',$request->papid)->where('expense_class',$request->expense_class)->where('uacsid', $request->uacsid)->where('year', $request->year_uacs)->groupby('office')->get();

  
  if (count($Actitivities) > 0) {

    return response()->json($Actitivities,);
  }

}


public function ChargingOffice(Request $request) 
{

  $RemBalyear = Allocation::where('activityid','=',$request->activityid)->where('year','=',$request->year)->where('office','=',$request->office)->where('papid','=',$request->papid)->where('expense_class', $request->expense_class)->get();
  if (count($RemBalyear) > 0) {
    return response()->json($RemBalyear,);
  }

}

public function ChargingOfficeSAA(Request $request) 
{

  $RemBalyear = FMAllocationActivitySAA::where('activityid','=',$request->activityid)->where('year','=',$request->year)->where('office','=',$request->office)->where('papid','=',$request->papid)->where('expense_class', $request->expense_class)->get();
  if (count($RemBalyear) > 0) {
    return response()->json($RemBalyear,);
  }

}

public function ChargingUACSoffice(Request $request) 
{

  $RemBalyear = FMAllocationUACS::where('uacsid','=',$request->uacsid)->where('year','=',$request->year_uacs)->where('office','=',$request->uacsoffice)->where('papid','=',$request->papid)->where('expense_class', $request->expense_class)->get();
  if (count($RemBalyear) > 0) {
    return response()->json($RemBalyear,);
  }

}

public function ChargingUACSofficeSAA(Request $request) 
{

  $RemBalyear = FMAllocationUACSSAA::where('uacsid','=',$request->uacsid)->where('year','=',$request->year_uacs)->where('office','=',$request->uacsoffice)->where('papid','=',$request->papid)->where('expense_class', $request->expense_class)->get();
  if (count($RemBalyear) > 0) {
    return response()->json($RemBalyear,);
  }

}
//End Charging 


    public function getAccountNumber(Request $request) 
    {
      $AccountNumbers = AccountNumber::where('acct_id','=',$request->acct_id)->get();

      if (count($AccountNumbers) > 0) {

        return response()->json($AccountNumbers,);

      }
    }

    
    public function getAccountAddress(Request $request) 
    {
      $AccountAddress = AccountName::where('id','=',$request->acct_id)->get();

      if (count($AccountAddress) > 0) {

        return response()->json($AccountAddress,);

      }
    }

    public function getPayee(Request $request) 
    {

        $AccountNumbers = AccountNumber::where('acct_id','=',$request->acct_id)->get();

        if (count($AccountNumbers) > 0) {

        return response()->json($AccountNumbers,);

      }
    }

    

    public function  accountingentry(Request $request) 
    {
        
        $this->authorize('FmAccounting', \App\Models\FinancialManagement::class);

        $formfields = $request->validate([
            'fmid' => 'required',
            'activity_id' => 'required',
            'uacs_id' => 'required',
        ]);

        $formfields['debit'] = $request->debit;
        $formfields['credit'] =  $request->credit;
        $formfields['userid'] = auth()->user()->id;
        
        AccountEntry::updateorCreate($formfields);
        
        $formfield = $request->validate([
            'actiondate' => 'required',
        ]);
        
        $formfield['action'] = 'ACCOUNTING ENTRY CREATED';
        $voucher = FinancialManagement::where('id', '=', $request->fmid)->get()->first();
        $formfield['sequenceid'] = $voucher->sequenceid;
        $formfield['userid'] = auth()->user()->id;
        $formfield['remarks'] = '';
        $formfield['is_active'] = true;

        $data = Employee::where('email','=',auth()->user()->email)->get()->first(); 
        $formfield['officeid'] = $data->officeid;
        $formfield['sectionid'] = $data->sectionid;
        $formfield['unitid'] =  $data->unitid;
        $formfield['userunitid'] = $data->unitid;
        $formfield['is_accepted'] = true;
            
        FinancialManagementRoute::updateorCreate($formfield);

        return back()->with('message', "Accounting Entry Saved Successfully!");

    }


    public function  planningChargingSAA(Request $request) 
    
    {

        $this->authorize('FMPlanning', \App\Models\FinancialManagement::class);

        $formfields1 = $request->validate([
            'fmid' => 'required',
            'papid_saa' => 'required',
            'activityid_saa' => 'required',
            'uacsid_saa' => 'required',
            'amount_saa' => 'required',
            'year_saa' => 'required',
            'year_uacs_saa' => 'required',
            'activityoffice_saa' => 'required',
            'uacsoffice_saa' => 'required',
            'expense_class_saa' => 'required',
            // '	papoffice' => 'required',
        ]);

        $otherfields = $request->validate([
           
            'rem_bal_saa' => 'required',
            'rem_baluacs_saa' => 'required',

        ]);

      
        $total = FinancialManagement::where('id',$request->fmid)->get()->first();

       
            $ChargingAmounts = FMCharging::where('fmid',$request->fmid)->get();

            $subtotal = 0;
            foreach($ChargingAmounts as $ChargingAmount)
            {
                $subtotal = $subtotal + (float)$ChargingAmount->amount;
            }
    
            $balance = ((float)$total->amount) -  $subtotal;
            
    
            $formfields['papid'] = $request->papid_saa;
            $formfields['fmid'] = $request->fmid;
            $formfields['activityid'] = $request->activityid_saa;
            $formfields['uacsid'] = $request->uacsid_saa;
            $formfields['amount'] = $request->amount_saa;
            $formfields['year'] = $request->year_saa;
            $formfields['year_uacs'] = $request->year_uacs_saa;
            $formfields['activityoffice'] = $request->activityoffice_saa;
            $formfields['uacsoffice'] = $request->uacsoffice_saa;
            $formfields['expense_class'] = $request->expense_class_saa;

         if($balance >= 0)
          {

            if(((float)$request->amount_saa)  > ((float)$balance))

            {
                return back()->with('failed', "Charging exceeds the amount of the voucher. Please check Charging!");
            }
    
            else
            {

                $formfields['userid'] = auth()->user()->id;
                $formfields['is_obligated'] = false;
                $formfields['is_disbursed'] = false;
                $formfields['category'] = "SAA";
      
                $Check = FMCharging::where('fmid','=',$request->fmid)->where('papid','=',$request->papid_saa)->where('activityid','=',$request->activityid_saa)->where('uacsid','=',$request->uacsid_saa)->where('category','SAA')->where('year','=',$request->year_saa)->where('year_uacs','=',$request->year_uacs_saa)->where('activityoffice','=',$request->activityoffice_saa)->where('expense_class','=',$request->expense_class_saa)->get()->first();
              
                if ($Check)
                {
                    return back()->with('failed', 'Duplicate Activity Charging!');
             
                }

                $Check = FMCharging::where('fmid','=',$request->fmid)->where('papid','=',$request->papid_saa)->where('activityid','=',$request->activityid_saa)->where('uacsid','=',$request->uacsid_saa)->where('category','SAA')->where('year','=',$request->year_saa)->where('year_uacs','=',$request->year_uacs_saa)->where('uacsoffice','=',$request->uacsoffice_saa)->where('expense_class','=',$request->expense_class_saa)->get()->first();
              
                if ($Check)
                {
                    return back()->with('failed', 'Duplicate UACS Charging!');
             
                }
                $PapOffice =  FMAllocationActivitySAA::where('activityid','=',$request->activityid_saa)->where('papid','=',$request->papid_saa)->where('year','=',$request->year_saa)->where('expense_class','=',$request->expense_class_saa)->get()->first();
                $formfields['papoffice'] = $PapOffice->papoffice;
                FMCharging::Create($formfields);
        
                $formfield = $request->validate([
                    'actiondate' => 'required',
                ]);
        
                $formfield['action'] = 'CHARGING CREATED';
                $voucher = FinancialManagement::where('id', '=', $request->fmid)->get()->first();
                $formfield['sequenceid'] = $voucher->sequenceid;
                $formfield['userid'] = auth()->user()->id;
                $formfield['remarks'] = 'Charging Amount : ' . $request->amount_saa;
                $formfield['is_active'] = true;
                
                $data = Employee::where('email','=',auth()->user()->email)->get()->first(); 
                $formfield['officeid'] = $data->officeid;
                $formfield['sectionid'] = $data->sectionid;
                $formfield['unitid'] =  $data->unitid;
                $formfield['userunitid'] = $data->unitid;
                $formfield['is_accepted'] = true;
            
                $AllocationActivity = FMAllocationActivitySAA::where('activityid', '=', $request->activityid_saa)->where('year','=',$request->year_saa)->where('papid','=',$request->papid_saa)->where('office','=',$request->activityoffice_saa)->where('expense_class','=',$request->expense_class_saa)->get()->first();
                
                $num1 = $AllocationActivity->rem_bal;
                $num2 = $request->amount_saa;
    
                $AllocationUACS = FMAllocationUACSSAA::where('uacsid', '=', $request->uacsid_saa)->where('year','=',$request->year_saa)->where('papid','=',$request->papid_saa)->where('office','=',$request->uacsoffice_saa)->where('expense_class','=',$request->expense_class_saa)->get()->first();
                $num3 = $AllocationUACS->rem_bal;
                
                if ($num1 >= $num2 && $num3>=$num2)
                {
                    $rem_balActivity['rem_bal'] = $num1 - $num2;
                    $rem_balUacs['rem_bal'] = $num3 - $num2;
                    $AllocationActivity->update($rem_balActivity);
                    $AllocationUACS->update($rem_balUacs);
                    FinancialManagementRoute::create($formfield);
                    return back()->with('message', "Charging Saved Successfully!");

                }
                else
                {
                    return back()->with('failed', "Charging exceeds the remaining balance of Activity. Please check Charging!");
                }

            }
        }
        else
          {
            return back()->with('failed', "Charging exceeds the amount of the voucher. Please check Charging!");
          }
    }



    public function  planningCharging(Request $request) 
    
    {

        $this->authorize('FMPlanning', \App\Models\FinancialManagement::class);

        $formfields = $request->validate([
            'fmid' => 'required',
            'papid' => 'required',
            'activityid' => 'required',
            'uacsid' => 'required',
            'amount' => 'required',
            'year' => 'required',
            'year_uacs' => 'required',
            'activityoffice' => 'required',
            'uacsoffice' => 'required',
            'expense_class' => 'required',
            // '	papoffice' => 'required',
        ]);

        $otherfields = $request->validate([
           
            'rem_bal' => 'required',
            'rem_baluacs' => 'required',

        ]);

      
        $total = FinancialManagement::where('id',$request->fmid)->get()->first();

       
            $ChargingAmounts = FMCharging::where('fmid',$request->fmid)->get();

            $subtotal = 0;
            foreach($ChargingAmounts as $ChargingAmount)
            {
                $subtotal = $subtotal + (float)$ChargingAmount->amount;
            }
    
            $balance = ((float)$total->amount) -  $subtotal;
    

         if($balance >= 0)
          {

            if(((float)$request->amount)  > ((float)$balance))

            {
                return back()->with('failed', "Charging exceeds the amount of the voucher. Please check Charging!");
            }
    
            else
            {

                $formfields['userid'] = auth()->user()->id;
                $formfields['is_obligated'] = false;
                $formfields['is_disbursed'] = false;
                $formfields['category'] = "GAA";
      
                $Check = FMCharging::where('fmid','=',$request->fmid)->where('papid','=',$request->papid)->where('activityid','=',$request->activityid)->where('uacsid','=',$request->uacsid)->where('year','=',$request->year)->where('category','SAA')->where('year_uacs','=',$request->year_uacs)->where('activityoffice','=',$request->activityoffice)->where('expense_class','=',$request->expense_class)->get()->first();
              
                if ($Check)
                {
                    return back()->with('failed', 'Duplicate Activity Charging!');
             
                }

                $Check = FMCharging::where('fmid','=',$request->fmid)->where('papid','=',$request->papid)->where('activityid','=',$request->activityid)->where('uacsid','=',$request->uacsid)->where('year','=',$request->year)->where('category','SAA')->where('year_uacs','=',$request->year_uacs)->where('uacsoffice','=',$request->uacsoffice)->where('expense_class','=',$request->expense_class)->get()->first();
              
                if ($Check)
                {
                    return back()->with('failed', 'Duplicate UACS Charging!');
             
                }
                $PapOffice =  Allocation::where('activityid','=',$request->activityid)->where('papid','=',$request->papid)->where('year','=',$request->year)->where('expense_class','=',$request->expense_class)->get()->first();
                $formfields['papoffice'] = $PapOffice->papoffice;
                FMCharging::Create($formfields);
        
                $formfield = $request->validate([
                    'actiondate' => 'required',
                ]);
        
                $formfield['action'] = 'CHARGING CREATED';
                $voucher = FinancialManagement::where('id', '=', $request->fmid)->get()->first();
                $formfield['sequenceid'] = $voucher->sequenceid;
                $formfield['userid'] = auth()->user()->id;
                $formfield['remarks'] = 'Charging Amount : ' . $request->amount;
                $formfield['is_active'] = true;
                
                $data = Employee::where('email','=',auth()->user()->email)->get()->first(); 
                $formfield['officeid'] = $data->officeid;
                $formfield['sectionid'] = $data->sectionid;
                $formfield['unitid'] =  $data->unitid;
                $formfield['userunitid'] = $data->unitid;
                $formfield['is_accepted'] = true;
            
                $AllocationActivity = Allocation::where('activityid', '=', $request->activityid)->where('year','=',$request->year)->where('papid','=',$request->papid)->where('office','=',$request->activityoffice)->where('expense_class','=',$request->expense_class)->get()->first();
                
                $num1 = $AllocationActivity->rem_bal;
                $num2 = $request->amount;
                $AllocationUACS = FMAllocationUACS::where('uacsid', '=', $request->uacsid)->where('year','=',$request->year)->where('papid','=',$request->papid)->where('office','=',$request->uacsoffice)->where('expense_class','=',$request->expense_class)->get()->first();
                $num3 = $AllocationUACS->rem_bal;
                
                if ($num1 >= $num2 && $num3>=$num2)
                {
                    $rem_balActivity['rem_bal'] = $num1 - $num2;
                    $rem_balUacs['rem_bal'] = $num3 - $num2;
                    $AllocationActivity->update($rem_balActivity);
                    $AllocationUACS->update($rem_balUacs);
                    FinancialManagementRoute::create($formfield);
                    return back()->with('message', "Charging Saved Successfully!");

                }
                else
                {
                    return back()->with('failed', "Charging exceeds the remaining balance of Activity. Please check Charging!");
                }

            }
        }
        else
          {
            return back()->with('failed', "Charging exceeds the amount of the voucher. Please check Charging!");
          }
    }

    public function signatoryincoming() 
    {

        $this->authorize('FMSignatory', \App\Models\FinancialManagement::class);
     
        $Count = $this->getcount();
        $Employee = Employee:: where('email', auth()->user()->email )->get()->first();
        $Routes = FinancialManagementRoute::orderBy('created_at', 'DESC')->with('Voucher','User','AccountName')
                    ->get()->unique('sequenceid')->where('is_forwarded','=','1')->where('unitid','=',$Employee->unitid);
 
        $FinalAda = [];
        foreach($Routes as $Route)
        {

          $Voucher = FinancialManagement::where('sequenceid', $Route->sequenceid)->get()->first();

          $AdaNo = FMCash::where('fmid', $Voucher->id)->get()->first();

          if ($AdaNo)
          {
            $FinalAda[] = array($Route->id, $AdaNo->adano);
          }

        } 
        return view('financial-management.signatory.incoming.index',compact('Count','Routes','FinalAda'));
        
    } 

    public function signatoryprocessing() 
    {
        $this->authorize('FMSignatory', \App\Models\FinancialManagement::class);
        
        $Count = $this->getcount();

        $Employee = Employee:: where('email', auth()->user()->email )->get()->first();
        $Routes = FinancialManagementRoute::orderBy('created_at', 'DESC')->with('Voucher','User','AccountName')
                    ->get()->unique('sequenceid')->where('is_accepted','=','1')->where('unitid','=',$Employee->unitid);
   
        $Offices = Office::orderby('id')->get();
        $Sections = Section::orderby('section')->get();
        $Units = Unit::orderby('unit')->get();

        $AdaProcessing = [];


        foreach($Routes as $Route)
        {
          if($Route->remarks == 'ACCEPTED - FOR SIGNATURE')
          {
            $Voucher = FinancialManagement::where('sequenceid', $Route->sequenceid)->get()->first();

            $AdaNo = FMCash::where('fmid', $Voucher->id)->get()->first();

            if ($AdaNo)
            {
              $AdaProcessing[] = array($Route->id, $AdaNo->adano);
            }
         
          }

        } 


 

        return view('financial-management.signatory.processing.index',compact('Count','Routes','Offices','Sections','Units','AdaProcessing'));
        
    } 

    public function signatoryoutgoing() 
    {
        $this->authorize('FMSignatory', \App\Models\FinancialManagement::class);
        $Count = $this->getcount();

        $Employee = Employee:: where('email', auth()->user()->email )->get()->first();
        $Routes = FinancialManagementRoute::orderBy('created_at', 'DESC')->with('Voucher','User','AccountName')
                    ->get()->unique('sequenceid')->where('is_forwarded','=','1')->where('userunitid','=',$Employee->unitid);
        $Offices = Office::orderby('id')->get();
        $Sections = Section::orderby('section')->get();
        $Units = Unit::orderby('unit')->get();

        return view('financial-management.signatory.outgoing.index',compact('Count','Routes','Offices','Sections','Units'));
        
    } 


    public function signatoryrejected() 
    {
        $this->authorize('FMSignatory', \App\Models\FinancialManagement::class);
        $Count = $this->getcount();

        $Employee = Employee:: where('email', auth()->user()->email )->get()->first();
        $Routes = FinancialManagementRoute::orderBy('created_at', 'DESC')->with('Voucher','User','AccountName')
                    ->get()->unique('sequenceid')->where('is_rejected','=','1')->where('unitid','=',$Employee->unitid);
        $Offices = Office::orderby('id')->get();
        $Sections = Section::orderby('section')->get();
        $Units = Unit::orderby('unit')->get();

    
   
        return view('financial-management.signatory.rejected.index',compact('Count','Routes','Offices','Sections','Units'));
        
    } 

    public function recordsincoming() 
    {

        $this->authorize('FMRecords', \App\Models\FinancialManagement::class);
     
        $Count = $this->getcount();
        $Employee = Employee:: where('email', auth()->user()->email )->get()->first();
        $Routes = FinancialManagementRoute::orderBy('created_at', 'DESC')->with('Voucher','User','AccountName')
                    ->get()->unique('sequenceid')->where('is_forwarded','=','1')->where('unitid','=',$Employee->unitid);
        
        return view('financial-management.records.incoming.index',compact('Count','Routes'));
        
    } 

    public function recordspending() 
    {

        $this->authorize('FMRecords', \App\Models\FinancialManagement::class);
     
        $Count = $this->getcount();
        $Employee = Employee:: where('email', auth()->user()->email )->get()->first();
        $Routes = FinancialManagementRoute::orderBy('created_at', 'DESC')->with('Voucher','User','AccountName')
                    ->get()->unique('sequenceid')->where('is_accepted','=','0')->where('is_rejected','=','0')->where('is_forwarded','=','0')->where('action','!=','CLOSED')->where('unitid','=',$Employee->unitid);
        
        return view('financial-management.records.pending.index',compact('Count','Routes'));
        
    } 

    public function recordsprocessing() 
    {
        $this->authorize('FMRecords', \App\Models\FinancialManagement::class);
        
        $Count = $this->getcount();

        $Employee = Employee:: where('email', auth()->user()->email )->get()->first();
        $Routes = FinancialManagementRoute::orderBy('created_at', 'DESC')->with('Voucher','User','AccountName')
                    ->get()->unique('sequenceid')->where('is_accepted','=','1')->where('unitid','=',$Employee->unitid);
        $Routes1 = FinancialManagementRoute::orderBy('created_at', 'DESC')->with('Voucher','User','AccountName')
                    ->get()->unique('sequenceid')->where('is_accepted','=','0')->where('is_rejected','=','0')->where('is_forwarded','=','0')->where('unitid','=',$Employee->unitid);

        $Offices = Office::orderby('id')->get();
        $Sections = Section::orderby('section')->get();
        $Units = Unit::orderby('unit')->get();

        return view('financial-management.records.processing.index',compact('Count','Routes','Routes1','Offices','Sections','Units'));
        
    } 

    public function recordsoutgoing() 
    {
        $this->authorize('FMRecords', \App\Models\FinancialManagement::class);
        $Count = $this->getcount();

        $Employee = Employee:: where('email', auth()->user()->email )->get()->first();
        $Routes = FinancialManagementRoute::orderBy('created_at', 'DESC')->with('Voucher','User','AccountName')
                    ->get()->unique('sequenceid')->where('is_forwarded','=','1')->where('userunitid','=',$Employee->unitid);
        $Offices = Office::orderby('id')->get();
        $Sections = Section::orderby('section')->get();
        $Units = Unit::orderby('unit')->get();

        

   
        return view('financial-management.records.outgoing.index',compact('Count','Routes','Offices','Sections','Units'));
        
    } 


    public function recordsrejected() 
    {
        $this->authorize('FMRecords', \App\Models\FinancialManagement::class);
        $Count = $this->getcount();

        $Employee = Employee:: where('email', auth()->user()->email )->get()->first();
        $Routes = FinancialManagementRoute::orderBy('created_at', 'DESC')->with('Voucher','User','AccountName')
                    ->get()->unique('sequenceid')->where('is_rejected','=','1')->where('unitid','=',$Employee->unitid);
        $Offices = Office::orderby('id')->get();
        $Sections = Section::orderby('section')->get();
        $Units = Unit::orderby('unit')->get();

    
   
        return view('financial-management.records.rejected.index',compact('Count','Routes','Offices','Sections','Units'));
        
    } 

    public function cashieraccountnumber() 
    {

        $this->authorize('FMCashier', \App\Models\FinancialManagement::class);
     
        $Count = $this->getcountcashier();

        $AccountNames = AccountName::orderby('acct_name','desc')->get();
        $AccountNumbers  = AccountNumber::with('AccountName')->orderby('id','desc')->get();
        // $Employee = Employee:: where('email', auth()->user()->email )->get()->first();
        // $Routes = FinancialManagementRoute::orderBy('created_at', 'DESC')->with('Voucher','User')
        //             ->get()->unique('sequenceid')->where('is_forwarded','=','1')->where('unitid','=',$Employee->unitid);
        
        return view('financial-management.cashier.accountnumber.index',compact('Count','AccountNames', 'AccountNumbers'));
        
    } 

    public function cashieraccountnumbercreate(Request $request) {

        $this->authorize('create', \App\Models\FMCash::class);

        $formfields = $request->validate([
            'acct_id' => 'required',
            'bank_name' => 'required',
            'acct_no' => 'required',
            'bank_code' => 'required',
            
        ]);
     
        $check = AccountNumber::where([
            ['acct_no', '=', $request->acct_no],
            
  
          ])->first();
  
          if ($check) {
            return back()->with('failed', 'Duplicate Account Number!');
          }

        AccountNumber::updateorCreate($formfields);
    
        return back()->with('message', "Account Number created Successfully!");
 
    }

    public function cashieraccountnamecreate(Request $request) {

        $this->authorize('create', \App\Models\FMCash::class);

        $formfields = $request->validate([
            'acct_name' => 'required',
            'address' => 'required',
            'tinno' => 'required',
        ]);
     
        $check = AccountName::where([
            ['acct_name', '=', $request->acct_name],
  
          ])->first();
  
          if ($check) {
            return back()->with('failed', 'Duplicate Account Name!');
          }

               
        $check = AccountName::where([
            ['tinno', '=', $request->tinno],
  
          ])->first();
  
          if ($check) {
            return back()->with('failed', 'Duplicate Tin Number!');
          }


        AccountName::updateorCreate($formfields);
    
        return back()->with('message', "Account Name created Successfully!");
 
    }

    public function activateaccount(Request $request, AccountName $AccountName) {

        $this->authorize('update', $AccountName);
    
        $formfields['is_active'] = true;
            
        $AccountName->update($formfields);
             
          return back()->with ('success', 'Account Name Updated Successfully!');
       }

       public function deactivateaccount(Request $request, AccountName $AccountName) {

        $this->authorize('update', $AccountName);
    
        $formfields['is_active'] = false;
            
        $AccountName->update($formfields);
             
          return back()->with ('success', 'Account Name Updated Successfully!');
       }
    
          
    public function cashieraccountnumberdelete(Request $request, AccountNumber $AccountNumber) {

        $this->authorize('delete',$AccountNumber);

        $check = FinancialManagement::where('acct_no','=', $AccountNumber->id)->count();

        if($check > 0)
        {
            return back()->with('failed', "Account Number was used in Financial Management. Unable to Delete!");
        }

        $AccountNumber->delete();
    
        return back()->with('message', "Account Number deleted Successfully!");
 
    }

    public function cashieraccountnamedelete(Request $request, AccountName $AccountName) {

        $this->authorize('delete',$AccountName);

        $AccountNumber = AccountNumber::where('acct_id','=',$AccountName->id)->count();

        if($AccountNumber >= 1)

        {
            return back()->with('failed', "Account Name has Account Number. Unable to Delete!");
        }
        else
        {
            $check = FinancialManagement::where('acct_id','=', $AccountName->id)->count();

            if($check > 0)
            {
                return back()->with('failed', "Account Name was used in Financial Management. Unable to Delete!");   
            }
         
            $AccountName->delete();
            
            return back()->with('message', "Account Name deleted Successfully!");
        }
 
    }

    public function cashieraccountname() 
    {

        $this->authorize('FMCashier', \App\Models\FinancialManagement::class);
     
        $Count = $this->getcountcashier();
        $AccountNames = AccountName::orderby('acct_name','ASC')->get();
        
        return view('financial-management.cashier.accountname.index',compact('Count','AccountNames'));
        
    } 


    public function cashierincoming() 
    {

        $this->authorize('FMCashier', \App\Models\FinancialManagement::class);
     
        $Count = $this->getcountcashier();
        $Employee = Employee:: where('email', auth()->user()->email )->get()->first();
        $Routes = FinancialManagementRoute::orderBy('created_at', 'DESC')->with('Voucher','User','AccountName')
                    ->get()->unique('sequenceid')->where('is_forwarded','=','1')->where('unitid','=',$Employee->unitid);

                    $FinalAda = [];
                    foreach($Routes as $Route)
                    {
            
                      $Voucher = FinancialManagement::where('sequenceid', $Route->sequenceid)->get()->first();
            
                      $AdaNo = FMCash::where('fmid', $Voucher->id)->get()->first();
            
                      if ($AdaNo)
                      {
                        $FinalAda[] = array($Route->id, $AdaNo->adano);
                      }
            
                    } 
            

        
        return view('financial-management.cashier.incoming.index',compact('Count','Routes','FinalAda'));
        
    } 

    public function cashierprocessing() 
    {
        $this->authorize('FMCashier', \App\Models\FinancialManagement::class);
        
        $Count = $this->getcountcashier();

        $Employee = Employee:: where('email', auth()->user()->email )->get()->first();
        $Routes = FinancialManagementRoute::orderBy('created_at', 'DESC')->with('Voucher','User','AccountName')
                    ->get()->unique('sequenceid')->where('is_accepted','=','1')->where('unitid','=',$Employee->unitid);

        $Offices = Office::orderby('id')->get();
        $Sections = Section::orderby('section')->get();
        $Units = Unit::orderby('unit')->get();

        $AdaProcessing = [];


        foreach($Routes as $Route)
        {
          if($Route->remarks == 'ACCEPTED - FOR SIGNATURE')
          {
            $Voucher = FinancialManagement::where('sequenceid', $Route->sequenceid)->get()->first();

            $AdaNo = FMCash::where('fmid', $Voucher->id)->get()->first();

            if ($AdaNo)
            {
              $AdaProcessing[] = array($Route->id, $AdaNo->adano);
            }
         
          }

        } 


        return view('financial-management.cashier.processing.index',compact('Count','Routes','Offices','Sections','Units','AdaProcessing'));
        
    } 

    public function cashieroutgoing() 
    {
        $this->authorize('FMCashier', \App\Models\FinancialManagement::class);

        $Count = $this->getcountcashier();

        $Employee = Employee:: where('email', auth()->user()->email )->get()->first();
        $Routes = FinancialManagementRoute::orderBy('created_at', 'DESC')->with('Voucher','User','AccountName')
                    ->get()->unique('sequenceid')->where('is_forwarded','=','1')->where('unitid','!=','15')->where('userunitid','=',$Employee->unitid);
        $Offices = Office::orderby('id')->get();
        $Sections = Section::orderby('section')->get();
        $Units = Unit::orderby('unit')->get();

        

   
        return view('financial-management.cashier.outgoing.index',compact('Count','Routes','Offices','Sections','Units'));
        
    } 


    public function cashierrejected() 
    {
        $this->authorize('FMCashier', \App\Models\FinancialManagement::class);
        $Count = $this->getcountcashier();

        $Employee = Employee:: where('email', auth()->user()->email )->get()->first();
        $Routes = FinancialManagementRoute::orderBy('created_at', 'DESC')->with('Voucher','User','AccountName')
                    ->get()->unique('sequenceid')->where('is_rejected','=','1')->where('unitid','=',$Employee->unitid);
        $Offices = Office::orderby('id')->get();
        $Sections = Section::orderby('section')->get();
        $Units = Unit::orderby('unit')->get();

    
   
        return view('financial-management.cashier.rejected.index',compact('Count','Routes','Offices','Sections','Units'));
        
    } 

    public function  cashierCashier(Request $request) 
    {
        
        $this->authorize('FMCashier', \App\Models\FinancialManagement::class);

        $Check = FMCash::where('fmid','=', $request->fmid)->count();

        if ($Check > 0)
        {
            return back()->with('failed', 'Voucher already have Check / ADA Number!.' );
        }

        // $Checkada = FMCash::where('adano','=', $request->adano)->count();

        // if ($Checkada > 0)
        // {
        //     return back()->with('failed', 'Duplicate ADA Number!.' );
        // }



        $formfields = $request->validate([
            'fmid' => 'required',
            'adano' => 'required',
            'mop' => 'required',
         
        ]);

        $formfields['userid'] = auth()->user()->id;
        


        $formfield = $request->validate([
            'actiondate' => 'required',
        ]);

        $formfield['action'] = 'CASHIER INFORMATION CREATED';
        $voucher = FinancialManagement::where('id', '=', $request->fmid)->get()->first();
        $formfield['sequenceid'] = $voucher->sequenceid;
        $formfield['userid'] = auth()->user()->id;
        $formfield['remarks'] = 'Check / Ada Number : ' . $request->adano;
        $formfield['is_active'] = true;
        
        $data = Employee::where('email','=',auth()->user()->email)->get()->first(); 
        $formfield['officeid'] = $data->officeid;
        $formfield['sectionid'] = $data->sectionid;
        $formfield['unitid'] =  $data->unitid;
        $formfield['userunitid'] = $data->unitid;
        $formfield['is_accepted'] = true;


        
        $Chargings = FMCharging::where('fmid','=', $request->fmid)->get();
        $Update['is_disbursed'] = true;
        foreach ($Chargings as $Charging)
        {
            $Charging->update($Update);
        }
        FMCash::updateorCreate($formfields);
        FinancialManagementRoute::updateorCreate($formfield);
        return back()->with('message', "Check / ADA Number Saved Successfully!");
    
        }       

        

    public function  cashierCashierdelete($Route) 
    {
        
        $this->authorize('FMCashier', \App\Models\FinancialManagement::class);
     
        $Cashier = FMCash::where('id','=',$Route)->get()->first();
    


   
        $Chargings = FMCharging::where('fmid','=', $Cashier->fmid)->get();
        $Update['is_disbursed'] = false;
        foreach ($Chargings as $Charging)
        {
            $Charging->update($Update);
        }
    
        $Cashier->delete();


        return back()->with('message', "Check / ADA Number deleted Successfully!");
    
    }

    
    public function accountingUACS() 
    {

        $this->authorize('FMAccounting', \App\Models\FinancialManagement::class);
     
        $Count = $this->getcountaccounting();
        
        $UACSs = AUACS::orderby('uacs','desc')->with('AActivity')->get();

        $Activities = AActivity::orderby('activity','desc')->get();
        
        return view('financial-management.Accounting.uacs.index',compact('Count','UACSs','Activities'));
        
    } 


    public function accountingActivity() 
    {

        $this->authorize('FMAccounting', \App\Models\FinancialManagement::class);
     
        $Count = $this->getcountaccounting();
        
        $Activities = AActivity::orderby('activity','desc')->get();
        
        return view('financial-management.Accounting.activity.index',compact('Count','Activities'));
        
    } 

    public function accountingUACScreate(Request $request) {

        $this->authorize('create', \App\Models\FMDV::class);

        $formfields = $request->validate([
            'a_activity_id' => 'required',
            'uacs' => 'required',
        ]);
     
        $check = AUACS::where([
            ['uacs', '=', $request->uacs],
  
          ])->first();
  
          if ($check) {
            return back()->with('failed', 'Duplicate UACS!');
          }

          AUACS::updateorCreate($formfields);
    
        return back()->with('message', "UACS created Successfully!");
 
    }

    public function accountingActivitycreate(Request $request) {

        $this->authorize('create', \App\Models\FMDV::class);

        $formfields = $request->validate([
            'activity' => 'required',
        ]);
     
        $check = AActivity::where([
            ['activity', '=', $request->activity],
  
          ])->first();
  
          if ($check) {
            return back()->with('failed', 'Duplicate Activity Title!');
          }

          AActivity::updateorCreate($formfields);
    
        return back()->with('message', "Activity Title created Successfully!");
 
    }


    public function accountingUACSdelete(Request $request, AUACS $UACS) {

   {   
        //

        $this->authorize('delete',$UACS);

            $UACS->delete();   
            
            return back()->with('message', "UACS deleted Successfully!");
        }
 
    }

    public function accountingActivitydelete(Request $request, AActivity $Activity) {

      
        //

        $this->authorize('delete',$Activity);

        $AUACS = AUACS::where('a_activity_id','=',$Activity->id)->count();


        if($AUACS >= 1)

        {
            return back()->with('failed', "Activity Title has UACS. Unable to Delete!");
        }
        else
        {
            $Activity->delete();
            
            return back()->with('message', "Account Name deleted Successfully!");
        }
 
    }


    public function accountingincoming() 
    {

        $this->authorize('FMAccounting', \App\Models\FinancialManagement::class);
     
        $Count = $this->getcountaccounting();
        $Employee = Employee:: where('email', auth()->user()->email )->get()->first();
        $Routes = FinancialManagementRoute::orderBy('created_at', 'DESC')->with('Voucher','User','AccountName')
                    ->get()->unique('sequenceid')->where('is_forwarded','=','1')->where('unitid','=',$Employee->unitid);

                    $FinalAda = [];
        foreach($Routes as $Route)
        {

          $Voucher = FinancialManagement::where('sequenceid', $Route->sequenceid)->get()->first();

          $AdaNo = FMCash::where('fmid', $Voucher->id)->get()->first();

          if ($AdaNo)
          {
            $FinalAda[] = array($Route->id, $AdaNo->adano);
          }

        } 

        
        return view('financial-management.Accounting.incoming.index',compact('Count','Routes','FinalAda'));
        
    } 

    public function accountingprocessing() 
    {
        $this->authorize('FMAccounting', \App\Models\FinancialManagement::class);
        
        $Count = $this->getcountaccounting();

        $Employee = Employee:: where('email', auth()->user()->email )->get()->first();
        $Routes = FinancialManagementRoute::orderBy('created_at', 'DESC')->with('Voucher','User','AccountName')
                    ->get()->unique('sequenceid')->where('is_accepted','=','1')->where('unitid','=',$Employee->unitid);

        $Offices = Office::orderby('id')->get();
        $Sections = Section::orderby('section')->get();
        $Units = Unit::orderby('unit')->get();

        $AccountingActivities = AActivity::orderby('activity','asc')->get();
        $BoxAs = BoxA::where('box','=','D')->orderby('certified_by','asc')->get();


        $AdaProcessing = [];


        foreach($Routes as $Route)
        {
          if($Route->remarks == 'ACCEPTED - FOR SIGNATURE')
          {
            $Voucher = FinancialManagement::where('sequenceid', $Route->sequenceid)->get()->first();

            $AdaNo = FMCash::where('fmid', $Voucher->id)->get()->first();

            if ($AdaNo)
            {
              $AdaProcessing[] = array($Route->id, $AdaNo->adano);
            }
         
          }

        } 

        return view('financial-management.accounting.processing.index',compact('Count','Routes','Offices','Sections','Units','AccountingActivities','BoxAs','AdaProcessing'));
        
    } 

    public function accountingoutgoing() 
    {
        $this->authorize('FMAccounting', \App\Models\FinancialManagement::class);
        $Count = $this->getcountaccounting();

        $Employee = Employee:: where('email', auth()->user()->email )->get()->first();
        $Routes = FinancialManagementRoute::orderBy('created_at', 'DESC')->with('Voucher','User','AccountName')
                    ->get()->unique('sequenceid')->where('is_forwarded','=','1')->where('userunitid','=',$Employee->unitid);
        $Offices = Office::orderby('id')->get();
        $Sections = Section::orderby('section')->get();
        $Units = Unit::orderby('unit')->get();

        

   
        return view('financial-management.accounting.outgoing.index',compact('Count','Routes','Offices','Sections','Units'));
        
    } 


    public function accountingrejected() 
    {
        $this->authorize('FMAccounting', \App\Models\FinancialManagement::class);
        $Count = $this->getcountaccounting();

        $Employee = Employee:: where('email', auth()->user()->email )->get()->first();
        $Routes = FinancialManagementRoute::orderBy('created_at', 'DESC')->with('Voucher','User','AccountName')
                    ->get()->unique('sequenceid')->where('is_rejected','=','1')->where('unitid','=',$Employee->unitid);
        $Offices = Office::orderby('id')->get();
        $Sections = Section::orderby('section')->get();
        $Units = Unit::orderby('unit')->get();

    
   
        return view('financial-management.accounting.rejected.index',compact('Count','Routes','Offices','Sections','Units'));
        
    } 



    //

    public function  accountingReview(Request $request) 
    {
        
        $this->authorize('FMAccounting', \App\Models\FinancialManagement::class);

        $formfields = $request->validate([
            'fmid' => 'required',
        ]);


        if($request->is_available == "on")

        {
            $formfield['is_available'] = true;
            $formfields['is_available'] = true;
        }
        else
        {
            $formfield['is_available'] = false;
            $formfields['is_available'] = false;
        }

        

        if($request->is_subject == "on")

        {
            $formfield['is_subject'] = true;
            $formfields['is_subject'] = true;
        }
        else
        {
            $formfield['is_subject'] = false;
            $formfields['is_subject'] = false;
        }


        if($request->is_supporting == "on")

        {
            $formfield['is_supporting'] = true;
            $formfields['is_supporting'] = true;
        }
        else
        {
            $formfield['is_supporting'] = false;
            $formfields['is_supporting'] = false;
        }

        $formfields['userid'] = auth()->user()->id;


        $review = FMReview::where('fmid','=',$request->fmid)->count();

        if($review > 0)
        {
            $ReviewUpdate =  FMReview::where('fmid','=',$request->fmid)->get()->first();
            $ReviewUpdate->update($formfield);
        }
        else
        {
            FMReview::updateorCreate($formfields);
        }
   

        return back()->with('message', "Review of Documents Saved Successfully!");
        
    
    }



    //

    public function  accountingDV(Request $request) 
    {
        
        $this->authorize('FMAccounting', \App\Models\FinancialManagement::class);

        $formfields = $request->validate([
            'fmid' => 'required',
            'dvno' => 'required',
            'jevno' => 'required',
        ]);

        $formfields['userid'] = auth()->user()->id;

        $Count = FMDV::where('fmid',$request->fmid)->count();

        if($Count > 0)
        {
            return back()->with('failed', "DV already have a Value! Please delete previous DV to add new DV!");
        }
        
        $check = FMDV::where('jevno','=',$request->jevno)->get()->first();

        if ($check)
        {
            return back()->with('failed', "Duplicate JEV Number!");
        }

        $checkdvno = FMDV::where('dvno','=',$request->dvno)->get()->first();

        if ($checkdvno)
        {
            return back()->with('failed', "Duplicate DV Number!");
        }
      
   

        
        FMDV::updateorCreate($formfields);

        $formfield = $request->validate([
            'actiondate' => 'required',
        ]);

        $formfield['action'] = 'DV CREATED';
        $voucher = FinancialManagement::where('id', '=', $request->fmid)->get()->first();
        $formfield['sequenceid'] = $voucher->sequenceid;
        $formfield['userid'] = auth()->user()->id;
        $formfield['remarks'] = 'DV Number : ' . $request->dvno;
        $formfield['is_active'] = true;
        
        $data = Employee::where('email','=',auth()->user()->email)->get()->first(); 
        $formfield['officeid'] = $data->officeid;
        $formfield['sectionid'] = $data->sectionid;
        $formfield['unitid'] =  $data->unitid;
        $formfield['userunitid'] = $data->unitid;
        $formfield['is_accepted'] = true;
    
        FinancialManagementRoute::updateorCreate($formfield);
    
      

        return back()->with('message', "DV Saved Successfully!");
        
    
    }



    public function  accountingDVdelete($Route) 
    {
        
        $this->authorize('FMAccounting', \App\Models\FinancialManagement::class);
     
        $DV = FMDV::where('id','=',$Route)->get()->first();
    
        $DV->delete();

        return back()->with('message', "DV Number deleted Successfully!");
    
    }

    public function  accountingAccountingEntrydelete($Route) 
    {
        
        $this->authorize('FMAccounting', \App\Models\FinancialManagement::class);
     
        $AccountingEntry = AccountEntry::where('id','=',$Route)->get()->first();
    
        $AccountingEntry->delete();

        return back()->with('message', "Accounting Entry deleted Successfully!");
    
    }




    public function budgetincoming() 
    {

        $this->authorize('FMBudget', \App\Models\FinancialManagement::class);
     
        $Count = $this->getcount();
        $Employee = Employee:: where('email', auth()->user()->email )->get()->first();
        $Routes = FinancialManagementRoute::orderBy('created_at', 'DESC')->with('Voucher','User','AccountName')
                    ->get()->unique('sequenceid')->where('is_forwarded','=','1')->where('unitid','=',$Employee->unitid);
        
        return view('financial-management.Budget.incoming.index',compact('Count','Routes'));
        
    } 

    public function budgetprocessing() 
    {
        $this->authorize('FMBudget', \App\Models\FinancialManagement::class);
        
        $Count = $this->getcount();

        $Employee = Employee:: where('email', auth()->user()->email )->get()->first();
        $Routes = FinancialManagementRoute::orderBy('created_at', 'DESC')->with('Voucher','User','AccountName')
                    ->get()->unique('sequenceid')->where('is_accepted','=','1')->where('unitid','=',$Employee->unitid);

        $Offices = Office::orderby('id')->get();
        $Sections = Section::orderby('section')->get();
        $Units = Unit::orderby('unit')->get();

        return view('financial-management.Budget.processing.index',compact('Count','Routes','Offices','Sections','Units'));
        
    } 

    public function budgetrejected() 
    {
        $this->authorize('FMBudget', \App\Models\FinancialManagement::class);
        $Count = $this->getcount();

        $Employee = Employee:: where('email', auth()->user()->email )->get()->first();
        $Routes = FinancialManagementRoute::orderBy('created_at', 'DESC')->with('Voucher','User','AccountName')
                    ->get()->unique('sequenceid')->where('is_rejected','=','1')->where('unitid','=',$Employee->unitid);
        $Offices = Office::orderby('id')->get();
        $Sections = Section::orderby('section')->get();
        $Units = Unit::orderby('unit')->get();

    
   
        return view('financial-management.budget.rejected.index',compact('Count','Routes','Offices','Sections','Units'));
        
    } 

    public function budgetoutgoing() 
    {
        $this->authorize('FMBudget', \App\Models\FinancialManagement::class);
        $Count = $this->getcount();

        $Employee = Employee:: where('email', auth()->user()->email )->get()->first();
        $Routes = FinancialManagementRoute::orderBy('created_at', 'DESC')->with('Voucher','User','AccountName')
                    ->get()->unique('sequenceid')->where('is_forwarded','=','1')->where('userunitid','=',$Employee->unitid);
        $Offices = Office::orderby('id')->get();
        $Sections = Section::orderby('section')->get();
        $Units = Unit::orderby('unit')->get();

        

   
        return view('financial-management.budget.outgoing.index',compact('Count','Routes','Offices','Sections','Units'));
        
    } 

    public function  budgetORS(Request $request) 
    {
        
   
        $this->authorize('FMBudget', \App\Models\FinancialManagement::class);

        $Check = FMORS::where('fmid','=', $request->fmid)->count();

        if ($Check > 0)
        {
            return back()->with('failed', 'Voucher already have ORS!.' );
        }

        $formfields = $request->validate([
            'fmid' => 'required',
            'orsno' => 'required',
            'particulars' => 'required',
            'obligation' => 'required',
            'fc' => 'required',
            'payable' => 'required',
        ]);


        $check = FMORS::where('orsno','=',$request->orsno)->get()->first();

        if ($check)
        {
            return back()->with('failed', "Duplicate ORS Number!");
        }

      
        if(!empty($request->payment))
            $formfields['payment'] = $request->payment;

        if(!empty($request->nyd))
            $formfields['nyd'] = $request->nyd;
            
        if(!empty($request->dd))
            $formfields['dd'] = $request->dd;

        $formfields['userid'] = auth()->user()->id;
        
        FMORS::updateorcreate($formfields);

        $formfield = $request->validate([
            'actiondate' => 'required',
        ]);

        $formfield['action'] = 'ORS CREATED';
        $voucher = FinancialManagement::where('id', '=', $request->fmid)->get()->first();
        $formfield['sequenceid'] = $voucher->sequenceid;
        $formfield['userid'] = auth()->user()->id;
        $formfield['remarks'] = 'ORS Number : ' . $request->orsno;
        $formfield['is_active'] = true;
        
        $data = Employee::where('email','=',auth()->user()->email)->get()->first(); 
        $formfield['officeid'] = $data->officeid;
        $formfield['sectionid'] = $data->sectionid;
        $formfield['unitid'] =  $data->unitid;
        $formfield['userunitid'] = $data->unitid;
        $formfield['is_accepted'] = true;
    
        FinancialManagementRoute::updateorCreate($formfield);

        $Chargings = FMCharging::where('fmid','=', $request->fmid)->get();
        $Update['is_obligated'] = true;
        foreach ($Chargings as $Charging)
        {
            $Charging->update($Update);
        }

        return back()->with('message', "ORS Saved and Obligate Successfully!");
    
    }

    public function  budgetORSdelete($Route) 
    {
        
        $this->authorize('FMBudget', \App\Models\FinancialManagement::class);
     
        $FMORS = FMORS::where('id','=',$Route)->get()->first();
      
        // $this->authorize('delete', $FMORS);

        $Chargings = FMCharging::where('fmid','=', $FMORS->fmid)->get();
        $Update['is_obligated'] = false;
        foreach ($Chargings as $Charging)
        {
            $Charging->update($Update);
        }
    
        $FMORS->delete();
 
        return back()->with('message', "ORS deleted Successfully!");
    
    }

  

    public function  planningChargingdelete($Route) 
    {
        
        $this->authorize('FMPlanning', \App\Models\FinancialManagement::class);
     
        $Charging = FMCharging::where('id','=',$Route)->get()->first();


        if ($Charging->category == 'GAA')
      
        {

        $Allocation = Allocation::where('activityid', '=', $Charging->activityid)->where('year','=',$Charging->year)->where('office','=',$Charging->activityoffice)->where('expense_class',$Charging->expense_class)->get()->first();
        $AllocationUACS = FMAllocationUACS::where('uacsid', '=', $Charging->uacsid)->where('year','=',$Charging->year)->where('office','=',$Charging->uacsoffice)->where('expense_class',$Charging->expense_class)->get()->first();     
        
        $num1 = $Allocation->rem_bal;
        $num2 = $Charging->amount;
        $num3 = $AllocationUACS->rem_bal;

       

        $formfield['action'] = 'CHARGING DELETED';
        $voucher = FinancialManagement::where('id', '=', $Charging->fmid)->get()->first();
        $formfield['sequenceid'] = $voucher->sequenceid;
        $formfield['userid'] = auth()->user()->id;
        $formfield['remarks'] = 'Charging Amount : ' . $Charging->amount;
        $formfield['is_active'] = true;
        
        $data = Employee::where('email','=',auth()->user()->email)->get()->first(); 
        $formfield['officeid'] = $data->officeid;
        $formfield['sectionid'] = $data->sectionid;
        $formfield['unitid'] =  $data->unitid;
        $formfield['userunitid'] = $data->unitid;
        $formfield['is_accepted'] = true;

        FinancialManagementRoute::create($formfield);

        ////KEN

        $rem_bal['rem_bal'] = $num1 + $num2;
        $rem_balUACS['rem_bal'] = $num3 + $num2;
        $Allocation->update($rem_bal);
        $AllocationUACS->update($rem_balUACS);
        $Charging->delete();

        return back()->with('message', "Charging deleted Successfully!");

      }

      if ($Charging->category == 'SAA')
      
      {

      $Allocation = FMAllocationActivitySAA::where('activityid', '=', $Charging->activityid)->where('year','=',$Charging->year)->where('office','=',$Charging->activityoffice)->where('expense_class',$Charging->expense_class)->get()->first();
      $AllocationUACS = FMAllocationUACSSAA::where('uacsid', '=', $Charging->uacsid)->where('year','=',$Charging->year)->where('office','=',$Charging->uacsoffice)->where('expense_class',$Charging->expense_class)->get()->first();     
      
      $num1 = $Allocation->rem_bal;
      $num2 = $Charging->amount;
      $num3 = $AllocationUACS->rem_bal;

     

      $formfield['action'] = 'CHARGING DELETED';
      $voucher = FinancialManagement::where('id', '=', $Charging->fmid)->get()->first();
      $formfield['sequenceid'] = $voucher->sequenceid;
      $formfield['userid'] = auth()->user()->id;
      $formfield['remarks'] = 'Charging Amount : ' . $Charging->amount;
      $formfield['is_active'] = true;
      
      $data = Employee::where('email','=',auth()->user()->email)->get()->first(); 
      $formfield['officeid'] = $data->officeid;
      $formfield['sectionid'] = $data->sectionid;
      $formfield['unitid'] =  $data->unitid;
      $formfield['userunitid'] = $data->unitid;
      $formfield['is_accepted'] = true;

      FinancialManagementRoute::create($formfield);

      ////KEN

      $rem_bal['rem_bal'] = $num1 + $num2;
      $rem_balUACS['rem_bal'] = $num3 + $num2;
      $Allocation->update($rem_bal);
      $AllocationUACS->update($rem_balUACS);
      $Charging->delete();

      return back()->with('message', "Charging deleted Successfully!");

    }
    
    }


    public function planningincoming() 
    {
        $this->authorize('FMPlanning', \App\Models\FinancialManagement::class);
        $Count = $this->getcount();

        $Employee = Employee:: where('email', auth()->user()->email )->get()->first();
        $Routes = FinancialManagementRoute::orderBy('created_at', 'DESC')->with('Voucher','User','AccountName')
                    ->get()->unique('sequenceid')->where('is_forwarded','=','1')->where('unitid','=',$Employee->unitid);

        return view('financial-management.planning.incoming.index',compact('Count','Routes'));
        
    } 

    public function planningprocessing() 
    {
        $this->authorize('FMPlanning', \App\Models\FinancialManagement::class);
        $Count = $this->getcount();

        $Employee = Employee:: where('email', auth()->user()->email )->get()->first();
        $Routes = FinancialManagementRoute::orderBy('created_at', 'DESC')->with('Voucher','User','AccountName')
                    ->get()->unique('sequenceid')->where('is_accepted','=','1')->where('unitid','=',$Employee->unitid);

        $PAPs = FMPlanningPAP::orderby('pap','asc')->get();
        $UACSs = FMPlanningUACS::orderby('uacs','asc')->get();
        $Activities = FMPlanningActivity::orderby('activity','asc')->get();
        $Offices = Office::orderby('id')->get();
        $Sections = Section::orderby('section')->get();
        $Units = Unit::orderby('unit')->get();

        return view('financial-management.planning.processing.index',compact('Count','Routes','PAPs','UACSs','Activities','Offices','Sections','Units'));
        
    } 

    public function planningoutgoing() 
    {
        $this->authorize('FMPlanning', \App\Models\FinancialManagement::class);
        $Count = $this->getcount();

        $Employee = Employee:: where('email', auth()->user()->email )->get()->first();
        $Routes = FinancialManagementRoute::orderBy('created_at', 'DESC')->with('Voucher','User','AccountName')
                    ->get()->unique('sequenceid')->where('is_forwarded','=','1')->where('userunitid','=',$Employee->unitid);
        $Offices = Office::orderby('id')->get();
        $Sections = Section::orderby('section')->get();
        $Units = Unit::orderby('unit')->get();

        

   
        return view('financial-management.planning.outgoing.index',compact('Count','Routes','Offices','Sections','Units'));
        
    } 

    public function planningrejected() 
    {
        $this->authorize('FMPlanning', \App\Models\FinancialManagement::class);
        $Count = $this->getcount();

        $Employee = Employee:: where('email', auth()->user()->email )->get()->first();
        $Routes = FinancialManagementRoute::orderBy('created_at', 'DESC')->with('Voucher','User','AccountName')
                    ->get()->unique('sequenceid')->where('is_rejected','=','1')->where('userunitid','=',$Employee->unitid);
        $Offices = Office::orderby('id')->get();
        $Sections = Section::orderby('section')->get();
        $Units = Unit::orderby('unit')->get();

        return view('financial-management.planning.rejected.index',compact('Count','Routes','Offices','Sections','Units'));
        
    } 


   
    public function planningAllocation() {

        $this->authorize('viewany', \App\Models\Allocation::class);     
        $Allocations = Allocation::orderby('id','desc')->with('Activity','PAP')->get();
        $Activities= FMPlanningActivity::orderby('id','desc')->get();
        $PAPs = FMPlanningPAP::orderby('id','desc')->get();
        $Count = $this->getcount();
        return view('financial-management.planning.allocation.index',compact('Count','Allocations','Activities','PAPs'));
    }

    public function planningAllocationSAA() {

      $this->authorize('viewany', \App\Models\Allocation::class);     
      $Allocations = FMAllocationActivitySAA::orderby('id','desc')->with('Activity','PAP')->get();
      $Activities= FMPlanningActivity::orderby('id','desc')->get();
      $PAPs = FMPlanningPAP::orderby('id','desc')->get();
      $Count = $this->getcount();
      return view('financial-management.planning.allocation.saa.index',compact('Count','Allocations','Activities','PAPs'));
  }


    public function planningAllocationcreate(Request $request) {

        $this->authorize('create', \App\Models\FMPlanningUACS::class);

        $formfields = $request->validate([
            'papid' => 'required',
            'activityid' => 'required',
            'year' => 'required',
            'office' => 'required',
            'amount' => 'required',
            'papoffice' => 'required',
            'expense_class' => 'required',
        ]);

        $check = Allocation::where([
            ['activityid', '=', $request->activityid],
            ['year','=', $request->year],
            ['office','=', $request->office],
            ['papid','=', $request->papid]
  
          ])->first();

          if ($request->office != $request->papoffice) {
            return back()->with('failed', 'Office not Match!' );
          }
  
          if ($check) {
            return back()->with('failed', 'Activity has allocation for the year' . ' ' . $request->year );
           }

            
            $formfields['rem_bal'] = $request->amount;

            $ActivityRemBal = FMAllocationPAP::where('papid','=',$request->papid)->where('year','=',$request->year)->where('office','=',$request->papoffice)->where('expense_class','=',$request->expense_class)->get()->first();

        
            
          
            if ($ActivityRemBal->rem_bal_activity >= $request->amount)
            {
       
                $NewRembal['rem_bal_activity'] = $ActivityRemBal->rem_bal_activity - $request->amount;
                $ActivityRemBal->update($NewRembal);
           
            Allocation::updateorCreate($formfields);
        
            return back()->with('message', "Allocation created Successfully!");
            }
            else
            {
                return back()->with('failed', 'Program Balance is larger than the Activity Amount. Unable to Save!');  
            }
   
     

 
    }


    //create SAA allocation
    public function planningAllocationcreateSAA(Request $request) {
   

      $this->authorize('create', \App\Models\FMAllocationPAPSAA::class);

      $formfields = $request->validate([
          'papid' => 'required',
          'activityid' => 'required',
          'year' => 'required',
          'office' => 'required',
          'amount' => 'required',
          'papoffice' => 'required',
          'expense_class' => 'required',
      ]);

      $check = FMAllocationActivitySAA::where([
          ['activityid', '=', $request->activityid],
          ['year','=', $request->year],
          ['office','=', $request->office],
          ['papid','=', $request->papid]

        ])->first();

        if ($request->office != $request->papoffice) {
          return back()->with('failed', 'Office not Match!' );
        }

        if ($check) {
          return back()->with('failed', 'Activity has allocation for the year' . ' ' . $request->year );
         }

          
          $formfields['rem_bal'] = $request->amount;

          $ActivityRemBal = FMAllocationPAPSAA::where('papid','=',$request->papid)->where('year','=',$request->year)->where('office','=',$request->papoffice)->where('expense_class','=',$request->expense_class)->get()->first();

      
          
        if($ActivityRemBal)
        {
          if ($ActivityRemBal->rem_bal_activity >= $request->amount)
          {
     
              $NewRembal['rem_bal_activity'] = $ActivityRemBal->rem_bal_activity - $request->amount;
              $ActivityRemBal->update($NewRembal);
         
          FMAllocationActivitySAA::updateorCreate($formfields);
      
          return back()->with('message', "Allocation created Successfully!");
          }
          else
          {
              return back()->with('failed', 'Program Balance is larger than the Activity Amount. Unable to Save!');  
          }

        }
        else
        {
          return back()->with('failed', 'Unable to save. Please contact System Administrator!');  
        }

 
   


  }


    public function planningAllocationdelete(Request $request, Allocation $Allocation) {

        $this->authorize('delete',$Allocation);

        $check = FMCharging::where([
            ['category', '=', $Allocation->category],
            ['papid', '=', $Allocation->papid],
            ['activityid', '=', $Allocation->activityid],
            ['activityoffice', '=', $Allocation->office],
            ['year','=', $Allocation->year]
  
          ])->first();
 
          if ($check) {

            return back()->with('failed', 'Allocation has a Voucher Record! Unable to delete!' );

           }  

           $ActivityRemBal = FMAllocationPAP::where('papid','=',$Allocation->papid)->where('year','=',$Allocation->year)->where('office','=',$Allocation->papoffice)->where('expense_class','=',$Allocation->expense_class)->get()->first();
           $NewRembal['rem_bal_activity'] = $ActivityRemBal->rem_bal_activity + $Allocation->amount;
            $ActivityRemBal->update($NewRembal);

         $Allocation->delete();
    
        return back()->with('message', "Allocation deleted Successfully!");
 
    }

    public function planningAllocationdeleteSAA(Request $request, FMAllocationActivitySAA $Allocation) {


      $this->authorize('create', \App\Models\FMAllocationPAPSAA::class);

      $check = FMCharging::where([
          ['category', '=', $Allocation->category],
          ['papid', '=', $Allocation->papid],
          ['activityid', '=', $Allocation->activityid],
          ['activityoffice', '=', $Allocation->office],
          ['year','=', $Allocation->year]

        ])->first();

        if ($check) {

          return back()->with('failed', 'Allocation has a Voucher Record! Unable to delete!' );

         }  

         $ActivityRemBal = FMAllocationPAPSAA::where('papid','=',$Allocation->papid)->where('year','=',$Allocation->year)->where('office','=',$Allocation->papoffice)->where('expense_class','=',$Allocation->expense_class)->get()->first();

         if($ActivityRemBal)
         {
          $NewRembal['rem_bal_activity'] = $ActivityRemBal->rem_bal_activity + $Allocation->amount;
          $ActivityRemBal->update($NewRembal);

          $Allocation->delete();
          return back()->with('message', "Allocation deleted Successfully!");

         }
         else{
          return back()->with('failed', 'Unable to save. Please contact System Administrator!');  
         }
     
  


  }


    public function planningAllocationPAP() {

        $this->authorize('viewany', \App\Models\Allocation::class);   
        $Allocations = FMAllocationPAP::orderby('id','desc')->with('PAP')->get();  
        $PAPs = FMPlanningPAP::orderby('id','desc')->get();
        $Count = $this->getcount();
        return view('financial-management.planning.allocationpap.index',compact('Count','PAPs','Allocations'));
    }

    public function planningAllocationPAPSAA() {

      $this->authorize('viewany', \App\Models\Allocation::class);   
      $Allocations = FMAllocationPAPSAA::orderby('id','desc')->with('PAP')->get();  
      $PAPs = FMPlanningPAP::orderby('id','desc')->get();
      $Count = $this->getcount();
      return view('financial-management.planning.allocationpap.saa.index',compact('Count','PAPs','Allocations'));
  }

    public function planningAllocationcreatePAP(Request $request) {

        $this->authorize('create', \App\Models\FMAllocationPAP::class);

        $formfields = $request->validate([
            'papid' => 'required',
            'year' => 'required',
            'amount' => 'required',
            'office' => 'required',
            'expense_class'=> 'required',
        ]);

        $check = FMAllocationPAP::where([
            ['papid', '=', $request->papid],
            ['year','=', $request->year],
            ['office','=', $request->office],

  
          ])->first();
  
          if ($check) {
            return back()->with('failed', 'PAP has allocation!');
           }
     
        $formfields['rem_bal_uacs'] = $request->amount;
        $formfields['rem_bal_activity'] = $request->amount;
        FMAllocationPAP::updateorCreate($formfields);
    
        return back()->with('message', "Allocation created Successfully!");
 
    }

    public function planningAllocationcreatePAPSAA(Request $request) {

      $this->authorize('create', \App\Models\FMAllocationPAP::class);

      $formfields = $request->validate([
          'papid' => 'required',
          'year' => 'required',
          'amount' => 'required',
          'office' => 'required',
          'expense_class'=> 'required',
      ]);

      $check = FMAllocationPAP::where([
          ['papid', '=', $request->papid],
          ['year','=', $request->year],
          ['office','=', $request->office],


        ])->first();

        if ($check) {
          return back()->with('failed', 'PAP has allocation!');
         }
   
      $formfields['rem_bal_uacs'] = $request->amount;
      $formfields['rem_bal_activity'] = $request->amount;
      $Success = FMAllocationPAPSAA::updateorCreate($formfields);
  
      if ($Success)
      {
        return back()->with('message', "Allocation created Successfully!");
      }
      else
      {
        return back()->with('failed', 'Unable to Save.Please Contact System Administrator!');  
      }  

  }

    public function planningAllocationdeletePAP(Request $request, FMAllocationPAP $Allocation) {

        $this->authorize('delete',$Allocation);

        $check = FMAllocationUACS::where([
            ['papid', '=', $Allocation->papid],
            ['year','=', $Allocation->year]
  
          ])->first();
  
          if ($check) {
            return back()->with('failed', 'Allocation has a UACS Amount! Unable to delete!' );
           }

           $check = Allocation::where([
            ['papid', '=', $Allocation->papid],
            ['year','=', $Allocation->year]
  
          ])->first();
  
          if ($check) {
            return back()->with('failed', 'Allocation has a Activity Amount! Unable to delete!' );
           }

        $Allocation->delete();
    
        return back()->with('message', "Allocation deleted Successfully!");
 
    }

    public function planningAllocationdeletePAPSAA(Request $request, FMAllocationPAPSAA $Allocation) {

  
      $this->authorize('delete',$Allocation);

      $check = FMAllocationUACSSAA::where([
          ['papid', '=', $Allocation->papid],
          ['year','=', $Allocation->year]

        ])->first();

        if ($check) {
          return back()->with('failed', 'Allocation has a UACS Amount! Unable to delete!' );
         }

         $check = FMAllocationActivitySAA::where([
          ['papid', '=', $Allocation->papid],
          ['year','=', $Allocation->year]

        ])->first();

        if ($check) {
          return back()->with('failed', 'Allocation has a Activity Amount! Unable to delete!' );
         }

      $Allocation->delete();
  
      return back()->with('message', "Allocation deleted Successfully!");

  }


    public function planningAllocationUACS() {

        $this->authorize('viewany', \App\Models\Allocation::class);     
        $Allocations = FMAllocationUACS::orderby('id','desc')->with('PAP')->get();
        $PAPs = FMPlanningPAP::orderby('id','desc')->get();
        $UACSs = FMPlanningUACS::orderby('id','desc')->get();
        $Count = $this->getcount();
        return view('financial-management.planning.allocationuacs.index',compact('Count','Allocations','PAPs','UACSs'));
    }


    public function planningAllocationUACSSAA() {

      $this->authorize('viewany', \App\Models\Allocation::class);     
      $Allocations = FMAllocationUACSSAA::orderby('id','desc')->with('PAP')->get();
      $PAPs = FMPlanningPAP::orderby('id','desc')->get();
      $UACSs = FMPlanningUACS::orderby('id','desc')->get();
      $Count = $this->getcount();
      return view('financial-management.planning.allocationuacs.saa.index',compact('Count','Allocations','PAPs','UACSs'));
  }

    public function planningAllocationcreateUACS(Request $request) {


      
        $this->authorize('create', \App\Models\FMPlanningUACS::class);

        $formfields = $request->validate([
            'papid' => 'required',
            'uacsid' => 'required',
            'year' => 'required',
            'amount' => 'required',
            'office' => 'required',
            'expense_class' => 'required',
        ]);
        $formfields['papoffice'] = $request->papoffice1;
        $check = FMAllocationUACS::where([
            ['papid', '=', $request->papid],
            ['uacsid', '=', $request->uacsid],
            ['year','=', $request->year],
            ['office','=', $request->office],
          ])->first();
  
          if ($request->office != $request->papoffice1) {
            return back()->with('failed', 'Office not Match!' );
          }
          
          if ($check) {
            // return back()->with('error', 'UACS has allocation for the year' . ' ' . $request->year );

            $formfields['rem_bal'] = $request->amount + $check->rem_bal;
            $formfields['amount'] = $request->amount + $check->amount;

            $ActivityRemBal = FMAllocationPAP::where('papid','=',$request->papid)->where('year','=',$request->year)->where('office','=',$request->papoffice)->where('expense_class','=',$request->expense_class)->get()->first();
    

            if ($ActivityRemBal->rem_bal_uacs >= $request->amount)
            {
       
                $NewRembal['rem_bal_uacs'] = $ActivityRemBal->rem_bal_uacs- $request->amount;
                $ActivityRemBal->update($NewRembal);
           
                $check->update($formfields);
        
            return back()->with('message', "Allocation created Successfully!");
            }
            else
            {
                return back()->with('failed', 'Program Balance is larger than the UACS Amount. Unable to Save!');  
            }
        }
     
        $formfields['rem_bal'] = $request->amount;

        $ActivityRemBal = FMAllocationPAP::where('papid','=',$request->papid)->where('year','=',$request->year)->where('office','=',$request->papoffice1)->where('expense_class','=',$request->expense_class)->get()->first();

        
            
          
        if ($ActivityRemBal->rem_bal_uacs >= $request->amount)
        {
   
            $NewRembal['rem_bal_uacs'] = $ActivityRemBal->rem_bal_uacs- $request->amount;
            $ActivityRemBal->update($NewRembal);
       
            FMAllocationUACS::updateorCreate($formfields);
    
        return back()->with('message', "Allocation created Successfully!");
        }
        else
        {
            return back()->with('failed', 'Program Balance is larger than the UACS Amount. Unable to Save!');  
        }

    }


    public function planningAllocationcreateUACSSAA(Request $request) {


      
      $this->authorize('create', \App\Models\FMPlanningUACS::class);

      $formfields = $request->validate([
          'papid' => 'required',
          'uacsid' => 'required',
          'year' => 'required',
          'amount' => 'required',
          'office' => 'required',
          'expense_class' => 'required',
      ]);
      $formfields['papoffice'] = $request->papoffice1;
      $check = FMAllocationUACSSAA::where([
          ['papid', '=', $request->papid],
          ['uacsid', '=', $request->uacsid],
          ['year','=', $request->year],
          ['office','=', $request->office],
        ])->first();

        if ($request->office != $request->papoffice1) {
          return back()->with('failed', 'Office not Match!' );
        }
        
        if ($check) {
          // return back()->with('error', 'UACS has allocation for the year' . ' ' . $request->year );

          $formfields['rem_bal'] = $request->amount + $check->rem_bal;
          $formfields['amount'] = $request->amount + $check->amount;
          
          $ActivityRemBal = FMAllocationPAPSAA::where('papid','=',$request->papid)->where('year','=',$request->year)->where('office','=',$request->papoffice1)->where('expense_class','=',$request->expense_class)->get()->first();
  
          
          if ($ActivityRemBal->rem_bal_uacs >= $request->amount)
            {
      
                $NewRembal['rem_bal_uacs'] = $ActivityRemBal->rem_bal_uacs- $request->amount;
                $ActivityRemBal->update($NewRembal);
          
                $success = $check->update($formfields);

                if ($success)
                {
                  return back()->with('message', "Allocation created Successfully!");
                }
                else{
                  return back()->with('failed', 'Unable to save. Please contact System Administrator!');  
                }
       
            }
            else
            {
                return back()->with('failed', 'Program Balance is larger than the UACS Amount. Unable to Save!');  
            }
        }
   
      $formfields['rem_bal'] = $request->amount;

      $ActivityRemBal = FMAllocationPAPSAA::where('papid','=',$request->papid)->where('year','=',$request->year)->where('office','=',$request->papoffice1)->where('expense_class','=',$request->expense_class)->get()->first();

    
        
      if ($ActivityRemBal->rem_bal_uacs >= $request->amount)
      {
 
          $NewRembal['rem_bal_uacs'] = $ActivityRemBal->rem_bal_uacs- $request->amount;
          $ActivityRemBal->update($NewRembal);
     
          FMAllocationUACSSAA::updateorCreate($formfields);
  
      return back()->with('message', "Allocation created Successfully!");
      }
      else
      {
          return back()->with('failed', 'Program Balance is larger than the UACS Amount. Unable to Save!');  
      }

  }

    public function planningrealignmentUACS(Request $request) {


      
        $this->authorize('create', \App\Models\FMPlanningUACS::class);

        $formfields = $request->validate([
            'papidrealign1' => 'required',
            'yearrealign1' => 'required',
            'uacsidrealign1' => 'required',
            'rem_balrealign1' => 'required',
            'office1' => 'required',
            // 'papidrealign2' => 'required',
            'expense_class_realign' => 'required',
            'uacsidrealign2' => 'required',
            'office2' => 'required',
            'amount' => 'required',
            'papoffice' => 'required',
            
        ]);

        $newbal = FMAllocationUACS::where([
            ['papid', '=', $request->papidrealign1],
            ['uacsid', '=', $request->uacsidrealign1],
            ['year','=', $request->yearrealign1],
            ['office','=', $request->office1],
            ['expense_class','=', $request->expense_class_realign],
          ])->get()->first();
        if ($newbal->rem_bal < (float)$request->amount)
        {

            return back()->with('failed', 'Amount is larger than the remaining balance. Please check realignment!' );
        }

   
            $check = FMAllocationUACS::where([
                ['papid', '=', $request->papidrealign1],
                ['papoffice', '=', $request->papoffice],
                ['uacsid', '=', $request->uacsidrealign2],
                ['year','=', $request->yearrealign1],
                ['office','=', $request->office2],
                ['expense_class','=', $request->expense_class_realign],
              ])->get()->first();
      
    
              if ($check)
                {       
                $realignment['rem_bal'] = (float)$request->amount + $check->rem_bal;
                $realignment['amount'] = (float)$request->amount + $check->amount;
       
        
                $realignmentfrom['rem_bal'] =(float)$newbal->rem_bal - (float)$request->amount;
                $realignmentfrom['amount'] = (float)$newbal->amount - (float)$request->amount;

            
                $newbal->update($realignmentfrom);
                $check->update($realignment);
                $realign['category'] = "SAA";
                $realign['papid'] = $request->papidrealign1;
                $realign['expense_class'] = $request->expense_class_realign;
                $realign['year'] = $request->yearrealign1;
                $realign['office'] = $request->papoffice;
                $realign['from_uacs'] = $request->uacsidrealign1;
                $realign['from_office'] = $request->office1;
                $realign['from_balance'] = $request->rem_balrealign1;
                
                $realign['to_office'] = $request->office2;
                $realign['to_uacs'] = $request->uacsidrealign2;
                // $realign['to_balance'] = $request->papidrealign1;  

                $realign['realign_amount'] = $request->amount; 
                $realign['userid'] = auth()->user()->id;


                $success1 = RealignHistory::create($realign);

                if ($success1)
                {
                  return back()->with('message', "Allocation realigned Successfully!");
                }
                else
                {
                  return back()->with('failed', 'Unable to save. Please contact System Administrator!' );
                }

                }
            else
            {

                $data['papid'] = $request->papidrealign1;
                $data['papoffice'] = $request->papoffice;
                $data['uacsid'] = $request->uacsidrealign2;
                $data['year'] = $request->yearrealign1;
                $data['office'] = $request->office2;
                $data['amount'] = $request->amount;
                $data['rem_bal'] = $request->amount;
                $data['expense_class'] = $request->expense_class_realign;
                $realignmentfrom['rem_bal'] =(float)$newbal->rem_bal - (float)$request->amount;
                $realignmentfrom['amount'] = (float)$newbal->amount - (float)$request->amount;

                $newbal->update($realignmentfrom);
                $success = FMAllocationUACS::updateorcreate($data);
                if ($success)
                {
                  $realign['category'] = "GAA";
                  $realign['papid'] = $request->papidrealign1;
                  $realign['expense_class'] = $request->expense_class_realign;
                  $realign['year'] = $request->yearrealign1;
                  $realign['office'] = $request->papoffice;
                  $realign['from_uacs'] = $request->uacsidrealign1;
                  $realign['from_office'] = $request->office1;
                  $realign['from_balance'] = $request->rem_balrealign1;
                  
                  $realign['to_office'] = $request->office2;
                  $realign['to_uacs'] = $request->uacsidrealign2;
                  // $realign['to_balance'] = $request->papidrealign1;  

                  $realign['realign_amount'] = $request->amount; 
                  $realign['userid'] = auth()->user()->id;

                  $success1 = RealignHistory::create($realign);                       

                  if ($success1)
                  {
                    return back()->with('message', "Allocation realigned Successfully!");
                  }
                  else
                  {
                    return back()->with('failed', 'Unable to save. Please contact System Administrator!' );
                  }
             
                }
                else
                {
                  return back()->with('failed', 'Unable to save. Please contact System Administrator!' );
                }
      
            }
 
        
        }
        


        public function planningrealignmentUACSSAA(Request $request) {


      
          $this->authorize('create', \App\Models\FMPlanningUACS::class);
  
          $formfields = $request->validate([
              'papidrealign1' => 'required',
              'yearrealign1' => 'required',
              'uacsidrealign1' => 'required',
              'rem_balrealign1' => 'required',
              'office1' => 'required',
              // 'papidrealign2' => 'required',
              'expense_class_realign' => 'required',
              'uacsidrealign2' => 'required',
              'office2' => 'required',
              'amount' => 'required',
              'papoffice' => 'required',
              
          ]);
  
          $newbal = FMAllocationUACSSAA::where([
              ['papid', '=', $request->papidrealign1],
              ['uacsid', '=', $request->uacsidrealign1],
              ['year','=', $request->yearrealign1],
              ['office','=', $request->office1],
              ['expense_class','=', $request->expense_class_realign],
            ])->get()->first();
          
          if ($newbal->rem_bal < (float)$request->amount)
          {
  
              return back()->with('failed', 'Amount is larger than the remaining balance. Please check realignment!' );
          }
  
     
              $check = FMAllocationUACSSAA::where([
                  ['papid', '=', $request->papidrealign1],
                  ['papoffice', '=', $request->papoffice],
                  ['uacsid', '=', $request->uacsidrealign2],
                  ['year','=', $request->yearrealign1],
                  ['office','=', $request->office2],
                  ['expense_class','=', $request->expense_class_realign],
                ])->get()->first();
        
      
                if ($check)
                  {       
                  $realignment['rem_bal'] = (float)$request->amount + $check->rem_bal;
                  $realignment['amount'] = (float)$request->amount + $check->amount;
         
          
                  $realignmentfrom['rem_bal'] =(float)$newbal->rem_bal - (float)$request->amount;
                  $realignmentfrom['amount'] = (float)$newbal->amount - (float)$request->amount;
  
              
                    $newbal->update($realignmentfrom);
                    $check->update($realignment);

                    $realign['category'] = "SAA";
                    $realign['papid'] = $request->papidrealign1;
                    $realign['expense_class'] = $request->expense_class_realign;
                    $realign['year'] = $request->yearrealign1;
                    $realign['office'] = $request->papoffice;
                    $realign['from_uacs'] = $request->uacsidrealign1;
                    $realign['from_office'] = $request->office1;
                    $realign['from_balance'] = $request->rem_balrealign1;
                    
                    $realign['to_office'] = $request->office2;
                    $realign['to_uacs'] = $request->uacsidrealign2;
                    // $realign['to_balance'] = $request->papidrealign1;  

                    $realign['realign_amount'] = $request->amount; 
                    $realign['userid'] = auth()->user()->id;

                
                    $success1 = RealignHistory::create($realign);

                    if ($success1)
                    {
                      return back()->with('message', "Allocation realigned Successfully!");
                    }
                    else
                    {
                      return back()->with('failed', 'Unable to save. Please contact System Administrator!' );
                    }

              
  
                  }
              else
              {
  
                  $data['papid'] = $request->papidrealign1;
                  $data['papoffice'] = $request->papoffice;
                  $data['uacsid'] = $request->uacsidrealign2;
                  $data['year'] = $request->yearrealign1;
                  $data['office'] = $request->office2;
                  $data['amount'] = $request->amount;
                  $data['rem_bal'] = $request->amount;
                  $data['expense_class'] = $request->expense_class_realign;
                  $realignmentfrom['rem_bal'] =(float)$newbal->rem_bal - (float)$request->amount;
                  $realignmentfrom['amount'] = (float)$newbal->amount - (float)$request->amount;
  
                  $newbal->update($realignmentfrom);
                 $success =  FMAllocationUACSSAA::updateorcreate($data);
                 if ($success)
                 {
                  $realign['category'] = "SAA";
                  $realign['papid'] = $request->papidrealign1;
                  $realign['expense_class'] = $request->expense_class_realign;
                  $realign['year'] = $request->yearrealign1;
                  $realign['office'] = $request->papoffice;
                  $realign['from_uacs'] = $request->uacsidrealign1;
                  $realign['from_office'] = $request->office1;
                  $realign['from_balance'] = $request->rem_balrealign1;
                  
                  $realign['to_office'] = $request->office2;
                  $realign['to_uacs'] = $request->uacsidrealign2;
                  // $realign['to_balance'] = $request->papidrealign1;  

                  $realign['realign_amount'] = $request->amount; 
                  $realign['userid'] = auth()->user()->id;

                  dd($realign);
                  $success1 = RealignHistory::create($realign);

                  if ($success1)
                  {
                    return back()->with('message', "Allocation realigned Successfully!");
                  }
                  else
                  {
                    return back()->with('failed', 'Unable to save. Please contact System Administrator!' );
                  }
                 }
                 else{
                  return back()->with('failed', 'Unable to save. Please contact System Administrator!' );
                 }
              
              }
   
          
          }
          


    public function planningAllocationdeleteUACS(Request $request, FMAllocationUACS $Allocation) {

        $this->authorize('delete',$Allocation);

        $check = FMCharging::where([
            ['category', '=', $Allocation->category],
            ['papid', '=', $Allocation->papid],
            ['uacsid', '=', $Allocation->uacsid],
            ['year','=', $Allocation->year],
  
          ])->first();
  
          if ($check) {
            return back()->with('failed', 'Allocation has a Voucher Record! Unable to delete!' );
           }


        $ActivityRemBal = FMAllocationPAP::where('papid','=',$Allocation->papid)->where('year','=',$Allocation->year)->where('expense_class','=',$Allocation->expense_class)->get()->first();
        $NewRembal['rem_bal_uacs'] = $ActivityRemBal->rem_bal_uacs + $Allocation->amount;
         $ActivityRemBal->update($NewRembal);

         $success = $Allocation->delete();

         if ($success)
         {
           return back()->with('message', "Allocation deleted Successfully!");
         }
         else
         {
           return back()->with('failed', 'Unable to save. Please contact System Administrator!' );
         }
    }


    public function planningAllocationdeleteUACSSAA(Request $request, FMAllocationUACSSAA $Allocation) {

      $this->authorize('create', \App\Models\FMPlanningUACS::class);

      $check = FMCharging::where([
          ['category', '=', $Allocation->category],
          ['papid', '=', $Allocation->papid],
          ['uacsid', '=', $Allocation->uacsid],
          ['year','=', $Allocation->year],

        ])->first();

        if ($check) {
          return back()->with('failed', 'Allocation has a Voucher Record! Unable to delete!' );
         }


      $ActivityRemBal = FMAllocationPAPSAA::where('papid','=',$Allocation->papid)->where('year','=',$Allocation->year)->where('expense_class','=',$Allocation->expense_class)->get()->first();
      $NewRembal['rem_bal_uacs'] = $ActivityRemBal->rem_bal_uacs + $Allocation->amount;
       $ActivityRemBal->update($NewRembal);

      $success = $Allocation->delete();

      if ($success)
      {
        return back()->with('message', "Allocation deleted Successfully!");
      }
      else
      {
        return back()->with('failed', 'Unable to save. Please contact System Administrator!' );
      }

  }




    public function planningPAP() {

        $this->authorize('viewany', \App\Models\FMPlanningPAP::class);     
        $PAPs = FMPlanningPAP::orderby('id','desc')->get();
        $Count = $this->getcount();
        return view('financial-management.planning.pap.index',compact('Count','PAPs'));
    }

    public function planningUACS() {

        $this->authorize('viewany', \App\Models\FMPlanningPAP::class);     
        $UACSs = FMPlanningUACS::orderby('id','desc')->get();
        $Count = $this->getcount();
        return view('financial-management.planning.uacs.index',compact('Count','UACSs'));
    }

    public function planningActivity() {

        $this->authorize('viewany', \App\Models\FMPlanningActivity::class);     
        $Activities = FMPlanningActivity::with('PAP')->orderby('id','desc')->get();
        $PAPs = FMPlanningPAP::orderby('pap','asc')->get();
        $Count = $this->getcount();
        return view('financial-management.planning.activity.index',compact('Count','Activities','PAPs'));
    }



    public function planningPAPcreate(Request $request) {

        $this->authorize('create', \App\Models\FMPlanningPAP::class);

        $formfields = $request->validate([
            'pap' => 'required',
        ]);
     
        $check = FMPlanningPAP::where([
            ['pap', '=', $request->pap],
  
          ])->first();
  
          if ($check) {
            return back()->with('failed', 'Duplicate PAP!');
          }

        FMPlanningPAP::updateorCreate($formfields);
    
        return back()->with('message', "PAP created Successfully!");
 
    }

    public function planningActivitycreate(Request $request) {

        $this->authorize('create', \App\Models\FMPlanningActivity::class);

        $formfields = $request->validate([
            'papid' => 'required',
            'activity' => 'required',
        ]);
     
        $check = FMPlanningActivity::where([
            ['activity', '=', $request->activity],
            ['papid', '=', $request->papid],
          ])->first();
  
          if ($check) {
            return back()->with('failed', 'Duplicate Activity!');
          }

        FMPlanningActivity::updateorCreate($formfields);
    
        return back()->with('message', "Activity created Successfully!");
 
    }

    public function planningUACScreate(Request $request) {

        $this->authorize('create', \App\Models\FMPlanningUACS::class);

        $formfields = $request->validate([
            'uacs' => 'required',
            'description' => 'required',
        ]);

        $check = FMPlanningUACS::where([
            ['uacs', '=', $request->uacs],
  
          ])->first();
  
          if ($check) {
            return back()->with('failed', 'Duplicate UACS!');
          }
     
        FMPlanningUACS::updateorCreate($formfields);
    
        return back()->with('message', "UACS created Successfully!");
 
    }

    public function planningPAPdelete(Request $request, FMPlanningPAP $PAP) {

        $this->authorize('delete',$PAP);

        $PAP->delete();
    
        return back()->with('message', "PAP deleted Successfully!");
 
    }

    public function planningActivitydelete(Request $request, FMPlanningActivity $Activity) {

        $this->authorize('delete',$Activity);

        $Activity->delete();
    
        return back()->with('message', "Activity deleted Successfully!");
 
    }

    public function planningUACSdelete(Request $request, FMPlanningUACS $UACS) {

        $this->authorize('delete',$UACS);

        $UACS->delete();
    
        return back()->with('message', "UACS deleted Successfully!");
 
    }

    public function othersincoming() 
    {

        $this->authorize('FMOthers', \App\Models\FinancialManagement::class);
     
        $Count = $this->getcount();
        $Employee = Employee:: where('email', auth()->user()->email )->get()->first();
        $Routes = FinancialManagementRoute::orderBy('created_at', 'DESC')->with('Voucher','User','AccountName')
                    ->get()->unique('sequenceid')->where('is_forwarded','=','1')->where('unitid','=',$Employee->unitid);
        
        return view('financial-management.others.incoming.index',compact('Count','Routes'));
        
    } 

    public function othersprocessing() 
    {
        $this->authorize('FMOthers', \App\Models\FinancialManagement::class);
        
        $Count = $this->getcount();

        $Employee = Employee:: where('email', auth()->user()->email )->get()->first();
        $Routes = FinancialManagementRoute::orderBy('created_at', 'DESC')->with('Voucher','User','AccountName')
                    ->get()->unique('sequenceid')->where('is_accepted','=','1')->where('unitid','=',$Employee->unitid);
  


        return view('financial-management.others.processing.index',compact('Count','Routes'));
        
    } 

    public function othersclose(Request $request) 
    {

        $Voucher = FinancialManagement::where('sequenceid', '=', $request->sequenceid)->get()->first();
        
        $this->authorize('FMOthers', \App\Models\FinancialManagement::class);


        $formfields['sequenceid'] = $request->sequenceid;
        $formfields['userid'] = auth()->user()->id;
        $formfields['is_active'] = FALSE;
        $formfields['action'] = 'CLOSED';
        $formfields['actiondate'] = now();
   

        $Office = Employee::where('email','=',auth()->user()->email)->get()->first();
   
        $formfields['officeid'] = $Office->officeid;
        $formfields['sectionid'] = $Office->sectionid;
        $formfields['unitid'] = $Office->unitid;
        $employee = Employee::where('email','=',auth()->user()->email)->get()->first();
        $formfields['userunitid'] = $employee->unitid;

        FinancialManagementRoute::updateorCreate($formfields);

        return back()->with('message', "Route Closed Successfully!");
    } 

}
