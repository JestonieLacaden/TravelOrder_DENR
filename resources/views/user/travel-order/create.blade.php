<!-- /.modal -->

@php
    $currentEmployee = \App\Models\Employee::where('email', auth()->user()->email)->first();
    $hasSignature = $currentEmployee && !empty($currentEmployee->signature_path);
@endphp

<div class="modal fade" id="new-travelorder-modal-lg">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Travel Order</h4>
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
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">Travel Order Information</h3>

                                    </div>

                                    <!-- /.card-header -->
                                    <!-- form start -->

                                    <form method="POST" action="{{ route('userTravelOrder.storeUserTravelOrder') }}" enctype="multipart/form-data" id="travel-order-form">

                                        {{ csrf_field() }}
                                        <div class="card-body">

                                            <div class="card-body">


                                                <div class="form-group row">
                                                    <label class="col-sm-3" for="daterange">Date Range : <span class="text-danger">*</span></label>
                                                    <div class="input-group col-sm-9">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                                <i class="far fa-calendar-alt"></i>
                                                            </span>
                                                        </div>
                                                        <input type="text" name="daterange" id="daterange" class="form-control float-right" oninput="this.value = this.value.toUpperCase()">
                                                    </div>
                                                    <!-- /.input group -->
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-sm-3" for="approver1">
                                                        Unit/Section Chief:<small class="text-muted d-block">(Initial)</small>
                                                    </label>
                                                    <div class="col-sm-9">
                                                        <select id="approver1" name="approver1" class="form-control select2" style="width:100%;">
                                                            <option value="">-- Choose Unit/Section Chief --</option>
                                                            @foreach($SectionChiefs as $emp)
                                                            <option value="{{ $emp->id }}">
                                                                {{ $emp->lastname }}, {{ $emp->firstname }} {{ $emp->middlename }} - {{ $emp->Unit->unit ?? 'N/A' }}
                                                            </option>
                                                            @endforeach
                                                        </select>
                                                        <small class="text-muted"><i>Optional</i></small>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-sm-3" for="approver2">
                                                        Division Chief or In-Charge: <span class="text-danger">*</span><small class="text-muted d-block">(Recommending Approval)</small>
                                                    </label>
                                                    <div class="col-sm-9">
                                                        <select id="approver2" name="approver2" class="form-control select2" style="width:100%;">
                                                            <option value="" disabled selected>-- Choose Division Chief --</option>
                                                            @foreach($DivisionChiefs as $emp)
                                                            <option value="{{ $emp->id }}">
                                                                {{ $emp->lastname }}, {{ $emp->firstname }} {{ $emp->middlename }} - {{ $emp->section->section ?? 'N/A' }}
                                                            </option>
                                                            @endforeach
                                                        </select>
                                                        {{-- <small class="text-muted">Note: Please select the Division Chief currently in-charge of your division</small> --}}
                                                        {{-- <small class="text-muted d-block">(Recommending Approval)</small> --}}
                                                        @error('approver2')
                                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-sm-3" for="approver3">
                                                        PENRO or In-Charge: <span class="text-danger">*</span><small class="text-muted d-block">(Final Approval)</small>
                                                    </label>
                                                    <div class="col-sm-9">
                                                        <select id="approver3" name="approver3" class="form-control select2" style="width:100%;" required>
                                                            <option value="" disabled selected>-- Choose PENRO --</option>
                                                            @foreach($PENROs as $emp)
                                                            <option value="{{ $emp->id }}">
                                                                {{ $emp->lastname }}, {{ $emp->firstname }} {{ $emp->middlename }} - {{ $emp->section->section ?? 'N/A' }}
                                                            </option>
                                                            @endforeach
                                                        </select>
                                                        {{-- <small class="text-muted">Note: Please select the PENRO currently in-charge of your office</small> --}}
                                                        {{-- <small class="text-muted d-block">(Final Approval)</small> --}}
                                                        @error('approver3')
                                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                                        @enderror
                                                    </div>
                                                </div>

                                                {{-- <div class="form-group row">
                          <label class="col-sm-3" for="travelordersignatoryid">
                            Signatory: <span class="text-danger">*</span>
                          </label>
                          <div class="col-sm-9">
                            <select id="travelordersignatoryid" name="travelordersignatoryid" class="form-control select2" style="width:100%;">
                              <option value="" disabled selected>-- Choose Signatory --</option>
                              @foreach($SignatoryOptions as $opt)
                              <option value="{{ $opt->travelordersignatoryid }}">
                                                {{ $opt->TravelOrderSignatory->name }}
                                                </option>
                                                @endforeach
                                                </select>
                                                @error('travelordersignatoryid')
                                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                                                @enderror
                                            </div>
                                        </div> --}}

                                        <div class="form-group row">
                                            <label class="col-sm-3" for="destinationoffice">Destination : <span class="text-danger">*</span></label>
                                            <div class=" col-sm-9">
                                                <input name="destinationoffice" id="destinationoffice" class="form-control" type="text" placeholder="Enter Destination" oninput="this.value = this.value.toUpperCase()">
                                                @error('destination')
                                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3" for="purpose">Purpose of travel : <span class="text-danger">*</span></label>
                                            <div class=" col-sm-9">
                                                <input name="purpose" id="purpose" class="form-control" type="text" placeholder="Enter Purpose of Travel" oninput="this.value = this.value.toUpperCase()">
                                                @error('purpose')
                                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3" for="perdime">Per Diem : <span class="text-danger">*</span></label>
                                            <div class=" col-sm-9">
                                                <input name="perdime" id="perdime" class="form-control" type="number" placeholder="Enter Per Diem" oninput="this.value = this.value.toUpperCase()">
                                                @error('perdime')
                                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3" for="appropriation">Appropriation : <span class="text-danger">*</span></label>
                                            <div class=" col-sm-9">
                                                <select name="appropriation" id="appropriation" class="form-control select2" style="width:100%;" required>
                                                    <option value="" disabled selected>-- Choose Appropriation --</option>
                                                    <option value="GAA">GAA</option>
                                                    <option value="SAA">SAA</option>
                                                    <option value="Continuing">Continuing</option>
                                                    <option value="IPAF RIA">IPAF RIA</option>
                                                    <option value="other">Other Appropriation</option>
                                                </select>
                                                @error('appropriation')
                                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row" id="other-appropriation-group" style="display:none;">
                                            <label class="col-sm-3" for="other_appropriation">Specify Other : <span class="text-danger">*</span></label>
                                            <div class=" col-sm-9">
                                                <input name="other_appropriation" id="other_appropriation" class="form-control" type="text" placeholder="Enter Other Appropriation" oninput="this.value = this.value.toUpperCase()">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3" for="remarks">Remarks : <span class="text-danger">*</span></label>
                                            <div class=" col-sm-9">
                                                <input name="remarks" id="remarks" class="form-control" type="text" placeholder="Enter Remarks" oninput="this.value = this.value.toUpperCase()" value="SUBMIT REPORT UPON COMPLETION OF TRAVEL">
                                                @error('remarks')
                                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Pre-payment Option -->
                                        <div class="form-group row">
                                            <label class="col-sm-3">Pre-Payment:</label>
                                            <div class="col-sm-9">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="is_prepayment" name="is_prepayment" value="1" {{ !$hasSignature ? 'disabled' : '' }}>
                                                    <label class="custom-control-label" for="is_prepayment">
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
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                @can('AddUserTravelOrder', \App\Models\TravelOrder::class)
                                <button type="submit" id="to-submit-btn" class="btn btn-primary">
                                    <span class="btn-text">Submit</span>
                                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                    <span class="loading-text d-none">Submitting...</span>
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
    {{-- <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
        </div> --}}
</div>
<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<script>
    $(document).ready(function() {
        var hasSignature = {{ $hasSignature ? 'true' : 'false' }};

        // Prevent double submission with loading animation
        $('#travel-order-form').on('submit', function(e) {
            var submitBtn = $('#to-submit-btn');

            // Check if pre-payment is checked and user has no signature
            if ($('#is_prepayment').is(':checked')) {
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
        $('#new-travelorder-modal-lg').on('hidden.bs.modal', function() {
            var submitBtn = $('#to-submit-btn');
            submitBtn.prop('disabled', false);
            submitBtn.find('.btn-text').removeClass('d-none');
            submitBtn.find('.spinner-border').addClass('d-none');
            submitBtn.find('.loading-text').addClass('d-none');
        });

        // Handle Appropriation dropdown change
        $('#appropriation').on('change', function() {
            if ($(this).val() === 'other') {
                $('#other-appropriation-group').show();
                $('#other_appropriation').prop('required', true);
            } else {
                $('#other-appropriation-group').hide();
                $('#other_appropriation').prop('required', false).val('');
            }
        });
    });

</script>
