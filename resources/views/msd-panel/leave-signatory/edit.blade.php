<!-- /.modal -->

<div class="modal fade" id="edit-signatory-modal-lg{{ $LeaveSignatory->id }}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Signatory</h4>
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
                                        <h3 class="card-title">Signatory Information</h3>

                                    </div>

                                    <!-- /.card-header -->
                                    <!-- form start -->

                                    <form method="POST" action="{{ route('leave-signatory.update',[ $LeaveSignatory->id])}}" enctype="multipart/form-data">

                                        {{ csrf_field() }}
                                        @method('PUT')
                                        <div class="card-body">

                                            @foreach ([1,2,3] as $i)
                                            @php
                                            $approverField = "approver{$i}";
                                            $sigColumn = "signature{$i}_path";
                                            $currentApproverId = $LeaveSignatory->{$approverField};
                                            $currentSigUrl = $LeaveSignatory->{$sigColumn} ? asset('storage/'.$LeaveSignatory->{$sigColumn}) : null;
                                            @endphp

                                            <div class="form-group row">
                                                <label class="col-sm-3" for="approver{{ $i }}">Signatory {{ $i }} : <span class="text-danger">*</span></label>

                                                {{-- IMPORTANT: data-* attributes for JS logic --}}
                                                <div class="col-sm-9" data-slot="{{ $i }}" data-current-approver-id="{{ $currentApproverId }}" data-current-sig-url="{{ $currentSigUrl }}">
                                                    <select id="approver{{ $i }}" name="approver{{ $i }}" class="form-control select2" style="width:100%;">
                                                        <option value="" disabled>-- Choose Employee Name --</option>
                                                        @foreach($Employees as $emp)
                                                        <option value="{{ $emp->id }}" {{ $emp->id == $currentApproverId ? 'selected' : '' }}>
                                                            {{ $emp->lastname.', '.$emp->firstname.' '.$emp->middlename }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                    @error("approver{$i}") <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-3">Signature {{ $i }} (PNG/JPG)</label>
                                                <div class="col-sm-9">

                                                    {{-- CURRENT SIGNATURE + REMOVE (auto-toggle sa JS) --}}
                                                    <div id="currentSigBlock{{ $i }}" style="{{ $currentSigUrl ? '' : 'display:none' }}">
                                                        <div class="mb-2">
                                                            <small class="text-muted d-block">Current:</small>
                                                            @if($currentSigUrl)
                                                            <img src="{{ $currentSigUrl }}" alt="signature{{ $i }}" style="height:60px">
                                                            @endif
                                                        </div>
                                                        <div class="form-check mb-2">
                                                            <input class="form-check-input" type="checkbox" id="remove_signature{{ $i }}" name="remove_signature{{ $i }}" value="1">
                                                            <label class="form-check-label" for="remove_signature{{ $i }}">Remove existing signature</label>
                                                        </div>
                                                    </div>

                                                    <input id="signatureInput{{ $i }}" type="file" name="signature{{ $i }}" class="form-control" accept="image/png,image/jpeg" onchange="previewSig(this,'sig{{ $i }}Preview{{ $LeaveSignatory->id }}')">
                                                    <small class="text-muted">PNG/JPG up to ~500KB.</small>

                                                    <div class="mt-2">
                                                        <img id="sig{{ $i }}Preview{{ $LeaveSignatory->id }}" style="height:60px;display:none;">
                                                    </div>

                                                    @error("signature{$i}") <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                                                </div>
                                            </div>
                                            @endforeach



                                        </div>
                                        <!-- /.card-body -->
                                        <div class="card-footer">
                                            <button type="submit" class="btn btn-primary">Submit</button>
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
@push('scripts')
<script>
    function previewSig(input, imgId) {
        const img = document.getElementById(imgId);
        if (input.files && input.files[0]) {
            img.src = URL.createObjectURL(input.files[0]);
            img.style.display = 'inline-block';
        } else {
            img.src = '';
            img.style.display = 'none';
        }
    }

    function onApproverChange(selectEl) {
        const container = selectEl.closest('.col-sm-9'); // yung may data-*
        const slot = container.getAttribute('data-slot');
        const currentId = container.getAttribute('data-current-approver-id');
        const sigUrl = container.getAttribute('data-current-sig-url') || '';

        const selected = selectEl.value;

        const block = document.getElementById('currentSigBlock' + slot);
        const removeCb = document.getElementById('remove_signature' + slot);
        const fileInput = document.getElementById('signatureInput' + slot);
        const preview = document.getElementById('sig' + slot + 'Preview{{ $LeaveSignatory->id }}');

        // If same approver AND we have an existing signature, show current block; else hide.
        const canShow = (selected && currentId && selected === currentId && sigUrl !== '');

        if (block) block.style.display = canShow ? '' : 'none';
        if (removeCb) removeCb.checked = false;

        // Always clear file input + live preview when changing approver
        if (fileInput) fileInput.value = '';
        if (preview) {
            preview.src = '';
            preview.style.display = 'none';
        }
    }

    // Bind listeners
    document.querySelectorAll('select[id^="approver"]').forEach(sel => {
        sel.addEventListener('change', () => onApproverChange(sel));
        // run once on load to set correct state
        onApproverChange(sel);
    });

</script>
@endpush

