<!-- Edit Travel Order Modal -->

@php
    $currentEmployee = \App\Models\Employee::where('email', auth()->user()->email)->first();
    $hasSignature = $currentEmployee && !empty($currentEmployee->signature_path);

    // Get the signatory record to access approver IDs
    $signatory = $TravelOrder->selectedSignatory;
    $currentApprover1 = $signatory ? $signatory->approver1 : null;
    $currentApprover2 = $signatory ? $signatory->approver2 : null;
    $currentApprover3 = $signatory ? $signatory->approver3 : null;
@endphp

<div class="modal fade" id="edit-travelorder-modal-{{ $TravelOrder->id }}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h4 class="modal-title">Edit Travel Order</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <!-- left column -->
                            <div class="col-md-12">
                                <!-- general form elements -->
                                <div class="card card-warning">
                                    <div class="card-header">
                                        <h3 class="card-title">Travel Order Information</h3>
                                    </div>

                                    <!-- /.card-header -->
                                    <!-- form start -->
                                    <form method="POST" action="{{ route('userTravelOrder.update', $TravelOrder->id) }}" enctype="multipart/form-data" id="edit-travel-order-form-{{ $TravelOrder->id }}">
                                        {{ csrf_field() }}
                                        @method('PUT')

                                        <div class="card-body">
                                            <div class="form-group row">
                                                <label class="col-sm-3" for="daterange-{{ $TravelOrder->id }}">Date Range : <span class="text-danger">*</span></label>
                                                <div class="input-group col-sm-9">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="far fa-calendar-alt"></i>
                                                        </span>
                                                    </div>
                                                    <input type="text" name="daterange" id="daterange-{{ $TravelOrder->id }}" class="form-control float-right daterange-edit" value="{{ $TravelOrder->daterange }}" oninput="this.value = this.value.toUpperCase()">
                                                </div>
                                                <!-- /.input group -->
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-3" for="approver1-{{ $TravelOrder->id }}">
                                                    Unit/Section Chief:<small class="text-muted d-block">(Initial)</small>
                                                </label>
                                                <div class="col-sm-9">
                                                    <select id="approver1-{{ $TravelOrder->id }}" name="approver1" class="form-control select2" style="width:100%;">
                                                        <option value="">-- Choose Unit/Section Chief --</option>
                                                        @foreach($SectionChiefs as $emp)
                                                        <option value="{{ $emp->id }}" {{ $currentApprover1 == $emp->id ? 'selected' : '' }}>
                                                            {{ $emp->lastname }}, {{ $emp->firstname }} {{ $emp->middlename }} - {{ $emp->Unit->unit ?? 'N/A' }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                    <small class="text-muted"><i>optional</i></small>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-3" for="approver2-{{ $TravelOrder->id }}">
                                                    Division Chief or In-Charge: <span class="text-danger">*</span><small class="text-muted d-block">(Recommending Approval)</small>
                                                </label>
                                                <div class="col-sm-9">
                                                    <select id="approver2-{{ $TravelOrder->id }}" name="approver2" class="form-control select2" style="width:100%;">
                                                        <option value="" disabled>-- Choose Division Chief --</option>
                                                        @foreach($DivisionChiefs as $emp)
                                                        <option value="{{ $emp->id }}" {{ $currentApprover2 == $emp->id ? 'selected' : '' }}>
                                                            {{ $emp->lastname }}, {{ $emp->firstname }} {{ $emp->middlename }} - {{ $emp->section->section ?? 'N/A' }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-3" for="approver3-{{ $TravelOrder->id }}">
                                                    PENRO or In-Charge: <span class="text-danger">*</span><small class="text-muted d-block">(Final Approval)</small>
                                                </label>
                                                <div class="col-sm-9">
                                                    <select id="approver3-{{ $TravelOrder->id }}" name="approver3" class="form-control select2" style="width:100%;" required>
                                                        <option value="" disabled>-- Choose PENRO --</option>
                                                        @foreach($PENROs as $emp)
                                                        <option value="{{ $emp->id }}" {{ $currentApprover3 == $emp->id ? 'selected' : '' }}>
                                                            {{ $emp->lastname }}, {{ $emp->firstname }} {{ $emp->middlename }} - {{ $emp->section->section ?? 'N/A' }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-3" for="destinationoffice-{{ $TravelOrder->id }}">Destination : <span class="text-danger">*</span></label>
                                                <div class=" col-sm-9">
                                                    <input name="destinationoffice" id="destinationoffice-{{ $TravelOrder->id }}" class="form-control" type="text" value="{{ $TravelOrder->destinationoffice }}" placeholder="Enter Destination" oninput="this.value = this.value.toUpperCase()">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-3" for="purpose-{{ $TravelOrder->id }}">Purpose of travel : <span class="text-danger">*</span></label>
                                                <div class=" col-sm-9">
                                                    <input name="purpose" id="purpose-{{ $TravelOrder->id }}" class="form-control" type="text" value="{{ $TravelOrder->purpose }}" placeholder="Enter Purpose of Travel" oninput="this.value = this.value.toUpperCase()">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-3" for="perdime-{{ $TravelOrder->id }}">Per Diem : <span class="text-danger">*</span></label>
                                                <div class=" col-sm-9">
                                                    <input name="perdime" id="perdime-{{ $TravelOrder->id }}" class="form-control" type="number" value="{{ $TravelOrder->perdime }}" placeholder="Enter Per Diem">
                                                </div>
                                            </div>

                                            @php
                                                $isOther = !in_array($TravelOrder->appropriation, ['GAA', 'SAA', 'Continuing', 'IPAF RIA']);
                                                $appropriationValue = $isOther ? 'other' : $TravelOrder->appropriation;
                                            @endphp
                                            <div class="form-group row">
                                                <label class="col-sm-3" for="appropriation-{{ $TravelOrder->id }}">Appropriation : <span class="text-danger">*</span></label>
                                                <div class=" col-sm-9">
                                                    <select name="appropriation" id="appropriation-{{ $TravelOrder->id }}" class="form-control select2" style="width:100%;" required>
                                                        <option value="" disabled>-- Choose Appropriation --</option>
                                                        <option value="GAA" {{ $appropriationValue == 'GAA' ? 'selected' : '' }}>GAA</option>
                                                        <option value="SAA" {{ $appropriationValue == 'SAA' ? 'selected' : '' }}>SAA</option>
                                                        <option value="Continuing" {{ $appropriationValue == 'Continuing' ? 'selected' : '' }}>Continuing</option>
                                                        <option value="IPAF RIA" {{ $appropriationValue == 'IPAF RIA' ? 'selected' : '' }}>IPAF RIA</option>
                                                        <option value="other" {{ $isOther ? 'selected' : '' }}>Other Appropriation</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row" id="other-appropriation-group-{{ $TravelOrder->id }}" style="display:{{ $isOther ? 'flex' : 'none' }};">
                                                <label class="col-sm-3" for="other_appropriation-{{ $TravelOrder->id }}">Specify Other : <span class="text-danger">*</span></label>
                                                <div class=" col-sm-9">
                                                    <input name="other_appropriation" id="other_appropriation-{{ $TravelOrder->id }}" class="form-control" type="text" placeholder="Enter Other Appropriation" oninput="this.value = this.value.toUpperCase()" value="{{ $isOther ? $TravelOrder->appropriation : '' }}">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-3" for="remarks-{{ $TravelOrder->id }}">Remarks : <span class="text-danger">*</span></label>
                                                <div class=" col-sm-9">
                                                    <input name="remarks" id="remarks-{{ $TravelOrder->id }}" class="form-control" type="text" value="{{ $TravelOrder->remarks }}" placeholder="Enter Remarks" oninput="this.value = this.value.toUpperCase()">
                                                </div>
                                            </div>

                                            <!-- Pre-payment Option -->
                                            <div class="form-group row">
                                                <label class="col-sm-3">Pre-Payment:</label>
                                                <div class="col-sm-9">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input" id="is_prepayment-{{ $TravelOrder->id }}" name="is_prepayment" value="1" {{ $TravelOrder->is_prepayment ? 'checked' : '' }} {{ !$hasSignature ? 'disabled' : '' }}>
                                                        <label class="custom-control-label" for="is_prepayment-{{ $TravelOrder->id }}">
                                                            This is a pre-payment travel order
                                                        </label>
                                                    </div>
                                                    <small class="form-text text-muted">
                                                        Check this if payment will be made before travel. Your signature from your profile will be used.
                                                    </small>
                                                    @if(!$hasSignature)
                                                    <div class="alert alert-warning mt-2 mb-0">
                                                        <i class="fas fa-exclamation-triangle"></i>
                                                        You haven't uploaded your signature yet. Please <a href="{{ route('user.profile') }}" target="_blank">update your profile</a> first.
                                                    </div>
                                                    @else
                                                    <div class="alert alert-success mt-2 mb-0">
                                                        <i class="fas fa-check-circle"></i>
                                                        Signature found in your profile. It will be used when you check pre-payment.
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.card-body -->

                                        <div class="card-footer">
                                            @can('update', $TravelOrder)
                                            <button type="submit" id="to-edit-submit-btn-{{ $TravelOrder->id }}" class="btn btn-warning">
                                                <span class="btn-text">Update</span>
                                                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                                <span class="loading-text d-none">Updating...</span>
                                            </button>
                                            @endcan
                                        </div>
                                    </form>
                                </div>
                                <!-- /.card -->
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<script>
    $(document).ready(function() {
        var hasSignature = {{ $hasSignature ? 'true' : 'false' }};
        var formId = '#edit-travel-order-form-{{ $TravelOrder->id }}';
        var toId = '{{ $TravelOrder->id }}';

        // Initialize daterangepicker for edit form
        $('#daterange-' + toId).daterangepicker();

        // Initialize select2 for edit form
        $('#approver1-' + toId + ', #approver2-' + toId + ', #approver3-' + toId).select2({
            dropdownParent: $('#edit-travelorder-modal-' + toId)
        });

        // Prevent double submission with loading animation
        $(formId).on('submit', function(e) {
            var submitBtn = $('#to-edit-submit-btn-' + toId);

            // Check if pre-payment is checked and user has no signature
            if ($('#is_prepayment-' + toId).is(':checked')) {
                if (!hasSignature) {
                    e.preventDefault();
                    alert('Please upload your signature in your profile first before creating a pre-payment travel order.');
                    return false;
                }
            }

            // Check if already submitting
            if (submitBtn.prop('disabled')) {
                e.preventDefault();
                return false;
            }

            // Disable button
            submitBtn.prop('disabled', true);

            // Show loading state
            submitBtn.find('.btn-text').addClass('d-none');
            submitBtn.find('.spinner-border').removeClass('d-none');
            submitBtn.find('.loading-text').removeClass('d-none');

            // Optional: Re-enable after timeout (fallback in case of error)
            setTimeout(function() {
                submitBtn.prop('disabled', false);
                submitBtn.find('.btn-text').removeClass('d-none');
                submitBtn.find('.spinner-border').addClass('d-none');
                submitBtn.find('.loading-text').addClass('d-none');
            }, 10000); // 10 seconds timeout
        });

        // Reset form when modal closes
        $('#edit-travelorder-modal-' + toId).on('hidden.bs.modal', function() {
            var submitBtn = $('#to-edit-submit-btn-' + toId);
            submitBtn.prop('disabled', false);
            submitBtn.find('.btn-text').removeClass('d-none');
            submitBtn.find('.spinner-border').addClass('d-none');
            submitBtn.find('.loading-text').addClass('d-none');
        });

        // Handle Appropriation dropdown change
        $('#appropriation-' + toId).on('change', function() {
            if ($(this).val() === 'other') {
                $('#other-appropriation-group-' + toId).show();
                $('#other_appropriation-' + toId).prop('required', true);
            } else {
                $('#other-appropriation-group-' + toId).hide();
                $('#other_appropriation-' + toId).prop('required', false).val('');
            }
        });
    });
</script>
