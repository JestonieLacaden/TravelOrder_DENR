@extends('layouts.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>My Memoranda</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Memorandums</li>
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
                            <h3 class="card-title">My Memoranda List</h3>
                            <div class="card-tools">
                                <a href="{{ route('memorandums.create') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i> Create New Memorandum
                                </a>
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
                                        <div class="col-md-3">
                                            <input type="date" id="filterDateFrom" class="form-control form-control-sm" placeholder="From Date">
                                        </div>
                                        <span class="align-self-center mx-2">-</span>
                                        <div class="col-md-3">
                                            <input type="date" id="filterDateTo" class="form-control form-control-sm" placeholder="To Date">
                                        </div>
                                        <div class="col-md-5">
                                            <select id="filterStatus" class="form-control form-control-sm">
                                                <option value="">All Status</option>
                                                <option value="Draft">Draft</option>
                                                <option value="For Review">For Review</option>
                                                <option value="Returned">Returned</option>
                                                <option value="Approved">Approved</option>
                                                <option value="Previewed">Previewed</option>
                                                <option value="Generated">Generated</option>
                                                <option value="Forwarded">Forwarded</option>
                                                <option value="Signed">Signed</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <table class="table table-bordered table-striped" id="memorandumsTable">
                                <thead>
                                    <tr>
                                        <th>Memo #</th>
                                        <th>Date</th>
                                        <th>Subject</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($memorandums as $memo)
                                    <tr>
                                        <td data-memo-number="{{ $memo->memorandum_number }}" data-raw-date="{{ $memo->memorandum_date ? $memo->memorandum_date->format('Y-m-d') : '' }}">{{ $memo->memorandum_number }}</td>
                                        <td data-date="{{ $memo->memorandum_date ? $memo->memorandum_date->format('M d, Y') : '-' }}">{{ $memo->memorandum_date ? $memo->memorandum_date->format('M d, Y') : '-' }}</td>
                                        <td data-subject="{{ $memo->subject }}">{{ Str::limit($memo->subject, 50) }}</td>
                                        <td data-status="{{ $memo->status }}">
                                            @if($memo->status === 'Draft')
                                            <span class="badge badge-secondary">Draft</span>
                                            @elseif($memo->status === 'Previewed')
                                            <span class="badge badge-info">Previewed</span>
                                            @elseif($memo->status === 'For Review')
                                            <span class="badge badge-warning">For Review</span>
                                            @elseif($memo->status === 'Returned')
                                            <span class="badge badge-danger">Returned</span>
                                            @elseif($memo->status === 'Approved')
                                            <span class="badge badge-success">Approved</span>
                                            @elseif($memo->status === 'Generated')
                                            <span class="badge badge-success">Generated</span>
                                            @elseif($memo->status === 'Forwarded')
                                            <span class="badge badge-primary">Forwarded</span>
                                            @elseif($memo->status === 'Signed')
                                            <span class="badge badge-dark">Signed</span>
                                            @endif
                                        </td>

                                        <td>
                                            <a href="{{ route('memorandums.show', $memo->id) }}" class="btn btn-sm btn-info" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($memo->status === 'Draft')
                                            <a href="{{ route('memorandums.edit', $memo->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @endif
                                            <form action="{{ route('memorandums.destroy', $memo->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this memorandum?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No memorandums found.</td>
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
        // Search functionality
        $('#searchInput').on('keyup', function() {
            filterTable();
        });

        // Filter functionality
        $('#filterDateFrom, #filterDateTo, #filterStatus').on('change', function() {
            filterTable();
        });

        function filterTable() {
            let searchValue = $('#searchInput').val().toLowerCase();
            let dateFromFilter = $('#filterDateFrom').val();
            let dateToFilter = $('#filterDateTo').val();
            let statusFilter = $('#filterStatus').val().toLowerCase();

            $('#memorandumsTable tbody tr').each(function() {
                let row = $(this);
                let memoNumber = row.find('td:eq(0)').data('memo-number') || '';
                let rawDate = row.find('td:eq(0)').data('raw-date') || '';
                let date = row.find('td:eq(1)').data('date') || '';
                let subject = row.find('td:eq(2)').data('subject') || '';
                let status = row.find('td:eq(3)').data('status') || '';

                // Convert all to lowercase for comparison
                memoNumber = memoNumber.toString().toLowerCase();
                date = date.toString().toLowerCase();
                subject = subject.toString().toLowerCase();
                status = status.toString().toLowerCase();

                // Search across all fields
                let searchMatch = searchValue === '' ||
                    memoNumber.includes(searchValue) ||
                    date.includes(searchValue) ||
                    subject.includes(searchValue) ||
                    status.includes(searchValue);

                // Date range filter
                let dateMatch = true;
                if (rawDate && (dateFromFilter || dateToFilter)) {
                    if (dateFromFilter && rawDate < dateFromFilter) {
                        dateMatch = false;
                    }
                    if (dateToFilter && rawDate > dateToFilter) {
                        dateMatch = false;
                    }
                }

                // Status filter
                let statusMatch = statusFilter === '' || status === statusFilter;

                // Show/hide row based on all conditions
                if (searchMatch && dateMatch && statusMatch) {
                    row.show();
                } else {
                    row.hide();
                }
            });

            // Show "No results" message if all rows are hidden
            let visibleRows = $('#memorandumsTable tbody tr:visible').length;
            if (visibleRows === 0) {
                if ($('#noResultsRow').length === 0) {
                    $('#memorandumsTable tbody').append(
                        '<tr id="noResultsRow"><td colspan="5" class="text-center">No matching memorandums found.</td></tr>'
                    );
                }
            } else {
                $('#noResultsRow').remove();
            }
        }
    });

</script>
@endsection
