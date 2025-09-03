<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Prefer local assets over CDN to avoid mixed versions --}}
    <link rel="icon" href="{{ asset('images/logo.png') }}" />
    <title>{{ config('app.name', 'Information System') }}</title>

    @include('partials.style')

    {{-- Keep ONE daterangepicker.css (local) --}}
    <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">

    @stack('styles')
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        @include('partials.header')
        @include('partials.sidebar')

        @yield('content')

        @include('partials.footer')
    </div>

    {{-- Load your app/global JS (likely includes jQuery + Bootstrap/AdminLTE) --}}
    @include('partials.javascript')

    {{-- DO NOT load jQuery again if partials.javascript already has it --}}
    {{-- <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script> --}}

    {{-- These depend on jQuery being already loaded in partials.javascript --}}
    <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>

    {{-- (Optional) debug AFTER libs are in place --}}
    <script>
        if (window.jQuery) {
      console.log('jQuery Version:', $.fn.jquery);
    } else {
      console.warn('jQuery not found — check partials.javascript');
    }
    </script>

    {{-- Page-specific scripts (e.g., your edit modal init) --}}
    @stack('scripts')
</body>

</html>