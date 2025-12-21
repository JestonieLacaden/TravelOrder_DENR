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
                                    <form method="POST" action="  {{ route('userleave.storeUserLeave') }}" enctype="multipart/form-data" id="leave-form">

                                        {{ csrf_field() }}
                                        <div class="card-body">
                                            <div class="form-group row ">
                                                <label class="col-sm-3" for="leaveid">Leave Type : <span class="text-danger">*</span></label>
                                                <div class="col-sm-9">
                                                    <select id="leaveid" name="leaveid" class="form-control select2" style="width: 100%;" required>
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
                                                    <input type="text" name="daterange" id="daterange" class="form-control float-right">
                                                </div>
                                                <!-- /.input group -->
                                            </div>


                                            <!-- Dynamic 6.B Details (shown based on Leave Type) -->
                                            <div id="details-6b" class="mt-3" style="display:none;">
                                                <div class="card card-outline card-info">
                                                    <div class="card-header">
                                                        <h3 class="card-title">6.B Details of Leave</h3>
                                                    </div>
                                                    <div class="card-body">
                                                        <!-- Vacation / Mandatory / Special Privilege Leave -->
                                                        <div id="group-vl" style="display:none;">
                                                            <p class="mb-2"><i>In case of Vacation/Mandatory/Forced/Special Privilege Leave:</i></p>
                                                            <div class="form-group">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="location_choice" id="loc_ph" value="within_ph">
                                                                    <label class="form-check-label" for="loc_ph">Within the Philippines</label>
                                                                </div>
                                                                <textarea class="form-control mt-2" name="location_within_ph" id="location_within_ph" rows="2" placeholder="Specify location within the Philippines" style="resize: none;" disabled></textarea>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="location_choice" id="loc_abroad" value="abroad">
                                                                    <label class="form-check-label" for="loc_abroad">Abroad (Specify)</label>
                                                                </div>
                                                                <textarea class="form-control mt-2" name="location_abroad" id="location_abroad" rows="2" placeholder="Specify location abroad" style="resize: none;" disabled></textarea>
                                                            </div>
                                                        </div>

                                                        <!-- Sick Leave -->
                                                        <div id="group-sick" style="display:none;">
                                                            <p class="mb-2"><i>In case of Sick Leave:</i></p>
                                                            <div class="form-group">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="sick_choice" id="sick_hospital" value="hospital">
                                                                    <label class="form-check-label" for="sick_hospital">In Hospital (Specify Illness)</label>
                                                                </div>
                                                                <textarea class="form-control mt-2" name="hospital_specify" id="hospital_specify" rows="2" placeholder="Specify illness (hospitalized)" style="resize: none;" disabled></textarea>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="sick_choice" id="sick_outpatient" value="outpatient">
                                                                    <label class="form-check-label" for="sick_outpatient">Out Patient (Specify Illness)</label>
                                                                </div>
                                                                <textarea class="form-control mt-2" name="outpatient_specify" id="outpatient_specify" rows="2" placeholder="Specify illness (outpatient)" style="resize: none;" disabled></textarea>
                                                            </div>
                                                        </div>

                                                        <!-- Study Leave -->
                                                        <div id="group-study" style="display:none;">
                                                            <p class="mb-2"><i>In case of Study Leave:</i></p>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="study_choice" id="study_masters" value="masters">
                                                                <label class="form-check-label" for="study_masters">Completion of Master's Degree</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="study_choice" id="study_bar" value="bar_board">
                                                                <label class="form-check-label" for="study_bar">BAR/Board Examination Review</label>
                                                            </div>
                                                            <input type="hidden" name="study_masters_degree" id="study_masters_degree" value="0">
                                                            <input type="hidden" name="study_bar_board" id="study_bar_board" value="0">
                                                        </div>

                                                        <!-- Others -->
                                                        <div id="group-others" style="display:none;">
                                                            <p class="mb-2"><i>Other Purpose:</i></p>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="others_choice" id="others_monetization" value="monetization">
                                                                <label class="form-check-label" for="others_monetization">Monetization of Leave Credits</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="others_choice" id="others_terminal" value="terminal">
                                                                <label class="form-check-label" for="others_terminal">Terminal Leave</label>
                                                            </div>
                                                            <input type="hidden" name="other_monetization" id="other_monetization" value="0">
                                                            <input type="hidden" name="other_terminal_leave" id="other_terminal_leave" value="0">
                                                        </div>

                                                        <!-- Hidden commutation field (7.D auto-set on submit) -->
                                                        <input type="hidden" name="commutation" id="commutation" value="not_requested">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary" id="submit-leave-btn">
                                        <span id="submit-text">Submit</span>
                                        <span id="submit-spinner" style="display: none;">
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

