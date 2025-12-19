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


    <script>
        window.employeeId = {
            {
                optional(auth() - > user() - > Employee) - > id ?? 'null'
            }
        };

    </script>

    <script src="https://cdn.jsdelivr.net/npm/pusher-js@7/dist/web/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1/dist/echo.iife.js"></script>

    <script>
        window.Echo = new Echo({
            broadcaster: 'pusher'
            , key: 'local-key'
            , wsHost: window.location.hostname
            , wsPort: 6001
            , forceTLS: false
            , disableStats: true
            , enabledTransports: ['ws', 'wss']
        , });

        // 1) listen for your own Travel Order updates
        if (window.employeeId) {
            window.Echo.private('users/' + window.employeeId)
                .listen('.TravelOrderStatusChanged', (e) => {
                    // find row and update the status text
                    const row = document.querySelector(`[data-to-id="${e.id}"]`);
                    const cell = row ? row.querySelector('.js-status') : null;
                    if (!cell) return;

                    if (e.is_approve2) {
                        cell.className = 'js-status bg-success p-2 rounded';
                        cell.textContent = e.approved_code ? `Approved (${e.approved_code})` : 'Approved';
                    } else {
                        cell.className = 'js-status bg-warning p-2 rounded';
                        cell.textContent = 'Pending : 2nd Approval';
                    }
                });
        }

        // 2) signatory badge
        window.Echo.private('signatories')
            .listen('.RequestsCountChanged', (e) => {
                const badge = document.getElementById('req-badge');
                if (!badge) return;
                const c = e.count || 0;
                badge.textContent = c > 999 ? '999+' : c;
                badge.style.display = c > 0 ? '' : 'none';
            });

    </script>

    <script>
        (function() {
            var endpoint = @json(route('mail.pending-counts'));

            function setBadge(selector, value) {
                var el = $(selector);
                if (!el.length) return;
                value = Number(value || 0);
                if (value > 0) el.text(value > 999 ? '999+' : value).show();
                else el.hide();
            }

            // badge refresher for sidebar
            function refreshBadges() {
                $.getJSON(endpoint).done(function(d) {
                    setBadge('#leave-badge', d && d.leave);
                    setBadge('#to-badge', d && d.to);
                });
            }

            refreshBadges();
            setInterval(refreshBadges, 15000);

            // Show notification when NEW requests arrive (count increases)
            var path = window.location.pathname;
            var lastTO = Number(@json($toPendingCount ?? 0));
            var lastLeave = Number(@json($leavePendingCount ?? 0));

            function checkForUpdates() {
                $.getJSON(endpoint).done(function(d) {
                    var to = Number(d && d.to || 0);
                    var leave = Number(d && d.leave || 0);

                    // Check if on TO Request page and count INCREASED (new request sent)
                    if (path.indexOf('/mail/travel-order-request') !== -1 && to > lastTO) {
                        var diff = to - lastTO;
                        var message = diff === 1 
                            ? 'A new Travel Order request has been submitted. Please refresh to view.' 
                            : diff + ' new Travel Order requests have been submitted. Please refresh to view.';
                        
                        toastr.info(message, 'New Request', {
                            closeButton: true,
                            progressBar: true,
                            timeOut: 0,
                            extendedTimeOut: 0,
                            positionClass: 'toast-top-right',
                            onclick: function() {
                                location.reload();
                            }
                        });
                    }

                    // Check if on Leave Request page and count INCREASED (new request sent)
                    if (path.indexOf('/mail/leave-request') !== -1 && leave > lastLeave) {
                        var diff = leave - lastLeave;
                        var message = diff === 1 
                            ? 'A new Leave request has been submitted. Please refresh to view.' 
                            : diff + ' new Leave requests have been submitted. Please refresh to view.';
                        
                        toastr.info(message, 'New Request', {
                            closeButton: true,
                            progressBar: true,
                            timeOut: 0,
                            extendedTimeOut: 0,
                            positionClass: 'toast-top-right',
                            onclick: function() {
                                location.reload();
                            }
                        });
                    }

                    lastTO = to;
                    lastLeave = leave;
                });
            }

            // Watch for changes only if on request pages
            if (path.indexOf('/mail/travel-order-request') !== -1 ||
                path.indexOf('/mail/leave-request') !== -1) {
                setInterval(checkForUpdates, 15000);
            }
        })();

    </script>









</body>

</html>
