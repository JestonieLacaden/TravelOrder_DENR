<!-- Approver1 Edit Modal for Leave Credits -->
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
                                <tr>
                                    <td><strong>Total Earned</strong></td>
                                    <td class="text-center">
                                        <input type="number" name="vacation_earned" min="0" class="form-control form-control-sm text-center" value="{{ $Leave->vacation_earned ?? 0 }}" placeholder="0">
                                    </td>
                                    <td class="text-center">
                                        <input type="number" name="sick_earned" min="0" class="form-control form-control-sm text-center" value="{{ $Leave->sick_earned ?? 0 }}" placeholder="0">
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Less this Application</strong></td>
                                    <td class="text-center">
                                        <input type="number" name="vacation_this_app" min="0" class="form-control form-control-sm text-center" value="{{ $Leave->vacation_this_app ?? 0 }}" placeholder="0">
                                    </td>
                                    <td class="text-center">
                                        <input type="number" name="sick_this_app" min="0" class="form-control form-control-sm text-center" value="{{ $Leave->sick_this_app ?? 0 }}" placeholder="0">
                                    </td>
                                </tr>
                                <tr class="bg-light">
                                    <td><strong>Balance</strong></td>
                                    <td class="text-center">
                                        <input type="number" name="vacation_balance" min="0" class="form-control form-control-sm text-center" value="{{ $Leave->vacation_balance ?? 0 }}" placeholder="0">
                                    </td>
                                    <td class="text-center">
                                        <input type="number" name="sick_balance" min="0" class="form-control form-control-sm text-center" value="{{ $Leave->sick_balance ?? 0 }}" placeholder="0">
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
                            <input type="number" name="days_with_pay" min="0" class="form-control form-control-sm" value="{{ $Leave->days_with_pay ?? 0 }}" placeholder="0">
                        </div>
                        <div class="col-md-4">
                            <label class="text-sm">Days without Pay:</label>
                            <input type="number" name="days_without_pay" min="0" class="form-control form-control-sm" value="{{ $Leave->days_without_pay ?? 0 }}" placeholder="0">
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
        }

        captureOriginalValues();

        modal.on('show.bs.modal', function() {
            resetToOriginal();
            hasChanges = false;
            isSubmitting = false;
            pendingHide = false;
        });

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
