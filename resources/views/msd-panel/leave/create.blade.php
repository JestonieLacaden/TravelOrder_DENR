
<!-- /.modal -->

<div class="modal fade" id="new-leave-modal-lg" >
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add Leave</h4>
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
                  <form method="POST" action="  {{ route('leave-management.store') }}" enctype="multipart/form-data">

                    {{ csrf_field() }}
                  <div class="card-body">

                    <div class="card-body">
                      <div class="form-group row">
                          <label class="col-sm-3" for="employeeid">Employee Name : <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                          <select id="employeeid"  name="employeeid" class="form-control select2" aria-placeholder="-- Choose Employee Name --" style="width: 100%;">
                          @foreach($Employees as $Employee)
                            <option value = "{{ $Employee->id }}">{{ $Employee->lastname . ', ' . $Employee->firstname . ' ' . $Employee->middlename }}</option>
                            @endforeach
                          </select>
                          @error('employeeid')
                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                          @enderror
                      </div>
                    </div>
                      <div class="form-group row ">
                        <label class="col-sm-3" for="leaveid">Leave Type  : <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                        <select id="leaveid"  name="leaveid" class="form-control select2" aria-placeholder="-- Choose Leave Type --" style="width: 100%;">
                      
                         @foreach($Leave_Types as $Leave_Type)
                          
                           <option value = "{{ $Leave_Type->id }}">{{ $Leave_Type->leave_type }}</option>
                          @endforeach
                        </select>
                        @error('leaveid')
                          <p class="text-danger text-xs mt-1">{{$message}}</p>
                        @enderror
                    </div>
                  </div>

                    <div class="form-group row">
                      <label class="col-sm-3" for="leave_type">Date Range : <span class="text-danger">*</span></label>
                        <div class="input-group col-sm-9">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                <i class="far fa-calendar-alt"></i>
                                </span>
                            </div>
                            <input type="text"  name="daterange" id="daterange" class="form-control float-right">
                            </div>
                            <!-- /.input group -->
                        </div>
                    </div>
                  </div>
                    <!-- /.card-body -->
                     <div class="card-footer">
                      <button type="submit"  class="btn btn-primary">Submit</button>
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
