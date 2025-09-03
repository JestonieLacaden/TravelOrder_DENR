
<!-- /.modal -->

<div class="modal fade" id="edit-signatory-modal-lg{{ $SetLeaveSignatory->id }}" >
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
         
                  <form method="POST" action="{{ route('set-leave-signatory.update',[$SetLeaveSignatory->id]) }} " enctype="multipart/form-data">

                    {{ csrf_field() }}
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label" for="sectionid"> Section Name : <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                              <select  name="sectionid"  id="sectionid" readonly  class="form-control select2" style="width: 100%;">
                                <option selected  value="{{ $SetLeaveSignatory->officeid.','. $SetLeaveSignatory->sectionid }}" class="bg-light"> {{ $SetLeaveSignatory->section->section }}</option>
                                
                              </select>
                              @error('sectionid')
                              <p class="text-danger text-xs mt-1">{{$message}}</p>
                              @enderror
                            </div>
                          </div> 

                          <div class="form-group  row">
                            <label class="col-sm-3" for="leavesignatoryid">Signatory Name : <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <select id="leavesignatoryid"  name="leavesignatoryid" class="form-control select2" aria-placeholder="-- Choose Signatory Name --"  style="width: 100%;">
                                @foreach($LeaveSignatories as $LeaveSignatory)
                                  @if($LeaveSignatory->id == $SetLeaveSignatory->leavesignatoryid)
                                  <option selected value = "{{ $LeaveSignatory->id }}">{{$LeaveSignatory->name }}</option>
                                  @else
                                 <option value = "{{ $LeaveSignatory->id }}">{{$LeaveSignatory->name }}</option>
                                 @endif
                                @endforeach
                                </select>
                                @error('leavesignatoryid')
                                 <p class="text-danger text-xs mt-1">{{$message}}</p>
                                @enderror
                            </div>
                          </div>

                
                    </div>
                    <!-- /.card-body -->
                     <div class="card-footer">
                        @can('edit', \App\Models\SetLeaveSignatory::class)
                      <button type="submit"  class="btn btn-primary">Submit</button>
                        @endcan
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
