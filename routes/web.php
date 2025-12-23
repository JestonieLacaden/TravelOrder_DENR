<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\UserController;

use App\Http\Controllers\User\MailController;
use App\Http\Controllers\User\DtrController as UserDtrController;

use App\Http\Controllers\DocumentTracking\DocumentController;
use App\Http\Controllers\DocumentTracking\AttachmentController;

use App\Http\Controllers\FinancialManagement\FinancialManagementController;
use App\Http\Controllers\FinancialManagement\FinancialManagementRouteController;

use App\Http\Controllers\Msd\DtrController;
use App\Http\Controllers\Msd\DtrSignatoryController;
use App\Http\Controllers\Msd\EventController;
use App\Http\Controllers\Msd\LeaveController;
use App\Http\Controllers\Msd\TravelOrderController;

use App\Http\Controllers\Admin\RolesController;
// Optional: kung ginagamit mo ito sa ibang lugar
use App\Http\Controllers\EmployeeSignatureController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Auth / dashboard
Route::get('/', [AuthController::class, 'dashboard'])->middleware('auth');
Route::get('dashboard', [AuthController::class, 'dashboard'])->middleware('auth')->name('dashboard');
Route::post('custom-login', [AuthController::class, 'customLogin'])->name('login.custom');
Route::get('login', [AuthController::class, 'index'])->name('login');
Route::get('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::group(['middleware' => 'auth'], function () {

    /*
    |----------------------------------------------------------------------
    | Financial Management (single declarations only)
    |----------------------------------------------------------------------
    */
    Route::get('financial-management/allocation-report', [FinancialManagementController::class, 'allocationreport'])->name('financial-management.Allocationreport');
    Route::get('financial-management/allocation-uacs-report', [FinancialManagementController::class, 'allocationUACSreport'])->name('financial-management.AllocationUACSreport');
    Route::get('financial-management/allocation-payee-report', [FinancialManagementController::class, 'allocationPayeereport'])->name('financial-management.AllocationPayeereport');
    Route::get('financial-management/allocation-pap-report', [FinancialManagementController::class, 'allocationPAPreport'])->name('financial-management.AllocationPAPreport');
    Route::get('financial-management/financial_tracking', [FinancialManagementController::class, 'FinancialTracking'])->name('financial-management.FinancialTracking');
    Route::get('financial-management/realignment_report', [FinancialManagementController::class, 'RealignmentReport'])->name('financial-management.RealignmentReport');

    Route::post('financial-management/signatory-section/signatory-approve{Route}', [FinancialManagementRouteController::class, 'SignatoryApprove'])->name('fm.signatoryapprove');
    Route::post('financial-management/planning-section/accept-signature', [FinancialManagementController::class, 'acceptIncomingSignature'])->name('fm.signatureaccept');
    Route::post('financial-management/planning-section/accept-by-batch', [FinancialManagementController::class, 'acceptIncomingByBatch'])->name('fm.acceptbybatch');
    Route::post('financial-management/planning-section/accept', [FinancialManagementController::class, 'acceptIncoming'])->name('fm.accept');

    Route::get('financial-management/planning-section/pap', [FinancialManagementRouteController::class, 'planningPAP'])->name('fmplanning.pap');
    Route::post('financial-management/planning-section/pap/create', [FinancialManagementRouteController::class, 'planningPAPcreate'])->name('fmplanning.papcreate');
    Route::post('financial-management/planning-section/pap/{PAP}/delete', [FinancialManagementRouteController::class, 'planningPAPdelete'])->name('fmplanning.papdelete');

    Route::get('financial-management/planning-section/allocation', [FinancialManagementRouteController::class, 'planningAllocation'])->name('fmplanning.allocation');
    Route::get('financial-management/planning-section/allocation-saa', [FinancialManagementRouteController::class, 'planningAllocationSAA'])->name('fmplanning.allocationsaa');
    Route::post('financial-management/planning-section/allocation/create', [FinancialManagementRouteController::class, 'planningAllocationcreate'])->name('fmplanning.allocationcreate');
    Route::post('financial-management/planning-section/allocation-saa/create', [FinancialManagementRouteController::class, 'planningAllocationcreateSAA'])->name('fmplanning.allocationcreateSAA');
    Route::post('financial-management/planning-section/allocation/{Allocation}/delete', [FinancialManagementRouteController::class, 'planningAllocationdelete'])->name('fmplanning.allocationdelete');
    Route::post('financial-management/planning-section/allocation-saa/{Allocation}/delete', [FinancialManagementRouteController::class, 'planningAllocationdeleteSAA'])->name('fmplanning.allocationdeleteSAA');

    Route::get('financial-management/planning-section/allocation-uacs', [FinancialManagementRouteController::class, 'planningAllocationUACS'])->name('fmplanning.allocationUACS');
    Route::get('financial-management/planning-section/allocation-uacs-saa', [FinancialManagementRouteController::class, 'planningAllocationUACSSAA'])->name('fmplanning.allocationUACSSAA');
    Route::post('financial-management/planning-section/allocation-uacs/create', [FinancialManagementRouteController::class, 'planningAllocationcreateUACS'])->name('fmplanning.allocationcreateUACS');
    Route::post('financial-management/planning-section/allocation-uacs-saa/create', [FinancialManagementRouteController::class, 'planningAllocationcreateUACSSAA'])->name('fmplanning.allocationcreateUACSSAA');
    Route::post('financial-management/planning-section/allocation-uacs/{Allocation}/delete', [FinancialManagementRouteController::class, 'planningAllocationdeleteUACS'])->name('fmplanning.allocationdeleteUACS');
    Route::post('financial-management/planning-section/allocation-uacs-saa/{Allocation}/delete', [FinancialManagementRouteController::class, 'planningAllocationdeleteUACSSAA'])->name('fmplanning.allocationdeleteUACSSAA');

    Route::post('financial-management/planning-section/allocation-uacs/realignment', [FinancialManagementRouteController::class, 'planningrealignmentUACS'])->name('fmplanning.realignmentUACS');
    Route::post('financial-management/planning-section/allocation-uacs-saa/realignment', [FinancialManagementRouteController::class, 'planningrealignmentUACSSAA'])->name('fmplanning.realignmentUACSSAA');

    Route::get('financial-management/planning-section/allocation-pap', [FinancialManagementRouteController::class, 'planningAllocationPAP'])->name('fmplanning.allocationPAP');
    Route::get('financial-management/planning-section/allocation-pap-saa', [FinancialManagementRouteController::class, 'planningAllocationPAPSAA'])->name('fmplanning.allocationPAPsaa');
    Route::post('financial-management/planning-section/allocation-pap/create', [FinancialManagementRouteController::class, 'planningAllocationcreatePAP'])->name('fmplanning.allocationcreatePAP');
    Route::post('financial-management/planning-section/allocation-pap-saa/create', [FinancialManagementRouteController::class, 'planningAllocationcreatePAPSAA'])->name('fmplanning.allocationcreatePAPSAA');
    Route::post('financial-management/planning-section/allocation-pap/{Allocation}/delete', [FinancialManagementRouteController::class, 'planningAllocationdeletePAP'])->name('fmplanning.allocationdeletePAP');
    Route::post('financial-management/planning-section/allocation-pap-saa/{Allocation}/delete', [FinancialManagementRouteController::class, 'planningAllocationdeletePAPSAA'])->name('fmplanning.allocationdeletePAPSAA');

    Route::get('financial-management/planning-section/uacs', [FinancialManagementRouteController::class, 'planningUACS'])->name('fmplanning.uacs');
    Route::post('financial-management/planning-section/uacs/create', [FinancialManagementRouteController::class, 'planningUACScreate'])->name('fmplanning.uacscreate');
    Route::post('financial-management/planning-section/uacs/{UACS}/delete', [FinancialManagementRouteController::class, 'planningUACSdelete'])->name('fmplanning.uacsdelete');

    Route::get('financial-management/planning-section/activity', [FinancialManagementRouteController::class, 'planningActivity'])->name('fmplanning.activity');
    Route::post('financial-management/planning-section/activity/create', [FinancialManagementRouteController::class, 'planningActivitycreate'])->name('fmplanning.activitycreate');
    Route::post('financial-management/planning-section/activity/{Activity}/delete', [FinancialManagementRouteController::class, 'planningActivitydelete'])->name('fmplanning.activitydelete');

    Route::post('financial-management/planning-section/reject', [FinancialManagementController::class, 'rejectIncoming'])->name('fm.reject');

    Route::get('financial-management/planning-section/processing/activity/get_remaining_balance', [FinancialManagementRouteController::class, 'getRemBal'])->name('fmplanning.getRemBal');
    Route::get('financial-management/planning-section/processing/activity/get_remaining_balance_office', [FinancialManagementRouteController::class, 'getRemBalOffice'])->name('fmplanning.getRemBalOffice');
    Route::get('financial-management/planning-section/processing/activity/get_remaining_balance_year', [FinancialManagementRouteController::class, 'getRemBalyear'])->name('fmplanning.getRemBalyear');

    Route::get('financial-management/planning-section/processing/activity/get_remaining_balance_allocation_pap', [FinancialManagementRouteController::class, 'getRemBalAllocPAP'])->name('fmplanning.getRemBalAllocPAP');
    Route::get('financial-management/planning-section/processing/activity/get_remaining_balance_allocation_pap_saa', [FinancialManagementRouteController::class, 'getRemBalAllocPAPSAA'])->name('fmplanning.getRemBalAllocPAPSAA');
    Route::get('financial-management/planning-section/processing/activity/get_remaining_balance_allocation_pap_office', [FinancialManagementRouteController::class, 'getRemBalAllocPAPoffice'])->name('fmplanning.getRemBalAllocPAPoffice');
    Route::get('financial-management/planning-section/processing/activity/get_remaining_balance_allocation_pap_office_saa', [FinancialManagementRouteController::class, 'getRemBalAllocPAPofficeSAA'])->name('fmplanning.getRemBalAllocPAPofficeSAA');

    Route::get('financial-management/planning-section/processing/activity/get_remaining_balance_allocation_pap_year', [FinancialManagementRouteController::class, 'getRemBalAllocPAPyear'])->name('fmplanning.getRemBalAllocPAPyear');
    Route::get('financial-management/planning-section/processing/activity/get_remaining_balance_allocation_pap_year_saa', [FinancialManagementRouteController::class, 'getRemBalAllocPAPyearSAA'])->name('fmplanning.getRemBalAllocPAPyearSAA');

    Route::get('financial-management/planning-section/processing/activity/get_remaining_balance_allocation_uacs', [FinancialManagementRouteController::class, 'getRemBalAllocUACS'])->name('fmplanning.getRemBalAllocUACS');
    Route::get('financial-management/planning-section/processing/activity/get_remaining_balance_allocation_uacs_saa', [FinancialManagementRouteController::class, 'getRemBalAllocUACSSAA'])->name('fmplanning.getRemBalAllocUACSSAA');
    Route::get('financial-management/planning-section/processing/activity/get_remaining_balance_allocation_uacs_year', [FinancialManagementRouteController::class, 'getRemBalAllocUACSyear'])->name('fmplanning.getRemBalAllocUACSyear');
    Route::get('financial-management/planning-section/processing/activity/get_remaining_balance_allocation_uacs_year_saa', [FinancialManagementRouteController::class, 'getRemBalAllocUACSyearSAA'])->name('fmplanning.getRemBalAllocUACSyearSAA');

    Route::get('financial-management/planning-section/processing/activity/get_remaining_balance_allocation_uacs_expense', [FinancialManagementRouteController::class, 'getRemBalrealignyexpense'])->name('fmplanning.getRemBalrealignyexpense');
    Route::get('financial-management/planning-section/processing/activity/get_remaining_balance_allocation_uacs_office', [FinancialManagementRouteController::class, 'getRemBalrealignoffice'])->name('fmplanning.getRemBalrealignoffice');
    Route::get('financial-management/planning-section/processing/activity/get_remaining_balance_allocation_uacs_office_saa', [FinancialManagementRouteController::class, 'getRemBalrealignofficeSAA'])->name('fmplanning.getRemBalrealignofficeSAA');

    Route::get('financial-management/planning-section/processing/activity/get_remaining_balance_allocation_uacs_realign_year', [FinancialManagementRouteController::class, 'getRemBalrealign_year'])->name('fmplanning.getRemBalrealign_year');
    Route::get('financial-management/planning-section/processing/activity/get_remaining_balance_allocation_uacs_realign_year_saa', [FinancialManagementRouteController::class, 'getRemBalrealign_yearSAA'])->name('fmplanning.getRemBalrealign_yearSAA');

    Route::get('financial-management/planning-section/processing/activity/charging/expense_class', [FinancialManagementRouteController::class, 'ChargingExpenseClass'])->name('fmplanning.ChargingExpenseClass');
    Route::get('financial-management/planning-section/processing/activity/charging/expense_class_saa', [FinancialManagementRouteController::class, 'ChargingExpenseClassSAA'])->name('fmplanning.ChargingExpenseClassSAA');

    Route::get('financial-management/planning-section/processing/activity/charging/activity', [FinancialManagementRouteController::class, 'ChargingActivity'])->name('fmplanning.ChargingActivity');
    Route::get('financial-management/planning-section/processing/activity/charging/activity_saa', [FinancialManagementRouteController::class, 'ChargingActivitySAA'])->name('fmplanning.ChargingActivitySAA');

    Route::get('financial-management/planning-section/processing/activity/charging/year', [FinancialManagementRouteController::class, 'ChargingYear'])->name('fmplanning.ChargingYear');
    Route::get('financial-management/planning-section/processing/activity/charging/year_saa', [FinancialManagementRouteController::class, 'ChargingYearSAA'])->name('fmplanning.ChargingYearSAA');

    Route::get('financial-management/planning-section/processing/activity/charging/office', [FinancialManagementRouteController::class, 'ChargingOffice'])->name('fmplanning.ChargingOffice');
    Route::get('financial-management/planning-section/processing/activity/charging/office_saa', [FinancialManagementRouteController::class, 'ChargingOfficeSAA'])->name('fmplanning.ChargingOfficeSAA');

    Route::get('financial-management/planning-section/processing/activity/charging/expense_class_uacs', [FinancialManagementRouteController::class, 'ChargingExpenseClassUACS'])->name('fmplanning.ChargingExpenseClassUACS');
    Route::get('financial-management/planning-section/processing/activity/charging/expense_class_uacs_saa', [FinancialManagementRouteController::class, 'ChargingExpenseClassUACSSAA'])->name('fmplanning.ChargingExpenseClassUACSSAA');

    Route::get('financial-management/planning-section/processing/activity/charing/uacs', [FinancialManagementRouteController::class, 'ChargingUACS'])->name('fmplanning.ChargingUACS');
    Route::get('financial-management/planning-section/processing/activity/charing/uacs_saa', [FinancialManagementRouteController::class, 'ChargingUACSSAA'])->name('fmplanning.ChargingUACSSAA');

    Route::get('financial-management/planning-section/processing/activity/charging/year_uacs', [FinancialManagementRouteController::class, 'ChargingUACSyear'])->name('fmplanning.ChargingUACSyear');
    Route::get('financial-management/planning-section/processing/activity/charging/year_uacs_saa', [FinancialManagementRouteController::class, 'ChargingUACSyearSAA'])->name('fmplanning.ChargingUACSyearSAA');

    Route::get('financial-management/planning-section/processing/activity/charging/office_uacs', [FinancialManagementRouteController::class, 'ChargingUACSoffice'])->name('fmplanning.ChargingUACSoffice');
    Route::get('financial-management/planning-section/processing/activity/charging/office_uacs_saa', [FinancialManagementRouteController::class, 'ChargingUACSofficeSAA'])->name('fmplanning.ChargingUACSofficeSAA');

    Route::get('financial-management/planning-section/processing/activity/get_remaining_balance_allocation_uacs_realignment_year', [FinancialManagementRouteController::class, 'getRemBalrealignAllocUACSyear'])->name('fmplanning.getRemBalrealignyear');
    Route::get('financial-management/planning-section/processing/activity/get_remaining_balance_allocation_uacs_realignment_year_saa', [FinancialManagementRouteController::class, 'getRemBalrealignAllocUACSyearSAA'])->name('fmplanning.getRemBalrealignyearSAA');

    Route::get('financial-management/planning-section/processing/activity/get_remaining_balance_allocation_uacs_realignment_uacs', [FinancialManagementRouteController::class, 'getRemBalrealignAllocUACS'])->name('fmplanning.getRemBalrealignuacs');
    Route::get('financial-management/planning-section/processing/activity/get_remaining_balance_allocation_uacs_realignment_uacs_alloc', [FinancialManagementRouteController::class, 'getRemBalrealignAllocUACSalloc'])->name('fmplanning.getRemBalrealignbal');
    Route::get('financial-management/planning-section/processing/activity/get_remaining_balance_allocation_uacs_realignment_uacs_alloc_saa', [FinancialManagementRouteController::class, 'getRemBalrealignAllocUACSallocSAA'])->name('fmplanning.getRemBalrealignbalSAA');

    Route::get('financial-management/planning-section/processing/activity/get_remaining_balance_allocation_uacs_realignment_uacs_alloc_office', [FinancialManagementRouteController::class, 'getRemBalrealignAllocUACSallocOffice'])->name('fmplanning.getRemBalrealignbalOffice');
    Route::get('financial-management/planning-section/processing/activity/get_remaining_balance_allocation_uacs_realignment_uacs_alloc_office_saa', [FinancialManagementRouteController::class, 'getRemBalrealignAllocUACSallocOfficeSAA'])->name('fmplanning.getRemBalrealignbalOfficeSAA');

    Route::get('financial-management/planning-section/', [FinancialManagementController::class, 'FMPlanning'])->name('financial-management.FMPlanning');

    Route::get('financial-management/planning-section/rejected', [FinancialManagementRouteController::class, 'planningrejected'])->name('fmplanning.rejected');
    Route::get('financial-management/planning-section/outgoing', [FinancialManagementRouteController::class, 'planningoutgoing'])->name('fmplanning.outgoing');
    Route::get('financial-management/planning-section/processing', [FinancialManagementRouteController::class, 'planningprocessing'])->name('fmplanning.processing');

    // Cashier / Budget / Accounting / Records / Signatory / Others – (kept as-is)
    Route::get('financial-management/budget-section/rejected', [FinancialManagementRouteController::class, 'budgetrejected'])->name('fmbudget.rejected');
    Route::get('financial-management/budget-section/outgoing', [FinancialManagementRouteController::class, 'budgetoutgoing'])->name('fmbudget.outgoing');
    Route::get('financial-management/budget-section/incoming', [FinancialManagementRouteController::class, 'budgetincoming'])->name('fmbudget.incoming');
    Route::get('financial-management/budget-section/processing', [FinancialManagementRouteController::class, 'budgetprocessing'])->name('fmbudget.processing');
    Route::post('financial-management/budget-section/processing/add-ors/{Route}', [FinancialManagementRouteController::class, 'budgetORS'])->name('fmbudget.ors');
    Route::post('financial-management/budget-section/processing/delete-ors/{Route}', [FinancialManagementRouteController::class, 'budgetORSdelete'])->name('fmbudget.orsdelete');

    Route::get('financial-management/accounting-section/activity', [FinancialManagementRouteController::class, 'accountingActivity'])->name('fmaccounting.activity');
    Route::get('financial-management/accounting-section/uacs', [FinancialManagementRouteController::class, 'accountingUACS'])->name('fmaccounting.uacs');
    Route::post('financial-management/accounting-section/activity/create', [FinancialManagementRouteController::class, 'accountingActivitycreate'])->name('fmaccounting.activitycreate');
    Route::post('financial-management/accounting-section/uacs/create', [FinancialManagementRouteController::class, 'accountingUACScreate'])->name('fmaccounting.uacscreate');
    Route::post('financial-management/accounting-section/activity/delete/{Activity}', [FinancialManagementRouteController::class, 'accountingActivitydelete'])->name('fmaccounting.activitydelete');
    Route::post('financial-management/accounting-section/uacs/delete/{UACS}', [FinancialManagementRouteController::class, 'accountingUACSdelete'])->name('fmaccounting.uacsdelete');
    Route::post('financial-management/accounting-section/processing/add-accounting-entry/{Route}', [FinancialManagementRouteController::class, 'accountingentry'])->name('fmaccounting.accountingentry');

    Route::get('financial-management/accounting-section/rejected', [FinancialManagementRouteController::class, 'accountingrejected'])->name('fmaccounting.rejected');
    Route::get('financial-management/accounting-section/outgoing', [FinancialManagementRouteController::class, 'accountingoutgoing'])->name('fmaccounting.outgoing');
    Route::get('financial-management/accounting-section/incoming', [FinancialManagementRouteController::class, 'accountingincoming'])->name('fmaccounting.incoming');
    Route::get('financial-management/accounting-section/processing', [FinancialManagementRouteController::class, 'accountingprocessing'])->name('fmaccounting.processing');
    Route::post('financial-management/accounting-section/processing/add-dv/{Route}', [FinancialManagementRouteController::class, 'accountingDV'])->name('fmaccounting.dv');
    Route::post('financial-management/accounting-section/processing/review-of-document/{Route}', [FinancialManagementRouteController::class, 'accountingReview'])->name('fmaccounting.review');
    Route::post('financial-management/accounting-section/processing/delete-dv/{Route}', [FinancialManagementRouteController::class, 'accountingDVdelete'])->name('fmaccounting.dvdelete');
    Route::post('financial-management/accounting-section/processing/delete-accounting-entry/{Route}', [FinancialManagementRouteController::class, 'accountingAccountingEntrydelete'])->name('fmaccounting.accountingentrydelete');

    Route::get('financial-management/cashier-section/account-name', [FinancialManagementRouteController::class, 'cashieraccountname'])->name('fmcashier.accountname');
    Route::put('financial-management/cashier-section/account-name_activate/{AccountName}', [FinancialManagementRouteController::class, 'activateaccount'])->name('account.activate');
    Route::put('financial-management/cashier-section/account-name_deactivate/{AccountName}', [FinancialManagementRouteController::class, 'deactivateaccount'])->name('account.deactivate');

    Route::get('financial-management/cashier-section/account-number', [FinancialManagementRouteController::class, 'cashieraccountnumber'])->name('fmcashier.accountnumber');
    Route::post('financial-management/cashier-section/account-name/create', [FinancialManagementRouteController::class, 'cashieraccountnamecreate'])->name('fmcashier.accountnamecreate');
    Route::post('financial-management/cashier-section/account-number/create', [FinancialManagementRouteController::class, 'cashieraccountnumbercreate'])->name('fmcashier.accountnumbercreate');
    Route::post('financial-management/cashier-section/account-number/delete/{AccountNumber}', [FinancialManagementRouteController::class, 'cashieraccountnumberdelete'])->name('fmcashier.accountnumberdelete');
    Route::post('financial-management/cashier-section/account-name/delete/{AccountName}', [FinancialManagementRouteController::class, 'cashieraccountnamedelete'])->name('fmcashier.accountnamedelete');

    Route::get('financial-management/box-a', [FinancialManagementRouteController::class, 'boxa'])->name('financial-management.boxa');
    Route::post('financial-management/cashier-section/box-a/create', [FinancialManagementRouteController::class, 'boxacreate'])->name('financial-management.boxacreate');
    Route::post('financial-management/cashier-section/box-a/delete/{BoxA}', [FinancialManagementRouteController::class, 'boxadelete'])->name('financial-management.boxadelete');

    Route::get('financial-management/records-section/pending', [FinancialManagementRouteController::class, 'recordspending'])->name('fmrecords.pending');
    Route::get('financial-management/records-section/rejected', [FinancialManagementRouteController::class, 'recordsrejected'])->name('fmrecords.rejected');
    Route::get('financial-management/records-section/outgoing', [FinancialManagementRouteController::class, 'recordsoutgoing'])->name('fmrecords.outgoing');
    Route::get('financial-management/records-section/incoming', [FinancialManagementRouteController::class, 'recordsincoming'])->name('fmrecords.incoming');
    Route::get('financial-management/records-section/processing', [FinancialManagementRouteController::class, 'recordsprocessing'])->name('fmrecords.processing');

    Route::get('financial-management/signatory-section/rejected', [FinancialManagementRouteController::class, 'signatoryrejected'])->name('fmsignatory.rejected');
    Route::get('financial-management/signatory-section/outgoing', [FinancialManagementRouteController::class, 'signatoryoutgoing'])->name('fmsignatory.outgoing');
    Route::get('financial-management/signatory-section/incoming', [FinancialManagementRouteController::class, 'signatoryincoming'])->name('fmsignatory.incoming');
    Route::get('financial-management/signatory-section/processing', [FinancialManagementRouteController::class, 'signatoryprocessing'])->name('fmsignatory.processing');

    Route::get('financial-management/others-section/incoming', [FinancialManagementRouteController::class, 'othersincoming'])->name('fmothers.incoming');
    Route::get('financial-management/others-section/processing', [FinancialManagementRouteController::class, 'othersprocessing'])->name('fmothers.processing');
    Route::post('financial-management//others-section/processing/close/{Route}', [FinancialManagementRouteController::class, 'othersclose'])->name('fmothers.close');

    Route::get('financial-management/voucher/{voucher}/view', [FinancialManagementController::class, 'view'])->name('financial-management.view');
    Route::get('financial-management/voucher/view', [FinancialManagementController::class, 'viewvoucher'])->name('financial-management.viewvoucher');

    Route::post('financial-management/route', [FinancialManagementRouteController::class, 'store'])->name('fmroute.store');
    Route::post('financial-management/route-by-ada', [FinancialManagementRouteController::class, 'storebyAda'])->name('fmroutebyada.store');

    // Printing
    Route::get('financial-management/voucher/print/ors/{Voucher}', [FinancialManagementController::class, 'printors'])->name('financial-management.printors');
    Route::get('financial-management/voucher/print/dv/{Voucher}', [FinancialManagementController::class, 'printdv'])->name('financial-management.printdv');

    /*
    |----------------------------------------------------------------------
    | Document Tracking (user)
    |----------------------------------------------------------------------
    */
    Route::post('document-tracking/action', [App\Http\Controllers\User\RouteController::class, 'close'])->name('route.close');
    Route::post('document-tracking/mail/incoming-document/accept', [App\Http\Controllers\User\RouteController::class, 'acceptIncoming'])->name('route.acceptIncoming');
    Route::post('document-tracking/mail/incoming-document/reject', [App\Http\Controllers\User\RouteController::class, 'rejectIncoming'])->name('route.rejectIncoming');

    // NOTE: iniwan ko lang itong resource na ito (Routes). Huwag nang ulitin sa ibang group.
    Route::resource('document-tracking/route', App\Http\Controllers\User\RouteController::class);

    // Mail (no resource to avoid conflict)
    Route::get('document-tracking/mail', [MailController::class, 'index'])->name('mails.index');
    Route::get('document-tracking/mail/document-created', [MailController::class, 'documentcreated'])->name('mail.documentcreated');
    Route::get('document-tracking/mail/processing-document', [MailController::class, 'processing'])->name('mail.processing');
    Route::get('document-tracking/mail/incoming-document', [MailController::class, 'incoming'])->name('mail.incoming');
    Route::get('document-tracking/mail/accepted-document', [MailController::class, 'processing'])->name('mail.accepted');
    Route::get('document-tracking/mail/closed-document', [MailController::class, 'closed'])->name('mail.closed');
    Route::get('document-tracking/mail/outgoing-document', [MailController::class, 'outgoing'])->name('mail.outgoing');
    Route::get('document-tracking/mail/rejected-document', [MailController::class, 'rejected'])->name('mail.rejected');

    // Document view helpers
    Route::get('document-tracking/document/view', [DocumentController::class, 'viewdocument'])->name('document-tracking.viewdocument');

    /*
    |----------------------------------------------------------------------
    | Change password
    |----------------------------------------------------------------------
    */
    Route::get('change-password', [UserController::class, 'changepassword'])->name('changepassword.index');
    Route::post('change-password/update/{User}', [UserController::class, 'updatepassword'])->name('changepassword.update');

    /*
    |----------------------------------------------------------------------
    | Mail: Employee Requests / Leave / TO
    |----------------------------------------------------------------------
    */
    Route::get('mail/employee-request', [MailController::class, 'employeerequest'])->name('mail.employeerequest');
    Route::get('mail/leave-request', [MailController::class, 'leaverequest'])->name('mail.leaverequest');
    Route::get('mail/travel-order-request/{user}/edit', [MailController::class, 'travelorderedit'])->name('mail.travelorderedit');
    Route::get('mail/travel-order-request', [MailController::class, 'travelorderequest'])->name('mail.travelorderrequest');
    // user can print his own leave (policy will guard access)
    Route::get('leave/{Leave}/print', [\App\Http\Controllers\Msd\LeaveController::class, 'print'])
        ->name('leave.print');



    // AJAX polling endpoint (kung ginagamit mo)
    Route::get('/ajax/mail/pending-counts', [MailController::class, 'pendingCounts'])->name('mail.pending-counts');

    /*
    |----------------------------------------------------------------------
    | User DTR
    |----------------------------------------------------------------------
    */
    Route::resource('my-daily-time-record', UserDtrController::class);
    Route::post('daily-time-record/request', [UserDtrController::class, 'store'])->name('daily-time-record.user.store');
    Route::post('msd-management/encoder/my-daily-time-record/view/', [UserDtrController::class, 'print'])->name('my-daily-time-record.print');

    /*
    |----------------------------------------------------------------------
    | Financial Management main resource
    |----------------------------------------------------------------------
    */
    Route::resource('financial-management', FinancialManagementController::class);

    /*
    |----------------------------------------------------------------------
    | ADMIN group (iniwan ko ang orihinal na structure/URLs)
    |  NOTE: hindi ko ginalaw ang 'auth' => 'admin' attribute mo
    |----------------------------------------------------------------------
    */
    Route::group([
        'auth' => 'admin',
    ], function () {

        Route::get('document-tracking/document/{Document}', [DocumentController::class, 'view'])->name('document-tracking.view');
        Route::get('document-tracking/attachment/view/{Document}', [AttachmentController::class, 'view'])->name('attachment.view');

        Route::resource('document-tracking', DocumentController::class);
        Route::resource('document-tracking/attachment', AttachmentController::class);

        Route::get('document-tracking/{Document}/print', [DocumentController::class, 'print'])->name('document-tracking.print');

        Route::resource('msd-management/encoder/employee', App\Http\Controllers\Admin\EmployeeController::class);
        Route::resource('data-management/employee/office', App\Http\Controllers\Admin\OfficeController::class);
        Route::resource('data-management/employee/section', App\Http\Controllers\Admin\SectionController::class);
        Route::resource('data-management/employee/unit', App\Http\Controllers\Admin\UnitController::class);
        Route::resource('data-management/user/user', App\Http\Controllers\Auth\UserController::class);
        Route::resource('data-management/user/role', RolesController::class);

        // Leave Credits Import Routes
        Route::get('leave-management/import-credits', [App\Http\Controllers\LeaveCreditsController::class, 'index'])->name('leave-credits.index');
        Route::post('leave-management/upload-excel', [App\Http\Controllers\LeaveCreditsController::class, 'upload'])->name('leave-credits.upload');
        Route::post('leave-management/import-data', [App\Http\Controllers\LeaveCreditsController::class, 'import'])->name('leave-credits.import');
        Route::post('leave-management/cancel-import', [App\Http\Controllers\LeaveCreditsController::class, 'cancelUpload'])->name('leave-credits.cancel');
        Route::get('leave-management/employee-credits/{employeeId}', [App\Http\Controllers\LeaveCreditsController::class, 'getEmployeeCredits'])->name('leave-credits.employee');

        Route::resource('msd-management/encoder/daily-time-record', DtrController::class);
        Route::resource('msd-management/event', EventController::class);

        Route::get('document-tracking/event/atttachment/{Event}/view/', [EventController::class, 'viewattachment'])->name('eventattachment.view');

        Route::post('msd-management/encoder/daily-time-record/absent', [DtrController::class, 'storeabsent'])->name('daily-time-record.storeabsent');
        Route::post('msd-management/encoder/daily-time-record/event', [DtrController::class, 'storeevent'])->name('daily-time-record.storeevent');
        Route::post('msd-management/encoder/daily-time-record/view/', [DtrController::class, 'print'])->name('daily-time-record.print');

        Route::get('data-management/user/role/edit/{User}', [RolesController::class, 'edit'])->name('role.edit');

        Route::resource('msd-management/encoder/leave-management', LeaveController::class);
        Route::resource('msd-management/encoder/travel-order', TravelOrderController::class);
        Route::resource('msd-management/settings/leave-mgmt', App\Http\Controllers\Msd\LeaveTypeController::class);
        Route::resource('msd-management/settings/leave-settings/leave-signatory', App\Http\Controllers\Msd\LeaveSignatoryController::class);
        Route::resource('msd-management/settings/leave-settings/set-leave-signatory', App\Http\Controllers\Msd\SetLeaveSignatoryController::class);
        Route::resource('msd-management/settings/travel-order-settings/travel-order-signatory', App\Http\Controllers\Msd\TravelOrderSignatoryController::class);
        Route::resource('msd-management/settings/travel-order-settings/set-travel-order-signatory', App\Http\Controllers\Msd\SetTravelOrderSignatoryController::class);
        Route::resource('msd-management/settings/travel-order-settings/section-chief', App\Http\Controllers\Msd\SectionChiefController::class);

        // AJAX API for Section Chief (get employees by unit)
        Route::get('api/employees-by-unit/{unitid}', [App\Http\Controllers\Msd\SectionChiefController::class, 'getEmployeesByUnit'])->name('api.employees-by-unit');

        Route::put('leave/{Leave}/accept', [LeaveController::class, 'accept'])->name('leave.accept');
        Route::put('leave/{Leave}/reject', [LeaveController::class, 'reject'])->name('leave.reject');
        Route::put('leave/{Leave}/credits-save', [LeaveController::class, 'saveCredits'])->name('leave.credits.save');
        Route::put('leave/{Leave}/approver2-save', [LeaveController::class, 'saveApprover2'])->name('leave.approver2.save');
        Route::put('leave/{Leave}/approver3-save', [LeaveController::class, 'saveApprover3'])->name('leave.approver3.save');
        Route::get('leave/{Leave}/print', [LeaveController::class, 'print'])->name('leave.print');

        Route::get('leave-management', [LeaveController::class, 'userindex'])->name('userleave.index');
        Route::get('leave-management/check-updates', [LeaveController::class, 'checkUpdates'])->name('leave.checkUpdates');
        Route::post('leave-management/create', [LeaveController::class, 'storeUserLeave'])->name('userleave.storeUserLeave');
        Route::get('leave-management/summary', [LeaveController::class, 'summary'])->name('leave.summary');
        Route::get('leave-management/summary/filtered', [LeaveController::class, 'summaryfilter'])->name('leave.summaryfilter');

        Route::put('travel-order/{TravelOrder}/accept', [TravelOrderController::class, 'accept'])->name('travel-order.accept');
        Route::put('travel-order/{TravelOrder}/reject', [TravelOrderController::class, 'reject'])->name('travel-order.reject');
        Route::get('travel-order-management', [TravelOrderController::class, 'userindex'])->name('usertravelorder.index');
        Route::post('travel-order-management/create', [TravelOrderController::class, 'storeUserTravelOrder'])->name('userTravelOrder.storeUserTravelOrder');
        Route::get('travel-order/{TravelOrder}/print', [TravelOrderController::class, 'print'])->name('travelorder.print');

        Route::post('msd-management/travel-order/advance/', [TravelOrderController::class, 'advance'])->name('travel-order.advance');
        Route::post('msd-management/leave/advance/', [LeaveController::class, 'advance'])->name('leave.advance');

        Route::get('msd-management/dtr-signatory/', [DtrSignatoryController::class, 'index'])->name('dtr-signatory.index');
        Route::post('msd-management/dtr-signatory/add-signatory', [DtrSignatoryController::class, 'store'])->name('dtr-signatory.store');
        Route::post('msd-management/dtr-signatory/{DtrSignatory}update-signatory', [DtrSignatoryController::class, 'update'])->name('dtr-signatory.update');

        Route::post('msd-management/dtr/upload', [DtrController::class, 'uploadDTR'])->name('upload.dtr');
    });

    /*
    |----------------------------------------------------------------------
    | RECORDS group
    |----------------------------------------------------------------------
    */
    Route::group([
        'auth' => 'records',
    ], function () {
        Route::get('document-tracking/{Document}/edit', [DocumentController::class, 'edit']);
        Route::get('document-tracking/{Document}/view', [DocumentController::class, 'view']);
        Route::get('document-tracking/attachment/view/{Document}', [AttachmentController::class, 'view'])->name('attachment.view');

        Route::resource('document-tracking', DocumentController::class);
        Route::resource('document-tracking/attachment', AttachmentController::class);

        Route::get('document-tracking/{Document}/print', [DocumentController::class, 'print'])->name('document-tracking.print');
    });

    /*
    |----------------------------------------------------------------------
    | MSD group
    |----------------------------------------------------------------------
    */
    Route::group([
        'auth' => 'msd',
    ], function () {

        Route::resource('msd-management/encoder/daily-time-record', DtrController::class);
        Route::post('msd-management/encoder/daily-time-record/absent', [DtrController::class, 'storeabsent'])->name('daily-time-record.storeabsent');
        Route::post('msd-management/encoder/daily-time-record/event', [DtrController::class, 'storeevent'])->name('daily-time-record.storeevent');
        Route::post('msd-management/encoder/daily-time-record/view/', [DtrController::class, 'print'])->name('daily-time-record.print');

        Route::resource('msd-management/event', EventController::class);
        Route::get('document-tracking/event/atttachment/{Event}/view/', [EventController::class, 'viewattachment'])->name('eventattachment.view');

        Route::resource('msd-management/encoder/leave-management', LeaveController::class);
        Route::resource('msd-management/settings/leave-settings/leave-mgmt', App\Http\Controllers\Msd\LeaveTypeController::class);
        Route::resource('msd-management/settings/leave-settings/leave-signatory', App\Http\Controllers\Msd\LeaveSignatoryController::class);
        Route::resource('msd-management/settings/leave-settings/set-leave-signatory', App\Http\Controllers\Msd\SetLeaveSignatoryController::class);

        Route::resource('msd-management/settings/travel-order-settings/travel-order-signatory', App\Http\Controllers\Msd\TravelOrderSignatoryController::class);
        Route::resource('msd-management/settings/travel-order-settings/set-travel-order-signatory', App\Http\Controllers\Msd\SetTravelOrderSignatoryController::class);

        Route::put('travel-order/{TravelOrder}/accept', [TravelOrderController::class, 'accept'])->name('travel-order.accept');
        Route::put('travel-order/{TravelOrder}/reject', [TravelOrderController::class, 'reject'])->name('travel-order.reject');
        Route::get('travel-order-management', [TravelOrderController::class, 'userindex'])->name('usertravelorder.index');
        Route::post('travel-order-management/create', [TravelOrderController::class, 'storeUserTravelOrder'])->name('userTravelOrder.storeUserTravelOrder');

        Route::post('daily-time-record/view/', [UserDtrController::class, 'print'])->name('my-daily-time-record.print');
        Route::get('daily-time-record/request/', [UserDtrController::class, 'request'])->name('my-daily-time-record.request');

        Route::post('msd-management/travel-order/advance/', [TravelOrderController::class, 'advance'])->name('travel-order.advance');
        Route::post('msd-management/leave/advance/', [LeaveController::class, 'advance'])->name('leave.advance');

        Route::get('msd-management/dtr-signatory/', [DtrSignatoryController::class, 'index'])->name('dtr-signatory.index');
        Route::post('msd-management/dtr-signatory/add-signatory', [DtrSignatoryController::class, 'store'])->name('dtr-signatory.store');
        Route::post('msd-management/dtr-signatory/{DtrSignatory}update-signatory', [DtrSignatoryController::class, 'update'])->name('dtr-signatory.update');
    });

    /*
    |----------------------------------------------------------------------
    | Travel Order Approver 2 – single declaration only
    |----------------------------------------------------------------------
    */
    Route::put(
        'msd-management/encoder/travel-order/{travel_order}/update-approve2',
        [TravelOrderController::class, 'updateApprove2']
    )->name('travel-order.update-approve2');
});
