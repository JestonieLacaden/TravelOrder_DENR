<!-- /.modal -->

<div class="modal fade" id="new-signatory-modal-lg">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Division Signatory</h4>
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
                                        <h3 class="card-title">Division Signatory Information</h3>
                                        <p class="text-sm text-muted mb-0">Section Chiefs are managed in "Set Section Chief" page</p>
                                    </div>

                                    <!-- /.card-header -->
                                    <!-- form start -->

                                    <form method="POST" action="{{ route('travel-order-signatory.store') }}" enctype="multipart/form-data">

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

                                            {{-- Approver 1 (Section Chief) hidden - managed in Set Section Chief page --}}
                                            <input type="hidden" name="approver1" value="">

                                            <div class="form-group  row">
                                                <label class="col-sm-3" for="approver2">Division Chief (Signatory 2) : <span class="text-danger">*</span></label>
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
                                                <label class="col-sm-3">Signature (Signatory 2)</label>
                                                <div class="col-sm-9">
                                                    <input type="file" name="approver2_signature" accept="image/*" class="form-control">
                                                    <small class="text-muted">PNG/JPG/WEBP up to 2MB</small>
                                                    @error('approver2_signature')<p class="text-danger text-xs mt-1">{{ $message }}</p>@enderror
                                                </div>
                                            </div>

                                            <div class="form-group  row">
                                                <label class="col-sm-3" for="approver3">Signatory 3 (PENRO) : <span class="text-danger">*</span></label>
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
                                                <label class="col-sm-3">Signature (Signatory 3)</label>
                                                <div class="col-sm-9">
                                                    <input type="file" name="approver3_signature" accept="image/*" class="form-control">
                                                    <small class="text-muted">PNG/JPG/WEBP up to 2MB</small>
                                                    @error('approver3_signature')<p class="text-danger text-xs mt-1">{{ $message }}</p>@enderror
                                                </div>
                                            </div>


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
