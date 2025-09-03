<?php

namespace App\Http\Controllers\FinancialManagement;

use App\Models\Unit;
use App\Models\Route;
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
use App\Models\BoxA;
use App\Models\BoxD;
use App\Models\FinancialManagementRoute;
use App\Models\FMCash;
use App\Models\FMDV;
use App\Models\FMORS;
use App\Models\FMReview;
use App\Models\RealignHistory;
use PhpParser\Node\Stmt\Foreach_;

class FinancialManagementController extends Controller
{

    public function index() {

        $this->authorize('viewany', \App\Models\FinancialManagement::class);

        $Vouchers = FinancialManagement::orderby('id','desc')->with('AccountName')->get();

     
        return view('financial-management.index', compact('Vouchers'));
    }

    //


   

    public function allocationPayeereport() {

   
        $this->authorize('viewany', \App\Models\FinancialManagement::class);

        $Activities = FMPlanningActivity::orderby('id','desc')->with('Allocation','Voucher','PAP')->get();



        $ActivityReports = array();
        
        $ChargingPerActivity = array();

        foreach($Activities as $Activity)
        {
            foreach ($Activity->allocation as $Allocation)
            {
                $PAP = FMPlanningPAP::where('id','=',$Activity->papid)->get()->first();
                 $Chargings = FMCharging::where('activityid', '=', $Allocation->activityid)->where('year','=',$Allocation->year)->where('activityoffice','=',$Allocation->office)->where('expense_class',$Allocation->expense_class)->with('FinancialManagement')->get();
                foreach ($Chargings as $Charging) 
                {

                    $Vouchers = FinancialManagement::orderby('id','desc')->where('id','=',$Charging->fmid)->with('AccountName')->get();
                    
                    foreach ($Vouchers as $Voucher)
                    {
                        $ObligationAmount = FMCharging::where('activityid', '=', $Allocation->activityid)->where('fmid','=',$Voucher->id)->where('year','=',$Allocation->year)->where('activityoffice','=',$Allocation->office)->where('expense_class',$Allocation->expense_class)->where('is_obligated','=', true)->with('FinancialManagement')->sum('amount');
                        $DisbursementAmount = FMCharging::where('activityid', '=', $Allocation->activityid)->where('fmid','=',$Voucher->id)->where('year','=',$Allocation->year)->where('activityoffice','=',$Allocation->office)->where('expense_class',$Allocation->expense_class)->where('is_disbursed','=', true)->with('FinancialManagement')->sum('amount');
                      
            
                        $ActivityReports[] = array($Allocation->year,$PAP->pap,$Activity->activity,$Voucher->sequenceid,$Voucher->AccountName->acct_name,number_format($ObligationAmount,2,'.',','),number_format($DisbursementAmount,2,'.',','), $Allocation->expense_class ); 
                
                    }           
                }
                
            }
    
        }    



        return view('financial-management.allocationpayeereport',compact('ActivityReports'));
    }
  
    public function allocationreport() {

   
        $this->authorize('viewany', \App\Models\FinancialManagement::class);

        $Activities = FMPlanningActivity::orderby('id','desc')->with('Allocation','Voucher','PAP')->get();


        $ActivityReports = array();
        
        $ChargingPerActivity = array();

        foreach($Activities as $Activity)
        {
            foreach ($Activity->allocation as $Allocation)
            {
           
                $ObligationAmount = FMCharging::where('activityid', '=', $Allocation->activityid)->where('year','=',$Allocation->year)->where('activityoffice','=',$Allocation->office)->where('expense_class',$Allocation->expense_class)->where('is_obligated','=', true)->with('FinancialManagement')->sum('amount');
                $DisbursementAmount = FMCharging::where('activityid', '=', $Allocation->activityid)->where('year','=',$Allocation->year)->where('activityoffice','=',$Allocation->office)->where('expense_class',$Allocation->expense_class)->where('is_disbursed','=', true)->with('FinancialManagement')->sum('amount');

                 if ( $ObligationAmount !=0) {
                    $DO = round(($DisbursementAmount / $ObligationAmount )*100,1);
                    $OA = round(($ObligationAmount / $Allocation->amount) * 100, 1);
               
                 }
                 else
                {
                    $DO = 0;
                    $OA = 0;
        
                }

                if ( $Allocation->amount !=0) {
                
                    $DA = round(($DisbursementAmount / $Allocation->amount) * 100, 1);
                }
                else
                {
                    $DA = 0;
                }
      
                $ActivityReports[] = array($Allocation->year, $Activity->activity, number_format($Allocation->amount,2,'.',','), number_format($Allocation->rem_bal,2,'.',','), number_format($ObligationAmount,2,'.',','), $OA . ' %' ,$Activity->PAP->pap,number_format($DisbursementAmount,2,'.',','), $DO . ' %', $Allocation->office,$Allocation->expense_class, $DA . ' %'); 
                
            }
    
        }    



        return view('financial-management.allocationreport',compact('ActivityReports'));
    }


