@extends('layouts.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Memorandum Inbox</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Inbox</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Memorandums For Review</h3>
                            <div class="card-tools">
                                <span class="badge badge-primary">
                                    {{ $memorandums->where('user_action_status', 'pending')->count() }} Pending
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            @endif

                            @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            @endif

                            <!-- Search and Filter Section -->
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <input type="text" id="searchInput" class="form-control" placeholder="Search memorandums...">
                                        <div class="input-group-append">
                                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <select id="filterCreator" class="form-control form-control-sm">
                                                <option value="">All Creators</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <select id="filterStatus" class="form-control form-control-sm">
                                                <option value="">All Status</option>
                                                <option value="For Review">For Review</option>
                                                <option value="Approved">Approved</option>
                                                <option value="Returned">Returned</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <select id="filterAction" class="form-control form-control-sm">
                                                <option value="">All Actions</option>
                                                <option value="pending">Pending</option>
                                                <option value="approved">Approved</option>
                                                <option value="returned">Returned</option>
                                                <option value="revised">Edited</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <table class="table table-bordered table-striped" id="inboxTable">
                                <thead>
                                    <tr>
                                        <th>Memo #</th>
                                        <th>From</th>
                                        <th>Subject</th>
                                        <th>Status</th>
                                        <th>My Action</th>
                                        <th>Can Edit</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($memorandums as $memo)
                                    <tr>
                                        <td data-memo-number="{{ $memo->memorandum_number }}">{{ $memo->memorandum_number }}</td>
                                        <td data-creator="{{ $memo->forwarded_by_name ?? 'Unknown' }}">{{ $memo->forwarded_by_name ?? 'Unknown' }}</td>
                                        <td data-subject="{{ $memo->subject }}">{{ Str::limit($memo->subject, 50) }}</td>
                                        <td data-status="{{ $memo->status }}">
                                            @if($memo->status === 'For Review')
                                            <span class="badge badge-warning">For Review</span>
                                            @elseif($memo->status === 'Approved')
                                            <span class="badge badge-success">Approved</span>
                                            @elseif($memo->status === 'Returned')
                                            <span class="badge badge-danger">Returned</span>
                                            @endif
                                        </td>
                                        <td data-action="{{ $memo->user_action_status }}">
                                            @if($memo->user_action_status === 'pending')
                                            <span class="badge badge-warning">
                                                <i class="fas fa-clock"></i> Pending
                                                @elseif($memo->user_action_status === 'received')
                                                <span class="badge badge-info">
                                                    <i class="fas fa-inbox"></i> Received
                                                </span>
                                            </span>
                                            @elseif($memo->user_action_status === 'approved')
                                            <span class="badge badge-success">
                                                <i class="fas fa-check"></i> Approved
                                                <option value="received">Received</option>
                                            </span>
                                            @elseif($memo->user_action_status === 'returned')
                                            <span class="badge badge-danger">
                                                <i class="fas fa-times"></i> Returned
                                            </span>
                                            @elseif($memo->user_action_status === 'revised')
                                            <span class="badge badge-info">
                                                <i class="fas fa-edit"></i> Edited
                                            </span>
                                            @endif
                                        </td>
                                        <td data-can-edit="{{ $memo->can_edit ? 'yes' : 'no' }}">
                                            @if($memo->can_edit)
                                            <span class="badge badge-success">
                                                <i class="fas fa-check"></i> Yes
                                            </span>
                                            @else
                                            <span class="badge badge-secondary">
                                                <i class="fas fa-times"></i> No
                                            </span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('memorandums.show', ['id' => $memo->id, 'from' => 'inbox']) }}" class="btn btn-sm btn-info" title="View & Review">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No memorandums in your inbox.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>

                            <div class="mt-3">
                                {{ $memorandums->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    $(document).ready(function() {
        // Populate filter dropdowns with unique values from table
        populateFilters();

        // Search functionality
        $('#searchInput').on('keyup', function() {
            filterTable();
        });

        // Filter functionality
        $('#filterCreator, #filterStatus, #filterAction').on('change', function() {
            filterTable();
        });

        function populateFilters() {
            let creators = new Set();

            $('#inboxTable tbody tr').each(function() {
                let creator = $(this).find('td:eq(1)').data('creator');

                if (creator) creators.add(creator);
            });

            // Populate Creator filter
            Array.from(creators).sort().forEach(function(value) {
                $('#filterCreator').append($('<option>', {
                    value: value
                    , text: value
                }));
            });
        }

        function filterTable() {
            let searchValue = $('#searchInput').val().toLowerCase();
            let creatorFilter = $('#filterCreator').val().toLowerCase();
            let statusFilter = $('#filterStatus').val().toLowerCase();
            let actionFilter = $('#filterAction').val().toLowerCase();

            $('#inboxTable tbody tr').each(function() {
                let row = $(this);
                let memoNumber = row.find('td:eq(0)').data('memo-number') || '';
                let creator = row.find('td:eq(1)').data('creator') || '';
                let subject = row.find('td:eq(2)').data('subject') || '';
                let status = row.find('td:eq(3)').data('status') || '';
                let action = row.find('td:eq(4)').data('action') || '';

                // Convert all to lowercase for comparison
                memoNumber = memoNumber.toString().toLowerCase();
                creator = creator.toString().toLowerCase();
                subject = subject.toString().toLowerCase();
                status = status.toString().toLowerCase();
                action = action.toString().toLowerCase();

                // Search across all fields
                let searchMatch = searchValue === '' ||
                    memoNumber.includes(searchValue) ||
                    creator.includes(searchValue) ||
                    subject.includes(searchValue) ||
                    status.includes(searchValue) ||
                    action.includes(searchValue);

                // Filter by individual columns
                let creatorMatch = creatorFilter === '' || creator === creatorFilter;
                let statusMatch = statusFilter === '' || status === statusFilter;
                let actionMatch = actionFilter === '' || action === actionFilter;

                // Show/hide row based on all conditions
                if (searchMatch && creatorMatch && statusMatch && actionMatch) {
                    row.show();
                } else {
                    row.hide();
                }
            });

            // Show "No results" message if all rows are hidden
            let visibleRows = $('#inboxTable tbody tr:visible').length;
            if (visibleRows === 0) {
                if ($('#noResultsRow').length === 0) {
                    $('#inboxTable tbody').append(
                        '<tr id="noResultsRow"><td colspan="7" class="text-center">No matching memorandums found.</td></tr>'
                    );
                }
            } else {
                $('#noResultsRow').remove();
            }
        }
    });

</script>
@endsection