<!-- Bootstrap confirmation modal for discard actions -->
<div class="modal fade" id="confirm-discard-modal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle"></i> Unsaved Changes
                </h5>
            </div>
            <div class="modal-body">
                <p id="confirm-discard-message" class="mb-0">You have unsaved changes. Do you want to discard them?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">No, Continue Editing</button>
                <button type="button" class="btn btn-danger" id="confirm-discard-proceed">Yes, Discard Changes</button>
            </div>
        </div>
    </div>
</div>

<script>
    (function() {
        function norm(text) {
            return (text || '').toLowerCase().trim();
        }

        function show(containerId) {
            document.getElementById('details-6b').style.display = 'block';
            ['group-vl', 'group-sick', 'group-study', 'group-others'].forEach(function(id) {
                var el = document.getElementById(id);
                if (el) el.style.display = (id === containerId) ? 'block' : 'none';
            });
        }

        function hideAll() {
            document.getElementById('details-6b').style.display = 'none';
            ['group-vl', 'group-sick', 'group-study', 'group-others'].forEach(function(id) {
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
            disableTextarea('#location_within_ph');
            disableTextarea('#location_abroad');
            disableTextarea('#hospital_specify');
            disableTextarea('#outpatient_specify');
            document.getElementById('study_masters_degree').value = '0';
            document.getElementById('study_bar_board').value = '0';
            document.getElementById('other_monetization').value = '0';
            document.getElementById('other_terminal_leave').value = '0';
            if (commutation) commutation.value = 'not_requested';
            hideAll();
        }

        function enforceSingleCheckbox(groupName) {
            var boxes = Array.prototype.slice.call(document.querySelectorAll('input[name="' + groupName + '"]'));
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

        function markDirtyTracker(tracker) {
            return function() {
                tracker.dirty = true;
            };
        }

        function anyChecked(name) {
            return Array.prototype.some.call(document.querySelectorAll('input[name="' + name + '"]'), function(el) {
                return el.checked;
            });
        }

        function anyTextareaFilled() {
            return Array.prototype.some.call(document.querySelectorAll('#details-6b textarea'), function(el) {
                return (el.value || '').trim().length > 0;
            });
        }

        function isDirty(tracker) {
            return tracker.dirty || anyTextareaFilled() || anyChecked('location_choice') || anyChecked('sick_choice') || anyChecked('study_choice') || anyChecked('others_choice');
        }

        document.addEventListener('DOMContentLoaded', function() {
            var select = document.getElementById('leaveid');
            var commutation = document.getElementById('commutation');
            var modal = document.getElementById('new-leave-modal-lg');
            var tracker = {
                dirty: false
                , lastValue: ''
            };
            var pendingAction = null;

            function showConfirm(message, onConfirm) {
                pendingAction = onConfirm;
                var msgEl = document.getElementById('confirm-discard-message');
                if (msgEl) msgEl.textContent = message;
                if (typeof $ !== 'undefined') {
                    $('#confirm-discard-modal').modal('show');
                }
            }

            if (typeof $ !== 'undefined') {
                $('#confirm-discard-proceed').on('click', function() {
                    $('#confirm-discard-modal').modal('hide');
                    if (typeof pendingAction === 'function') {
                        pendingAction();
                    }
                    pendingAction = null;
                });
            }

            enforceSingleCheckbox('location_choice');
            enforceSingleCheckbox('sick_choice');

            ['#loc_ph', '#loc_abroad', '#sick_hospital', '#sick_outpatient'].forEach(function(sel) {
                var el = document.querySelector(sel);
                if (!el) return;
                el.addEventListener('change', function() {
                    tracker.dirty = true;
                });
            });
            document.querySelectorAll('#details-6b textarea').forEach(function(el) {
                el.addEventListener('input', markDirtyTracker(tracker));
            });
            document.querySelectorAll('input[name="study_choice"]').forEach(function(el) {
                el.addEventListener('change', markDirtyTracker(tracker));
            });
            document.querySelectorAll('input[name="others_choice"]').forEach(function(el) {
                el.addEventListener('change', markDirtyTracker(tracker));
            });

            // Checkbox → textarea toggle
            [
                ['#loc_ph', '#location_within_ph', '#location_abroad']
                , ['#loc_abroad', '#location_abroad', '#location_within_ph']
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
                ['#sick_hospital', '#hospital_specify', '#outpatient_specify']
                , ['#sick_outpatient', '#outpatient_specify', '#hospital_specify']
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
            document.querySelectorAll('input[name="study_choice"]').forEach(function(r) {
                r.addEventListener('change', function() {
                    document.getElementById('study_masters_degree').value = (this.value === 'masters') ? '1' : '0';
                    document.getElementById('study_bar_board').value = (this.value === 'bar_board') ? '1' : '0';
                });
            });

            // Others radios → hidden fields + commutation
            document.querySelectorAll('input[name="others_choice"]').forEach(function(r) {
                r.addEventListener('change', function() {
                    var monet = document.getElementById('other_monetization');
                    var term = document.getElementById('other_terminal_leave');
                    monet.value = (this.value === 'monetization') ? '1' : '0';
                    term.value = (this.value === 'terminal') ? '1' : '0';
                    if (commutation) commutation.value = 'requested';
                });
            });

            function applyDefaultsForType(text) {
                // Reset base requirements
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

            function resetAll() {
                if (select) select.value = '';
                resetState(commutation);
                tracker.dirty = false;
                tracker.lastValue = '';
            }

            function onLeaveChange(evt) {
                var opt = select.options[select.selectedIndex];
                var newText = norm(opt && (opt.getAttribute('data-text') || opt.text));
                var newVal = select.value;
                if (tracker.lastValue && tracker.lastValue !== newVal && isDirty(tracker)) {
                    evt.preventDefault();
                    var restoreVal = tracker.lastValue;
                    select.value = tracker.lastValue;
                    showConfirm('Changing leave type will discard your inputs. Continue?', function() {
                        select.value = newVal;
                        resetState(commutation);
                        tracker.lastValue = newVal;
                        applyDefaultsForType(newText);
                        tracker.dirty = false;
                    });
                    return;
                }
                resetState(commutation);
                tracker.lastValue = newVal;
                applyDefaultsForType(newText);
                tracker.dirty = false;
            }

            if (select) {
                hideAll();
                select.addEventListener('change', onLeaveChange);
            }

            // Modal close warning if dirty
            if (modal && typeof $ !== 'undefined') {
                $('#new-leave-modal-lg').on('hide.bs.modal', function(e) {
                    if (isDirty(tracker)) {
                        e.preventDefault();
                        showConfirm('You have unsaved inputs. Close and discard?', function() {
                            resetAll();
                            $('#new-leave-modal-lg').modal('hide');
                        });
                        return;
                    }
                    resetAll();
                });
            }
            // Prevent duplicate submission with loading state
            const leaveForm = document.getElementById('leave-form');
            const submitBtn = document.getElementById('submit-leave-btn');
            const submitText = document.getElementById('submit-text');
            const submitSpinner = document.getElementById('submit-spinner');

            if (leaveForm && submitBtn) {
                leaveForm.addEventListener('submit', function(e) {
                    // Disable button and show loading
                    submitBtn.disabled = true;
                    submitText.style.display = 'none';
                    submitSpinner.style.display = 'inline';

                    // Note: Form will submit normally, button stays disabled to prevent duplicates
                });

                // Reset button state when modal is opened
                $('#new-leave-modal-lg').on('shown.bs.modal', function() {
                    submitBtn.disabled = false;
                    submitText.style.display = 'inline';
                    submitSpinner.style.display = 'none';
                });
            }
        });
    })();

</script>
