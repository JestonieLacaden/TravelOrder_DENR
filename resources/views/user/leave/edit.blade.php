<!-- Edit Leave Modal -->

<div class="modal fade" id="edit-leave-modal-{{ $Leave->id }}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h4 class="modal-title">Edit Leave</h4>
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
                                        <h3 class="card-title">Leave Information</h3>
                                    </div>

                                    <!-- /.card-header -->
                                    <!-- form start -->
                                    <form method="POST" action="{{ route('userleave.update', $Leave->id) }}" enctype="multipart/form-data" id="edit-leave-form-{{ $Leave->id }}">
                                        {{ csrf_field() }}
                                        @method('PUT')

                                        <div class="card-body">
                                            <div class="form-group row ">
                                                <label class="col-sm-3" for="leaveid-{{ $Leave->id }}">Leave Type : <span class="text-danger">*</span></label>
                                                <div class="col-sm-9">
                                                    <select id="leaveid-{{ $Leave->id }}" name="leaveid" class="form-control select2 leave-type-edit" style="width: 100%;" required data-leave-id="{{ $Leave->id }}">
                                                        <option value="" disabled>-- Choose Leave Type --</option>
                                                        @foreach($Leave_Types as $Leave_Type)
                                                        <option value="{{ $Leave_Type->id }}" data-text="{{ $Leave_Type->leave_type }}" {{ $Leave->leaveid == $Leave_Type->id ? 'selected' : '' }}>{{ $Leave_Type->leave_type }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-3" for="daterange-{{ $Leave->id }}">Date Range : <span class="text-danger">*</span></label>
                                                <div class="input-group col-sm-9">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="far fa-calendar-alt"></i>
                                                        </span>
                                                    </div>
                                                    <input type="text" name="daterange" id="daterange-{{ $Leave->id }}" class="form-control float-right daterange-edit" value="{{ $Leave->daterange }}">
                                                </div>
                                                <!-- /.input group -->
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-3">Half Day:</label>
                                                <div class="col-sm-9">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input" id="is_half_day-{{ $Leave->id }}" name="is_half_day" value="1" {{ $Leave->is_half_day ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="is_half_day-{{ $Leave->id }}">
                                                            This is a half-day leave
                                                        </label>
                                                    </div>
                                                    <small class="form-text text-muted">
                                                        Check this if you're only taking a half day off (AM or PM)
                                                    </small>
                                                </div>
                                            </div>

                                            <!-- Dynamic 6.B Details (shown based on Leave Type) -->
                                            <div id="details-6b-{{ $Leave->id }}" class="mt-3" style="display:none;">
                                                <div class="card card-outline card-info">
                                                    <div class="card-header">
                                                        <h3 class="card-title">6.B Details of Leave</h3>
                                                    </div>
                                                    <div class="card-body">
                                                        <!-- Vacation / Mandatory / Special Privilege Leave -->
                                                        <div id="group-vl-{{ $Leave->id }}" style="display:none;">
                                                            <p class="mb-2"><i>In case of Vacation/Mandatory/Forced/Special Privilege Leave:</i></p>
                                                            <div class="form-group">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="location_choice" id="loc_ph-{{ $Leave->id }}" value="within_ph" {{ $Leave->location_within_ph ? 'checked' : '' }}>
                                                                    <label class="form-check-label" for="loc_ph-{{ $Leave->id }}">Within the Philippines</label>
                                                                </div>
                                                                <textarea class="form-control mt-2" name="location_within_ph" id="location_within_ph-{{ $Leave->id }}" rows="2" placeholder="Specify location within the Philippines" style="resize: none;" {{ $Leave->location_within_ph ? '' : 'disabled' }}>{{ $Leave->location_within_ph }}</textarea>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="location_choice" id="loc_abroad-{{ $Leave->id }}" value="abroad" {{ $Leave->location_abroad ? 'checked' : '' }}>
                                                                    <label class="form-check-label" for="loc_abroad-{{ $Leave->id }}">Abroad (Specify)</label>
                                                                </div>
                                                                <textarea class="form-control mt-2" name="location_abroad" id="location_abroad-{{ $Leave->id }}" rows="2" placeholder="Specify location abroad" style="resize: none;" {{ $Leave->location_abroad ? '' : 'disabled' }}>{{ $Leave->location_abroad }}</textarea>
                                                            </div>
                                                        </div>

                                                        <!-- Sick Leave -->
                                                        <div id="group-sick-{{ $Leave->id }}" style="display:none;">
                                                            <p class="mb-2"><i>In case of Sick Leave:</i></p>
                                                            <div class="form-group">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="sick_choice" id="sick_hospital-{{ $Leave->id }}" value="hospital" {{ $Leave->hospital_specify ? 'checked' : '' }}>
                                                                    <label class="form-check-label" for="sick_hospital-{{ $Leave->id }}">In Hospital (Specify Illness)</label>
                                                                </div>
                                                                <textarea class="form-control mt-2" name="hospital_specify" id="hospital_specify-{{ $Leave->id }}" rows="2" placeholder="Specify illness (hospitalized)" style="resize: none;" {{ $Leave->hospital_specify ? '' : 'disabled' }}>{{ $Leave->hospital_specify }}</textarea>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="sick_choice" id="sick_outpatient-{{ $Leave->id }}" value="outpatient" {{ $Leave->outpatient_specify ? 'checked' : '' }}>
                                                                    <label class="form-check-label" for="sick_outpatient-{{ $Leave->id }}">Out Patient (Specify Illness)</label>
                                                                </div>
                                                                <textarea class="form-control mt-2" name="outpatient_specify" id="outpatient_specify-{{ $Leave->id }}" rows="2" placeholder="Specify illness (outpatient)" style="resize: none;" {{ $Leave->outpatient_specify ? '' : 'disabled' }}>{{ $Leave->outpatient_specify }}</textarea>
                                                            </div>
                                                        </div>

                                                        <!-- Study Leave -->
                                                        <div id="group-study-{{ $Leave->id }}" style="display:none;">
                                                            <p class="mb-2"><i>In case of Study Leave:</i></p>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="study_choice" id="study_masters-{{ $Leave->id }}" value="masters" {{ $Leave->study_masters_degree ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="study_masters-{{ $Leave->id }}">Completion of Master's Degree</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="study_choice" id="study_bar-{{ $Leave->id }}" value="bar_board" {{ $Leave->study_bar_board ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="study_bar-{{ $Leave->id }}">BAR/Board Examination Review</label>
                                                            </div>
                                                            <input type="hidden" name="study_masters_degree" id="study_masters_degree-{{ $Leave->id }}" value="{{ $Leave->study_masters_degree ? 1 : 0 }}">
                                                            <input type="hidden" name="study_bar_board" id="study_bar_board-{{ $Leave->id }}" value="{{ $Leave->study_bar_board ? 1 : 0 }}">
                                                        </div>

                                                        <!-- Others -->
                                                        <div id="group-others-{{ $Leave->id }}" style="display:none;">
                                                            <p class="mb-2"><i>Other Purpose:</i></p>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="others_choice" id="others_monetization-{{ $Leave->id }}" value="monetization" {{ $Leave->other_monetization ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="others_monetization-{{ $Leave->id }}">Monetization of Leave Credits</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="others_choice" id="others_terminal-{{ $Leave->id }}" value="terminal" {{ $Leave->other_terminal_leave ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="others_terminal-{{ $Leave->id }}">Terminal Leave</label>
                                                            </div>
                                                            <input type="hidden" name="other_monetization" id="other_monetization-{{ $Leave->id }}" value="{{ $Leave->other_monetization ? 1 : 0 }}">
                                                            <input type="hidden" name="other_terminal_leave" id="other_terminal_leave-{{ $Leave->id }}" value="{{ $Leave->other_terminal_leave ? 1 : 0 }}">
                                                        </div>

                                                        <!-- Hidden commutation field (7.D auto-set on submit) -->
                                                        <input type="hidden" name="commutation" id="commutation-{{ $Leave->id }}" value="{{ $Leave->commutation ?? 'not_requested' }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.card-body -->

                                        <div class="card-footer">
                                            @can('update', $Leave)
                                            <button type="submit" id="edit-submit-leave-btn-{{ $Leave->id }}" class="btn btn-warning">
                                                <span id="edit-submit-text-{{ $Leave->id }}">Update</span>
                                                <span id="edit-submit-spinner-{{ $Leave->id }}" style="display: none;">
                                                    <i class="fas fa-spinner fa-spin"></i> Updating...
                                                </span>
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
(function() {
    var leaveId = {{ $Leave->id }};

    function norm(text) {
        return (text || '').toLowerCase().trim();
    }

    function show(containerId) {
        document.getElementById('details-6b-' + leaveId).style.display = 'block';
        ['group-vl', 'group-sick', 'group-study', 'group-others'].forEach(function(id) {
            var el = document.getElementById(id + '-' + leaveId);
            if (el) el.style.display = (id === containerId) ? 'block' : 'none';
        });
    }

    function hideAll() {
        document.getElementById('details-6b-' + leaveId).style.display = 'none';
        ['group-vl', 'group-sick', 'group-study', 'group-others'].forEach(function(id) {
            var el = document.getElementById(id + '-' + leaveId);
            if (el) el.style.display = 'none';
        });
    }

    function setRequired(groupName, isRequired) {
        document.querySelectorAll('input[name="' + groupName + '"]').forEach(function(r) {
            if (isRequired) {
                r.setAttribute('required', 'required');
            } else {
                r.removeAttribute('required');
            }
        });
    }

    function enableTextarea(sel) {
        var el = document.querySelector(sel + '-' + leaveId);
        if (!el) return;
        el.removeAttribute('disabled');
        el.setAttribute('required', 'required');
    }

    function disableTextarea(sel) {
        var el = document.querySelector(sel + '-' + leaveId);
        if (!el) return;
        el.value = '';
        el.setAttribute('disabled', 'disabled');
        el.removeAttribute('required');
    }

    function applyDefaultsForType(text) {
        var commutation = document.getElementById('commutation-' + leaveId);
        if (commutation) commutation.value = 'not_requested';
        setRequired('location_choice', false);
        setRequired('sick_choice', false);
        setRequired('study_choice', false);
        setRequired('others_choice', false);

        disableTextarea('#location_within_ph');
        disableTextarea('#location_abroad');
        disableTextarea('#hospital_specify');
        disableTextarea('#outpatient_specify');

        if (text.includes('vacation') || text.includes('mandatory') || text.includes('forced') || text.includes('special privilege')) {
            show('group-vl');
            setRequired('location_choice', true);
        } else if (text.includes('sick')) {
            show('group-sick');
            setRequired('sick_choice', true);
        } else if (text.includes('study')) {
            show('group-study');
            setRequired('study_choice', true);
        } else if (text.includes('others') || text.includes('other')) {
            show('group-others');
            setRequired('others_choice', true);
            if (commutation) commutation.value = 'requested';
        } else {
            hideAll();
        }
    }

    $(document).ready(function() {
        // Initialize daterangepicker
        $('#daterange-' + leaveId).daterangepicker();

        // Initialize select2
        $('#leaveid-' + leaveId).select2({
            dropdownParent: $('#edit-leave-modal-' + leaveId)
        });

        // Initialize the form based on current leave type
        var currentSelect = document.getElementById('leaveid-' + leaveId);
        if (currentSelect) {
            var opt = currentSelect.options[currentSelect.selectedIndex];
            var currentText = norm(opt && (opt.getAttribute('data-text') || opt.text));
            applyDefaultsForType(currentText);
        }

        // Handle leave type change
        $('#leaveid-' + leaveId).on('change', function() {
            var opt = this.options[this.selectedIndex];
            var newText = norm(opt && (opt.getAttribute('data-text') || opt.text));
            applyDefaultsForType(newText);
        });

        // Checkbox → textarea toggle for location
        $('#loc_ph-' + leaveId).on('change', function() {
            if (this.checked) {
                enableTextarea('#location_within_ph');
                disableTextarea('#location_abroad');
            } else {
                disableTextarea('#location_within_ph');
            }
        });

        $('#loc_abroad-' + leaveId).on('change', function() {
            if (this.checked) {
                enableTextarea('#location_abroad');
                disableTextarea('#location_within_ph');
            } else {
                disableTextarea('#location_abroad');
            }
        });

        // Checkbox → textarea toggle for sick
        $('#sick_hospital-' + leaveId).on('change', function() {
            if (this.checked) {
                enableTextarea('#hospital_specify');
                disableTextarea('#outpatient_specify');
            } else {
                disableTextarea('#hospital_specify');
            }
        });

        $('#sick_outpatient-' + leaveId).on('change', function() {
            if (this.checked) {
                enableTextarea('#outpatient_specify');
                disableTextarea('#hospital_specify');
            } else {
                disableTextarea('#outpatient_specify');
            }
        });

        // Study radios → hidden fields
        $('input[name="study_choice"]').on('change', function() {
            $('#study_masters_degree-' + leaveId).val(this.value === 'masters' ? '1' : '0');
            $('#study_bar_board-' + leaveId).val(this.value === 'bar_board' ? '1' : '0');
        });

        // Others radios → hidden fields + commutation
        $('input[name="others_choice"]').on('change', function() {
            $('#other_monetization-' + leaveId).val(this.value === 'monetization' ? '1' : '0');
            $('#other_terminal_leave-' + leaveId).val(this.value === 'terminal' ? '1' : '0');
            $('#commutation-' + leaveId).val('requested');
        });

        // Prevent duplicate submission
        $('#edit-leave-form-' + leaveId).on('submit', function(e) {
            var submitBtn = $('#edit-submit-leave-btn-' + leaveId);
            var submitText = $('#edit-submit-text-' + leaveId);
            var submitSpinner = $('#edit-submit-spinner-' + leaveId);

            submitBtn.prop('disabled', true);
            submitText.hide();
            submitSpinner.show();
        });

        // Reset button state when modal is opened
        $('#edit-leave-modal-' + leaveId).on('shown.bs.modal', function() {
            var submitBtn = $('#edit-submit-leave-btn-' + leaveId);
            var submitText = $('#edit-submit-text-' + leaveId);
            var submitSpinner = $('#edit-submit-spinner-' + leaveId);

            submitBtn.prop('disabled', false);
            submitText.show();
            submitSpinner.hide();
        });
    });
})();
</script>