    public function allocationPAPreport() {

   
        $this->authorize('viewany', \App\Models\FinancialManagement::class);

        $PAPs = FMPlanningPAP::orderby('id','desc')->with('Allocation','Voucher')->get();

        $ActivityReports = array();

        foreach ($PAPs as $PAP)
        {
            foreach ($PAP->allocation as $Allocation)
            {
                      
                $ObligationAmount = FMCharging::where('papid', '=', $Allocation->papid)->where('year','=',$Allocation->year)->where('papoffice','=',$Allocation->office)->where('expense_class','=',$Allocation->expense_class)->where('is_obligated','=', true)->with('FinancialManagement')->sum('amount');
                $DisbursementAmount = FMCharging::where('papid', '=', $Allocation->papid)->where('year','=',$Allocation->year)->where('papoffice','=',$Allocation->office)->where('expense_class','=',$Allocation->expense_class)->where('is_disbursed','=', true)->with('FinancialManagement')->sum('amount');
             if ( $ObligationAmount !=0) {
                    $DO = round(($DisbursementAmount / $ObligationAmount )*100,1);
                    $OA = round(($ObligationAmount / $Allocation->amount) * 100, 1);
             }
             else
            {
                $DO = 0;
                $OA = 0;
            }

            if ( $Allocation->amount !=0) {
                
                $DA = round(($DisbursementAmount / $Allocation->amount) * 100, 1);
            }
            else
            {
                $DA = 0;
            }

            // $ActivityReports[] = array($Allocation->year, $PAP->pap, number_format($Allocation->amount,2,'.',','), number_format($Allocation->rem_bal,2,'.',','), number_format($ObligationAmount,2,'.',','), $OA . ' %' ,$PAP->pap,number_format($DisbursementAmount,2,'.',','), $DO . ' %', $Allocation->office); 
                
            $ActivityReports[] = array($Allocation->year,$PAP->pap, $Allocation->office,number_format($Allocation->amount,2,'.',','),number_format($ObligationAmount,2,'.',','),number_format($DisbursementAmount,2,'.',','), $OA . ' %' , $DO . ' %',$Allocation->expense_class , $DA . ' %');
            }
        }


     
    

        return view('financial-management.allocationpapreport',compact('ActivityReports'));
    }
  


