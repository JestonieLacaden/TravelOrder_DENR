

<!-- /.modal -->

<div class="modal fade" id="print-dtr-modal-lg" >
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
            <h3 class="card-title">Print Daily Time Record </h3>
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
                    <h3 class="card-title">DTR Information</h3>
                
                  </div>
                  
                  <!-- /.card-header -->
                  <!-- form start -->
                  <form method="POST" action="{{ route('daily-time-record.print') }}" enctype="multipart/form-data">

                    {{ csrf_field() }}
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-sm-4" for="employeeid">Employee Name :<span class="text-danger">*</span></label>
                            <div class="col-sm-8" >
                            <select  id="employeeid"  name="employeeid" class="form-control select2" style="width: 100%;">
                                <option value=""  selected>-- Choose Employee --</option>
                                @foreach($Employees as $Employee)
                                <option value = "{{ $Employee->id}}"> {{ $Employee->firstname . " " . $Employee->middlename . " " . $Employee->lastname . " -  " . $Employee->office->office}}</option>
                                @endforeach
                            </select>
                            @error('employeeid')
                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                            @enderror
                            </div>                          
                        </div>
                    
                        <div class="form-group row">
                            <label class="col-sm-4" for="fromdate">DTR Date (from) :<span class="text-danger">*</span></label>
                            <div class=" col-sm-8">
                            <input name="fromdate" id="fromdate" class="form-control" type="date"  value={{ now() }} oninput="this.value = this.value.toUpperCase()">
                            @error('fromdate')
                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                            @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4" for="todate">DTR Date (to) :<span class="text-danger">*</span></label>
                            <div class=" col-sm-8">
                            <input name="todate" id="todate" class="form-control" type="date"  value={{ now() }} oninput="this.value = this.value.toUpperCase()">
                            @error('todate')
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
