@extends('layouts.app')

@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Update Employee</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard')}}">Home</a></li>
                        <li class="breadcrumb-item active">MSD Panel</li>
                        <li class="breadcrumb-item"><a href="{{ route('employee.index')}}">Employee Management</a></li>
                        <li class="breadcrumb-item active">New </li>

                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->

    <section class="content">
        <div class="container-fluid">

            @if(session('warning_chief_change'))
            <!-- Warning Alert for Section Chief Change -->
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <h5><i class="icon fas fa-exclamation-triangle"></i> Warning: Section Chief Assignment</h5>
                        <p>{!! session('warning_chief_change')['message'] !!}</p>
                        <p><strong>Do you want to proceed with this change?</strong></p>
                        <div class="mt-3">
                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#confirmRemoveChiefModal">
                                <i class="fas fa-check"></i> Yes, Proceed and Remove Chief Assignment
                            </button>
                            <a href="{{ route('employee.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div class="row">
                <!-- left column -->
                <div class="col-md m-auto">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Employee Information</h3>

                        </div>

                        <!-- /.card-header -->
                        <!-- form start -->
                        <form method="POST" action="{{ route('employee.update',[ $Employee->id])}}" enctype="multipart/form-data" id="employeeUpdateForm">

                            {{ csrf_field() }}
                            @method('PUT')
                            <div class="card-body">

                                <div class="form-group row  mb-4 {{ $errors->get('employeeid') ? 'has-error' : '' }}">
                                    <label class="col-sm-2 col-form-label" for="employeeid">Employee ID : <span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <input name="employeeid" class="form-control" type="text" maxlength="40" placeholder="Enter Employee ID" value=" {{ $Employee->employeeid }} " oninput="this.value = this.value.toUpperCase()" required>
                                        @error('employeeid')
                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row mb-4 ">
                                    <label class="col-sm-2 col-form-label" for="firstname">First Name :<span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <input name="firstname" id="firstname" class="form-control" type="text" placeholder="Enter First Name" value="{{ $Employee->firstname }} " oninput="this.value = this.value.toUpperCase()">
                                        @error('firstname')
                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row mb-4 ">
                                    <label class="col-sm-2 col-form-label" for="middlename">Middle Name :<span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <input name="middlename" class="form-control" type="text" placeholder="Enter Middle Name" value="{{ $Employee->middlename }} " oninput="this.value = this.value.toUpperCase()">
                                        @error('middlename')
                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row mb-4">
                                    <label class="col-sm-2 col-form-label" for="lastname">Last Name :<span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <input name="lastname" class="form-control" type="text" placeholder="Enter Last Name" value="{{ $Employee->lastname }} " oninput="this.value = this.value.toUpperCase()">
                                        @error('lastname')
                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-sm-2 col-form-label" for="birthdate">Birth Date :<span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <input name="birthdate" class="form-control" min="1930-01-01" type="date" value="{{ $Employee->birthdate && $Employee->birthdate != '0000-00-00' ? $Employee->birthdate : '' }}">
                                        @if($Employee->birthdate == '0000-00-00' || !$Employee->birthdate)
                                        <small class="text-warning"><i class="fas fa-exclamation-triangle"></i> Invalid or missing birth date - please update</small>
                                        @endif
                                        @error('birthdate')
                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-sm-2 col-form-label" for="email">Email :<span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <input name="email" class="form-control" type="text" placeholder="Enter Email Address" value="{{ $Employee->email }}">
                                        @error('email')
                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>


                                <div class="form-group row mb-4">
                                    <label class="col-sm-2 col-form-label" for="contactnumber">Contact Number :<span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <input name="contactnumber" class="form-control" maxlength="11" type="text" placeholder="Enter Contact Number" value="{{ $Employee->contactnumber }} ">
                                        @error('contactnumber')
                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-sm-2 col-form-label" for="address">Address :<span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <textarea name="address" class="form-control" rows="2" placeholder="Enter Address" oninput="this.value = this.value.toUpperCase()">{{ $Employee->address ?? '' }}</textarea>
                                        @if(!$Employee->address)
                                        <small class="text-warning"><i class="fas fa-exclamation-triangle"></i> Address is missing - please update</small>
                                        @endif
                                        @error('address')
                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="officesectionunit">Office / Section : <span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <select name="officesectionunit" id="officesectionunit" class="form-control select2" style="width: 100%;">
                                            <option value="">-- Choose office --</option>

                                            @foreach($Offices as $Office)
                                            <div>
                                                <optgroup label="{{$Office->office }}" class="bg-light">
                                                    </option>
                                                    @foreach ($Sections as $Section)
                                                    @if ( $Section->officeid == $Office->id );
                                                <optgroup label="- {{$Section->section }}"><strong></strong></option>
                                                    @foreach ($Units as $Unit)
                                                    @if ( $Unit->sectionid == $Section->id );
                                                    @php
                                                    $optionValue = "$Office->id,$Section->id,$Unit->id";
                                                    $oldValue = old('officesectionunit');
                                                    $currentValue = "$Employee->officeid,$Employee->sectionid,$Employee->unitid";
                                                    $isSelected = $oldValue ? ($oldValue == $optionValue) : ($currentValue == $optionValue);
                                                    @endphp
                                                    <option value="{{$optionValue}}" class="bg-light pl-4" {{ $isSelected ? 'selected' : '' }}>
                                                        {{$Unit->unit }}
                                                    </option>
                                                    @endif
                                                    @endforeach
                                                </optgroup>
                                                @endif
                                                @endforeach
                                                </optgroup>

                                            </div>
                                            @endforeach
                                        </select>
                                        @error('officesectionunit')
                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-sm-2 col-form-label" for="position">Position :<span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <input name="position" class="form-control" type="text" placeholder="Enter Position" value="{{ $Employee->position }}" oninput="this.value = this.value.toUpperCase()">
                                        @error('position')
                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-sm-2 col-form-label" for="datehired">Date Hired :<span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <input name="datehired" class="form-control" type="date" value="{{ $Employee->datehired && $Employee->datehired != '0000-00-00' ? $Employee->datehired : '' }}">
                                        @if($Employee->datehired == '0000-00-00' || !$Employee->datehired)
                                        <small class="text-warning"><i class="fas fa-exclamation-triangle"></i> Invalid or missing date hired - please update</small>
                                        @endif
                                        @error('datehired')
                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-sm-2 col-form-label" for="empstatus">Status :<span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <select name="empstatus" class="form-control select2" style="width: 100%;" value="{{$Employee->empstatus }}">
                                            <option selected="selected">PERMANENT</option>
                                            <option>CONTRACTUAL</option>
                                        </select>
                                        @error('empstatus')
                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Leave Balance Section -->
                                <hr>
                                <h5 class="mb-3"><i class="fas fa-calendar-check"></i> Leave Balance</h5>

                                <div class="form-group row mb-4">
                                    <label class="col-sm-2 col-form-label" for="vacation_leave_balance">Vacation Leave Balance:</label>
                                    <div class="col-sm-10">
                                        <input name="vacation_leave_balance" class="form-control" type="number" step="0.001" min="0" max="999" placeholder="0.000" value="{{ $Employee->vacation_leave_balance ?? 0 }}">
                                        <small class="text-muted">Enter vacation leave balance (e.g., 15.000)</small>
                                        @error('vacation_leave_balance')
                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-sm-2 col-form-label" for="sick_leave_balance">Sick Leave Balance:</label>
                                    <div class="col-sm-10">
                                        <input name="sick_leave_balance" class="form-control" type="number" step="0.001" min="0" max="999" placeholder="0.000" value="{{ $Employee->sick_leave_balance ?? 0 }}">
                                        <small class="text-muted">Enter sick leave balance (e.g., 15.000)</small>
                                        @error('sick_leave_balance')
                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-sm-2 col-form-label" for="force_leave_balance">Force Leave Balance:</label>
                                    <div class="col-sm-10">
                                        <input name="force_leave_balance" class="form-control" type="number" step="0.001" min="0" max="999" placeholder="0.000" value="{{ $Employee->force_leave_balance ?? 0 }}">
                                        <small class="text-muted">Enter force leave balance (e.g., 5.000)</small>
                                        @error('force_leave_balance')
                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-sm-2 col-form-label" for="special_privilege_leave_balance">Special Privilege Leave:</label>
                                    <div class="col-sm-10">
                                        <input name="special_privilege_leave_balance" class="form-control" type="number" step="0.001" min="0" max="999" placeholder="0.000" value="{{ $Employee->special_privilege_leave_balance ?? 0 }}">
                                        <small class="text-muted">Enter special privilege leave balance (e.g., 3.000)</small>
                                        @error('special_privilege_leave_balance')
                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-sm-2 col-form-label" for="solo_parent_leave_balance">Solo Parent Leave:</label>
                                    <div class="col-sm-10">
                        <input name="solo_parent_leave_balance" id="solo_parent_leave_balance" class="form-control" type="number" step="0.001" min="0" max="999" placeholder="0.000" value="{{ $Employee->solo_parent_leave_balance ?? 0 }}">
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-sm-2 col-form-label" for="solo_parent_eligible">Solo Parent Eligible:</label>
                                    <div class="col-sm-10">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" name="solo_parent_eligible" id="solo_parent_eligible" class="custom-control-input" value="1" {{ $Employee->solo_parent_eligible ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="solo_parent_eligible">
                                                Allow this employee to apply for Solo Parent Leave
                                            </label>
                                        </div>
                                        <small class="text-muted">Check this if employee is eligible for solo parent leave benefits</small>
                                        @error('solo_parent_eligible')
                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-sm-2 col-form-label" for="wellness_leave_balance">Wellness Leave:</label>
                                    <div class="col-sm-10">
                                        <input name="wellness_leave_balance" class="form-control" type="number" step="0.001" min="0" max="999" placeholder="0.000" value="{{ $Employee->wellness_leave_balance ?? 0 }}">
                                        <small class="text-muted">Enter wellness leave balance (e.g., 5.000)</small>
                                        @error('wellness_leave_balance')
                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>
                                <hr>
                                <!-- End Leave Balance Section -->

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="signature"> Signature Upload : </label>
                                    <div class="col-sm-10">
                                        @if(!empty($Employee->signature_path))
                                        <div class="mb-2">
                                            <img src="{{ asset('storage/' . $Employee->signature_path) }}" alt="Current Signature" style="max-height: 80px; border: 1px solid #ddd; padding: 5px; background: white;" onerror="this.style.display='none'">
                                            <p class="text-muted small mt-1">
                                                <i class="fas fa-check-circle text-success"></i> Current signature on file
                                            </p>
                                        </div>
                                        @else
                                        <p class="text-warning small">
                                            <i class="fas fa-exclamation-triangle"></i> No signature uploaded yet
                                        </p>
                                        @endif
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" name="signature" class="custom-file-input" accept="image/x-png,image/jpg,image/jpeg">
                                                <label class="custom-file-label" for="signature">Choose new signature file (Optional)</label>
                                            </div>
                                        </div>
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle"></i> Upload a clear signature image (PNG, JPG, JPEG). This will be used in leave applications.
                                        </small>
                                    </div>
                                    @error('signature')
                                    <p class="text-danger text-xs mt-1 col-sm-10 offset-sm-2">{{$message}}</p>
                                    @enderror
                                </div>

                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <button type="button" class="btn btn-secondary ml-2" id="cancelBtn">Cancel</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Cancel Confirmation Modal -->