    public function allocationUACSreport() {

   
        $this->authorize('viewany', \App\Models\FinancialManagement::class);

        $UACSs = FMPlanningUACS::orderby('id','desc')->with('Allocation','Voucher')->get();
        


        $ActivityReports = array();


        foreach($UACSs as $UACS)
        {
            foreach ($UACS->allocation as $Allocation)
            {
           
                $ObligationAmount = FMCharging::where('uacsid', '=', $Allocation->uacsid)->where('year','=',$Allocation->year)->where('uacsoffice','=',$Allocation->office)->where('expense_class',$Allocation->expense_class)->where('is_obligated','=', true)->with('FinancialManagement')->sum('amount');
                $DisbursementAmount = FMCharging::where('uacsid', '=', $Allocation->uacsid)->where('year','=',$Allocation->year)->where('uacsoffice','=',$Allocation->office)->where('expense_class',$Allocation->expense_class)->where('is_disbursed','=', true)->with('FinancialManagement')->sum('amount');
       
  
                 if ( $ObligationAmount !=0) {
                        $DO = round(($DisbursementAmount / $ObligationAmount )*100,1);
                        $OA = round(($ObligationAmount / $Allocation->amount) * 100, 1);
                 }
                 else
                {
                    $DO = 0;
                    $OA = 0;
                }
                
                
                if ( $Allocation->amount !=0) {
                
                    $DA = round(($DisbursementAmount / $Allocation->amount) * 100, 1);
                }
                else
                {
                    $DA = 0;
                }

                $PAP = FMPlanningPAP::where('id','=',$Allocation->papid)->get()->first();
                
                $ActivityReports[] = array($Allocation->year, $UACS->uacs, number_format($Allocation->amount,2,'.',','), number_format($Allocation->rem_bal,2,'.',','), number_format($ObligationAmount,2,'.',','), $OA . ' %' ,$PAP->pap,number_format($DisbursementAmount,2,'.',','), $DO . ' %', $Allocation->office,$Allocation->expense_class, $DA . ' %'); 
                
            }
    
        }    



        return view('financial-management.allocationuacsreport',compact('ActivityReports'));
    }

  
    public function RealignmentReport() {

        $Realignments = RealignHistory::with('PAP','UACSfrom','UACSto','User')->get();
        return view('financial-management.realignmentReport',compact('Realignments'));

    }


    public function FinancialTracking() {

        $this->authorize('viewany', \App\Models\FinancialManagement::class);

        $Vouchers = FinancialManagement::get();

        $FinancialTracking = array();

        foreach ($Vouchers as $Voucher)
        {
            $Routes = FinancialManagementRoute::where('sequenceid','=',$Voucher->sequenceid)->orderby('created_at', 'DESC')->get();

            foreach($Routes as $Route)
            {
              if($Route->action == 'ACCEPTED' || $Route->action == 'FORWARD TO'  || $Route->action == 'FOR LDDAP / ADA SIGNATURE')
              {
                $FinancialTracking[] = array($Route->action, $Route->unitid, $Route->userunitid);
                break;
              }
            }

        }

        $BudgetIncoming = 0;
        $BudgetProcessing = 0;
        $BudgetOutgoing = 0;

        $PlanningIncoming = 0;
        $PlanningProcessing = 0;
        $PlanningOutgoing = 0;
        
        $AccountingIncoming = 0;
        $AccountingProcessing = 0;
        $AccountingOutgoing = 0;

        $CashierIncoming = 0;
        $CashierProcessing = 0;
        $CashierOutgoing = 0;

        $RecordsIncoming = 0;
        $RecordsProcessing = 0;
        $RecordsOutgoing = 0;

        $MsdIncoming = 0;
        $MsdProcessing = 0;
        $MsdOutgoing = 0;

        $TsdIncoming = 0;
        $TsdProcessing = 0;
        $TsdOutgoing = 0;

        $PenroIncoming = 0;
        $PenroProcessing = 0;
        $PenroOutgoing = 0;

        foreach ($FinancialTracking as $Count)
        {
            if($Count[0] == 'ACCEPTED')
            {
                if ($Count[1] =='2')
                {
                    $PlanningProcessing = $PlanningProcessing + 1;
                }

                if ($Count[1] =='8')
                {
                    $BudgetProcessing = $BudgetProcessing + 1;
                }

                if ($Count[1] =='9')
                {
                    $AccountingProcessing = $AccountingProcessing + 1;
                }
                if ($Count[1] =='4'  && $Count[1] != '15')
                {

                    $CashierProcessing = $CashierProcessing + 1;
        
                }
                if ($Count[1] =='7')
                {
                    $RecordsProcessing = $RecordsProcessing + 1;
                }
                if ($Count[1] =='6')
                {
                    $MsdProcessing = $MsdProcessing + 1;
                }
                if ($Count[1] =='14')
                {
                    $TsdProcessing = $TsdProcessing + 1;
                }
                if ($Count[1] =='1')
                {
                    $PenroProcessing = $PenroProcessing + 1;
                }
            }

            if($Count[0] == 'FORWARD TO')
            {
                if ($Count[2] =='2')
                {
                    $PlanningOutgoing = $PlanningOutgoing + 1;
                }
                if ($Count[1] =='2')
                {
                    $PlanningIncoming = $PlanningIncoming + 1;
                }

                if ($Count[2] =='8')
                {
                    $BudgetOutgoing = $BudgetOutgoing + 1;
                }
                if ($Count[1] =='8')
                {
                    $BudgetIncoming = $BudgetIncoming + 1;
                }

                if ($Count[2] =='9')
                {
                    $AccountingOutgoing = $AccountingOutgoing + 1;
                }
                if ($Count[1] =='9')
                {
                    $AccountingIncoming = $AccountingIncoming + 1;
                }

                if ($Count[2] =='4' && $Count[1] != '15')
                {
                    $CashierOutgoing = $CashierOutgoing + 1;
                }
                if ($Count[1] =='4')
                {
                    $CashierIncoming = $CashierIncoming + 1;
                }
                if ($Count[2] =='7')
                {
                    $RecordsOutgoing = $RecordsOutgoing + 1;
                }
                if ($Count[1] =='7')
                {
                    $RecordsIncoming = $RecordsIncoming + 1;
                }
                if ($Count[2] =='6')
                {
                    $MsdOutgoing = $MsdOutgoing + 1;
                }
                if ($Count[1] =='6')
                {
                    $MsdIncoming = $MsdIncoming + 1;
                }
                if ($Count[2] =='14')
                {
                    $TsdOutgoing = $TsdOutgoing + 1;
                }
                if ($Count[1] =='14')
                {
                    $TsdIncoming = $TsdIncoming + 1;
                }
                if ($Count[2] =='1')
                {
                    $PenroOutgoing = $PenroOutgoing + 1;
                }
                if ($Count[1] =='1')
                {
                    $PenroIncoming = $PenroIncoming + 1;
                }
            }
  
        }

        return view('financial-management.financialtracking',compact('PlanningIncoming','PlanningProcessing','PlanningOutgoing','BudgetIncoming','BudgetProcessing','BudgetOutgoing', 'AccountingIncoming', 'AccountingProcessing','AccountingOutgoing','CashierIncoming','CashierProcessing','CashierOutgoing','RecordsIncoming','RecordsProcessing','RecordsOutgoing','MsdIncoming','MsdProcessing','MsdOutgoing','TsdIncoming','TsdProcessing','TsdOutgoing','PenroIncoming','PenroProcessing','PenroOutgoing'));
    }

    
    public function create() {

        $this->authorize('create', \App\Models\FinancialManagement::class);

        $AccountNames = AccountName::orderby('acct_name','DESC')->where('is_active','=',true)->get();

        $AccountNumbers = AccountNumber::orderby('id','DESC')->get();

        $BoxAs = BoxA::where('box','=','A')->orderby('certified_by','asc')->get();
 
        return view('financial-management.create',compact('AccountNames','AccountNumbers','BoxAs'));
    }

