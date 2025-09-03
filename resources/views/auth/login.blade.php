@extends('layouts.auth')

@section('content')


    <div class="login-box">

        <div class="row">
            <div class="col-12 text-center" >
                <div class="icheck-primary p-2">
                    <div class="mb-2">
                        <img src="{{ asset('images/logo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="height: 100px">
                    </div>
                    <div>
                        PENRO OCCIDENTAL MINDORO
                        <h5><strong> Information System </strong>  </h5>    
                    </div>       
                </div>
            </div>
        </div>
       
         <div class="card">
             <div class="card-body login-card-body">
                <p class="login-box-msg">Sign in to start your session</p>
                <form method="POST" action="{{ route('login.custom') }}" >
                     {{ csrf_field() }}
                     <div class="input-group mb-3">
                         <input type="email" id="email" name="email" class="form-control" placeholder="Enter Email Address" value=" {{ old('email') }}">
                             <div class="input-group-append">
                                 <div class="input-group-text">
                                     <span class="fas fa-envelope"></span>
                                 </div>
                             </div> 
                     </div>
                     @error('email')
                         <p class="text-danger text-xs mt-1">{{$message}}</p>
                     @enderror

                     <div class="input-group mb-3">
                         <input type="password" id="password" name="password" class="form-control" placeholder="Enter Password">
                             <div class="input-group-append">
                                 <div class="input-group-text">
                                 <span class="fas fa-lock"></span>
                             </div>
                         </div>
                     </div>
                     @error('password')
                         <p class="text-danger text-xs mt-1">{{$message}}</p>
                     @enderror

                     <div class="row">
                        <div class="col-8">
                          <div class="icheck-primary">
                            <p class="mt-2">
                                <a href="register.html" class="text-center">Register </a>
                              </p>
                          </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-4">
                          <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                        </div>
                        <!-- /.col -->
                    </div>
                     <!-- /.col -->
                </form>
            </div>
        </div>
    </div>
     <!-- /.login-card-body -->
@endsection