<div class="modal fade" id="cancelConfirmModal" tabindex="-1" role="dialog" aria-labelledby="cancelConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="cancelConfirmModalLabel">
                    <i class="fas fa-exclamation-triangle"></i> Confirm Cancel
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="mb-0">Are you sure you want to cancel?</p>
                <p class="text-danger mb-0"><strong>All changes will be lost.</strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> No, Stay
                </button>
                <button type="button" class="btn btn-warning" id="confirmCancelBtn">
                    <i class="fas fa-check"></i> Yes, Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmRemoveChiefModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle"></i> Confirm Section Chief Removal</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p><strong>Are you absolutely sure?</strong></p>
                <p>This employee will be automatically removed as Section Chief of <strong>{{ session('warning_chief_change')['unit_name'] ?? 'the assigned unit' }}</strong>.</p>
                <p class="text-danger"><i class="fas fa-info-circle"></i> This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Cancel
                </button>
                <button type="button" class="btn btn-danger" id="confirmProceedBtn">
                    <i class="fas fa-check"></i> Yes, Proceed
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('page-script')
<!-- bs-custom-file-input -->
<script src="{{asset('plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
<script>
    $(function() {
        bsCustomFileInput.init();

        // Store original form values
        const originalValues = {};
        $('input[type="text"], input[type="email"], input[type="date"], textarea, select').each(function() {
            const name = $(this).attr('name');
            if (name) {
                originalValues[name] = $(this).val();
            }
        });

        // Cancel button with smart warning
        $('#cancelBtn').on('click', function() {
            // Check if form has any changes from original
            let hasChanges = false;

            // Check text/email/date inputs and textareas
            $('input[type="text"], input[type="email"], input[type="date"], textarea, select').each(function() {
                const name = $(this).attr('name');
                if (name && originalValues[name] !== undefined) {
                    const currentVal = $(this).val();
                    const originalVal = originalValues[name];
                    // Compare trimmed values
                    if (currentVal.trim() !== originalVal.trim()) {
                        hasChanges = true;
                        return false; // break loop
                    }
                }
            });

            // Check file inputs
            if (!hasChanges) {
                $('input[type="file"]').each(function() {
                    if (this.files && this.files.length > 0) {
                        hasChanges = true;
                        return false; // break loop
                    }
                });
            }

            // If form has changes, show modal warning
            if (hasChanges) {
                $('#cancelConfirmModal').modal('show');
            } else {
                // No changes, go back directly
                window.location.href = '{{ route("employee.index") }}';
            }
        });

        // Confirm cancel button in modal
        $('#confirmCancelBtn').on('click', function() {
            window.location.href = '{{ route("employee.index") }}';
        });

        // Handle confirmation button click
        $('#confirmProceedBtn').click(function() {
            // Add hidden input to confirm chief removal
            $('#employeeUpdateForm').append('<input type="hidden" name="confirm_remove_chief" value="1">');
            // Submit the form
            $('#employeeUpdateForm').submit();
        });

        // Solo Parent Leave validation: disable input if checkbox is unchecked
        function toggleSoloParentLeaveInput() {
            const checkbox = $('#solo_parent_eligible');
            const input = $('#solo_parent_leave_balance');
            
            if (checkbox.is(':checked')) {
                input.prop('disabled', false);
                input.prop('readonly', false);
            } else {
                input.prop('disabled', true);
                input.prop('readonly', true);
                input.val('0.000'); // Reset to 0 when disabled
            }
        }

        // Run on page load
        toggleSoloParentLeaveInput();

        // Run when checkbox changes
        $('#solo_parent_eligible').on('change', function() {
            toggleSoloParentLeaveInput();
        });
    });

</script>
@endsection
