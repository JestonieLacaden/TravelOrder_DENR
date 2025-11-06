<!-- /.modal -->

<div class="modal fade" id="new-signatory-modal-lg">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Signatory</h4>
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

                                    <form method="POST" action="{{ route('leave-signatory.store') }}" enctype="multipart/form-data">

                                        {{ csrf_field() }}
                                        <div class="card-body">
                                            <div class="form-group  row">
                                                <label class="col-sm-3" for="name">Signatory Name : <span class="text-danger">*</span></label>
                                                <div class="col-sm-9">
                                                    <input name="name" id="name" class="form-control" type="text" placeholder="Enter Signatory Name">
                                                    @error('name')
                                                    <p class="text-danger text-xs mt-1">{{$message}}</p>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="form-group  row">
                                                <label class="col-sm-3" for="approver1">Signatory 1 : <span class="text-danger">*</span></label>
                                                <div class="col-sm-9">
                                                    <select id="approver1" name="approver1" class="form-control select2" aria-placeholder="-- Choose Employee Name --" style="width: 100%;">
                                                        <option value="" disabled selected>-- Choose Employee Name --</option>
                                                        @foreach($Employees as $Employee)
                                                        <option value="{{ $Employee->id }}">{{ $Employee->lastname . ', ' . $Employee->firstname . ' ' . $Employee->middlename }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('approver1')
                                                    <p class="text-danger text-xs mt-1">{{$message}}</p>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-3">Signature 1 (PNG/JPG)</label>
                                                <div class="col-sm-9">
                                                    <input type="file" name="signature1" class="form-control" accept="image/png,image/jpeg" onchange="previewSig(this,'sig1Preview')">
                                                    <small class="text-muted">Best: transparent PNG, max ~500KB & 5x2inch/1500x600px dimensions.</small>
                                                    <div class="mt-2">
                                                        <img id="sig1Preview" style="height:60px;display:none;">
                                                    </div>
                                                    @error('signature1') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                                                </div>
                                            </div>


                                            <div class="form-group  row">
                                                <label class="col-sm-3" for="approver2">Signatory 2 : <span class="text-danger">*</span></label>
                                                <div class="col-sm-9">
                                                    <select id="approver2" name="approver2" class="form-control select2" aria-placeholder="-- Choose Employee Name --" style="width: 100%;">
                                                        <option value="" disabled selected>-- Choose Employee Name --</option>
                                                        @foreach($Employees as $Employee)
                                                        <option value="{{ $Employee->id }}">{{ $Employee->lastname . ', ' . $Employee->firstname . ' ' . $Employee->middlename }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('approver2')
                                                    <p class="text-danger text-xs mt-1">{{$message}}</p>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-3">Signature 2 (PNG/JPG)</label>
                                                <div class="col-sm-9">
                                                    <input type="file" name="signature2" class="form-control" accept="image/png,image/jpeg" onchange="previewSig(this,'sig2Preview')">
                                                    <div class="mt-2">
                                                        <img id="sig2Preview" style="height:60px;display:none;">
                                                    </div>
                                                    @error('signature2') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                                                </div>
                                            </div>


                                            <div class="form-group  row">
                                                <label class="col-sm-3" for="approver3">Signatory 3 : <span class="text-danger">*</span></label>
                                                <div class="col-sm-9">
                                                    <select id="approver3" name="approver3" class="form-control select2" aria-placeholder="-- Choose Employee Name --" style="width: 100%;">
                                                        <option value="" disabled selected>-- Choose Employee Name --</option>
                                                        @foreach($Employees as $Employee)
                                                        <option value="{{ $Employee->id }}">{{ $Employee->lastname . ', ' . $Employee->firstname . ' ' . $Employee->middlename }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('approver3')
                                                    <p class="text-danger text-xs mt-1">{{$message}}</p>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-3">Signature 3 (PNG/JPG)</label>
                                                <div class="col-sm-9">
                                                    <input type="file" name="signature3" class="form-control" accept="image/png,image/jpeg" onchange="previewSig(this,'sig3Preview')">
                                                    <div class="mt-2">
                                                        <img id="sig3Preview" style="height:60px;display:none;">
                                                    </div>
                                                    @error('signature3') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                                                </div>
                                            </div>




                                        </div>
                                        <!-- /.card-body -->
                                        <div class="card-footer">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>

                                    </form>
                                </div>

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

                                </script>
                                @endpush

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

