<!-- Approver1 Edit Modal for Leave Credits -->
<style>
    /* Remove spinner arrows from number inputs */
    input[type="number"]::-webkit-outer-spin-button,
    input[type="number"]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type="number"] {
        -moz-appearance: textfield;
    }

</style>
<div class="modal fade" id="edit-leave-modal-lg{{ $Leave->id }}" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h4 class="modal-title">Edit Leave Credits - {{ optional($Leave->employee)->firstname ?? 'Employee' }} {{ optional($Leave->employee)->lastname ?? '' }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form method="POST" action="{{ route('leave.credits.save', $Leave->id) }}" id="edit-credits-form">
                {{ csrf_field() }}
                @method('PUT')

                <div class="modal-body">
                    <!-- Display key info -->
                    <div class="form-row mb-3 text-sm">
                        <div class="col-md-6">
                            <strong>Employee:</strong><br>
                            {{ optional($Leave->employee)->firstname ?? '' }} {{ optional($Leave->employee)->middlename ?? '' }} {{ optional($Leave->employee)->lastname ?? '' }}
                        </div>
                        <div class="col-md-6">
                            <strong>Leave Type:</strong><br>
                            {{ optional($Leave->leave_type)->leave_type ?? 'N/A' }}<br>
                            <small class="text-muted">
                                @php
                                $daterange = $Leave->daterange ?? '';
                                if (!empty($daterange)) {
                                $dates = explode(' - ', $daterange);
                                if (count($dates) == 2) {
                                try {
                                $startDate = \Carbon\Carbon::createFromFormat('m/d/Y', trim($dates[0]));
                                $endDate = \Carbon\Carbon::createFromFormat('m/d/Y', trim($dates[1]));
                                $dayCount = $endDate->diffInDays($startDate) + 1;

                                // Show 0.5 Day if half-day leave
                                if ($Leave->is_half_day) {
                                    echo trim($dates[0]) . ' - ' . trim($dates[1]) . ' (0.5 Day)';
                                } else {
                                    echo trim($dates[0]) . ' - ' . trim($dates[1]) . ' (' . $dayCount . ' Day' . ($dayCount != 1 ? 's' : '') . ')';
                                }
                                } catch (\Exception $e) {
                                echo $daterange;
                                }
                                } else {
                                echo $daterange;
                                }
                                }
                                @endphp
                            </small>
                        </div>
                    </div>

                    <hr>

                    <!-- Editable Leave Credits Table -->
                    <h6><strong>Leave Credits Certification</strong></h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width: 40%;">Criteria</th>
                                    <th class="text-center">Vacation Leave</th>
                                    <th class="text-center">Sick Leave</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                // Calculate days from daterange
                                $dayCount = 0;
                                $daterange = $Leave->daterange ?? '';
                                if (!empty($daterange)) {
                                $dates = explode(' - ', $daterange);
                                if (count($dates) == 2) {
                                try {
                                $startDate = \Carbon\Carbon::createFromFormat('m/d/Y', trim($dates[0]));
                                $endDate = \Carbon\Carbon::createFromFormat('m/d/Y', trim($dates[1]));
                                $dayCount = $endDate->diffInDays($startDate) + 1;
                                } catch (\Exception $e) {
                                $dayCount = 0;
                                }
                                }
                                }

                                // If half-day leave, use 0.5 instead of full day count
                                if ($Leave->is_half_day) {
                                    $dayCount = 0.5;
                                }

                                // Check leave type
                                $leaveTypeName = strtolower(optional($Leave->leave_type)->leave_type ?? '');
                                $isVacationLeave = strpos($leaveTypeName, 'vacation') !== false;
                                $isSickLeave = strpos($leaveTypeName, 'sick') !== false;

                                // Get employee's leave balances from employee table
                                $employee = $Leave->Employee;
                                $employeeVacationBalance = $employee ? ($employee->vacation_leave_balance ?? 0) : 0;
                                $employeeSickBalance = $employee ? ($employee->sick_leave_balance ?? 0) : 0;
                                @endphp

                                <tr>
                                    <td><strong>Total Earned</strong></td>
                                    <td class="text-center">
                                        <input type="text" name="vacation_earned" class="form-control form-control-sm text-center vacation-earned" value="{{ number_format($employeeVacationBalance, 3, '.', '') }}" placeholder="0.000" {{ !$isVacationLeave ? 'disabled' : '' }} pattern="[0-9]+(\.[0-9]{1,3})?">
                                    </td>
                                    <td class="text-center">
                                        <input type="text" name="sick_earned" class="form-control form-control-sm text-center sick-earned" value="{{ number_format($employeeSickBalance, 3, '.', '') }}" placeholder="0.000" {{ !$isSickLeave ? 'disabled' : '' }} pattern="[0-9]+(\.[0-9]{1,3})?">
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Less this Application</strong></td>
                                    <td class="text-center">
                                        <input type="text" name="vacation_this_app" class="form-control form-control-sm text-center vacation-this-app" value="{{ number_format($isVacationLeave ? $dayCount : 0, 3, '.', '') }}" placeholder="0.000" readonly pattern="[0-9]+(\\.[ 0-9]{1,3})?">
                                    </td>
                                    <td class="text-center">
                                        <input type="text" name="sick_this_app" class="form-control form-control-sm text-center sick-this-app" value="{{ number_format($isSickLeave ? $dayCount : 0, 3, '.', '') }}" placeholder="0.000" readonly pattern="[0-9]+(\\.[ 0-9]{1,3})?">
                                    </td>
                                </tr>
                                <tr class="bg-light">
                                    <td><strong>Balance</strong></td>
                                    <td class="text-center">
                                        <input type="text" name="vacation_balance" class="form-control form-control-sm text-center vacation-balance" value="{{ number_format($Leave->vacation_balance ?? 0, 3, '.', '') }}" placeholder="0.000" readonly pattern="[0-9]+(\\.[ 0-9]{1,3})?">
                                    </td>
                                    <td class="text-center">
                                        <input type="text" name="sick_balance" class="form-control form-control-sm text-center sick-balance" value="{{ number_format($Leave->sick_balance ?? 0, 3, '.', '') }}" placeholder="0.000" readonly pattern="[0-9]+(\\.[ 0-9]{1,3})?">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <hr>

                    <!-- 7.C Approved For -->
                    <h6><strong>7.C Approved For</strong></h6>
                    <div class="row">
                        <div class="col-md-4">
                            <label class="text-sm">Days with Pay:</label>
                            <input type="number" name="days_with_pay" min="0" step="0.5" class="form-control form-control-sm" value="{{ $Leave->days_with_pay && $Leave->days_with_pay > 0 ? $Leave->days_with_pay : '' }}" placeholder="0">
                        </div>
                        <div class="col-md-4">
                            <label class="text-sm">Days without Pay:</label>
                            <input type="number" name="days_without_pay" min="0" step="0.5" class="form-control form-control-sm" value="{{ $Leave->days_without_pay && $Leave->days_without_pay > 0 ? $Leave->days_without_pay : '' }}" placeholder="0">
                        </div>
                        <div class="col-md-4">
                            <label class="text-sm">Others (Specify):</label>
                            <input type="text" name="approved_others" class="form-control form-control-sm" value="{{ $Leave->approved_others ?? '' }}" placeholder="Specify others">
                        </div>
                    </div>

                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i> Fill in all applicable fields before approving or rejecting the request.
                    </small>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Done Editing
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Unsaved Changes Warning Modal -->
<div class="modal fade" id="unsaved-warning-modal-{{ $Leave->id }}" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle"></i> Unsaved Changes
                </h5>
            </div>
            <div class="modal-body">
                <p>You have unsaved changes. Do you want to discard them?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-action="cancel">No, Continue Editing</button>
                <button type="button" class="btn btn-danger" data-action="discard">Yes, Discard Changes</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        const modalId = 'edit-leave-modal-lg{{ $Leave->id }}';
        const warningModalId = 'unsaved-warning-modal-{{ $Leave->id }}';
        const modal = $('#' + modalId);
        const warningModal = $('#' + warningModalId);
        if (!modal.length) return;

        const form = modal.find('form');
        if (!form.length) return;

        let originalData = {};
        let hasChanges = false;
        let isSubmitting = false;
        let pendingHide = false;

        // Auto-compute balance when Total Earned changes
        function computeBalance() {
            // Vacation Leave Balance (3 decimals)
            const vacationEarned = parseFloat(modal.find('.vacation-earned').val()) || 0;
            const vacationThisApp = parseFloat(modal.find('.vacation-this-app').val()) || 0;
            let vacationBalance = Math.max(0, vacationEarned - vacationThisApp);
            // Round to 3 decimals
            vacationBalance = Math.round(vacationBalance * 1000) / 1000;
            modal.find('.vacation-balance').val(vacationBalance.toFixed(3));

            // Sick Leave Balance (3 decimals)
            const sickEarned = parseFloat(modal.find('.sick-earned').val()) || 0;
            const sickThisApp = parseFloat(modal.find('.sick-this-app').val()) || 0;
            let sickBalance = Math.max(0, sickEarned - sickThisApp);
            // Round to 3 decimals
            sickBalance = Math.round(sickBalance * 1000) / 1000;
            modal.find('.sick-balance').val(sickBalance.toFixed(3));

            console.log('Balance computed - Vacation:', vacationBalance, 'Sick:', sickBalance);

            // Validate: Don't allow saving if balance is 0 or negative (must fix the earned/this_app values)
            if (vacationBalance < 0 || sickBalance < 0) {
                console.warn('WARNING: Balance cannot be negative!');
            }
        }

        // Attach change event to Total Earned AND Less this Application fields
        modal.find('.vacation-earned, .sick-earned, .vacation-this-app, .sick-this-app').on('input change', function() {
            computeBalance();
        });

        // Initial computation when document is ready
        setTimeout(function() {
            computeBalance();
        }, 100);

        // Compute balance on modal show (after fields are populated)
        modal.on('shown.bs.modal', function() {
            setTimeout(function() {
                computeBalance();
                captureOriginalValues(); // Capture after balance is computed
            }, 200);
            hasChanges = false;
            isSubmitting = false;
            pendingHide = false;
        });

        function captureOriginalValues() {
            originalData = {};
            form.find('input, textarea').each(function() {
                originalData[this.name] = $(this).val();
            });
        }

        function resetToOriginal() {
            form.find('input, textarea').each(function() {
                if (originalData.hasOwnProperty(this.name)) {
                    $(this).val(originalData[this.name]);
                }
            });
            computeBalance();
        }

        captureOriginalValues();

        form.on('input change', 'input, textarea', function() {
            hasChanges = false;
            form.find('input, textarea').each(function() {
                if (originalData[this.name] !== $(this).val()) {
                    hasChanges = true;
                    return false;
                }
            });
        });

        modal.on('hide.bs.modal', function(e) {
            if (hasChanges && !isSubmitting && !pendingHide) {
                e.preventDefault();
                e.stopImmediatePropagation();
                warningModal.modal('show');
                return false;
            }
        });

        warningModal.find('[data-action="discard"]').on('click', function() {
            warningModal.modal('hide');
            resetToOriginal();
            hasChanges = false;
            pendingHide = true;
            setTimeout(function() {
                modal.modal('hide');
                pendingHide = false;
            }, 300);
        });

        warningModal.find('[data-action="cancel"]').on('click', function() {
            warningModal.modal('hide');
        });

        form.on('submit', function(e) {
            // Validate before submit: warn if balance becomes 0 (likely a mistake)
            const vacationBalance = parseFloat(modal.find('.vacation-balance').val()) || 0;
            const sickBalance = parseFloat(modal.find('.sick-balance').val()) || 0;

            if (vacationBalance === 0 || sickBalance === 0) {
                const confirmSubmit = confirm('WARNING: One of the balances is 0. This will cause the employee balance to become 0. Are you sure the values are correct?');
                if (!confirmSubmit) {
                    e.preventDefault();
                    return false;
                }
            }

            isSubmitting = true;
        });
    });

</script>
