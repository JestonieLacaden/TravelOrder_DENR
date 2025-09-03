<!-- /.modal -->

<div class="modal fade" id="edit-dtr-signatory-modal-lg{{$DtrSignatory->id}}" >
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
                    <h3 class="card-title">Employee Information</h3>
                
                  </div>
                  
                  <!-- /.card-header -->
                  <!-- form start -->
                  <form method="POST" action="{{ route('dtr-signatory.update',[$DtrSignatory->id]) }}" enctype="multipart/form-data">

                    {{ csrf_field() }}
                  <div class="card-body">
                 <div class="form-group">
                      <label for="employeeid">Employee Name</label>
                      <select id="employeeid"  name="employeeid" class="form-control select2" style="width: 100%;">
                    
                                <option value = "{{ $DtrSignatory->Employee->id }}" selected>{{ $DtrSignatory->Employee->firstname . " " . $DtrSignatory->Employee->middlename . " " . $DtrSignatory->Employee->lastname}}</option>
                  
                      </select>
                      @error('employeeid')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                      @enderror
                    </div>

                    <div class="form-group">
                      <label for="signatory">Signatory</label>
                      <select id="signatory"  name="signatory" class="form-control select2" style="width: 100%;">
                        <option value=""  selected>-- Choose Signatory --</option>
                       @foreach($Employees as $Employee)
                        
                         <option value = "{{ $Employee->id }}">{{ $Employee->firstname . " " . $Employee->middlename  . " " . $Employee->lastname}}</option>
                        @endforeach
                      </select>
                      @error('employeeid')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                      @enderror
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
