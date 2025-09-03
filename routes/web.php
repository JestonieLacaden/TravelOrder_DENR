<?php

use App\Http\Controllers\Admin\RolesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\DocumentTracking\DocumentController;
use App\Http\Controllers\DocumentTracking\AttachmentController;
use App\Http\Controllers\FinancialManagement\FinancialManagementController;
use App\Http\Controllers\Msd\DtrController;
use App\Http\Controllers\Msd\DtrSignatoryController;
use App\Http\Controllers\Msd\EventController;
use App\Http\Controllers\Msd\LeaveController;
use App\Http\Controllers\Msd\TravelOrderController;
use App\Http\Controllers\User\DtrController as UserDtrController;
use App\Http\Controllers\User\MailController;
use App\Models\Document;
use App\Models\Event;
use App\Models\TravelOrder;
use App\Models\UserRole;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [AuthController::class,'dashboard'])->middleware('auth');
Route::get('dashboard', [AuthController::class,'dashboard'])->middleware('auth')->name('dashboard');
Route::post('custom-login', [AuthController::class, 'customLogin'])->name('login.custom'); 
Route::get('login', [AuthController::class, 'index'])->name('login');
Route::get('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');


// Route::group(['middleware' => 'DisableBackBtn'], function () {

    Route::group(['middleware' => 'auth'], function () {

        Route::get('financial-management/allocation-report', [FinancialManagementController::class, 'allocationreport'])->name('financial-management.Allocationreport'); 
        Route::get('financial-management/allocation-uacs-report', [FinancialManagementController::class, 'allocationUACSreport'])->name('financial-management.AllocationUACSreport'); 
        Route::get('financial-management/allocation-payee-report', [FinancialManagementController::class, 'allocationPayeereport'])->name('financial-management.AllocationPayeereport'); 
        Route::get('financial-management/allocation-pap-report', [FinancialManagementController::class, 'allocationPAPreport'])->name('financial-management.AllocationPAPreport');
        Route::get('financial-management/financial_tracking', [FinancialManagementController::class, 'FinancialTracking'])->name('financial-management.FinancialTracking');
        Route::get('financial-management/realignment_report', [FinancialManagementController::class, 'RealignmentReport'])->name('financial-management.RealignmentReport');
        Route::post('financial-management/signatory-section/signatory-approve{Route}', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'SignatoryApprove'])->name('fm.signatoryapprove');

        Route::post('financial-management/planning-section/accept-signature', [App\Http\Controllers\FinancialManagement\FinancialManagementController::class, 'acceptIncomingSignature'])->name('fm.signatureaccept');
        Route::post('financial-management/planning-section/accept-by-batch', [App\Http\Controllers\FinancialManagement\FinancialManagementController::class, 'acceptIncomingByBatch'])->name('fm.acceptbybatch');



        Route::post('financial-management/planning-section/accept', [App\Http\Controllers\FinancialManagement\FinancialManagementController::class, 'acceptIncoming'])->name('fm.accept');
        Route::get('financial-management/planning-section/pap', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'planningPAP'])->name('fmplanning.pap');
        Route::post('financial-management/planning-section/pap/create', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'planningPAPcreate'])->name('fmplanning.papcreate');
        Route::post('financial-management/planning-section/pap/{PAP}/delete', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'planningPAPdelete'])->name('fmplanning.papdelete');
        
        Route::get('financial-management/planning-section/allocation', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'planningAllocation'])->name('fmplanning.allocation');
        Route::get('financial-management/planning-section/allocation-saa', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'planningAllocationSAA'])->name('fmplanning.allocationsaa');
        Route::post('financial-management/planning-section/allocation/create', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'planningAllocationcreate'])->name('fmplanning.allocationcreate');
        Route::post('financial-management/planning-section/allocation-saa/create', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'planningAllocationcreateSAA'])->name('fmplanning.allocationcreateSAA');

        Route::post('financial-management/planning-section/allocation/{Allocation}/delete', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'planningAllocationdelete'])->name('fmplanning.allocationdelete');
        Route::post('financial-management/planning-section/allocation-saa/{Allocation}/delete', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'planningAllocationdeleteSAA'])->name('fmplanning.allocationdeleteSAA');   

        Route::get('financial-management/planning-section/allocation-uacs', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'planningAllocationUACS'])->name('fmplanning.allocationUACS');
        Route::get('financial-management/planning-section/allocation-uacs-saa', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'planningAllocationUACSSAA'])->name('fmplanning.allocationUACSSAA');

        Route::post('financial-management/planning-section/allocation-uacs/create', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'planningAllocationcreateUACS'])->name('fmplanning.allocationcreateUACS');
        Route::post('financial-management/planning-section/allocation-uacs-saa/create', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'planningAllocationcreateUACSSAA'])->name('fmplanning.allocationcreateUACSSAA');

        Route::post('financial-management/planning-section/allocation-uacs/{Allocation}/delete', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'planningAllocationdeleteUACS'])->name('fmplanning.allocationdeleteUACS');
        Route::post('financial-management/planning-section/allocation-uacs-saa/{Allocation}/delete', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'planningAllocationdeleteUACSSAA'])->name('fmplanning.allocationdeleteUACSSAA');

        Route::post('financial-management/planning-section/allocation-uacs/realignment', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'planningrealignmentUACS'])->name('fmplanning.realignmentUACS');
        Route::post('financial-management/planning-section/allocation-uacs-saa/realignment', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'planningrealignmentUACSSAA'])->name('fmplanning.realignmentUACSSAA');


        Route::get('financial-management/planning-section/allocation-pap', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'planningAllocationPAP'])->name('fmplanning.allocationPAP');
        Route::get('financial-management/planning-section/allocation-pap-saa', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'planningAllocationPAPSAA'])->name('fmplanning.allocationPAPsaa');
       
        Route::post('financial-management/planning-section/allocation-pap/create', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'planningAllocationcreatePAP'])->name('fmplanning.allocationcreatePAP');
        Route::post('financial-management/planning-section/allocation-pap-saa/create', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'planningAllocationcreatePAPSAA'])->name('fmplanning.allocationcreatePAPSAA');

        Route::post('financial-management/planning-section/allocation-pap/{Allocation}/delete', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'planningAllocationdeletePAP'])->name('fmplanning.allocationdeletePAP');
        Route::post('financial-management/planning-section/allocation-pap-saa/{Allocation}/delete', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'planningAllocationdeletePAPSAA'])->name('fmplanning.allocationdeletePAPSAA');


        Route::get('financial-management/planning-section/uacs', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'planningUACS'])->name('fmplanning.uacs');
        Route::post('financial-management/planning-section/uacs/create', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'planningUACScreate'])->name('fmplanning.uacscreate');
        Route::post('financial-management/planning-section/uacs/{UACS}/delete', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'planningUACSdelete'])->name('fmplanning.uacsdelete');
        Route::get('financial-management/planning-section/activity', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'planningActivity'])->name('fmplanning.activity');
        Route::post('financial-management/planning-section/activity/create', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'planningActivitycreate'])->name('fmplanning.activitycreate');
        Route::post('financial-management/planning-section/activity/{Activity}/delete', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'planningActivitydelete'])->name('fmplanning.activitydelete');
        Route::post('financial-management/planning-section/reject', [App\Http\Controllers\FinancialManagement\FinancialManagementController::class, 'rejectIncoming'])->name('fm.reject');
        Route::get('document-tracking/mail', [App\Http\Controllers\User\MailController::class, 'index'])->name('mails.index');
        Route::get('financial-management/planning-section/rejected', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'planningrejected'])->name('fmplanning.rejected');
        Route::get('financial-management/planning-section/outgoing', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'planningoutgoing'])->name('fmplanning.outgoing');
        Route::get('financial-management/planning-section/processing', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'planningprocessing'])->name('fmplanning.processing');
        Route::post('financial-management/planning-section/processing/add-charging/{Route}', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'planningCharging'])->name('fmplanning.charging');
        Route::post('financial-management/planning-section/processing/add-charging_saa/{Route}', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'planningChargingSAA'])->name('fmplanning.chargingSAA');
        Route::post('financial-management/planning-section/processing/delete-charging/{Route}', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'planningChargingdelete'])->name('fmplanning.chargingdelete');
        
        // Route::get('financial-management/create/getPayee', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'getPayee'])->name('financialmanagement.getPayee');
        Route::get('financial-management/planning-section/processing/activity/get_remaining_balance', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'getRemBal'])->name('fmplanning.getRemBal');
        Route::get('financial-management/planning-section/processing/activity/get_remaining_balance_office', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'getRemBalOffice'])->name('fmplanning.getRemBalOffice');
        Route::get('financial-management/planning-section/processing/activity/get_remaining_balance_year', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'getRemBalyear'])->name('fmplanning.getRemBalyear');


        Route::get('financial-management/planning-section/processing/activity/get_remaining_balance_allocation_pap', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'getRemBalAllocPAP'])->name('fmplanning.getRemBalAllocPAP');
        Route::get('financial-management/planning-section/processing/activity/get_remaining_balance_allocation_pap_saa', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'getRemBalAllocPAPSAA'])->name('fmplanning.getRemBalAllocPAPSAA');
        Route::get('financial-management/planning-section/processing/activity/get_remaining_balance_allocation_pap_office', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'getRemBalAllocPAPoffice'])->name('fmplanning.getRemBalAllocPAPoffice');
        Route::get('financial-management/planning-section/processing/activity/get_remaining_balance_allocation_pap_office_saa', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'getRemBalAllocPAPofficeSAA'])->name('fmplanning.getRemBalAllocPAPofficeSAA');

        Route::get('financial-management/planning-section/processing/activity/get_remaining_balance_allocation_pap_year', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'getRemBalAllocPAPyear'])->name('fmplanning.getRemBalAllocPAPyear');
        Route::get('financial-management/planning-section/processing/activity/get_remaining_balance_allocation_pap_year_saa', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'getRemBalAllocPAPyearSAA'])->name('fmplanning.getRemBalAllocPAPyearSAA');

        Route::get('financial-management/planning-section/processing/activity/get_remaining_balance_year_allocation_pap', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'getRemBalyearAllocPAP'])->name('fmplanning.getRemBalyearAllocPAP');
        Route::get('financial-management/planning-section/processing/activity/get_remaining_balance_year_allocation_pap_saa', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'getRemBalyearAllocPAPSAA'])->name('fmplanning.getRemBalyearAllocPAPSAA');


        Route::get('financial-management/planning-section/processing/activity/get_remaining_balance_allocation_uacs', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'getRemBalAllocUACS'])->name('fmplanning.getRemBalAllocUACS');
        Route::get('financial-management/planning-section/processing/activity/get_remaining_balance_allocation_uacs_saa', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'getRemBalAllocUACSSAA'])->name('fmplanning.getRemBalAllocUACSSAA');

        Route::get('financial-management/planning-section/processing/activity/get_remaining_balance_allocation_uacs_year', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'getRemBalAllocUACSyear'])->name('fmplanning.getRemBalAllocUACSyear');
        Route::get('financial-management/planning-section/processing/activity/get_remaining_balance_allocation_uacs_year_saa', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'getRemBalAllocUACSyearSAA'])->name('fmplanning.getRemBalAllocUACSyearSAA');

        Route::get('financial-management/planning-section/processing/activity/get_remaining_balance_allocation_uacs_expense', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'getRemBalrealignyexpense'])->name('fmplanning.getRemBalrealignyexpense');
        Route::get('financial-management/planning-section/processing/activity/get_remaining_balance_allocation_uacs_office', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'getRemBalrealignoffice'])->name('fmplanning.getRemBalrealignoffice');
        Route::get('financial-management/planning-section/processing/activity/get_remaining_balance_allocation_uacs_office_saa', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'getRemBalrealignofficeSAA'])->name('fmplanning.getRemBalrealignofficeSAA');
 
        Route::get('financial-management/planning-section/processing/activity/get_remaining_balance_allocation_uacs_expense_saa', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'getRemBalrealignyexpenseSAA'])->name('fmplanning.getRemBalrealignyexpenseSAA');

        Route::get('financial-management/planning-section/processing/activity/get_remaining_balance_allocation_uacs_realign_year', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'getRemBalrealign_year'])->name('fmplanning.getRemBalrealign_year');
        Route::get('financial-management/planning-section/processing/activity/get_remaining_balance_allocation_uacs_realign_year_saa', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'getRemBalrealign_yearSAA'])->name('fmplanning.getRemBalrealign_yearSAA');

        Route::get('financial-management/planning-section/processing/activity/charging/expense_class', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'ChargingExpenseClass'])->name('fmplanning.ChargingExpenseClass');
        Route::get('financial-management/planning-section/processing/activity/charging/expense_class_saa', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'ChargingExpenseClassSAA'])->name('fmplanning.ChargingExpenseClassSAA');

        Route::get('financial-management/planning-section/processing/activity/charging/activity', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'ChargingActivity'])->name('fmplanning.ChargingActivity');
        Route::get('financial-management/planning-section/processing/activity/charging/activity_saa', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'ChargingActivitySAA'])->name('fmplanning.ChargingActivitySAA');

        Route::get('financial-management/planning-section/processing/activity/charging/year', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'ChargingYear'])->name('fmplanning.ChargingYear');
        Route::get('financial-management/planning-section/processing/activity/charging/year_saa', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'ChargingYearSAA'])->name('fmplanning.ChargingYearSAA');

        Route::get('financial-management/planning-section/processing/activity/charging/office', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'ChargingOffice'])->name('fmplanning.ChargingOffice');
        Route::get('financial-management/planning-section/processing/activity/charging/office_saa', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'ChargingOfficeSAA'])->name('fmplanning.ChargingOfficeSAA');

        
        Route::get('financial-management/planning-section/processing/activity/charging/expense_class_uacs', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'ChargingExpenseClassUACS'])->name('fmplanning.ChargingExpenseClassUACS');
        Route::get('financial-management/planning-section/processing/activity/charging/expense_class_uacs_saa', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'ChargingExpenseClassUACSSAA'])->name('fmplanning.ChargingExpenseClassUACSSAA');

        Route::get('financial-management/planning-section/processing/activity/charing/uacs', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'ChargingUACS'])->name('fmplanning.ChargingUACS');
        Route::get('financial-management/planning-section/processing/activity/charing/uacs_saa', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'ChargingUACSSAA'])->name('fmplanning.ChargingUACSSAA');

        Route::get('financial-management/planning-section/processing/activity/charging/year_uacs', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'ChargingUACSyear'])->name('fmplanning.ChargingUACSyear');
        Route::get('financial-management/planning-section/processing/activity/charging/year_uacs_saa', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'ChargingUACSyearSAA'])->name('fmplanning.ChargingUACSyearSAA');

        Route::get('financial-management/planning-section/processing/activity/charging/office_uacs', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'ChargingUACSoffice'])->name('fmplanning.ChargingUACSoffice');
        Route::get('financial-management/planning-section/processing/activity/charging/office_uacs_saa', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'ChargingUACSofficeSAA'])->name('fmplanning.ChargingUACSofficeSAA');

    
        
        

        
        Route::get('financial-management/planning-section/processing/activity/get_remaining_balance_allocation_uacs_realignment_year', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'getRemBalrealignAllocUACSyear'])->name('fmplanning.getRemBalrealignyear');
        Route::get('financial-management/planning-section/processing/activity/get_remaining_balance_allocation_uacs_realignment_year_saa', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'getRemBalrealignAllocUACSyearSAA'])->name('fmplanning.getRemBalrealignyearSAA');

        Route::get('financial-management/planning-section/processing/activity/get_remaining_balance_allocation_uacs_realignment_uacs', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'getRemBalrealignAllocUACS'])->name('fmplanning.getRemBalrealignuacs');
        Route::get('financial-management/planning-section/processing/activity/get_remaining_balance_allocation_uacs_realignment_uacs_alloc', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'getRemBalrealignAllocUACSalloc'])->name('fmplanning.getRemBalrealignbal');
        Route::get('financial-management/planning-section/processing/activity/get_remaining_balance_allocation_uacs_realignment_uacs_alloc_saa', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'getRemBalrealignAllocUACSallocSAA'])->name('fmplanning.getRemBalrealignbalSAA');

        Route::get('financial-management/planning-section/processing/activity/get_remaining_balance_allocation_uacs_realignment_uacs_alloc_office', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'getRemBalrealignAllocUACSallocOffice'])->name('fmplanning.getRemBalrealignbalOffice');
        Route::get('financial-management/planning-section/processing/activity/get_remaining_balance_allocation_uacs_realignment_uacs_alloc_office_saa', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'getRemBalrealignAllocUACSallocOfficeSAA'])->name('fmplanning.getRemBalrealignbalOfficeSAA');

        Route::get('financial-management/planning-section/processing/activity/get_remaining_balance_year_allocation_uacs', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'getRemBalyearAllocUACS'])->name('fmplanning.getRemBalyearAllocUACS');
        Route::get('financial-management/planning-section/processing/activity/get_remaining_balance_year_allocation_uacs_saa', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'getRemBalyearAllocUACSSAA'])->name('fmplanning.getRemBalyearAllocUACSSAA');



        Route::get('financial-management/planning-section/processing/uacs/get_remaining_balance', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'getRemBalUACS'])->name('fmplanning.getRemBalUACS');
        Route::get('financial-management/planning-section/processing/uacs/get_remaining_balance_office', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'getRemBalUACSOffice'])->name('fmplanning.getRemBalUACSOffice');
        Route::get('financial-management/planning-section/processing/uacs/get_remaining_balance_year', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'getRemBalyearUACS'])->name('fmplanning.getRemBalyearUACS');


        Route::get('financial-management/planning-section/processing/pap/get_activity', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'getActivity'])->name('fmplanning.getActivity');
        Route::get('financial-management/planning-section/processing/pap/get_activity_allocation', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'getActivityAllocation'])->name('fmplanning.getActivityAllocation');
        Route::get('financial-management/planning-section/processing/pap/get_activity_allocation_saa', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'getActivityAllocationSAA'])->name('fmplanning.getActivityAllocationSAA');


        Route::get('financial-management/accounting-section/processing/uacs/get_uacs', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'getUACS'])->name('fmaccounting.getuacs');
        Route::get('financial-management/create-voucher/get-account-number', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'getAccountNumber'])->name('financialmanagement.getAccountNumber');
        Route::get('financial-management/create-voucher/get-account-address', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'getAccountAddress'])->name('financialmanagement.getAccountAddress');
        Route::put('financial-management/accounting-section/processing/update-signatory/{Route}', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'boxdupdate'])->name('fmaccounting.updatesignatory');

        Route::get('financial-management/planning-section/incoming', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'planningincoming'])->name('fmplanning.incoming');
        Route::get('financial-management/planning-section/accepted', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'planningAccepted'])->name('fmplanning.accepted');
        
        Route::get('financial-management/budget-section/rejected', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'budgetrejected'])->name('fmbudget.rejected');
        Route::get('financial-management/budget-section/outgoing', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'budgetoutgoing'])->name('fmbudget.outgoing');
        Route::get('financial-management/budget-section/incoming', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'budgetincoming'])->name('fmbudget.incoming');
        Route::get('financial-management/budget-section/processing', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'budgetprocessing'])->name('fmbudget.processing');
        Route::post('financial-management/budget-section/processing/add-ors/{Route}', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'budgetORS'])->name('fmbudget.ors');
        Route::post('financial-management/budget-section/processing/delete-ors/{Route}', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'budgetORSdelete'])->name('fmbudget.orsdelete');

        Route::get('financial-management/accounting-section/activity', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'accountingActivity'])->name('fmaccounting.activity');
        Route::get('financial-management/accounting-section/uacs', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'accountingUACS'])->name('fmaccounting.uacs');
        Route::post('financial-management/accounting-section/activity/create', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'accountingActivitycreate'])->name('fmaccounting.activitycreate');
        Route::post('financial-management/accounting-section/uacs/create', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'accountingUACScreate'])->name('fmaccounting.uacscreate');
        Route::post('financial-management/accounting-section/activity/delete/{Activity}', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'accountingActivitydelete'])->name('fmaccounting.activitydelete');
        Route::post('financial-management/accounting-section/uacs/delete/{UACS}', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'accountingUACSdelete'])->name('fmaccounting.uacsdelete');
        Route::post('financial-management/accounting-section/processing/add-accounting-entry/{Route}', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'accountingentry'])->name('fmaccounting.accountingentry');

        Route::get('financial-management/accounting-section/rejected', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'accountingrejected'])->name('fmaccounting.rejected');
        Route::get('financial-management/accounting-section/outgoing', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'accountingoutgoing'])->name('fmaccounting.outgoing');
        Route::get('financial-management/accounting-section/incoming', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'accountingincoming'])->name('fmaccounting.incoming');
        Route::get('financial-management/accounting-section/processing', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'accountingprocessing'])->name('fmaccounting.processing');
        Route::post('financial-management/accounting-section/processing/add-dv/{Route}', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'accountingDV'])->name('fmaccounting.dv');
        Route::post('financial-management/accounting-section/processing/review-of-document/{Route}', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'accountingReview'])->name('fmaccounting.review');
        Route::post('financial-management/accounting-section/processing/delete-dv/{Route}', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'accountingDVdelete'])->name('fmaccounting.dvdelete');
        Route::post('financial-management/accounting-section/processing/delete-accounting-entry/{Route}', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'accountingAccountingEntrydelete'])->name('fmaccounting.accountingentrydelete');

        Route::get('financial-management/cashier-section/account-name', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'cashieraccountname'])->name('fmcashier.accountname');
        
        Route::put('financial-management/cashier-section/account-name_activate/{AccountName}', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'activateaccount'])->name('account.activate');
        Route::put('financial-management/cashier-section/account-name_deactivate/{AccountName}', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'deactivateaccount'])->name('account.deactivate');
        
        Route::get('financial-management/cashier-section/account-number', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'cashieraccountnumber'])->name('fmcashier.accountnumber');
        Route::post('financial-management/cashier-section/account-name/create', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'cashieraccountnamecreate'])->name('fmcashier.accountnamecreate');
        Route::post('financial-management/cashier-section/account-number/create', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'cashieraccountnumbercreate'])->name('fmcashier.accountnumbercreate');
        Route::post('financial-management/cashier-section/account-number/delete/{AccountNumber}', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'cashieraccountnumberdelete'])->name('fmcashier.accountnumberdelete');
        Route::post('financial-management/cashier-section/account-name/delete/{AccountName}', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'cashieraccountnamedelete'])->name('fmcashier.accountnamedelete');

        Route::get('financial-management/box-a', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'boxa'])->name('financial-management.boxa');
        Route::post('financial-management/cashier-section/box-a/create', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'boxacreate'])->name('financial-management.boxacreate');
        Route::post('financial-management/cashier-section/box-a/delete/{BoxA}', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'boxadelete'])->name('financial-management.boxadelete');
    
        Route::get('financial-management/cashier-section/rejected', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'cashierrejected'])->name('fmcashier.rejected');
        Route::get('financial-management/cashier-section/outgoing', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'cashieroutgoing'])->name('fmcashier.outgoing');
        Route::get('financial-management/cashier-section/incoming', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'cashierincoming'])->name('fmcashier.incoming');
        Route::get('financial-management/cashier-section/processing', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'cashierprocessing'])->name('fmcashier.processing');
        Route::post('financial-management/cashier-section/processing/add-cashier/{Route}', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'cashierCashier'])->name('fmcashier.cashier');
        Route::post('financial-management/cashier-section/processing/delete-cashier/{Route}', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'cashierCashierdelete'])->name('fmcashier.cashierdelete');

        Route::get('financial-management/records-section/pending', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'recordspending'])->name('fmrecords.pending');
        Route::get('financial-management/records-section/rejected', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'recordsrejected'])->name('fmrecords.rejected');
        Route::get('financial-management/records-section/outgoing', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'recordsoutgoing'])->name('fmrecords.outgoing');
        Route::get('financial-management/records-section/incoming', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'recordsincoming'])->name('fmrecords.incoming');
        Route::get('financial-management/records-section/processing', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'recordsprocessing'])->name('fmrecords.processing');

        Route::get('financial-management/signatory-section/rejected', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'signatoryrejected'])->name('fmsignatory.rejected');
        Route::get('financial-management/signatory-section/outgoing', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'signatoryoutgoing'])->name('fmsignatory.outgoing');
        Route::get('financial-management/signatory-section/incoming', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'signatoryincoming'])->name('fmsignatory.incoming');
        Route::get('financial-management/signatory-section/processing', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'signatoryprocessing'])->name('fmsignatory.processing');

        Route::get('financial-management/others-section/incoming', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'othersincoming'])->name('fmothers.incoming');
        Route::get('financial-management/others-section/processing', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'othersprocessing'])->name('fmothers.processing');
        Route::post('financial-management//others-section/processing/close/{Route}', [App\Http\Controllers\FinancialManagement\FinancialManagementRouteController::class, 'othersclose'])->name('fmothers.close');
    
        route::get('financial-management/voucher/print/ors/{Voucher}', [FinancialManagementController::class, 'printors'])->name('financial-management.printors');
        route::get('financial-management/voucher/print/dv/{Voucher}', [FinancialManagementController::class, 'printdv'])->name('financial-management.printdv');

        Route::get('financial-management/planning-section/', [FinancialManagementController::class, 'FMPlanning'])->name('financial-management.FMPlanning');
        Route::post('document-tracking/action',[App\Http\Controllers\User\RouteController::class,'close'])->name('route.close');
        Route::post('document-tracking/mail/incoming-document/accept', [App\Http\Controllers\User\RouteController::class, 'acceptIncoming'])->name('route.acceptIncoming');
        Route::post('document-tracking/mail/incoming-document/reject', [App\Http\Controllers\User\RouteController::class, 'rejectIncoming'])->name('route.rejectIncoming');
        Route::resource('document-tracking/route', ('App\Http\Controllers\User\RouteController'));
        Route::get('document-tracking/mail/document-created', [MailController::class, 'documentcreated'])->name('mail.documentcreated');
        Route::get('document-tracking/mail/processing-document', [MailController::class, 'processing'])->name('mail.processing');
        Route::get('document-tracking/mail/incoming-document', [MailController::class, 'incoming'])->name('mail.incoming');
        Route::get('document-tracking/document/view', [DocumentController::class, 'viewdocument'])->name('document-tracking.viewdocument'); 
        Route::get('document-tracking/mail/accepted-document', [MailController::class, 'processing'])->name('mail.accepted');
        Route::get('document-tracking/mail/closed-document', [MailController::class, 'closed'])->name('mail.closed');
        Route::get('document-tracking/mail/outgoing-document', [MailController::class, 'outgoing'])->name('mail.outgoing');
        Route::get('document-tracking/mail/rejected-document', [MailController::class, 'rejected'])->name('mail.rejected');   

        Route::get('change-password', [UserController::class, 'changepassword'])->name('changepassword.index');
        Route::post('change-password/update/{User}', [UserController::class, 'updatepassword'])->name('changepassword.update');

        Route::get('mail/employee-request', [MailController::class, 'employeerequest'])->name('mail.employeerequest');
        Route::get('mail/leave-request', [MailController::class, 'leaverequest'])->name('mail.leaverequest');
        Route::get('mail/travel-order-request/{user}/edit', [MailController::class, 'travelorderedit'])->name('mail.travelorderedit');
        Route::get('mail/travel-order-request', [MailController::class, 'travelorderequest'])->name('mail.travelorderrequest');
        Route::post('daily-time-record/request', [App\Http\Controllers\User\DtrController::class, 'store'])->name('daily-time-record.user.store');
        Route::resource('document-tracking/mail', ('App\Http\Controllers\User\MailController'));
        Route::resource('my-daily-time-record', ('App\Http\Controllers\User\DtrController'));
        Route::post('msd-management/encoder/my-daily-time-record/view/',[App\Http\Controllers\User\DtrController::class, 'print'])->name('my-daily-time-record.print');

        Route::resource('financial-management', ('App\Http\Controllers\FinancialManagement\FinancialManagementController'));
    
        Route::get('financial-management/voucher/{voucher}/view', [FinancialManagementController::class, 'view'])->name('financial-management.view');
        Route::get('financial-management/voucher/view', [FinancialManagementController::class, 'viewvoucher'])->name('financial-management.viewvoucher'); 
        
        Route::post('financial-management/route', ['App\Http\Controllers\FinancialManagement\FinancialManagementRouteController','store'])->name('fmroute.store');
        Route::post('financial-management/route-by-ada', ['App\Http\Controllers\FinancialManagement\FinancialManagementRouteController','storebyAda'])->name('fmroutebyada.store');
    
        route::group([
            'auth' => 'admin',

        ], function() {

            route::get('document-tracking/document/{Document}', [DocumentController::class, 'view'])->name('document-tracking.view'); 
            Route::get('document-tracking/document/view', [DocumentController::class, 'viewdocument'])->name('document-tracking.viewdocument'); 
            route::get('document-tracking/{Document}/edit', [DocumentController::class, 'edit']);
            route::get('document-tracking/attachment/view/{Document}', [AttachmentController::class, 'view'])->name('attachment.view');
            Route::resource('document-tracking', ('App\Http\Controllers\DocumentTracking\DocumentController'));  
            Route::resource('document-tracking/attachment', ('App\Http\Controllers\DocumentTracking\AttachmentController'));  
            route::get('document-tracking/{Document}/print', [DocumentController::class, 'print'])->name('document-tracking.print');
            Route::resource('msd-management/encoder/employee', ('App\Http\Controllers\Admin\EmployeeController'));
            Route::resource('data-management/employee/office', ('App\Http\Controllers\Admin\OfficeController'));
            Route::resource('data-management/employee/section', ('App\Http\Controllers\Admin\SectionController'));
            Route::resource('data-management/employee/unit', ('App\Http\Controllers\Admin\UnitController'));
            Route::resource('data-management/user/user', ('App\Http\Controllers\Auth\UserController'));

            Route::resource('document-tracking/route', ('App\Http\Controllers\User\RouteController'));
            // Route::post('document-tracking/route/delete/{Route}', [Route::class,'destroyroute'])->name('route.destroyroute'); 

            Route::resource('data-management/user/role', ('App\Http\Controllers\Admin\RolesController'));
            Route::resource('msd-management/encoder/daily-time-record', ('App\Http\Controllers\Msd\DtrController')); 
            Route::resource('msd-management/event', ('App\Http\Controllers\Msd\EventController'));
            route::get('document-tracking/event/atttachment/{Event}/view/', [EventController::class, 'viewattachment'])->name('eventattachment.view');
            Route::post('msd-management/encoder/daily-time-record/absent', [DtrController::class, 'storeabsent'])->name('daily-time-record.storeabsent'); 
            Route::post('msd-management/encoder/daily-time-record/event', [DtrController::class, 'storeevent'])->name('daily-time-record.storeevent'); 
            route::post('msd-management/encoder/daily-time-record/view/',[DtrController::class, 'print'])->name('daily-time-record.print');
            route::get('data-management/user/role/edit/{User}', [RolesController::class, 'edit'])->name('role.edit');
            Route::resource('msd-management/encoder/leave-management', ('App\Http\Controllers\Msd\LeaveController')); 
            Route::resource('msd-management/encoder/travel-order', ('App\Http\Controllers\Msd\TravelOrderController')); 
            Route::resource('msd-management/settings/leave-mgmt', ('App\Http\Controllers\Msd\LeaveTypeController')); 
            route::get('leave/{Leave}/print', [LeaveController::class, 'print'])->name('leave.print');
            route::put('leave/{Leave}/accept', [LeaveController::class, 'accept'])->name('leave.accept');
            route::put('leave/{Leave}/reject', [LeaveController::class, 'reject'])->name('leave.reject');
            route::get('leave-management', [LeaveController::class, 'userindex'])->name('userleave.index');
            route::post('leave-management/create', [LeaveController::class, 'storeUserLeave'])->name('userleave.storeUserLeave');
            route::get('leave-management/summary', [LeaveController::class, 'summary'])->name('leave.summary');
            route::get('leave-management/summary/filtered', [LeaveController::class, 'summaryfilter'])->name('leave.summaryfilter');
            Route::resource('msd-management/settings/leave-settings/leave-signatory', ('App\Http\Controllers\Msd\LeaveSignatoryController'));     
            Route::resource('msd-management/settings/leave-settings/set-leave-signatory', ('App\Http\Controllers\Msd\SetLeaveSignatoryController'));
            Route::resource('msd-management/settings/travel-order-settings/travel-order-signatory', ('App\Http\Controllers\Msd\TravelOrderSignatoryController'));    
            Route::resource('msd-management/settings/travel-order-settings/set-travel-order-signatory', ('App\Http\Controllers\Msd\SetTravelOrderSignatoryController'));  
            route::put('travel-order/{TravelOrder}/accept', [TravelOrderController::class, 'accept'])->name('travel-order.accept');
            route::put('travel-order/{TravelOrder}/reject', [TravelOrderController::class, 'reject'])->name('travel-order.reject');
            route::get('travel-order-management', [TravelOrderController::class, 'userindex'])->name('usertravelorder.index');
            route::post('travel-order-management/create', [TravelOrderController::class, 'storeUserTravelOrder'])->name('userTravelOrder.storeUserTravelOrder');
            route::get('travel-order/{TravelOrder}/print', [TravelOrderController::class, 'print'])->name('travelorder.print');
            route::post('msd-management/travel-order/advance/',[TravelOrderController::class, 'advance'])->name('travel-order.advance');
            route::post('msd-management/leave/advance/',[LeaveController::class, 'advance'])->name('leave.advance');

        
            route::get('msd-management/dtr-signatory/', [DtrSignatoryController::class, 'index'])->name('dtr-signatory.index');
            route::post('msd-management/dtr-signatory/add-signatory', [DtrSignatoryController::class, 'store'])->name('dtr-signatory.store');
            route::post('msd-management/dtr-signatory/{DtrSignatory}update-signatory', [DtrSignatoryController::class, 'update'])->name('dtr-signatory.update');

            route::post('msd-management/dtr/upload', [DtrController::class, 'uploadDTR'])->name('upload.dtr');
        });

        route::group([
            'auth' => 'records',

        ], function() {
            route::get('document-tracking/{Document}/edit', [DocumentController::class, 'edit']);
            route::get('document-tracking/{Document}/view', [DocumentController::class, 'view']); 
            route::get('document-tracking/attachment/view/{Document}', [AttachmentController::class, 'view'])->name('attachment.view');
            Route::resource('document-tracking', ('App\Http\Controllers\DocumentTracking\DocumentController'));  
            Route::resource('document-tracking/attachment', ('App\Http\Controllers\DocumentTracking\AttachmentController'));  
            route::get('document-tracking/{Document}/print', [DocumentController::class, 'print'])->name('document-tracking.print'); 

        });

        route::group([
            'auth' => 'msd',

        ], function() {
        
            Route::resource('msd-management/encoder/daily-time-record', ('App\Http\Controllers\Msd\DtrController'));  
            
            Route::post('msd-management/encoder/daily-time-record/absent', [DtrController::class, 'storeabsent'])->name('daily-time-record.storeabsent'); 
            Route::post('msd-management/encoder/daily-time-record/event', [DtrController::class, 'storeevent'])->name('daily-time-record.storeevent'); 
            Route::resource('msd-management/event', ('App\Http\Controllers\Msd\EventController'));         
            route::get('document-tracking/event/atttachment/{Event}/view/', [EventController::class, 'viewattachment'])->name('eventattachment.view');
            Route::resource('msd-management/encoder/leave-management', ('App\Http\Controllers\Msd\LeaveController'));
            Route::resource('msd-management/settings/leave-settings/leave-mgmt', ('App\Http\Controllers\Msd\LeaveTypeController'));  
            Route::resource('msd-management/settings/leave-settings/leave-signatory', ('App\Http\Controllers\Msd\LeaveSignatoryController'));     
            Route::resource('msd-management/settings/leave-settings/set-leave-signatory', ('App\Http\Controllers\Msd\SetLeaveSignatoryController'));
            Route::resource('msd-management/settings/leave-settings/leave-mgmt', ('App\Http\Controllers\Msd\LeaveTypeController'));   
            Route::resource('msd-management/settings/travel-order-settings/travel-order-signatory', ('App\Http\Controllers\Msd\TravelOrderSignatoryController'));     
            Route::resource('msd-management/settings/travel-order-settings/set-travel-order-signatory', ('App\Http\Controllers\Msd\SetTravelOrderSignatoryController'));  
            route::put('travel-order/{TravelOrder}/accept', [TravelOrderController::class, 'accept'])->name('travel-order.accept');
            route::put('travel-order/{TravelOrder}/reject', [TravelOrderController::class, 'reject'])->name('travel-order.reject');
            route::get('travel-order-management', [TravelOrderController::class, 'userindex'])->name('usertravelorder.index');
            route::post('travel-order-management/create', [TravelOrderController::class, 'storeUserTravelOrder'])->name('userTravelOrder.storeUserTravelOrder');
            route::post('msd-management/encoder/daily-time-record/view/',[DtrController::class, 'print'])->name('daily-time-record.print');
            route::post('daily-time-record/view/',[UserDtrController::class, 'print'])->name('my-daily-time-record.print');
            route::get('daily-time-record/request/',[UserDtrController::class, 'request'])->name('my-daily-time-record.request');
            route::post('msd-management/travel-order/advance/',[TravelOrderController::class, 'advance'])->name('travel-order.advance');
            route::post('msd-management/leave/advance/',[LeaveController::class, 'advance'])->name('leave.advance');
            route::get('msd-management/dtr-signatory/', [DtrSignatoryController::class, 'index'])->name('dtr-signatory.index');
            route::post('msd-management/dtr-signatory/add-signatory', [DtrSignatoryController::class, 'store'])->name('dtr-signatory.store');
            route::post('msd-management/dtr-signatory/{DtrSignatory}update-signatory', [DtrSignatoryController::class, 'update'])->name('dtr-signatory.update');
        });

    });
// });