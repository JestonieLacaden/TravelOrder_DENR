@extends('layouts.auth')

@section('content')


<div class="login-box">

    <div class="row">
        <div class="col-12 text-center">
            <div class="icheck-primary p-2">
                <div class="mb-2">
                    <img src="{{ asset('images/logo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="height: 100px">
                </div>
                <div>
                    PENRO OCCIDENTAL MINDORO
                    <h5><strong> Information System </strong> </h5>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Sign in to start your session</p>
            <form method="POST" action="{{ route('login.custom') }}">
                {{ csrf_field() }}
                <div class="form-group mb-3">
                    <label for="email">Username or Email</label>
                    <input type="text" id="email" name="email" class="form-control" placeholder="Enter Username or Email Address" value="{{ old('email') }}" required>
                    @error('email')
                    <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="password">Password</label>
                    <div class="input-group">
                        <input type="password" id="password" name="password" class="form-control" placeholder="Enter Password" required>
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                <i class="fas fa-eye" id="toggleIcon"></i>
                            </button>
                        </div>
                    </div>
                    @error('password')
                    <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-8">
                        {{-- Register button hidden --}}
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

<script>
    // Show/Hide Password Toggle
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    });

</script>

@endsection
