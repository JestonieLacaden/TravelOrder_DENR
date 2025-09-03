<!-- /.modal -->

<div class="modal fade" id="new-event-modal-lg" >
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">New Event</h4>
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
                    <h3 class="card-title">Event Information</h3>
                
                  </div>
                  
                  <!-- /.card-header -->
                  <!-- form start -->
                
                  <form method="POST" action="  {{ route('event.store') }}" enctype="multipart/form-data">

                    {{ csrf_field() }}
                  <div class="card-body">

                    <div class="form-group row">
                        <label class="col-sm-2" for="date">DTR Date : <span class="text-danger">*</span></label>
                        <div class=" col-sm-10">
                          <input name="date" id="date" class="form-control" type="date"  value={{ now() }} oninput="this.value = this.value.toUpperCase()">
                          @error('date')
                          <p class="text-danger text-xs mt-1">{{$message}}</p>
                          @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2" for="schedule">Schedule : <span class="text-danger">*</span></label>
                        <div class=" col-sm-10">
                            <select id="schedule"  name="schedule" class="form-control select2" aria-placeholder="Choose schedule" style="width: 100%;">
                              
                                <option value="MORNING">MORNING</option>
                                <option value="AFTERNOON">AFTERNOON</option>
                                <option value="WHOLE DAY" selected>WHOLE DAY</option>
                              </select>
                            @error('schedule')
                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                            @enderror
                        </div>
                  </div>

                    <div class="form-group row">
                        <label class="col-sm-2" for="type">Event Type : <span class="text-danger">*</span></label>
                        <div class=" col-sm-10">
                            <select id="type"  name="type" class="form-control select2" aria-placeholder="Choose Event Type" style="width: 100%;">
                           
                                <option value="EVENT" selected>EVENT</option>
                                <option value="HOLIDAY">HOLIDAY</option>
                              </select>
                            @error('type')
                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                            @enderror
                        </div>
                  </div>
              
                      <div class="form-group row">
                        <label class="col-sm-2" for="subject">Subject : <span class="text-danger">*</span></label>
                        <div class=" col-sm-10">
                            <input name="subject" id="subject" class="form-control" type="text" value= " {{old('subject')}}" placeholder="Enter Subject">
                            @error('subject')
                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                            @enderror
                        </div>
                      </div>   

                      <div class="form-group row">
                        <label class="col-sm-2" for="remarks">Remarks :</label>
                        <div class=" col-sm-10">
                            <input name="remarks" id="remarks" class="form-control" type="text" value= " {{old('remarks')}}" placeholder="Enter Remarks">
                            @error('remarks')
                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                            @enderror
                        </div>
                      </div> 
                      
                      <div class="form-group row">
                        <label class="col-sm-2" for="attachment">Attachment : </label>
                        <div class="input-group col-sm-10">
                            <div class="custom-file">
                                <input type="file" id="attachment" accept="application/pdf, image/*" name="attachment" class="custom-file-input">
                                <label class="custom-file-label" for="attachment">Choose file</label>
                                @error('attachment')
                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                                @enderror
                            </div>
                            <div class="input-group-append">
                                <span class="input-group-text">Upload</span>
                            </div>
                          </div>
                     </div>

                  </div>
                    <!-- /.card-body -->
                    @can('create', \App\Models\Event::class)
                    <div class="card-footer">
                      <button type="submit"  class="btn btn-primary">Submit</button>
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
