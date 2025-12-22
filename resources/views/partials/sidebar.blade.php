<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">


    <!-- Brand Logo -->
    <a href="/" class="brand-link">
        <span class="brand-text font-weight-light text-center">DENR Information System</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('images/logo.png') }}" class="brand-image img-circle elevation-3" style="opacity: .8">
            </div>
            <div class="info">


                <a href="#" class="d-block"> {{ auth()->user()->username }} </a>
            </div>
        </div>


        <!-- Sidebar Menu -->

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

                @if (Auth::check())
                <li class="nav-header">Main Menu</li>
                <li class="nav-item  ">
                    <a href="{{route('dashboard') }}" class="nav-link {{ 'dashboard' == request()->path() ? 'active' : ''}}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                            @if(!empty($EventCount))
                            <span class="badge badge-success right">{{ $EventCount }}</span>
                            @endif
                        </p>
                    </a>
                </li>
                @endif

                {{-- @can('viewany', \App\Models\Mail::class)
                <li class="nav-item  ">
                    <a href="{{route('mail.index') }}" class="nav-link
                {{ 'mail' == request()->path() ? 'active' : ''}}">
                <i class="nav-icon fas fa-envelope"></i>
                <p>
                    My Mail
                    <span class="badge bg-danger float-right">Important</span>
                </p>
                </a>
                </li>
                @endcan --}}
                @can ('viewLeaveindex', \App\Models\Leave::class)
                <li class="nav-item  ">
                    <a href="{{ route('userleave.index') }}" class="nav-link {{ Request::is('leave-management/*') ? 'active' : ''}} {{ Request::is('leave-management') ? 'active' : ''}}">
                        <i class="nav-icon fas fa-calendar"></i>
                        <p>
                            Leave
                        </p>
                    </a>
                </li>
                @endcan

                @can ('viewTravelOrderIndex', \App\Models\TravelOrder::class)
                <li class="nav-item  ">
                    <a href="{{ route('usertravelorder.index') }}" class="nav-link {{ Request::is('leave-management/*') ? 'active' : ''}} {{ Request::is('travel-order-management') ? 'active' : ''}}">
                        <i class="nav-icon fas fa-route"></i>
                        <p>
                            Travel Order
                        </p>
                    </a>
                </li>
                @endcan

                {{-- @can ('viewDTRIndex', \App\Models\Dtr_History::class)
                <li class="nav-item  ">
                    <a href="{{ route('my-daily-time-record.index') }}" class="nav-link
                {{ Request::is('my-daily-time-record') ? 'active' : '' }}">
                <i class="nav-icon fas fa-clock"></i>
                <p>
                    My Daily Time Record
                    <span class="badge bg-danger float-right">Important</span>
                </p>
                </a>
                </li>
                @endcan --}}


                @can('acceptemployee', \App\Models\Leave::class)
                {{-- Only show "My Mail" header if user has Leave or TO Request access --}}
                @if((!empty($showLeave) && $showLeave) || (!empty($showTO) && $showTO))
                <li class="nav-header">My Mail </li>
                @endif

                @if(!empty($showLeave) && $showLeave)
                <li class="nav-item   {{ request()->routeIs('mail.leaverequest') ? 'active' : '' }}">

                    <a class="nav-link" href="{{ route('mail.leaverequest') }}">
                        <i class="nav-icon fa fa-envelope-open"></i>
                        <p>
                            Leave Request(s)
                        </p>
                        <span id="leave-badge" class="badge badge-info" @if(($leavePendingCount ?? 0)==0) style="display:none" @endif>
                            {{ $leavePendingCount ?? 0 }}
                        </span>
                    </a>
                </li>
                @endif

                @if(!empty($showTO) && $showTO)
                <li class="nav-item   {{ request()->routeIs('mail.travelorderrequest') ? 'active' : '' }}">

                    <a class="nav-link" href="{{ route('mail.travelorderrequest') }}">
                        <i class="nav-icon fa fa-plane"></i>
                        <p>T.O. Request(s)</p>
                        <span id="to-badge" class="badge badge-info" @if(($toPendingCount ?? 0)==0) style="display:none" @endif>
                            {{ $toPendingCount ?? 0 }}
                        </span>
                    </a>
                </li>
                @endif


                {{-- @can('acceptemployee', \App\Models\Leave::class)
                <li class="nav-item">
                    <a href="{{ route('mail.travelorderrequest') }}" class="nav-link">
                <i class="nav-icon fas fa-users"></i>
                <p>
                    Employee Request(s)
                    <span id="req-badge" class="badge badge-info right" style="display:none">
                        {{ ($EmployeeRequestsTotal ?? 0) > 999 ? '999+' : ($EmployeeRequestsTotal ?? 0) }}
                    </span>
                </p>
                </a>
                </li>
                @endcan --}}


                {{-- @can('FMPlanning', \App\Models\FinancialManagement::class)
                <li class="nav-item ">
                    <a href="{{ route('fmplanning.incoming') }}"
                class="nav-link {{ Request::is('financial-management/planning-section/*') ? 'active' : ''}}">
                <i class="nav-icon fas fa-coins"></i>
                <p>
                    Financial Management - Planning Section
                </p>
                </a>
                </li>
                @endcan --}}

                {{-- @can('FMBudget', \App\Models\FinancialManagement::class)
                <li class="nav-item ">
                    <a href="{{ route('fmbudget.incoming') }}"
                class="nav-link {{ Request::is('financial-management/budget-section/*') ? 'active' : ''}}">
                <i class="nav-icon fas fa-coins"></i>
                <p>
                    Financial Management - Budget Unit
                </p>
                </a>
                </li>
                @endcan --}}

                {{-- @can('FMAccounting', \App\Models\FinancialManagement::class)
                <li class="nav-item ">
                    <a href="{{ route('fmaccounting.incoming') }}"
                class="nav-link {{ Request::is('financial-management/accounting-section/*') ? 'active' : ''}}">
                <i class="nav-icon fas fa-coins"></i>
                <p>
                    Financial Management - Accounting Unit
                </p>
                </a>
                </li>
                @endcan --}}

                {{-- @can('FMCashier', \App\Models\FinancialManagement::class)
                <li class="nav-item ">
                    <a href="{{ route('fmcashier.incoming') }}"
                class="nav-link {{ Request::is('financial-management/cashier-section/*') ? 'active' : ''}}">
                <i class="nav-icon fas fa-coins"></i>
                <p>
                    Financial Management - Cashier Unit
                </p>
                </a>
                </li>
                @endcan --}}

                {{-- @can('FMRecords', \App\Models\FinancialManagement::class)
                <li class="nav-item ">
                    <a href="{{ route('fmrecords.incoming') }}"
                class="nav-link {{ Request::is('financial-management/records-section/*') ? 'active' : ''}}">
                <i class="nav-icon fas fa-coins"></i>
                <p>
                    Financial Management - Records Unit
                </p>
                </a>
                </li>
                @endcan --}}

                {{-- @can('FMSignatory', \App\Models\FinancialManagement::class)
                <li class="nav-item ">
                    <a href="{{ route('fmsignatory.incoming') }}"
                class="nav-link {{ Request::is('financial-management/signatory-section/*') ? 'active' : ''}}">
                <i class="nav-icon fas fa-coins"></i>
                <p>
                    Financial Management - Signatory Section
                </p>
                </a>
                </li>
                @endcan --}}

                {{-- @can('FMOthers', \App\Models\FinancialManagement::class)
                <li class="nav-item ">
                    <a href="{{ route('fmothers.incoming') }}"
                class="nav-link {{ Request::is('financial-management/others-section/*') ? 'active' : ''}}">
                <i class="nav-icon fas fa-coins"></i>
                <p>
                    Financial Management - Others Section
                </p>
                </a>
                </li>
                @endcan --}}

                @endcan

                {{-- <li class="nav-header">Record - Section</li>
                <li class="nav-item ">
                    <a href="{{ route('document-tracking.index') }}"
                class="nav-link {{ 'document-tracking' == request()->path() ? 'active' : ''}}">
                <i class="nav-icon fas fa-folder-open"></i>
                <p>
                    Document Tracking
                    @if ($DocumentCount != 0)
                    @if ($DocumentCount > 999)
                    <span class="badge badge-info right">999+</span>
                    @else
                    <span class="badge badge-success right">{{ $DocumentCount }}</span>
                    @endif
                    @endif
                </p>
                </a>
                </li> --}}


                {{-- @can('create', \App\Models\Document::class)
                <li class="nav-item ">
                    <a href="{{ route('document-tracking.create') }}" class="nav-link">
                <i class="nav-icon fas fa-plus"></i>
                <p>
                    New Document
                </p>
                </a>
                </li>
                @endcan --}}


                @can('viewMSDSection', \App\Models\Dtr_History::class)
                <li class="nav-header">MSD - Section</li>
                @endcan


                @can('viewMSDSection', \App\Models\Dtr_History::class)
                <li class="nav-item">
                    <a href="" class="nav-link {{ Request::is('msd-management/encoder/*') ? 'active' : '' }}">
                        <i class="far fas fa-keyboard nav-icon"></i>
                        <p>
                            MSD - Encoder
                            <i class="right fas fa-angle-left"></i>
                            <span class="badge badge-info right">86</span>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('employee.index') }}" class="nav-link  {{ Request::is('msd-management/encoder/employee') ? 'active' : '' }}">
                                <p class="ml-4 p-2">Employee<span class="badge badge-info right">{{ $EmployeeCount
                                        }}</span></p>
                            </a>
                        </li>
                        @can('create',\App\Models\Dtr_History::class )
                        <li class="nav-item">
                            <a href="{{ route('daily-time-record.index') }}" class="nav-link
                            {{ Request::is('msd-management/encoder/daily-time-record') ? 'active' : '' }}">
                                <p class="ml-4 p-2">Daily Time Record</p>
                            </a>
                        </li>
                        @endcan
                        @can('MSDaddLeave',\App\Models\Leave::class)
                        <li class="nav-item">
                            <a href="{{ route('leave-management.index') }}" class="nav-link  {{ Request::is('msd-management/encoder/leave-management') ? 'active' : '' }}">
                                <p class="ml-4 p-2">Leave
                                    @if ($LeaveEncoderCount !=0)
                                    <span class="badge badge-warning right">{{ $LeaveEncoderCount }}</span>
                                    @endif
                                </p>
                            </a>
                        </li>
                        @endcan
                        @can('MsdCreate',\App\Models\TravelOrder::class)
                        <li class="nav-item">
                            <a href="{{ route('travel-order.index') }}" class="nav-link  {{ Request::is('msd-management/encoder/travel-order') ? 'active' : '' }}">
                                <p class="ml-4 p-2">Travel Order(s)
                                    @if ($TravelOrderEncoderCount !=0)
                                    <span class="badge badge-warning right">{{ $TravelOrderEncoderCount }}</span>
                                    @endif
                                </p>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcan
                @can('create', \App\Models\Event::class)
                <li class="nav-item">
                    <a href="{{ route('event.index') }}" class="nav-link {{ Request::is('msd-management/event') ? 'active' : '' }}">

                        <i class="far fas fa-calendar nav-icon"></i>
                        <p> MSD - Event</p>
                    </a>
                </li>
                @endcan

                {{-- @can('create', \App\Models\Event::class)
                <li class="nav-item">
                    <a href="{{ route('dtr-signatory.index') }}" class="nav-link
                {{ Request::is('msd-management/dtr-signatory') ? 'active' : '' }}">
                <i class="far fas fa-calendar-minus nav-icon"></i>
                <p>DTR Signatory</p>
                </a>
                </li>
                @endcan --}}

                @can('viewAny', \App\Models\Leave_Type::class)
                <li class="nav-item">
                    <a href="#" class="nav-link {{ Request::is('msd-management/settings/*') ? 'active' : '' }}">
                        <i class="far fas fa-cog nav-icon"></i>
                        <p>
                            MSD - Settings
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="#" class="nav-link  {{ Request::is('msd-management/settings/leave-settings/*') ? 'active' : '' }}">
                                <i class="far fas fa-users nav-icon"></i>
                                <p>
                                    Leave Settings
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('leave-mgmt.index') }}" class="nav-link  {{ Request::is('msd-management/settings/leave-settings/leave-mgmt') ? 'active' : '' }}">

                                        <p class="ml-4 p-2">Leave Credit<span class="badge badge-info right"></span></p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('leave-signatory.index') }}" class="nav-link  {{ Request::is('msd-management/settings/leave-settings/leave-signatory') ? 'active' : '' }}">

                                        <p class="ml-4 p-2">Leave Signatory<span class="badge badge-info right"></span>
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('set-leave-signatory.index') }}" class="nav-link  {{ Request::is('msd-management/settings/leave-settings/set-leave-signatory') ? 'active' : '' }}">

                                        <p class="ml-4 p-2">Set Signatory<span class="badge badge-info right"></span>
                                        </p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="#" class="nav-link  {{ Request::is('msd-management/settings/travel-order-settings/*') ? 'active' : '' }}">
                                <i class="far fas fa-users nav-icon"></i>
                                <p>
                                    Travel Order Settings
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">

                                    <a href="{{ route('travel-order-signatory.index') }} " class="nav-link  {{ Request::is('msd-management/settings/travel-order-settings/travel-order-signatory') ? 'active' : '' }}">

                                        <p class="ml-4 p-2">Division Signatories<span class="badge badge-info right"></span>
                                        </p>
                                    </a>
                                </li>
                                {{-- HIDDEN: Option 1 - Deprecated in favor of Option 2 (Set Section Chief)
                                <li class="nav-item">

                                    <a href="{{ route('set-travel-order-signatory.index') }}" class="nav-link {{ Request::is('msd-management/settings/travel-order-settings/set-travel-order-signatory') ? 'active' : '' }}">

                                <p class="ml-4 p-2">Set Signatory<span class="badge badge-info right"></span>
                                </p>
                                </a>
                        </li>
                        --}}
                        <li class="nav-item">

                            <a href="{{ route('section-chief.index') }}" class="nav-link  {{ Request::is('msd-management/settings/travel-order-settings/section-chief') ? 'active' : '' }}">

                                <p class="ml-4 p-2"><i class="fas fa-user-tie"></i> Set Section Chief<span class="badge badge-success right">NEW</span>
                                </p>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>



            </li>
            @endcan

            @can('viewany', \App\Models\User::class)
            <li class="nav-header">Administrator</li>
            <li class="nav-item">
                <a href="#" class="nav-link  {{ Request::is('data-management/*') ? 'active' : '' }}">
                    <p>
                        Data Management
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="" class="nav-link {{ Request::is('data-management/user/*') ? 'active' : '' }}">
                            <i class="far fas fa-users nav-icon"></i>
                            <p>
                                Users
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('user.index') }}" class="nav-link  {{ Request::is('data-management/user/user') ? 'active' : '' }}">

                                    <p class="ml-4 p-2">User<span class="badge badge-info right">{{ $UserCount
                                                }}</span>
                                    </p>
                                </a>
                            </li>
                        </ul>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('role.index') }}" class="nav-link  {{ Request::is('data-management/user/role') ? 'active' : '' }}">
                                    <p class="ml-4 p-2">Roles</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>

                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="#" class="nav-link {{ Request::is('data-management/employee/*') ? 'active' : '' }}">
                            <i class="far fas fa-address-book nav-icon"></i>
                            <p>
                                Employees
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">

                                <a href="{{ route('office.index') }}" class="nav-link {{ Request::is('data-management/employee/office') ? 'active' : '' }}">
                                    <p class="ml-4 p-2">Office<span class="badge badge-info right">{{ $OfficeCount
                                                }}</span></p>
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('section.index') }}" class="nav-link {{ Request::is('data-management/employee/section') ? 'active' : '' }}">
                                    <p class="ml-4 p-2">Section<span class="badge badge-info right">{{ $SectionCount
                                                }}</span></p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('unit.index') }}" class="nav-link {{ Request::is('data-management/employee/unit') ? 'active' : '' }}">
                                    <p class="ml-4 p-2">Unit <span class="badge badge-info right">{{ $UnitCount
                                                }}</span></p>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            @endcan

            {{-- @can('viewany', \App\Models\FinancialManagement::class)
                <li class="nav-header">Financial Management </li>
                <li class="nav-item ">
                    <a href="{{ route('financial-management.index') }}"
            class="nav-link {{ 'financial-management' == request()->path() ? 'active' : ''}}">
            <i class="nav-icon fas fa-coins"></i>
            <p>
                Financial Management
                @if ($FinancialManagementCount != 0)
                @if ($FinancialManagementCount > 999)
                <span class="badge badge-success right">999+</span>
                @else
                <span class="badge badge-info right">{{ $FinancialManagementCount }}</span>
                @endif
                @endif
            </p>
            </a>
            </li>
            @endcan --}}


            {{-- @can('viewany', \App\Models\FinancialManagement::class)
                <li class="nav-item">
                    <a href="#"
                        class="nav-link  {{ Request::is('financial-management/allocation*') ? 'active' : '' }} {{ Request::is('financial-management/financial_tracking') ? 'active' : '' }} {{ Request::is('financial-management/realignment_report') ? 'active' : '' }}">
            <p>
                Report
                <i class="right fas fa-angle-left"></i>
            </p>
            </a>
            @can('viewany', \App\Models\FinancialManagement::class)
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="" class="nav-link {{ Request::is('financial-management/allocation*') ? 'active' : '' }}">
                        <i class="far fas fa-file nav-icon"></i>
                        <p>
                            GAA
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('financial-management.AllocationPayeereport') }}" class="nav-link {{ 'financial-management/allocation-payee-report' == request()->path() ? 'active' : ''}}">
                                <i class="nav-icon fas fa-file"></i>
                                <p>
                                    Per Payee Report
                                </p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('financial-management.Allocationreport') }}" class="nav-link {{ 'financial-management/allocation-report' == request()->path() ? 'active' : ''}}">
                                <i class="nav-icon fas fa-file"></i>
                                <p>
                                    Per Activity Report
                                </p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('financial-management.AllocationUACSreport') }}" class="nav-link {{ 'financial-management/allocation-uacs-report' == request()->path() ? 'active' : ''}}">
                                <i class="nav-icon fas fa-file"></i>
                                <p>
                                    Per UACS Report
                                </p>
                            </a>
                        </li>
                    </ul>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('financial-management.AllocationPAPreport') }}" class="nav-link {{ 'financial-management/allocation-pap-report' == request()->path() ? 'active' : ''}}">
                                <i class="nav-icon fas fa-file"></i>
                                <p>
                                    Per PAP Report
                                </p>
                            </a>
                        </li>
                    </ul>



                </li>
            </ul>
            @endcan --}}
            {{-- <ul class="nav nav-treeview">
                        <li class="nav-item ">
                            <a href="{{ route('financial-management.FinancialTracking') }}"
            class="nav-link {{ 'financial-management/financial_tracking' == request()->path() ? 'active' : ''}}">
            <i class="nav-icon fas fa-file"></i>
            <p>
                Financial Tracking Report
            </p>
            </a>
            </li>
            </ul>

            <ul class="nav nav-treeview">
                <li class="nav-item ">
                    <a href="{{ route('financial-management.RealignmentReport') }}" class="nav-link {{ 'financial-management/realignment_report' == request()->path() ? 'active' : ''}}">
                        <i class="nav-icon fas fa-file"></i>
                        <p>
                            Realignment Report
                        </p>
                    </a>
                </li>
            </ul>


            </li>
            @endcan --}}


            {{-- @can('viewany', \App\Models\FinancialManagement::class)
                <li class="nav-item ">
                    <a href="{{ route('financial-management.FinancialTracking') }}"
            class="nav-link {{ 'financial-management/financial_tracking' == request()->path() ? 'active' : ''}}">
            <i class="nav-icon fas fa-file"></i>
            <p>
                Financial Tracking Report
            </p>
            </a>
            </li>
            @endcan --}}


            {{-- @can('create', \App\Models\FinancialManagement::class)
                <li class="nav-item ">
                    <a href="{{ route('financial-management.create') }}" class="nav-link">
            <i class="nav-icon fas fa-plus"></i>
            <p>
                New Voucher
            </p>
            </a>
            </li>
            @endcan

            @can('createBoxA', \App\Models\FinancialManagement::class)
            <li class="nav-item ">
                <a href="{{ route('financial-management.boxa') }}" class="nav-link">
                    <i class="nav-icon fas fa-plus"></i>
                    <p>
                        Set Box Signatories
                    </p>
                </a>
            </li>
            @endcan --}}

            </ul>
        </nav>

    </div>
    <!-- /.sidebar -->
</aside>
