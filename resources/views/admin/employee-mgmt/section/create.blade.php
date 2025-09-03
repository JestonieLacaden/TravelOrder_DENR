<!-- /.modal -->

<div class="modal fade" id="new-section-modal-lg" >
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">New Section</h4>
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
                    <h3 class="card-title">Section Information</h3>
                
                  </div>
                  
                  <!-- /.card-header -->
                  <!-- form start -->
                  <form method="POST" action="{{ route('section.store') }}" enctype="multipart/form-data">

                    {{ csrf_field() }}

                    <div class="card-body">
                      <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="officeid">Office : <span class="text-danger">*</span> </label>
                        <div class="col-sm-9">
                          <select name="officeid" class="form-control select2" style="width: 100%;">
                            <option value="" disabled selected>-- Choose Office --</option>
                            @foreach($Offices as $Office)
                            <option value="{{$Office->id}}">{{ $Office->office }}</option>
                            @endforeach
                          </select>
                        </div>
                     
                        @error('officeid')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                        @enderror
                      </div>
                       
                      <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="section">Section Name :<span class="text-danger">*</span> </label>
                        <div class="col-sm-9">
                          <input name="section" id="section" class="form-control" type="text" placeholder="Enter Section Name" value="{{old('section')}}" oninput="this.value = this.value.toUpperCase()">                   
                        </div>
                          @error('section')
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
