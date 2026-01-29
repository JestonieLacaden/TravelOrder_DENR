@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>All Notifications</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Notifications</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Memorandum Notifications</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-sm btn-primary" onclick="markAllAsRead()">
                            <i class="fas fa-check-double"></i> Mark All as Read
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($notifications->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($notifications as $notification)
                        <a href="{{ route('memorandums.show', $notification->memorandum_id) }}" class="list-group-item list-group-item-action {{ $notification->is_read ? '' : 'bg-light' }}">
                            <div class="d-flex w-100 justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center mb-1">
                                        @if($notification->type === 'forwarded')
                                        <i class="fas fa-share text-primary mr-2"></i>
                                        @elseif($notification->type === 'returned')
                                        <i class="fas fa-undo text-warning mr-2"></i>
                                        @elseif($notification->type === 'approved')
                                        <i class="fas fa-check-circle text-success mr-2"></i>
                                        @elseif($notification->type === 'commented')
                                        <i class="fas fa-comment text-info mr-2"></i>
                                        @elseif($notification->type === 'revised')
                                        <i class="fas fa-edit text-secondary mr-2"></i>
                                        @else
                                        <i class="fas fa-bell mr-2"></i>
                                        @endif

                                        <h6 class="mb-0">{{ $notification->title }}</h6>

                                        @if(!$notification->is_read)
                                        <span class="badge badge-primary ml-2">New</span>
                                        @endif
                                    </div>

                                    <p class="mb-1 text-sm">{{ $notification->message }}</p>

                                    <small class="text-muted">
                                        <i class="far fa-user"></i> {{ $notification->actor ? $notification->actor->name : 'System' }}
                                        &nbsp;|&nbsp;
                                        <i class="far fa-clock"></i> {{ $notification->created_at->diffForHumans() }}
                                    </small>
                                </div>

                                <div class="ml-3">
                                    <i class="fas fa-chevron-right text-muted"></i>
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No notifications yet</p>
                    </div>
                    @endif
                </div>

                @if($notifications->hasPages())
                <div class="card-footer">
                    {{ $notifications->links() }}
                </div>
                @endif
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script>
    function markAllAsRead() {
        $.post('{{ route("memorandums.notifications.mark-all-read") }}', {
            _token: '{{ csrf_token() }}'
        }).done(function() {
            toastr.success('All notifications marked as read');
            setTimeout(function() {
                location.reload();
            }, 1000);
        }).fail(function() {
            toastr.error('Failed to mark notifications as read');
        });
    }

</script>
@endpush
@endsection
