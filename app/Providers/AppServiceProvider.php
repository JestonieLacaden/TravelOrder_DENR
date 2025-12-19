<?php

namespace App\Providers;

use App\Models\Unit;
use App\Models\User;
use App\Models\Event;
use App\Models\Leave;
use App\Models\Route;
use App\Models\Office;
use App\Models\Section;
use App\Models\SectionChief;
use App\Models\Document;
use App\Models\Employee;
use App\Models\FinancialManagement;
use Laravel\Sanctum\Guard;
use App\Models\TravelOrder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\TravelOrderSignatory;
use App\Models\LeaveSignatory; //itry kong isama ito

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        //compose all the views....
        //  view()->composer('*', function ($view)
        //  {
        //  $IncomingCount = 0;
        //  $test = Employee::where('email','=', Auth::user()->email)->get()->first();
        //  $DataList = Route::orderBy('created_at', 'DESC')->with('document')
        //   ->get()->unique('documentid')->where('unitid','=',$test->unitid);
        //  foreach ($DataList as $Data)
        //  {
        //     if ($Data->is_forwarded == 1)
        //         {
        //             $IncomingCount = $IncomingCount + 1;
        //         }


        //  }
        //  $view->with('IncomingCount', $Incomingcount );
        // }




        // view()->composer('*', function ($view)
        // {
        //     $test = Employee::where('email','=', Auth::user()->email)->get()->first();
        //     $Incomingcount = Route::orderBy('created_at', 'DESC')->with('document')
        //     ->get()->unique('documentid')->where('unitid','=',$test->unitid)->where('is_forwarded','=','1')->count();

        //     $view->with('IncomingCount', $Incomingcount );
        // });

        // view()->composer('*', function ($view)
        // {
        //     $test = Employee::where('email','=', Auth::user()->email)->get()->first();
        //     $OutgoingCount = Route::orderBy('created_at', 'DESC')->with('document')
        //     ->get()->unique('documentid')->where('is_forwarded','=','1')->where('userunitid','=',$test->unitid)->count();

        //     $view->with('OutgoingCount', $OutgoingCount );
        // });
        // view()->composer('*', function ($view)
        // {
        //     $test = Employee::where('email','=', Auth::user()->email)->get()->first();
        //     $ClosedCount = Route::orderBy('created_at', 'DESC')->with('document')
        //     ->get()->unique('documentid')->where('is_active','=','0')->where('unitid','=',$test->unitid)->count();

        //     $view->with('ClosedCount', $ClosedCount );
        // });
        // view()->composer('*', function ($view)
        // {
        //     $test = Employee::where('email','=', Auth::user()->email)->get()->first();
        //     $AcceptedCount = Route::orderBy('created_at', 'DESC')->with('document')
        //     ->get()->unique('documentid')->where('is_accepted','=','1')->where('unitid','=',$test->unitid)->count();

        //     $view->with('AcceptedCount', $AcceptedCount );
        // });

        // view()->composer('*', function ($view)
        // {
        //     $test = Employee::where('email','=', Auth::user()->email)->get()->first();
        //     $RejectedCount = Route::orderBy('created_at', 'DESC')->with('document')
        //     ->get()->unique('documentid')->where('is_rejected','=','1')->where('unitid','=',$test->unitid)->count();

        //     $view->with('RejectedCount', $RejectedCount );
        // });

        // View::share('IncomingCount', Route::orderBy('created_at', 'DESC')->with('document')->get()->unique('documentid')->where('is_forwarded','=','1')->count());

        View::share('UnitCount', Unit::count());
        View::share('OfficeCount', Office::count());
        View::share('SectionCount', Section::count());
        View::share('EmployeeCount', Employee::count());
        View::share('UserCount', User::count());
        View::share('DocumentCount', Document::count());
        View::share('EventCount', Event::where('date', '>', now())->count());
        View::share('LeaveEncoderCount', Leave::where([
            ['is_rejected1', false],
            ['is_rejected2', false],
            ['is_rejected3', false],
            ['is_approve3', false],
        ])->count());
        View::share('TravelOrderEncoderCount', TravelOrder::where([
            ['is_rejected1', false],
            ['is_rejected2', false],
            ['is_approve2', false],
        ])->count());

        View::composer(['partials.sidebar', 'mails.employeerequest.index'], function ($view) {

            $toPendingCount = 0;
            $leavePendingCount = 0;
            $showTO = false;
            $showLeave = false;

            if (auth()->check()) {
                $emp = Employee::where('email', auth()->user()->email)->first();

                if ($emp) {
                    // --- TRAVEL ORDER signatory + pending
                    // ✅ Option 2: Show button for Section Chiefs OR Division/PENRO with signatory records

                    // Check if user is a current Section Chief
                    $isSectionChief = SectionChief::where('employeeid', $emp->id)->exists();

                    // Get all signatory IDs where this employee is an approver
                    $sigA1 = TravelOrderSignatory::where('approver1', $emp->id)->pluck('id');
                    $sigA2 = TravelOrderSignatory::where('approver2', $emp->id)->pluck('id');
                    $sigA3 = TravelOrderSignatory::where('approver3', $emp->id)->pluck('id');

                    // Count ACTUAL pending requests for each level
                    $pendingA1 = TravelOrder::whereIn('travelordersignatoryid', $sigA1)
                        ->where('is_approve1', false)
                        ->where('is_rejected1', false)
                        ->count();

                    $pendingA2 = TravelOrder::whereIn('travelordersignatoryid', $sigA2)
                        ->where('is_approve1', true)
                        ->where('is_approve2', false)
                        ->where('is_rejected1', false)
                        ->where('is_rejected2', false)
                        ->count();

                    $pendingA3 = TravelOrder::whereIn('travelordersignatoryid', $sigA3)
                        ->where('is_approve1', true)
                        ->where('is_approve2', true)
                        ->where('is_approve3', false)
                        ->where('is_rejected1', false)
                        ->where('is_rejected2', false)
                        ->where('is_rejected3', false)
                        ->count();

                    $toPendingCount = $pendingA1 + $pendingA2 + $pendingA3;

                    // Show button ALWAYS for Section Chiefs, OR if they have existing signatory records
                    // This ensures button stays visible even when all requests are approved
                    $showTO = $isSectionChief || $sigA1->isNotEmpty() || $sigA2->isNotEmpty() || $sigA3->isNotEmpty();

                    // --- LEAVE signatory + pending
                    // Note: kung may leavesignatoryid column sa Leaves, idagdag mo ang whereIn(...) sa bawat query.
                    $isA1 = \App\Models\LeaveSignatory::where('approver1', $emp->id)->exists();
                    $isA2 = \App\Models\LeaveSignatory::where('approver2', $emp->id)->exists();
                    $isA3 = \App\Models\LeaveSignatory::where('approver3', $emp->id)->exists();

                    $showLeave = $isA1 || $isA2 || $isA3;

                    $q1 = $isA1 ? \App\Models\Leave::where('is_approve1', false)->where('is_rejected1', false)->count() : 0;
                    $q2 = $isA2 ? \App\Models\Leave::where('is_approve1', true)->where('is_approve2', false)->where('is_rejected1', false)->where('is_rejected2', false)->count() : 0;
                    $q3 = $isA3 ? \App\Models\Leave::where('is_approve2', true)->where('is_approve3', false)->where('is_rejected2', false)->where('is_rejected3', false)->count() : 0;

                    $leavePendingCount = $q1 + $q2 + $q3;
                }
            }

            // optional realtime broadcast mo dati
            event(new \App\Events\RequestsCountChanged($toPendingCount + $leavePendingCount));

            $view->with([
                'showTO'              => $showTO,
                'showLeave'           => $showLeave,
                'toPendingCount'      => $toPendingCount,
                'leavePendingCount'   => $leavePendingCount,
                'EmployeeRequestsTotal' => $toPendingCount + $leavePendingCount,
            ]);
        });
    }
}