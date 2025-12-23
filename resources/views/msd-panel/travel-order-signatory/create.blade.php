<!-- /.modal -->

<div class="modal fade" id="new-signatory-modal-lg">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Division Signatory</h4>
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
                                        <h3 class="card-title">Division Signatory Information</h3>
                                        <p class="text-sm text-muted mb-0">Section Chiefs are managed in "Set Section Chief" page</p>
                                    </div>

                                    <!-- /.card-header -->
                                    <!-- form start -->

                                    <form method="POST" action="{{ route('travel-order-signatory.store') }}" enctype="multipart/form-data">

                                        {{ csrf_field() }}
                                        <div class="card-body">
                                            <div class="form-group  row">
                                                <label class="col-sm-3" for="unit-section">Unit/Section : <span class="text-danger">*</span></label>
                                                <div class="col-sm-9">
                                                    <select name="name" id="unit-section" class="form-control required-field" required>
                                                        <option value="">-- Piliin ang Unit/Section --</option>
                                                        @foreach($UnitsWithChief as $unit)
                                                        <option value="{{ $unit->unit }}">
                                                            {{ $unit->unit }}
                                                            @if($unit->Section)
                                                            ({{ $unit->Section->section ?? '' }})
                                                            @endif
                                                            - Chief: {{ optional($unit->sectionChief->employee)->lastname }}, {{ optional($unit->sectionChief->employee)->firstname }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                    <span class="text-danger text-xs" style="display:none;">The unit/section field is required.</span>
                                                    @error('name')
                                                    <p class="text-danger text-xs mt-1">{{$message}}</p>
                                                    @enderror
                                                </div>
                                            </div>

                                            {{-- Approver 1 (Section Chief) hidden - dynamically set based on selected Unit/Section --}}
                                            <input type="hidden" name="approver1" id="approver1" value="">

                                            <div class="form-group  row">
                                                <label class="col-sm-3" for="signatory-approver2">Division Chief (Signatory 2) : <span class="text-danger">*</span></label>
                                                <div class="col-sm-9">
                                                    <select id="signatory-approver2" name="approver2" class="form-control select2 required-field" required>
                                                        <option value="">-- Choose Employee Name --</option>
                                                        @foreach($Employees as $Employee)
                                                        <option value="{{ $Employee->id }}">{{ $Employee->lastname . ', ' . $Employee->firstname . ' ' . $Employee->middlename }}</option>
                                                        @endforeach
                                                    </select>
                                                    <span class="text-danger text-xs" style="display:none;">The approver2 field is required.</span>
                                                    @error('approver2')
                                                    <p class="text-danger text-xs mt-1">{{$message}}</p>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-3">Signature (Signatory 2)</label>
                                                <div class="col-sm-9">
                                                    <input type="file" name="approver2_signature" accept="image/*" class="form-control">
                                                    <small class="text-muted">PNG/JPG/WEBP up to 2MB</small>
                                                    @error('approver2_signature')<p class="text-danger text-xs mt-1">{{ $message }}</p>@enderror
                                                </div>
                                            </div>

                                            <div class="form-group  row">
                                                <label class="col-sm-3" for="signatory-approver3">Signatory 3 (PENRO) : <span class="text-danger">*</span></label>
                                                <div class="col-sm-9">
                                                    <select id="signatory-approver3" name="approver3" class="form-control select2 required-field" required>
                                                        <option value="">-- Choose Employee Name --</option>
                                                        @foreach($Employees as $Employee)
                                                        <option value="{{ $Employee->id }}">{{ $Employee->lastname . ', ' . $Employee->firstname . ' ' . $Employee->middlename }}</option>
                                                        @endforeach
                                                    </select>
                                                    <span class="text-danger text-xs" style="display:none;">The approver3 field is required.</span>
                                                    @error('approver3')
                                                    <p class="text-danger text-xs mt-1">{{$message}}</p>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-3">Signature (Signatory 3)</label>
                                                <div class="col-sm-9">
                                                    <input type="file" name="approver3_signature" accept="image/*" class="form-control">
                                                    <small class="text-muted">PNG/JPG/WEBP up to 2MB</small>
                                                    @error('approver3_signature')<p class="text-danger text-xs mt-1">{{ $message }}</p>@enderror
                                                </div>
                                            </div>


                                        </div>
                                        <!-- /.card-body -->
                                        <div class="card-footer">
                                            <button type="submit" id="signatory-submit-btn" class="btn btn-primary" disabled>Submit</button>
                                            <small class="text-muted ml-2">Please fill all required fields</small>
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
    // Enable/Disable Submit button based on required fields
    $(document).ready(function() {
        function checkRequiredFields() {
            var unitSection = $('#unit-section').val();
            var approver2 = $('#signatory-approver2').val();
            var approver3 = $('#signatory-approver3').val();

            // Enable button only if all required fields are filled
            if (unitSection && approver2 && approver3) {
                $('#signatory-submit-btn').prop('disabled', false);
                $('#signatory-submit-btn').next('small').hide();
            } else {
                $('#signatory-submit-btn').prop('disabled', true);
                $('#signatory-submit-btn').next('small').show();
            }
        }

        // Check on input/change
        $('#unit-section').on('change', checkRequiredFields);
        $('#signatory-approver2').on('change', checkRequiredFields);
        $('#signatory-approver3').on('change', checkRequiredFields);

        // Initial check
        checkRequiredFields();

        // Reset form when modal closes
        $('#new-signatory-modal-lg').on('hidden.bs.modal', function() {
            $(this).find('form')[0].reset();
            $('#unit-section').val('').trigger('change');
            $('#signatory-approver2').val('').trigger('change');
            $('#signatory-approver3').val('').trigger('change');
            checkRequiredFields();
        });

        // Map of unit/section to Section Chief employee ID
        var unitChiefMap = {};
        @foreach($UnitsWithChief as $unit)
        unitChiefMap['{{ $unit->unit }}'] = '{{ optional($unit->sectionChief->employee)->id ?? '
        ' }}';
        @endforeach

        // Set approver1 hidden input when unit/section changes
        $('#unit-section').on('change', function() {
            var selected = $(this).val();
            var chiefId = unitChiefMap[selected] || '';
            $('#approver1').val(chiefId);
        });
        // Set initial value if editing
        $('#unit-section').trigger('change');
    });

</script>
