<!-- /.modal -->

<div class="modal fade" id="new-leave-modal-lg">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Leave</h4>
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
                                        <h3 class="card-title">Leave Information</h3>

                                    </div>

                                    <!-- /.card-header -->
                                    <!-- form start -->
                                    <form method="POST" action="  {{ route('leave-management.store') }}" enctype="multipart/form-data" id="admin-leave-form">

                                        {{ csrf_field() }}
                                        <div class="card-body">

                                            <div class="card-body">
                                                <div class="form-group row">
                                                    <label class="col-sm-3" for="employeeid">Employee Name : <span class="text-danger">*</span></label>
                                                    <div class="col-sm-9">
                                                        <select id="employeeid" name="employeeid" class="form-control select2" aria-placeholder="-- Choose Employee Name --" style="width: 100%;" required>
                                                            <option value="" disabled selected>-- Choose Employee Name --</option>
                                                            @foreach($Employees as $Employee)
                                                            <option value="{{ $Employee->id }}">{{ $Employee->lastname . ', ' . $Employee->firstname . ' ' . $Employee->middlename }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('employeeid')
                                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="form-group row ">
                                                    <label class="col-sm-3" for="leaveid">Leave Type : <span class="text-danger">*</span></label>
                                                    <div class="col-sm-9">
                                                        <select id="leaveid" name="leaveid" class="form-control select2" aria-placeholder="-- Choose Leave Type --" style="width: 100%;" required>
                                                            <option value="" disabled selected>-- Choose Leave Type --</option>
                                                            @foreach($Leave_Types as $Leave_Type)

                                                            <option value="{{ $Leave_Type->id }}" data-text="{{ $Leave_Type->leave_type }}">{{ $Leave_Type->leave_type }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('leaveid')
                                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-sm-3" for="leave_type">Date Range : <span class="text-danger">*</span></label>
                                                    <div class="input-group col-sm-9">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                                <i class="far fa-calendar-alt"></i>
                                                            </span>
                                                        </div>
                                                        <input type="text" name="daterange" id="daterange" class="form-control float-right" required>
                                                    </div>
                                                    <!-- /.input group -->
                                                </div>
                                            </div>

                                            <!-- Dynamic 6.B Details (shown based on Leave Type) -->
                                            <div id="admin-details-6b" class="mt-3" style="display:none;">
                                                <div class="card card-outline card-info">
                                                    <div class="card-header">
                                                        <h3 class="card-title">6.B Details of Leave</h3>
                                                    </div>
                                                    <div class="card-body">
                                                        <!-- Vacation / Mandatory / Special Privilege Leave -->
                                                        <div id="admin-group-vl" style="display:none;">
                                                            <p class="mb-2"><i>In case of Vacation/Mandatory/Forced/Special Privilege Leave:</i></p>
                                                            <div class="form-group">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="location_choice" id="admin_loc_ph" value="within_ph">
                                                                    <label class="form-check-label" for="admin_loc_ph">Within the Philippines</label>
                                                                </div>
                                                                <textarea class="form-control mt-2" name="location_within_ph" id="admin_location_within_ph" rows="2" placeholder="Specify location within the Philippines" style="resize: none;" disabled></textarea>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="location_choice" id="admin_loc_abroad" value="abroad">
                                                                    <label class="form-check-label" for="admin_loc_abroad">Abroad (Specify)</label>
                                                                </div>
                                                                <textarea class="form-control mt-2" name="location_abroad" id="admin_location_abroad" rows="2" placeholder="Specify location abroad" style="resize: none;" disabled></textarea>
                                                            </div>
                                                        </div>

                                                        <!-- Sick Leave -->
                                                        <div id="admin-group-sick" style="display:none;">
                                                            <p class="mb-2"><i>In case of Sick Leave:</i></p>
                                                            <div class="form-group">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="sick_choice" id="admin_sick_hospital" value="hospital">
                                                                    <label class="form-check-label" for="admin_sick_hospital">In Hospital (Specify Illness)</label>
                                                                </div>
                                                                <textarea class="form-control mt-2" name="hospital_specify" id="admin_hospital_specify" rows="2" placeholder="Specify illness (hospitalized)" style="resize: none;" disabled></textarea>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="sick_choice" id="admin_sick_outpatient" value="outpatient">
                                                                    <label class="form-check-label" for="admin_sick_outpatient">Out Patient (Specify Illness)</label>
                                                                </div>
                                                                <textarea class="form-control mt-2" name="outpatient_specify" id="admin_outpatient_specify" rows="2" placeholder="Specify illness (outpatient)" style="resize: none;" disabled></textarea>
                                                            </div>
                                                        </div>

                                                        <!-- Study Leave -->
                                                        <div id="admin-group-study" style="display:none;">
                                                            <p class="mb-2"><i>In case of Study Leave:</i></p>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="study_choice" id="admin_study_masters" value="masters">
                                                                <label class="form-check-label" for="admin_study_masters">Completion of Master's Degree</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="study_choice" id="admin_study_bar" value="bar_board">
                                                                <label class="form-check-label" for="admin_study_bar">BAR/Board Examination Review</label>
                                                            </div>
                                                            <input type="hidden" name="study_masters_degree" id="admin_study_masters_degree" value="0">
                                                            <input type="hidden" name="study_bar_board" id="admin_study_bar_board" value="0">
                                                        </div>

                                                        <!-- Others -->
                                                        <div id="admin-group-others" style="display:none;">
                                                            <p class="mb-2"><i>Other Purpose:</i></p>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="others_choice" id="admin_others_monetization" value="monetization">
                                                                <label class="form-check-label" for="admin_others_monetization">Monetization of Leave Credits</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="others_choice" id="admin_others_terminal" value="terminal">
                                                                <label class="form-check-label" for="admin_others_terminal">Terminal Leave</label>
                                                            </div>
                                                            <input type="hidden" name="other_monetization" id="admin_other_monetization" value="0">
                                                            <input type="hidden" name="other_terminal_leave" id="admin_other_terminal_leave" value="0">
                                                        </div>

                                                        <!-- Hidden commutation field (7.D auto-set on submit) -->
                                                        <input type="hidden" name="commutation" id="admin_commutation" value="not_requested">
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <!-- /.card-body -->
                                        <div class="card-footer">
                                            <button type="submit" class="btn btn-primary" id="admin-submit-btn">
                                                <span id="admin-submit-text">Submit</span>
                                                <span id="admin-submit-spinner" style="display: none;">
                                                    <i class="fas fa-spinner fa-spin"></i> Submitting...
                                                </span>
                                            </button>
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
    (function() {
        function norm(text) {
            return (text || '').toLowerCase().trim();
        }

        function show(containerId) {
            document.getElementById('admin-details-6b').style.display = 'block';
            ['admin-group-vl', 'admin-group-sick', 'admin-group-study', 'admin-group-others'].forEach(function(id) {
                var el = document.getElementById(id);
                if (el) el.style.display = (id === containerId) ? 'block' : 'none';
            });
        }

        function hideAll() {
            document.getElementById('admin-details-6b').style.display = 'none';
            ['admin-group-vl', 'admin-group-sick', 'admin-group-study', 'admin-group-others'].forEach(function(id) {
                var el = document.getElementById(id);
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
            var el = document.querySelector(sel);
            if (!el) return;
            el.removeAttribute('disabled');
            el.setAttribute('required', 'required');
        }

        function disableTextarea(sel) {
            var el = document.querySelector(sel);
            if (!el) return;
            el.value = '';
            el.setAttribute('disabled', 'disabled');
            el.removeAttribute('required');
        }

        function clearInputs(selectors) {
            selectors.forEach(function(sel) {
                document.querySelectorAll(sel).forEach(function(el) {
                    if (el.type === 'checkbox' || el.type === 'radio') {
                        el.checked = false;
                    } else {
                        el.value = '';
                    }
                    el.removeAttribute('required');
                });
            });
        }

        function resetState(commutation) {
            clearInputs(['input[name="location_choice"]', 'input[name="sick_choice"]', 'input[name="study_choice"]', 'input[name="others_choice"]', 'textarea[name="location_within_ph"]', 'textarea[name="location_abroad"]', 'textarea[name="hospital_specify"]', 'textarea[name="outpatient_specify"]']);
            disableTextarea('#admin_location_within_ph');
            disableTextarea('#admin_location_abroad');
            disableTextarea('#admin_hospital_specify');
            disableTextarea('#admin_outpatient_specify');
            var mastersDegree = document.getElementById('admin_study_masters_degree');
            var barBoard = document.getElementById('admin_study_bar_board');
            var monetization = document.getElementById('admin_other_monetization');
            var terminalLeave = document.getElementById('admin_other_terminal_leave');
            if (mastersDegree) mastersDegree.value = '0';
            if (barBoard) barBoard.value = '0';
            if (monetization) monetization.value = '0';
            if (terminalLeave) terminalLeave.value = '0';
            if (commutation) commutation.value = 'not_requested';
            hideAll();
        }

        function enforceSingleCheckbox(groupName) {
            var boxes = Array.prototype.slice.call(document.querySelectorAll('#new-leave-modal-lg input[name="' + groupName + '"]'));
            boxes.forEach(function(box) {
                box.addEventListener('change', function() {
                    if (this.checked) {
                        boxes.forEach(function(other) {
                            if (other !== box) other.checked = false;
                        });
                    }
                });
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            var select = document.getElementById('leaveid');
            var commutation = document.getElementById('admin_commutation');
            var modal = document.getElementById('new-leave-modal-lg');

            enforceSingleCheckbox('location_choice');
            enforceSingleCheckbox('sick_choice');

            // Checkbox → textarea toggle
            [
                ['#admin_loc_ph', '#admin_location_within_ph', '#admin_location_abroad']
                , ['#admin_loc_abroad', '#admin_location_abroad', '#admin_location_within_ph']
            ].forEach(function(row) {
                var box = document.querySelector(row[0]);
                if (!box) return;
                box.addEventListener('change', function() {
                    if (box.checked) {
                        enableTextarea(row[1]);
                        disableTextarea(row[2]);
                    } else {
                        disableTextarea(row[1]);
                    }
                });
            });

            [
                ['#admin_sick_hospital', '#admin_hospital_specify', '#admin_outpatient_specify']
                , ['#admin_sick_outpatient', '#admin_outpatient_specify', '#admin_hospital_specify']
            ].forEach(function(row) {
                var box = document.querySelector(row[0]);
                if (!box) return;
                box.addEventListener('change', function() {
                    if (box.checked) {
                        enableTextarea(row[1]);
                        disableTextarea(row[2]);
                    } else {
                        disableTextarea(row[1]);
                    }
                });
            });

            // Study radios → hidden fields
            document.querySelectorAll('#new-leave-modal-lg input[name="study_choice"]').forEach(function(r) {
                r.addEventListener('change', function() {
                    var mastersDegree = document.getElementById('admin_study_masters_degree');
                    var barBoard = document.getElementById('admin_study_bar_board');
                    if (mastersDegree) mastersDegree.value = (this.value === 'masters') ? '1' : '0';
                    if (barBoard) barBoard.value = (this.value === 'bar_board') ? '1' : '0';
                });
            });

            // Others radios → hidden fields + commutation
            document.querySelectorAll('#new-leave-modal-lg input[name="others_choice"]').forEach(function(r) {
                r.addEventListener('change', function() {
                    var monet = document.getElementById('admin_other_monetization');
                    var term = document.getElementById('admin_other_terminal_leave');
                    if (monet) monet.value = (this.value === 'monetization') ? '1' : '0';
                    if (term) term.value = (this.value === 'terminal') ? '1' : '0';
                    if (commutation) commutation.value = 'requested';
                });
            });

            function applyDefaultsForType(text) {
                if (commutation) commutation.value = 'not_requested';
                setRequired('location_choice', false);
                setRequired('sick_choice', false);
                setRequired('study_choice', false);
                setRequired('others_choice', false);

                disableTextarea('#admin_location_within_ph');
                disableTextarea('#admin_location_abroad');
                disableTextarea('#admin_hospital_specify');
                disableTextarea('#admin_outpatient_specify');

                if (text.includes('vacation') || text.includes('mandatory') || text.includes('forced') || text.includes('special privilege')) {
                    show('admin-group-vl');
                    setRequired('location_choice', true);
                } else if (text.includes('sick')) {
                    show('admin-group-sick');
                    setRequired('sick_choice', true);
                } else if (text.includes('study')) {
                    show('admin-group-study');
                    setRequired('study_choice', true);
                } else if (text.includes('others') || text.includes('other')) {
                    show('admin-group-others');
                    setRequired('others_choice', true);
                    if (commutation) commutation.value = 'requested';
                } else {
                    hideAll();
                }
            }

            function resetAll() {
                if (select) {
                    $(select).val('').trigger('change');
                }
                var employeeSelect = document.getElementById('employeeid');
                if (employeeSelect) {
                    $(employeeSelect).val('').trigger('change');
                }
                resetState(commutation);
            }

            function onLeaveChange() {
                var opt = select.options[select.selectedIndex];
                var newText = norm(opt && (opt.getAttribute('data-text') || opt.text));
                resetState(commutation);
                applyDefaultsForType(newText);
            }

            if (select) {
                hideAll();
                $(select).on('change', onLeaveChange);
            }

            // Reset form when modal opens
            if (modal && typeof $ !== 'undefined') {
                $('#new-leave-modal-lg').on('shown.bs.modal', function() {
                    resetAll();
                });

                // Clean up on modal close
                $('#new-leave-modal-lg').on('hidden.bs.modal', function() {
                    resetAll();
                });
            }

            // Loading state for submit button
            const adminForm = document.getElementById('admin-leave-form');
            const adminSubmitBtn = document.getElementById('admin-submit-btn');
            const adminSubmitText = document.getElementById('admin-submit-text');
            const adminSubmitSpinner = document.getElementById('admin-submit-spinner');

            if (adminForm && adminSubmitBtn) {
                adminForm.addEventListener('submit', function(e) {
                    adminSubmitBtn.disabled = true;
                    adminSubmitText.style.display = 'none';
                    adminSubmitSpinner.style.display = 'inline';
                });

                $('#new-leave-modal-lg').on('shown.bs.modal', function() {
                    adminSubmitBtn.disabled = false;
                    adminSubmitText.style.display = 'inline';
                    adminSubmitSpinner.style.display = 'none';
                });
            }
        });
    })();

</script>