    public function store(Request $request) {
        
        $this->authorize('create', \App\Models\FinancialManagement::class);
        
        $formfields = $request->validate([
            'datereceived' => 'required',
            'acct_id' => 'required',
            'acct_no' => 'required',
            'office' => 'required',
            'address' => 'required',
            'particulars' => 'required',
            'amount' => 'required',
          
            'certified_by' => 'required',
            'certified_by_ors' => 'required',
        ]);

        $formfields['remarks'] = $request->remarks;
        $project = FinancialManagement::where('sequenceid', 'LIKE', '%' . date('Y') . '%')->latest()->first();
        
        if($project != null) 
            {
              $newproject = $project->id;
              $projectcount = (int)$newproject + 1;
            }
            else
              {
                 $projectcount = 1;
              }

        $formfields['sequenceid'] = 'SQ'.'-'.date('Y') .'-'. str_pad($projectcount, 8, '0', STR_PAD_LEFT) ;
        // $formfields['userid'] = auth()->user()->id;
        
        
        $EmployeeOffice = Employee::where('email',auth()->user()->email)->get()->first();
   
        $data['actiondate'] = now();
        $data['sequenceid'] = 'SQ'.'-'.date('Y') .'-'. str_pad($projectcount, 8, '0', STR_PAD_LEFT) ;
        $data['officeid'] = $EmployeeOffice->officeid;
        $data['sectionid'] =  $EmployeeOffice->sectionid;
        $data['unitid'] =  $EmployeeOffice->unitid;
        $data['action'] = 'VOUCHER CREATED';;
        $data['is_active'] = true;
        $data['userid'] =  auth()->user()->id;

        $employee = Employee::where('email','=',auth()->user()->email)->get()->first();
        $data['userunitid'] = $employee->unitid;
        
        FinancialManagementRoute::updateorCreate($data);
    
        $formfields['userid'] =  auth()->user()->id;
        $formfields['userunitid'] =  $EmployeeOffice->unitid;
   
        FinancialManagement::updateorCreate($formfields);
     

        return redirect()->route('financial-management.create')->with('message', "Voucher Saved Successfully!");
    }

