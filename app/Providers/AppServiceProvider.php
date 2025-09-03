<?php

namespace App\Providers;

use App\Models\Unit;
use App\Models\User;
use App\Models\Event;
use App\Models\Leave;
use App\Models\Route;
use App\Models\Office;
use App\Models\Section;
use App\Models\Document;
use App\Models\Employee;
use App\Models\FinancialManagement;
use Laravel\Sanctum\Guard;
use App\Models\TravelOrder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        View::share('EventCount', Event::where('date','>',now())->count());
        View::share('LeaveEncoderCount', Leave::where([
            ['is_rejected1',false],
            ['is_rejected2',false],
            ['is_rejected3',false],
            ['is_approve3',false],
            ])->count());
        View::share('TravelOrderEncoderCount', TravelOrder::where([
                ['is_rejected1',false],
                ['is_rejected2',false],
                ['is_approve2',false],
                ])->count());



    }
}
