
<div class="modal fade" id="edit-leave-modal-lg{{ $leaveType->id }}"  >
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Update Leave</h4>
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
                    <form method="POST" action="{{ route('leave-mgmt.update',[ $leaveType->id])}}" enctype="multipart/form-data">

                    {{ csrf_field() }}
                    @method('PUT')

                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-sm-2" for="leave_type">Remarks : <span class="text-danger">*</span></label>
                            <div class=" col-sm-10">
                                <input name="leave_type" id="leave_type" class="form-control" type="text" value= " {{ $leaveType->leave_type }}" readonly>
                                @error('leave_type')
                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                                @enderror
                            </div>
                          </div> 
                          <div class="form-group row">
                            <label class="col-sm-2" for="available">Available : <span class="text-danger">*</span></label>
                            <div class=" col-sm-10">
                                <input name="available" id="available" type="number" maxlength="2" class="form-control" type="text" value= {{ $leaveType->available }}>
                                @error('available')
                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                                @enderror
                            </div>
                          </div> 
                    
                        

                    </div>
                    <!-- /.card-body -->
                    @can('update', $leaveType)
                    <div class="card-footer">
                      <button type="SUBMIT"  class="btn btn-primary">Submit</button>
                    </div>
                    @endcan
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