<!-- Approver3 Edit Modal for Disapproval Reason -->
<div class="modal fade" id="edit-leave-modal-approver3-{{ $Leave->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalApprover3Label{{ $Leave->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title text-white" id="editModalApprover3Label{{ $Leave->id }}">Edit Disapproval (Approver 3)</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form method="POST" action="{{ route('leave.approver3.save', $Leave->id) }}">
                {{ csrf_field() }}
                @method('PUT')

                <div class="modal-body">
                    <div class="form-group">
                        <strong>Employee:</strong><br>
                        {{ optional($Leave->employee)->firstname ?? '' }} {{ optional($Leave->employee)->lastname ?? '' }}
                    </div>

                    <div class="form-group">
                        <strong>Leave Type:</strong><br>
                        {{ optional($Leave->leave_type)->leave_type ?? 'N/A' }}<br>
                        <small>{{ $Leave->daterange ?? '' }}</small>
                    </div>

                    <hr>

                    <h6><strong>7.D Disapproved Due To</strong></h6>

                    <div class="form-group">
                        <textarea style="resize: none" name="disapproved_reason" class="form-control" rows="6" placeholder="Enter disapproval reasons if applicable">{{ $Leave->disapproved_reason ?? '' }}</textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Unsaved Changes Warning Modal -->
<div class="modal fade" id="unsaved-warning-modal-a3-{{ $Leave->id }}" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
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
        const modalId = 'edit-leave-modal-approver3-{{ $Leave->id }}';
        const warningModalId = 'unsaved-warning-modal-a3-{{ $Leave->id }}';
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
