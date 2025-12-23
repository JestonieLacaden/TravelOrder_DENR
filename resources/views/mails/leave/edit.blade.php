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
                                echo trim($dates[0]) . ' - ' . trim($dates[1]) . ' (' . $dayCount . ' Day' . ($dayCount != 1 ? 's' : '') . ')';
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

                                // Check leave type
                                $leaveTypeName = strtolower(optional($Leave->leave_type)->leave_type ?? '');
                                $isVacationLeave = strpos($leaveTypeName, 'vacation') !== false;
                                $isSickLeave = strpos($leaveTypeName, 'sick') !== false;
                                @endphp

                                <tr>
                                    <td><strong>Total Earned</strong></td>
                                    <td class="text-center">
                                        <input type="number" name="vacation_earned" min="0" step="0.01" class="form-control form-control-sm text-center vacation-earned" value="{{ $Leave->vacation_earned && $Leave->vacation_earned > 0 ? $Leave->vacation_earned : '' }}" placeholder="0" {{ !$isVacationLeave ? 'disabled' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="number" name="sick_earned" min="0" step="0.01" class="form-control form-control-sm text-center sick-earned" value="{{ $Leave->sick_earned && $Leave->sick_earned > 0 ? $Leave->sick_earned : '' }}" placeholder="0" {{ !$isSickLeave ? 'disabled' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Less this Application</strong></td>
                                    <td class="text-center">
                                        <input type="number" name="vacation_this_app" min="0" step="0.5" class="form-control form-control-sm text-center vacation-this-app" value="{{ $isVacationLeave ? $dayCount : 0 }}" placeholder="0" readonly>
                                    </td>
                                    <td class="text-center">
                                        <input type="number" name="sick_this_app" min="0" step="0.5" class="form-control form-control-sm text-center sick-this-app" value="{{ $isSickLeave ? $dayCount : 0 }}" placeholder="0" readonly>
                                    </td>
                                </tr>
                                <tr class="bg-light">
                                    <td><strong>Balance</strong></td>
                                    <td class="text-center">
                                        <input type="number" name="vacation_balance" min="0" step="0.5" class="form-control form-control-sm text-center vacation-balance" value="{{ $Leave->vacation_balance ?? 0 }}" placeholder="0" readonly>
                                    </td>
                                    <td class="text-center">
                                        <input type="number" name="sick_balance" min="0" step="0.5" class="form-control form-control-sm text-center sick-balance" value="{{ $Leave->sick_balance ?? 0 }}" placeholder="0" readonly>
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
                            <input type="number" name="days_with_pay" min="0" class="form-control form-control-sm" value="{{ $Leave->days_with_pay && $Leave->days_with_pay > 0 ? $Leave->days_with_pay : '' }}" placeholder="0">
                        </div>
                        <div class="col-md-4">
                            <label class="text-sm">Days without Pay:</label>
                            <input type="number" name="days_without_pay" min="0" class="form-control form-control-sm" value="{{ $Leave->days_without_pay && $Leave->days_without_pay > 0 ? $Leave->days_without_pay : '' }}" placeholder="0">
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
            // Vacation Leave Balance (2 decimals max, no trailing .0)
            const vacationEarned = parseFloat(modal.find('.vacation-earned').val()) || 0;
            const vacationThisApp = parseFloat(modal.find('.vacation-this-app').val()) || 0;
            let vacationBalance = Math.max(0, vacationEarned - vacationThisApp);
            // Round to 2 decimals and remove trailing zeros
            vacationBalance = Math.round(vacationBalance * 100) / 100;
            modal.find('.vacation-balance').val(vacationBalance);

            // Sick Leave Balance (2 decimals max, no trailing .0)
            const sickEarned = parseFloat(modal.find('.sick-earned').val()) || 0;
            const sickThisApp = parseFloat(modal.find('.sick-this-app').val()) || 0;
            let sickBalance = Math.max(0, sickEarned - sickThisApp);
            // Round to 2 decimals and remove trailing zeros
            sickBalance = Math.round(sickBalance * 100) / 100;
            modal.find('.sick-balance').val(sickBalance);
        }

        // Attach change event to Total Earned fields
        modal.find('.vacation-earned, .sick-earned').on('input change', function() {
            computeBalance();
        });

        // Compute balance on modal show
        modal.on('show.bs.modal', function() {
            computeBalance();
            resetToOriginal();
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

        form.on('submit', function() {
            isSubmitting = true;
        });
    });

</script>
