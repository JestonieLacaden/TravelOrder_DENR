<!-- Approver2 Edit Modal for Recommendation -->
<div class="modal fade" id="edit-leave-modal-approver2-{{ $Leave->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalApprover2Label{{ $Leave->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="editModalApprover2Label{{ $Leave->id }}">Edit Recommendation (Approver 2)</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form method="POST" action="{{ route('leave.approver2.save', $Leave->id) }}">
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

                    <h6><strong>7.B Recommendation</strong></h6>

                    <!-- Hidden input to track recommendation state -->
                    <input type="hidden" name="recommendation" id="recommendation_value_{{ $Leave->id }}" value="{{ $Leave->recommendation ?? 'for_approval' }}">

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="rec_app_{{ $Leave->id }}" {{ (!$Leave->recommendation || $Leave->recommendation === 'for_approval') ? 'checked' : '' }}>
                        <label class="form-check-label" for="rec_app_{{ $Leave->id }}">For Approval</label>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="rec_dis_{{ $Leave->id }}" {{ ($Leave->recommendation === 'for_disapproval') ? 'checked' : '' }}>
                        <label class="form-check-label" for="rec_dis_{{ $Leave->id }}">For Disapproval</label>
                    </div>

                    <div class="form-group mt-3">
                        <label>Notes:</label>
                        <textarea name="recommendation_notes" style="resize: none" class="form-control" rows="4" placeholder="Enter recommendation notes">{{ $Leave->recommendation_notes ?? '' }}</textarea>
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
<div class="modal fade" id="unsaved-warning-modal-a2-{{ $Leave->id }}" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
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
        const modalId = 'edit-leave-modal-approver2-{{ $Leave->id }}';
        const warningModalId = 'unsaved-warning-modal-a2-{{ $Leave->id }}';
        const modal = $('#' + modalId);
        const warningModal = $('#' + warningModalId);
        if (!modal.length) return;

        const form = modal.find('form');
        if (!form.length) return;

        // Handle checkboxes - mutual exclusivity
        const approvalCheckbox = $('#rec_app_{{ $Leave->id }}');
        const disapprovalCheckbox = $('#rec_dis_{{ $Leave->id }}');
        const hiddenInput = $('#recommendation_value_{{ $Leave->id }}');

        console.log('Script loaded for Leave ID: {{ $Leave->id }}');

        approvalCheckbox.on('change', function() {
            console.log('For Approval clicked, checked:', this.checked);
            if (this.checked) {
                disapprovalCheckbox.prop('checked', false);
                hiddenInput.val('for_approval');
                console.log('Set to for_approval');
            } else {
                // If unchecking For Approval, check For Disapproval
                disapprovalCheckbox.prop('checked', true);
                hiddenInput.val('for_disapproval');
                console.log('Set to for_disapproval');
            }
            console.log('Hidden input value:', hiddenInput.val());
        });

        disapprovalCheckbox.on('change', function() {
            console.log('For Disapproval clicked, checked:', this.checked);
            if (this.checked) {
                approvalCheckbox.prop('checked', false);
                hiddenInput.val('for_disapproval');
                console.log('Set to for_disapproval');
            } else {
                // If unchecking For Disapproval, check For Approval
                approvalCheckbox.prop('checked', true);
                hiddenInput.val('for_approval');
                console.log('Set to for_approval');
            }
            console.log('Hidden input value:', hiddenInput.val());
        });

        let originalData = {};
        let hasChanges = false;
        let isSubmitting = false;
        let pendingHide = false;

        // Capture original
        function captureOriginalValues() {
            originalData = {};
            form.find('input, textarea').each(function() {
                if (this.type === 'checkbox') {
                    originalData[this.id] = this.checked;
                } else {
                    originalData[this.name] = $(this).val();
                }
            });
        }

        // Reset
        function resetToOriginal() {
            form.find('input, textarea').each(function() {
                if (this.type === 'checkbox') {
                    this.checked = originalData[this.id] || false;
                } else if (originalData.hasOwnProperty(this.name)) {
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
            console.log('Modal opened, initial values:', {
                approval: approvalCheckbox.prop('checked')
                , disapproval: disapprovalCheckbox.prop('checked')
                , hidden: hiddenInput.val()
            });
        });

        form.on('input change', 'input, textarea', function() {
            hasChanges = false;
            form.find('input, textarea').each(function() {
                if (this.type === 'checkbox') {
                    if (originalData[this.id] !== this.checked) {
                        hasChanges = true;
                        return false;
                    }
                } else if (originalData[this.name] !== $(this).val()) {
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
