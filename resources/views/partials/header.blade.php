 <!-- Navbar -->
 <nav class="main-header navbar navbar-expand navbar-white navbar-light">
     <!-- Left navbar links -->
     <ul class="navbar-nav">
         <li class="nav-item">
             <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
         </li>
     </ul>

     <!-- Right navbar links -->
     <ul class="navbar-nav ml-auto">
         <li class="nav-item">

             <div class="mt-2 pr-2"> Hi {{ auth()->user()->username }} ! </div>
         </li>
         <li class="nav-item">
             <i class="fas fas-line"></i>
         </li>
         <li class="nav-item">
             <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                 <i class="fas fa-expand-arrows-alt"></i>
             </a>
         </li>


         <li class="nav-item dropdown">
             <a class="nav-link" data-toggle="dropdown" href="#">
                 <i class="fas fa-cog"></i>
             </a>
             <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                 <div class="card card-widget widget-user">
                     <!-- Add the bg color to the header using any of the bg-* classes -->
                     <div class="widget-user-header bg-success">
                         <h6 class="widget-user-username">{{ auth()->user()->username }}</h6>
                         @php
                         $employee = \App\Models\Employee::where('email', auth()->user()->email)->first();
                         @endphp
                         @if($employee)
                         <h6 class="widget-user-desc">{{ $employee->position }}</h6>
                         <p class="widget-user-desc text-sm">{{ $employee->Unit ? $employee->Unit->unit : '' }}</p>
                         @endif
                     </div>
                     {{-- <div class="widget-user-image">
                         <img class="img-circle elevation-2" src="{{ asset('images/logo.png') }}" alt="User Avatar">

                 </div> --}}
                 <div class="card-footer">
                     <div class="col-sm-12">
                         <div class="dropdown-divider"></div>
                         <a href="{{ route('changepassword.index') }}" class="dropdown-item dropdown-footer text-primary">
                             <i class="fas fa-key mr-2"></i> Change Password
                         </a>
                     </div>
                     <div class="col-sm-12">
                         <div class="dropdown-divider"></div>
                         <a href="{{ route('logout') }}" class="dropdown-item dropdown-footer text-danger">
                             <i class="fas fa-sign-out-alt mr-2"></i> Log Out
                         </a>
                     </div>
                 </div>
             </div>
             </div>
         </li>
     </ul>
 </nav>
 <!-- /.navbar -->
