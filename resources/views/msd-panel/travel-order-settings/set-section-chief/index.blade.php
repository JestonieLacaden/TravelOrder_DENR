@extends('layouts.app')

@push('styles')
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
<!-- Select2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css">
<style>
    /* Fix Select2 dropdown border */
    .select2-container--bootstrap4 .select2-selection {
        border: 1px solid #ced4da !important;
        border-radius: 0.25rem;
    }

    .select2-container--bootstrap4 .select2-selection:focus,
    .select2-container--bootstrap4 .select2-selection--single:focus {
        border-color: #80bdff !important;
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .select2-container--bootstrap4 .select2-selection--single {
        height: calc(2.25rem + 2px) !important;
    }

    .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
        line-height: 2.25rem !important;
    }

    .select2-container--bootstrap4 .select2-selection--single .select2-selection__arrow {
        height: calc(2.25rem + 2px) !important;
    }

</style>
@endpush

@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><i class="fas fa-user-tie"></i> Set Section Chief</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item">MSD Management</li>
                        <li class="breadcrumb-item">Settings</li>
                        <li class="breadcrumb-item">Travel Order Settings</li>
                        <li class="breadcrumb-item active">Set Section Chief</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <!-- Success/Error Messages -->
                    @if(session()->has('message'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h5><i class="icon fas fa-check"></i> Success!</h5>
                        {{ session()->get('message') }}
                    </div>
                    @endif

                    @if(session()->has('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h5><i class="icon fas fa-ban"></i> Error!</h5>
                        {{ session()->get('error') }}
                    </div>
                    @endif

                    @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h5><i class="icon fas fa-ban"></i> Validation Error!</h5>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Manage Section Chiefs per Unit</h3>
                            <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addChiefModal" data-backdrop="static" data-keyboard="false">
                                <i class="fas fa-plus"></i> Add Section Chief
                            </button>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="sectionChiefTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Unit/Section</th>
                                        <th>Division (Section)</th>
                                        <th>Office</th>
                                        <th class="text-center">Current Chief</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($units as $unit)
                                    <tr>
                                        <td><strong>{{ $unit->unit }}</strong></td>
                                        <td>{{ $unit->Section->section ?? 'N/A' }}</td>
                                        <td>{{ $unit->Office->office ?? 'N/A' }}</td>
                                        <td class='text-center'>
                                            @if($unit->sectionChief)
                                            @php
                                            $chiefEmployee = $unit->sectionChief->employee;
                                            $unitMismatch = $chiefEmployee->unitid != $unit->id;
                                            @endphp
                                            <strong class="{{ $unitMismatch ? 'text-warning' : 'text-success' }}">
                                                {{ $chiefEmployee->firstname }} {{ $chiefEmployee->lastname }}
                                            </strong>
                                            @if($unitMismatch)
                                            <br>
                                            <span class="badge badge-warning">
                                                <i class="fas fa-exclamation-triangle"></i> Mismatch!
                                            </span>
                                            <br>
                                            <small class="text-danger">
                                                Currently in: {{ $chiefEmployee->unit->unit ?? 'Unknown' }}
                                            </small>
                                            @else
                                            <br>
                                            <small class="text-muted">{{ $chiefEmployee->position }}</small>
                                            @endif
                                            @else
                                            <span class="text-danger">Not Set</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($unit->sectionChief)
                                            @if($unit->sectionChief->employee->unitid != $unit->id)
                                            <span class="badge badge-warning">
                                                <i class="fas fa-exclamation-triangle"></i> Invalid
                                            </span>
                                            @else
                                            <span class="badge badge-success">
                                                <i class="fas fa-check-circle"></i> Active
                                            </span>
                                            @endif
                                            @else
                                            <span class="badge badge-warning"><i class="fas fa-exclamation-triangle"></i> Pending</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($unit->sectionChief)
                                            <button type="button" class="btn btn-sm btn-warning edit-chief" data-id="{{ $unit->sectionChief->id }}" data-unitid="{{ $unit->id }}" data-unitname="{{ $unit->unit }}" data-employeeid="{{ $unit->sectionChief->employeeid }}" data-toggle="tooltip" title="Edit Chief">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <form method="POST" action="{{ route('section-chief.destroy', $unit->sectionChief->id) }}" style="display:inline" onsubmit="return confirm('Are you sure you want to remove this Section Chief?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" data-toggle="tooltip" title="Remove Chief">
                                                    <i class="fas fa-trash"></i> Remove
                                                </button>
                                            </form>
                                            @else
                                            <button type="button" class="btn btn-sm btn-primary set-chief" data-unitid="{{ $unit->id }}" data-unitname="{{ $unit->unit }}" data-toggle="tooltip" title="Set Chief">
                                                <i class="fas fa-user-tie"></i> Set Chief
                                            </button>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">No units found</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>

<!-- Add/Edit Section Chief Modal -->
<div class="modal fade" id="addChiefModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form method="POST" action="{{ route('section-chief.store') }}" id="chiefForm">
                @csrf
                <input type="hidden" name="_method" value="POST" id="formMethod">
                <input type="hidden" name="id" id="chief_id">

                <div class="modal-header bg-primary">
                    <h4 class="modal-title" id="modalTitle"><i class="fas fa-user-tie"></i> Add Section Chief</h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label for="unitid">Select Unit/Section <span class="text-danger">*</span></label>
                        <select name="unitid" id="unitid" class="form-control select2" required style="width: 100%;">
                            <option value="">-- Select Unit --</option>
                            @foreach($units as $unit)
                            <option value="{{ $unit->id }}" data-sectionid="{{ $unit->sectionid }}" data-officeid="{{ $unit->officeid }}">
                                {{ $unit->unit }} ({{ $unit->Section->section ?? 'N/A' }} - {{ $unit->Office->office ?? 'N/A' }})
                            </option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle"></i> Select the unit/section where you want to assign a Section Chief
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="employeeid">Select Employee as Section Chief <span class="text-danger">*</span></label>
                        <select name="employeeid" id="employeeid" class="form-control select2" required disabled style="width: 100%;">
                            <option value="">-- Select Unit First --</option>
                        </select>
                        <small class="form-text text-muted">
                            <i class="fas fa-exclamation-triangle text-warning"></i> Only <strong>PERMANENT</strong> employees from the selected unit are shown
                        </small>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Section Chief
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#sectionChiefTable').DataTable({
            "responsive": true
            , "lengthChange": true
            , "autoWidth": false
            , "order": [
                [0, "asc"]
            ]
            , "pageLength": 25
        });

        // Initialize tooltips
        $('[data-toggle="tooltip"]').tooltip();

        // Initialize Select2
        $('.select2').select2({
            theme: 'bootstrap4'
            , dropdownParent: $('#addChiefModal')
        });

        // Function to load employees by unit
        function loadEmployeesByUnit(unitid) {
            var employeeDropdown = $('#employeeid');

            console.log('Loading employees for unit:', unitid); // DEBUG

            if (unitid) {
                // Show loading state
                employeeDropdown.html('<option value="">Loading...</option>');
                employeeDropdown.prop('disabled', true);

                var ajaxUrl = '{{ url("/api/employees-by-unit") }}/' + unitid;
                console.log('AJAX URL:', ajaxUrl); // DEBUG

                // AJAX call to get employees
                $.ajax({
                    url: ajaxUrl
                    , type: 'GET'
                    , dataType: 'json'
                    , success: function(data) {
                        console.log('AJAX Success:', data); // DEBUG
                        employeeDropdown.empty();

                        if (data.length > 0) {
                            employeeDropdown.append('<option value="">-- Select Employee --</option>');

                            $.each(data, function(index, employee) {
                                employeeDropdown.append(
                                    '<option value="' + employee.id + '">' +
                                    employee.firstname + ' ' + employee.lastname +
                                    ' (Permanent - ' + employee.position + ')' +
                                    '</option>'
                                );
                            });

                            employeeDropdown.prop('disabled', false);
                        } else {
                            employeeDropdown.append('<option value="">No permanent employees found</option>');
                            employeeDropdown.prop('disabled', true);
                        }
                    }
                    , error: function(xhr, status, error) {
                        console.error('AJAX Error:', xhr, status, error); // DEBUG
                        console.error('Response:', xhr.responseText); // DEBUG
                        employeeDropdown.empty();
                        employeeDropdown.append('<option value="">Error loading employees</option>');
                        employeeDropdown.prop('disabled', true);
                    }
                });
            } else {
                employeeDropdown.empty();
                employeeDropdown.append('<option value="">-- Select Unit First --</option>');
                employeeDropdown.prop('disabled', true);
            }
        }

        // When unit is selected, load employees via AJAX
        $('#unitid').on('change', function() {
            var unitid = $(this).val();
            console.log('Unit changed:', unitid); // DEBUG
            loadEmployeesByUnit(unitid);
        });

        // Edit chief button click (using event delegation for DataTable)
        $(document).on('click', '.edit-chief', function() {
            var id = $(this).data('id');
            var unitid = $(this).data('unitid');
            var unitname = $(this).data('unitname');
            var employeeid = $(this).data('employeeid');

            // Update modal title
            $('#modalTitle').html('<i class="fas fa-edit"></i> Edit Section Chief - ' + unitname);

            // Set form method to PUT and action URL
            $('#formMethod').val('PUT');
            $('#chiefForm').attr('action', '/msd-management/settings/travel-order-settings/section-chief/' + id);

            // Set hidden ID field
            $('#chief_id').val(id);

            // Set unit dropdown
            $('#unitid').val(unitid).trigger('change');

            // Manually load employees (for Select2 compatibility)
            loadEmployeesByUnit(unitid);

            // Wait for AJAX to load employees, then set employee
            setTimeout(function() {
                $('#employeeid').val(employeeid).trigger('change');
            }, 800);

            // Show modal
            $('#addChiefModal').modal('show');
        });

        // Set chief button click (using event delegation for DataTable)
        $(document).on('click', '.set-chief', function() {
            console.log('Set Chief button clicked'); // DEBUG
            var unitid = $(this).data('unitid');
            var unitname = $(this).data('unitname');

            console.log('Unit ID:', unitid, 'Unit Name:', unitname); // DEBUG
            resetModal();

            // Update modal title
            $('#modalTitle').html('<i class="fas fa-user-tie"></i> Set Section Chief - ' + unitname);

            // Pre-select unit
            $('#unitid').val(unitid).trigger('change');

            // Manually trigger employee loading (for Select2 compatibility)
            loadEmployeesByUnit(unitid);

            // Show modal
            $('#addChiefModal').modal('show');
        });

        // Reset modal on close
        $('#addChiefModal').on('hidden.bs.modal', function() {
            resetModal();
        });

        // Reset modal function
        function resetModal() {
            console.log('Resetting modal'); // DEBUG
            $('#modalTitle').html('<i class="fas fa-user-tie"></i> Add Section Chief');
            $('#chiefForm').attr('action', '{{ route("section-chief.store") }}');
            $('#formMethod').val('POST');
            $('#chief_id').val('');
            $('#unitid').val('').trigger('change');
            $('#employeeid').empty().append('<option value="">-- Select Unit First --</option>');
            $('#employeeid').prop('disabled', true);
        }
    });

</script>
@endpush