    public function viewvoucher() 
    
    {

        $Voucher = FinancialManagement::orderby('created_at','desc')->with('AccountName','AccountNumber','BoxA','BoxAORS')->where('userid','=',Auth()->user()->id)->get()->first();
       
        $this->authorize('view', $Voucher);

        $FloatAmount = number_format($Voucher->amount,2,'.',',');

        $Offices = Office::orderby('id')->get();
        $Sections = Section::orderby('section')->get();
        $Units = Unit::orderby('unit')->get();

        $AccountingActivities = AActivity::orderby('activity','asc')->get();

        $AccountingEntries = AccountEntry::where('fmid','=',$Voucher->id)->orderby('created_at', 'desc')->with('AActivity','AUACS')->get();
        

        $RouteSpecific = FinancialManagementRoute::where('sequenceid', $Voucher->sequenceid)->where('action','=','FORWARD TO')->orderBy('created_at', 'desc')->get()->first();  

        $Routes = FinancialManagementRoute::where('sequenceid', $Voucher->sequenceid)->orderby('created_at', 'desc')->with('Office', 'Section', 'Unit')->get();

        $Employee = Employee::where('email','=', auth()->user()->email)->get()->first();

        $Chargings = FMCharging::where('fmid','=',$Voucher->id)->with('PAP','Activity','UACS')->get();
        $PAPs = FMPlanningPAP::orderby('pap','asc')->get();
        $UACSs = FMPlanningUACS::orderby('uacs','asc')->get();
        $Activities = FMPlanningActivity::orderby('activity','asc')->get();

        $BoxAs = BoxA::where('box','=','D')->orderby('certified_by','asc')->get();

        $ORSs = FMORS::where('fmid','=',$Voucher->id)->get();
        $DVs = FMDV::where('fmid','=',$Voucher->id)->get();
             
        $Accounts = FMCash::where('payee','=',$Voucher->payee);

        $Cashiers = FMCash::where('fmid','=',$Voucher->id)->get();

        $Obligations = FMORS::where('fmid','=',$Voucher->id)->get();

        $Chargings = FMCharging::where('fmid','=',$Voucher->id)->with('PAP','Activity','UACS')->get();


        $ChargingTotal = FMCharging::where('fmid','=',$Voucher->id)->sum('amount');

        return view('financial-management.view', compact('BoxAs','Voucher','FloatAmount','Offices','Sections','AccountingEntries','AccountingActivities','Units','RouteSpecific','Routes','Employee','Chargings','PAPs','Activities','UACSs','ORSs','DVs','Accounts','Cashiers','Chargings','Obligations','ChargingTotal'));
    }

