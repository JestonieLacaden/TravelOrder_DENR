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

    <style>
        /* Smooth transitions for sidebar */
        .main-sidebar,
        .content-wrapper,
        .main-header {
            transition: margin-left 0.3s ease-in-out, width 0.3s ease-in-out, left 0.3s ease-in-out !important;
        }

        /* Force AdminLTE structure */
        .main-sidebar {
            position: fixed !important;
            top: 0 !important;
            height: 100vh !important;
            z-index: 1038 !important;
            overflow-y: auto !important;
        }

        /* Desktop - sidebar visible by default */
        @media (min-width: 992px) {

            /* Sidebar always at left on desktop */
            .main-sidebar {
                left: 0 !important;
            }

            /* Content and header with sidebar open (default) */
            body:not(.sidebar-collapse) .content-wrapper,
            body:not(.sidebar-collapse) .main-header {
                margin-left: 250px !important;
            }

            /* Content and header with sidebar collapsed (mini mode) */
            body.sidebar-collapse .content-wrapper,
            body.sidebar-collapse .main-header {
                margin-left: 4.6rem !important;
            }
        }

        /* Mobile/Tablet - sidebar hidden by default */
        @media (max-width: 991.98px) {

            .content-wrapper,
            .main-header {
                margin-left: 0 !important;
            }

            /* Sidebar hidden by default on mobile */
            body.sidebar-collapse .main-sidebar,
            body.sidebar-closed .main-sidebar {
                left: -250px !important;
            }

            /* Sidebar visible when opened on mobile */
            body:not(.sidebar-collapse):not(.sidebar-closed) .main-sidebar,
            body.sidebar-open .main-sidebar {
                left: 0 !important;
                box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
            }

            /* Dark overlay when sidebar is open on mobile */
            body:not(.sidebar-collapse):not(.sidebar-closed)::before,
            body.sidebar-open::before {
                content: '';
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                z-index: 1037;
            }
        }

        /* Mobile Responsive Adjustments */
        @media (max-width: 767px) {

            /* Make tables scrollable on mobile */
            .table-responsive {
                display: block;
                width: 100%;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            /* Adjust card padding for mobile */
            .card-body {
                padding: 0.75rem;
            }

            /* Stack buttons vertically on mobile */
            .btn-group {
                display: flex;
                flex-direction: column;
            }

            .btn-group .btn {
                margin-bottom: 0.5rem;
            }

            /* Adjust form controls */
            .form-group label {
                font-size: 0.9rem;
            }

            /* Reduce content wrapper padding on mobile */
            .content-wrapper {
                padding: 0.5rem !important;
            }

            .content-header {
                padding: 0.5rem 0.5rem 0 0.5rem !important;
            }

            /* Make modals full screen on mobile */
            .modal-dialog {
                margin: 0.5rem;
            }

            .modal-content {
                border-radius: 0.5rem;
            }
        }

        /* Tablet adjustments */
        @media (min-width: 768px) and (max-width: 991.98px) {

            /* Tables */
            table {
                font-size: 0.9rem;
            }
        }

    </style>

    @stack('styles')
</head>

<body class="hold-transition sidebar-mini layout-fixed">
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

        $(document).ready(function() {
            // Set initial sidebar state based on screen size
            function setInitialSidebarState() {
                const isMobile = $(window).width() < 992;
                const body = $('body');

                if (isMobile) {
                    // Mobile: hide sidebar initially
                    if (!body.hasClass('sidebar-collapse')) {
                        body.addClass('sidebar-collapse');
                    }
                }
                // Desktop: let AdminLTE handle it (default is open)
            }

            // Set initial state
            setInitialSidebarState();

            // Handle window resize - only adjust when crossing breakpoint
            let previouslyMobile = $(window).width() < 992;

            $(window).on('resize', function() {
                const currentlyMobile = $(window).width() < 992;

                // Only adjust if we crossed the mobile/desktop breakpoint
                if (previouslyMobile !== currentlyMobile) {
                    if (currentlyMobile) {
                        // Just switched to mobile - collapse sidebar
                        $('body').addClass('sidebar-collapse');
                    }
                    // When switching to desktop, let AdminLTE handle the state
                }

                previouslyMobile = currentlyMobile;
            });

            // On mobile, close sidebar when clicking the overlay
            $('body').on('click', function(e) {
                const isMobile = $(window).width() < 992;
                const body = $('body');

                if (isMobile && !body.hasClass('sidebar-collapse')) {
                    // Check if click is outside sidebar
                    if (!$(e.target).closest('.main-sidebar, [data-widget="pushmenu"]').length) {
                        body.addClass('sidebar-collapse');
                    }
                }
            });
        });

    </script>

    {{-- Page-specific scripts (e.g., your edit modal init) --}}
    @stack('scripts')


    <script>
        // Current logged-in employee ID (or null)
        window.employeeId = @json(optional(auth() -> user() -> Employee) -> id ?? null);

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
                        var message = diff === 1 ?
                            'A new Travel Order request has been submitted. Please refresh to view.' :
                            diff + ' new Travel Order requests have been submitted. Please refresh to view.';

                        toastr.info(message, 'New Request', {
                            closeButton: true
                            , progressBar: true
                            , timeOut: 0
                            , extendedTimeOut: 0
                            , positionClass: 'toast-top-right'
                            , onclick: function() {
                                location.reload();
                            }
                        });
                    }

                    // Check if on Leave Request page and count INCREASED (new request sent)
                    if (path.indexOf('/mail/leave-request') !== -1 && leave > lastLeave) {
                        var diff = leave - lastLeave;
                        var message = diff === 1 ?
                            'A new Leave request has been submitted. Please refresh to view.' :
                            diff + ' new Leave requests have been submitted. Please refresh to view.';

                        toastr.info(message, 'New Request', {
                            closeButton: true
                            , progressBar: true
                            , timeOut: 0
                            , extendedTimeOut: 0
                            , positionClass: 'toast-top-right'
                            , onclick: function() {
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

    {{-- Memorandum Notifications --}}
    <script>
        (function() {
            var notificationsEndpoint = '{{ route("memorandums.notifications.get") }}';
            var markReadEndpoint = '{{ route("memorandums.notifications.mark-read", ":id") }}';
            var markAllReadEndpoint = '{{ route("memorandums.notifications.mark-all-read") }}';

            function updateNotificationBadge(count) {
                var badge = $('#notificationCount');
                if (count > 0) {
                    badge.text(count > 99 ? '99+' : count).show();
                } else {
                    badge.hide();
                }
            }

            function getNotificationIcon(type) {
                var icons = {
                    'forwarded': '<i class="fas fa-share text-primary"></i>'
                    , 'returned': '<i class="fas fa-undo text-warning"></i>'
                    , 'approved': '<i class="fas fa-check-circle text-success"></i>'
                    , 'commented': '<i class="fas fa-comment text-info"></i>'
                    , 'revised': '<i class="fas fa-edit text-secondary"></i>'
                    , 'finalized': '<i class="fas fa-flag-checkered text-success"></i>'
                };
                return icons[type] || '<i class="fas fa-bell"></i>';
            }

            function renderNotifications(notifications) {
                var list = $('#notificationList');

                if (!notifications || notifications.length === 0) {
                    list.html('<a href="#" class="dropdown-item text-center text-muted"><small>No new notifications</small></a>');
                    return;
                }

                var html = '';
                notifications.forEach(function(notification) {
                    var readClass = notification.is_read ? 'bg-light' : 'bg-white';
                    var url = '{{ route("memorandums.show", ":id") }}'.replace(':id', notification.memorandum_id);

                    html += `
                        <a href="${url}" class="dropdown-item ${readClass} notification-item" data-id="${notification.id}">
                            <div class="d-flex align-items-start">
                                <div class="mr-2">${getNotificationIcon(notification.type)}</div>
                                <div class="flex-grow-1">
                                    <p class="mb-0 text-sm"><strong>${notification.title}</strong></p>
                                    <p class="mb-0 text-xs text-muted">${notification.message}</p>
                                    <p class="mb-0 text-xs text-muted"><i class="far fa-clock"></i> ${notification.created_at}</p>
                                </div>
                            </div>
                        </a>
                        <div class="dropdown-divider"></div>
                    `;
                });

                list.html(html);

                // Mark notification as read when clicked
                $('.notification-item').on('click', function(e) {
                    var notificationId = $(this).data('id');
                    $.post(markReadEndpoint.replace(':id', notificationId), {
                        _token: '{{ csrf_token() }}'
                    });
                });
            }

            function loadNotifications() {
                $.getJSON(notificationsEndpoint)
                    .done(function(data) {
                        updateNotificationBadge(data.unread_count);
                        $('#notificationHeader').text(data.unread_count + ' Notification' + (data.unread_count !== 1 ? 's' : ''));
                        renderNotifications(data.notifications);
                    })
                    .fail(function() {
                        console.error('Failed to load notifications');
                    });
            }

            // Load notifications on page load
            loadNotifications();

            // Refresh notifications every 30 seconds
            setInterval(loadNotifications, 30000);

            // Mark all as read button (optional - can add to header)
            window.markAllNotificationsAsRead = function() {
                $.post(markAllReadEndpoint, {
                    _token: '{{ csrf_token() }}'
                }).done(function() {
                    loadNotifications();
                    toastr.success('All notifications marked as read');
                });
            };

            // Reload notifications when dropdown is opened
            $('#notificationDropdown').on('show.bs.dropdown', function() {
                loadNotifications();
            });
        })();

    </script>









</body>

</html>
