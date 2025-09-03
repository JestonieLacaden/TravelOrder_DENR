<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="{{asset('images/logo.png')}}" />
        <title>{{ config('app.name', 'Infromation System') }}</title>       
       <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        <!-- Font Awesome Icons -->
        <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css') }}">
        <!-- SweetAlert2 -->
        <link rel="stylesheet" href="{{asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
        <!-- Toastr -->
        <link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css') }}">
        <!-- Theme style -->
        <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css') }}">
    </head>
    <body class="hold-transition login-page">
        
       
        @yield('content')
            
        <script src="{{asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>        
        <script src="{{asset('plugins/toastr/toastr.min.js') }}"></script>
        <script src="{{asset('plugins/jquery/jquery.min.js') }}"></script>
        <script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{asset('dist/js/adminlte.min.js') }}"></script>
      
        {{-- @if ($message = Session::get('error'))
            <script>
                Swal.fire({
                icon: 'error',
                title: 'Ooopppsss...',
                text: '{{ $message}}',
                showConfirmButton: false,
                timer: 2000
                })
                
            </script>
        @endif --}}
    </body>
</html>