    public function view(FinancialManagement $Voucher) 
    {

        $this->authorize('view', $Voucher);

   

        $Voucher = FinancialManagement::where('id','=', $Voucher->id)->orderby('created_at','desc')->with('AccountName','AccountNumber','BoxA')->get()->first();
       
        $FloatAmount = number_format($Voucher->amount,2,'.',',');

        $Offices = Office::orderby('id')->get();
        $Sections = Section::orderby('section')->get();
        $Units = Unit::orderby('unit')->get();

        $AccountingActivities = AActivity::orderby('activity','asc')->get();
        $AccountingEntries = AccountEntry::where('fmid','=',$Voucher->id)->orderby('created_at', 'desc')->with('AActivity','AUACS')->get();

        $RouteSpecific = FinancialManagementRoute::where('sequenceid', $Voucher->sequenceid)->where('action','=','FORWARD TO')->orderBy('created_at', 'desc')->get()->first();  

        $Routes = FinancialManagementRoute::where('sequenceid', $Voucher->sequenceid)->orderby('created_at', 'desc')->with('Office', 'Section', 'Unit')->get();

        $Employee = Employee::where('email','=', auth()->user()->email)->get()->first();
        $Chargings = FMCharging::where('fmid','=',$Voucher->id)->with('PAP','Activity','UACS')->get();
        $PAPs = FMPlanningPAP::orderby('pap','asc')->get();
        $UACSs = FMPlanningUACS::orderby('uacs','asc')->get();
        $Activities = FMPlanningActivity::orderby('activity','asc')->get();

        $BoxAs = BoxA::where('box','=','D')->orderby('certified_by','asc')->get();

        $ORSs = FMORS::where('fmid','=',$Voucher->id)->get();
        $DVs = FMDV::where('fmid','=',$Voucher->id)->get();

        $Accounts = FMCash::where('payee','=',$Voucher->payee);

        $Cashiers = FMCash::where('fmid','=',$Voucher->id)->get();

        $Chargings = FMCharging::where('fmid','=',$Voucher->id)->with('PAP','Activity','UACS')->get();

        $ChargingTotal = FMCharging::where('fmid','=',$Voucher->id)->sum('amount');


        $Obligations = FMORS::where('fmid','=',$Voucher->id)->get();
             
        return view('financial-management.view', compact('BoxAs','Voucher','FloatAmount','Offices','Sections','AccountingEntries','AccountingActivities','Units','RouteSpecific','Routes','Employee','Chargings','PAPs','Activities','UACSs','ORSs','DVs','Accounts','Cashiers','Obligations','Chargings','ChargingTotal'));
    }


    public function printors(FinancialManagement $Voucher) {

        if(auth()->check());
        
        {
 
            $Voucher = FinancialManagement::where('id','=', $Voucher->id)->orderby('created_at','desc')->with('AccountName','BoxA')->get()->first();

            $Employees = Employee::with('Unit','Office','User')->get();

            $Chargings = FMCharging::where('fmid','=',$Voucher->id)->with('PAP','Activity','UACS')->get();

            $FloatAmount = number_format($Voucher->amount,2,'.',',');

            $Obligations = FMORS::where('fmid','=',$Voucher->id)->get();

            $AccountName = AccountName::where('id','=', $Voucher->acct_id)->get()->first();

            return view('financial-management.printors', compact('Employees','Voucher','FloatAmount','Chargings','Obligations','AccountName'));
        }

       
    }

    public function printdv(FinancialManagement $Voucher) {

        if(auth()->check());
        
        {
 
            $Voucher = FinancialManagement::where('id','=', $Voucher->id)->orderby('created_at','desc')->with('AccountName','BoxA','AccountNumber','BoxD')->get()->first();

            $Review = FMReview::where('fmid','=',$Voucher->id)->get()->first();
          
            $Employees = Employee::with('Unit','Office','User')->get();

            $Chargings = FMCharging::where('fmid','=',$Voucher->id)->with('PAP','Activity','UACS')->get();

            $FloatAmount = number_format($Voucher->amount,2,'.',',');

            $Obligations = FMORS::where('fmid','=',$Voucher->id)->get();

            $DVs = FMDV::where('fmid','=',$Voucher->id)->get();

            $BoxD = BoxD::where('fmid','=',$Voucher->id)->get()->first();

            $Cashiers = FMCash::where('fmid','=',$Voucher->id)->get();

            $AccountingEntries = AccountEntry::where('fmid','=',$Voucher->id)->get();

            $AccountName = AccountName::where('id','=', $Voucher->acct_id)->get()->first();

   
       
            return view('financial-management.printdv', compact('BoxD','Cashiers','Employees','Voucher','FloatAmount','DVs','Chargings','Obligations','AccountingEntries','Review','AccountName'));

        }

       
    }

    

 

