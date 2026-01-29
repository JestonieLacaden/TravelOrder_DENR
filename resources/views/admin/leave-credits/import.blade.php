@extends('layouts.app')

@section('content')

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Import Leave Credits</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Leave Management</a></li>
                        <li class="breadcrumb-item active">Import Credits</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">

            <!-- Upload Form Card -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-file-excel"></i> Upload Leave Card Excel File
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <form id="uploadForm" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="excel_file">Select Excel File (.xlsx or .xls)</label>
                                    <div class="input-group">
                                        <input type="file" name="excel_file" id="excel_file" class="form-control" accept=".xlsx,.xls" required>
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-primary" id="uploadBtn">
                                                <i class="fas fa-upload"></i> Upload & Preview
                                            </button>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted">
                                        Supported formats: .xlsx, .xls | Maximum size: 10MB
                                    </small>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box bg-info">
                                <span class="info-box-icon"><i class="fas fa-info-circle"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Expected Format</span>
                                    <span class="info-box-number">Leave Card (1990 Format)</span>
                                    <div class="progress">
                                        <div class="progress-bar" style="width: 100%"></div>
                                    </div>
                                    <span class="progress-description">
                                        <small>Employee name in header<br>Monthly earned credits in columns</small>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Loading indicator -->
                    <div id="loadingIndicator" style="display: none;" class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Processing file...</span>
                        </div>
                        <p class="mt-2">Processing Excel file, please wait...</p>
                    </div>
                </div>
            </div>

            <!-- Preview Card -->
            <div class="card" id="previewCard" style="display: none;">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-eye"></i> Preview Import Data
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-danger btn-sm" id="cancelBtn">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Employee Info -->
                    <div id="employeeInfo" class="mb-3">
                        <div class="row">
                            <div class="col-md-6">
                                <h5><strong>Employee Information</strong></h5>
                                <p id="employeeName" class="mb-1"></p>
                                <p id="employeeMatch" class="mb-1"></p>
                            </div>
                            <div class="col-md-6">
                                <div id="importSummary" class="text-right">
                                    <div class="info-box bg-light">
                                        <span class="info-box-icon bg-success"><i class="fas fa-check"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Import Summary</span>
                                            <span class="info-box-number" id="summaryText">0 / 0 records</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Data Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="previewTable">
                            <thead class="bg-light">
                                <tr>
                                    <th>Year</th>
                                    <th>Month</th>
                                    <th>Vacation Earned</th>
                                    <th>Sick Earned</th>
                                    <th>Vacation Balance</th>
                                    <th>Sick Balance</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="previewTableBody">
                            </tbody>
                        </table>
                    </div>

                    <!-- Action Buttons -->
                    <div class="text-center mt-4">
                        <button type="button" class="btn btn-success btn-lg" id="importBtn">
                            <i class="fas fa-save"></i> Confirm & Import Data
                        </button>
                        <button type="button" class="btn btn-secondary btn-lg ml-2" id="cancelImportBtn">
                            <i class="fas fa-times"></i> Cancel Import
                        </button>
                    </div>
                </div>
            </div>

            <!-- Recent Imports -->
            @if(!$recentImports->isEmpty())
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-history"></i> Recent Imports
                    </h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Employee</th>
                                    <th>Imported By</th>
                                    <th>Records</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentImports as $date => $imports)
                                @foreach($imports as $import)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($import->imported_at)->format('M d, Y H:i') }}</td>
                                    <td>
                                        <strong>{{ optional($import->employee)->firstname }} {{ optional($import->employee)->lastname }}</strong>
                                    </td>
                                    <td>{{ optional($import->importedBy)->name ?? 'System' }}</td>
                                    <td>
                                        <span class="badge badge-info">
                                            {{ $imports->where('employeeid', $import->employeeid)->count() }} records
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </section>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Upload form submission
        $('#uploadForm').on('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const fileInput = $('#excel_file')[0];

            if (!fileInput.files.length) {
                toastr.error('Please select a file to upload');
                return;
            }

            $('#loadingIndicator').show();
            $('#uploadBtn').prop('disabled', true);

            $.ajax({
                url: '{{ route("leave-credits.upload") }}'
                , type: 'POST'
                , data: formData
                , processData: false
                , contentType: false
                , success: function(response) {
                    if (response.success) {
                        showPreview(response.data);
                        toastr.success(response.message);
                    } else {
                        toastr.error(response.message);
                    }
                }
                , error: function(xhr) {
                    const response = xhr.responseJSON;
                    toastr.error(response ? .message || 'Error uploading file');
                }
                , complete: function() {
                    $('#loadingIndicator').hide();
                    $('#uploadBtn').prop('disabled', false);
                }
            });
        });

        // Show preview data
        function showPreview(data) {
            // Employee info
            $('#employeeName').html('<strong>Name:</strong> ' + data.employee_name);

            let matchStatus = data.employee_matched ?
                '<span class="text-success"><i class="fas fa-check"></i> Matched in database</span>' :
                '<span class="text-warning"><i class="fas fa-exclamation-triangle"></i> Employee not found - please verify name</span>';
            $('#employeeMatch').html('<strong>Status:</strong> ' + matchStatus);

            // Summary
            $('#summaryText').text(data.summary.valid_records + ' / ' + data.summary.total_records + ' valid records');

            // Table data
            let tableHtml = '';
            data.records.forEach(function(record) {
                let statusBadge = record.valid ?
                    '<span class="badge badge-success">Valid</span>' :
                    '<span class="badge badge-danger">Invalid</span>';

                if (record.errors.length > 0) {
                    statusBadge += '<br><small class="text-danger">' + record.errors.join(', ') + '</small>';
                }

                tableHtml += `
                <tr>
                    <td>${record.period_year}</td>
                    <td>${record.month_name}</td>
                    <td>${record.vacation_earned}</td>
                    <td>${record.sick_earned}</td>
                    <td>${record.vacation_balance || 'N/A'}</td>
                    <td>${record.sick_balance || 'N/A'}</td>
                    <td>${statusBadge}</td>
                </tr>
            `;
            });

            $('#previewTableBody').html(tableHtml);
            $('#previewCard').show();

            // Enable import button only if employee is matched and has valid records
            $('#importBtn').prop('disabled', !data.employee_matched || data.summary.valid_records === 0);
        }

        // Import data
        $('#importBtn').on('click', function() {
            if (!confirm('Are you sure you want to import this data? This action cannot be undone.')) {
                return;
            }

            $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Importing...');

            $.ajax({
                url: '{{ route("leave-credits.import") }}'
                , type: 'POST'
                , data: {
                    _token: '{{ csrf_token() }}'
                }
                , success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        $('#previewCard').hide();
                        $('#uploadForm')[0].reset();

                        // Show summary of errors if any
                        if (response.errors && response.errors.length > 0) {
                            let errorHtml = '<ul>';
                            response.errors.forEach(function(error) {
                                errorHtml += '<li>' + error + '</li>';
                            });
                            errorHtml += '</ul>';

                            toastr.warning('Import completed with some warnings: ' + errorHtml, '', {
                                timeOut: 10000
                            });
                        }

                        // Refresh page to show updated recent imports
                        setTimeout(function() {
                            location.reload();
                        }, 2000);
                    } else {
                        toastr.error(response.message);
                    }
                }
                , error: function(xhr) {
                    const response = xhr.responseJSON;
                    toastr.error(response ? .message || 'Error importing data');
                }
                , complete: function() {
                    $('#importBtn').prop('disabled', false).html('<i class="fas fa-save"></i> Confirm & Import Data');
                }
            });
        });

        // Cancel buttons
        $('#cancelBtn, #cancelImportBtn').on('click', function() {
            if (confirm('Are you sure you want to cancel? All preview data will be lost.')) {
                $.ajax({
                    url: '{{ route("leave-credits.cancel") }}'
                    , type: 'POST'
                    , data: {
                        _token: '{{ csrf_token() }}'
                    }
                });

                $('#previewCard').hide();
                $('#uploadForm')[0].reset();
            }
        });
    });

</script>
@endsection
