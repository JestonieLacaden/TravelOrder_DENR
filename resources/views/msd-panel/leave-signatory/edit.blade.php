
<!-- /.modal -->

<div class="modal fade" id="edit-signatory-modal-lg{{ $LeaveSignatory->id }}" >
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
                      <div class="form-group  row">
                          <label class="col-sm-3" for="name">Signatory Name : <span class="text-danger">*</span></label>
                          <div class="col-sm-9">
                            <input name="name" id="name" class="form-control" type="text" placeholder="Enter Signatory Name" value="{{ $LeaveSignatory->name }}">
                            @error('name')
                              <p class="text-danger text-xs mt-1">{{$message}}</p>
                            @enderror
                          </div>
                      </div>

                      <div class="form-group  row">
                        <label class="col-sm-3" for="approver1">Signatory 1 : <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                          <select id="approver1"  name="approver1" class="form-control select2" aria-placeholder="-- Choose Employee Name --" style="width: 100%;">                  
                            @foreach($Employees as $Employee)
                                @if($Employee->id == $LeaveSignatory->approver1)
                                <option selected value = "{{ $Employee->id }}">{{ $Employee->lastname . ', ' . $Employee->firstname . ' ' . $Employee->middlename }}</option>
                                @else 
                                <option value = "{{ $Employee->id }}">{{ $Employee->lastname . ', ' . $Employee->firstname . ' ' . $Employee->middlename }}</option>
                                @endif
                            @endforeach
                          </select>
                          @error('approver1')
                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                          @enderror
                        </div>
                      </div>

                      <div class="form-group  row">
                        <label class="col-sm-3" for="approver2">Signatory 2 : <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                          <select id="approver2"  name="approver2" class="form-control select2" aria-placeholder="-- Choose Employee Name --" style="width: 100%;">
                             @foreach($Employees as $Employee)
                                @if($Employee->id == $LeaveSignatory->approver2)
                                <option selected value = "{{ $Employee->id }}">{{ $Employee->lastname . ', ' . $Employee->firstname . ' ' . $Employee->middlename }}</option>
                                @else 
                                <option value = "{{ $Employee->id }}">{{ $Employee->lastname . ', ' . $Employee->firstname . ' ' . $Employee->middlename }}</option>
                                @endif
                            @endforeach
                          </select>
                          @error('approver2')
                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                          @enderror
                        </div>
                      </div>

                      <div class="form-group  row">
                        <label class="col-sm-3" for="approver3">Signatory 3 : <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                          <select id="approver3"  name="approver3" class="form-control select2" aria-placeholder="-- Choose Employee Name --" style="width: 100%;">
                             @foreach($Employees as $Employee)
                                @if($Employee->id == $LeaveSignatory->approver3)
                                <option selected value = "{{ $Employee->id }}">{{ $Employee->lastname . ', ' . $Employee->firstname . ' ' . $Employee->middlename }}</option>
                                @else 
                                <option value = "{{ $Employee->id }}">{{ $Employee->lastname . ', ' . $Employee->firstname . ' ' . $Employee->middlename }}</option>
                                @endif
                            @endforeach
                          </select>
                          @error('approver3')
                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                          @enderror
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
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->