    public function    acceptIncomingByBatch(Request $request) {
   



        
        // $this->authorize('acceptIncoming', $Voucher);

    
        $formfields['userid'] = auth()->user()->id;
        $formfields['is_active'] = true;
        
        $officeunitsection = Employee::where('email','=', auth()->user()->email)->get()->first();

        $formfields['officeid'] = $officeunitsection->officeid;
        $formfields['sectionid'] = $officeunitsection->sectionid;
        $formfields['unitid'] = $officeunitsection->unitid;
        $formfields['actiondate'] = now();
        $formfields['action'] = 'ACCEPTED';
        $formfields['is_accepted'] = true;
        $employee = Employee::where('email','=',auth()->user()->email)->get()->first();
        $formfields['userunitid'] = $employee->unitid;
        $formfields['remarks'] = 'ACCEPTED - FOR SIGNATURE';


        $Vouchers = FMCash::where('adano', '=', $request->batchid)->get();
       
       
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




    public function acceptIncoming(Request $request) {
   
        $Voucher = FinancialManagement::where('sequenceid', '=', $request->sequenceid)->get()->first();
        
        $this->authorize('acceptIncoming', $Voucher);

        $formfields['sequenceid'] = $Voucher->sequenceid;
        $formfields['userid'] = auth()->user()->id;
        $formfields['is_active'] = true;
        
        $officeunitsection = Employee::where('email','=', auth()->user()->email)->get()->first();

        $formfields['officeid'] = $officeunitsection->officeid;
        $formfields['sectionid'] = $officeunitsection->sectionid;
        $formfields['unitid'] = $officeunitsection->unitid;
        $formfields['actiondate'] = now();
        $formfields['action'] = 'ACCEPTED';
        $formfields['is_accepted'] = true;
        $employee = Employee::where('email','=',auth()->user()->email)->get()->first();
        $formfields['userunitid'] = $employee->unitid;

        FinancialManagementRoute::updateorCreate($formfields);

        return back()->with('message', "Route Accepted Successfully!");



    }


    public function acceptIncomingSignature(Request $request) {
   
        $Voucher = FinancialManagement::where('sequenceid', '=', $request->sequenceid)->get()->first();
        
        $this->authorize('acceptIncoming', $Voucher);

        $formfields['sequenceid'] = $Voucher->sequenceid;
        $formfields['userid'] = auth()->user()->id;
        $formfields['is_active'] = true;
        
        $officeunitsection = Employee::where('email','=', auth()->user()->email)->get()->first();

        $formfields['officeid'] = $officeunitsection->officeid;
        $formfields['sectionid'] = $officeunitsection->sectionid;
        $formfields['unitid'] = $officeunitsection->unitid;
        $formfields['actiondate'] = now();
        $formfields['action'] = 'ACCEPTED';
        $formfields['is_accepted'] = true;
        $employee = Employee::where('email','=',auth()->user()->email)->get()->first();
        $formfields['userunitid'] = $employee->unitid;
        $formfields['remarks'] = 'ACCEPTED - FOR SIGNATURE';
        FinancialManagementRoute::updateorCreate($formfields);

        return back()->with('message', "Route Accepted Successfully!");



    }


    public function rejectIncoming(Request $request) {
   
        $Voucher = FinancialManagement::where('sequenceid', '=', $request->sequenceid)->get()->first();
        
        $this->authorize('acceptIncoming', $Voucher);

        $formfields['sequenceid'] = $Voucher->sequenceid;
        $formfields['userid'] = auth()->user()->id;
        $formfields['is_active'] = true;
        
        $officeunitsection = Employee::where('email','=', auth()->user()->email)->get()->first();

        $formfields['officeid'] = $officeunitsection->officeid;
        $formfields['sectionid'] = $officeunitsection->sectionid;
        $formfields['unitid'] = $officeunitsection->unitid;
        $formfields['actiondate'] = now();
        $formfields['action'] = 'REJECTED';
        $formfields['is_rejected'] = true;
        $employee = Employee::where('email','=',auth()->user()->email)->get()->first();
        $formfields['userunitid'] = $employee->unitid;

        FinancialManagementRoute::updateorCreate($formfields);

        return back()->with('message', "Route Rejected Successfully!");



    }

    
}
