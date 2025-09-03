
<div class="modal fade" id="edit-event-modal-lg{{ $Event->id }}"  >
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Update Event</h4>
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
                  <form method="POST" action="{{ route('event.update',[ $Event->id])}}" enctype="multipart/form-data">

                    {{ csrf_field() }}
                    @method('PUT')

                    <div class="card-body">
                    
                        <div class="form-group row">
                            <label class="col-sm-3" for="date">DTR Date : <span class="text-danger">*</span></label>
                            <div class=" col-sm-9">
                              <input name="date" id="date" class="form-control" type="date"  value={{ $Event->date }} oninput="this.value = this.value.toUpperCase()">
                              @error('date')
                              <p class="text-danger text-xs mt-1">{{$message}}</p>
                              @enderror
                            </div>
                        </div>
    
                        <div class="form-group row">
                            <label class="col-sm-3" for="schedule">Schedule : <span class="text-danger">*</span></label>
                            <div class=" col-sm-9">
                                <select id="schedule"  name="schedule" class="form-control select2" aria-placeholder="Choose schedule" style="width: 100%;">
                                  @switch($Event->schedule)
                                      @case('MORNING')
                                      <option value="MORNING" selected>MORNING</option>
                                      <option value="AFTERNOON">AFTERNOON</option>
                                      <option value="WHOLE DAY">WHOLE DAY</option>
                                          @break
                                      @case('AFTERNOON')
                                      <option value="MORNING">MORNING</option>
                                      <option value="AFTERNOON" selected>AFTERNOON</option>
                                      <option value="WHOLE DAY">WHOLE DAY</option>
                                          @break
                                     @case('WHOLE DAY')
                                     <option value="MORNING">MORNING</option>
                                     <option value="AFTERNOON">AFTERNOON</option>
                                     <option value="WHOLE DAY" selected>WHOLE DAY</option>
                                          @break
                                      @default
                                  @endswitch
                                  </select>
                                @error('schedule')
                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                                @enderror
                            </div>
                      </div>
    
                        <div class="form-group row">
                            <label class="col-sm-3" for="type">Event Type : <span class="text-danger">*</span></label>
                            <div class=" col-sm-9">
                                <select id="type"  name="type" class="form-control select2" aria-placeholder="Choose Event Type" style="width: 100%;">
                                    @switch($Event->type)
                                        @case('EVENT')
                                        <option value="EVENT" selected>EVENT</option>
                                        <option value="HOLIDAY">HOLIDAY</option>
                                            @break
                                        @case('HOLIDAY')
                                        <option value="EVENT">EVENT</option>
                                        <option value="HOLIDAY" selected>HOLIDAY</option>
                                            @break
                                        @default                        
                                    @endswitch
                                  </select>
                                @error('type')
                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                                @enderror
                            </div>
                      </div>
                  
                          <div class="form-group row">
                            <label class="col-sm-3" for="subject">Subject : <span class="text-danger">*</span></label>
                            <div class=" col-sm-9">
                                <input name="subject" id="subject" class="form-control" type="text" value= " {{ $Event->subject }}" placeholder="Enter Subject">
                                @error('subject')
                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                                @enderror
                            </div>
                          </div>   
    
                          <div class="form-group row">
                            <label class="col-sm-3" for="remarks">Remarks :</label>
                            <div class=" col-sm-9">
                                <input name="remarks" id="remarks" class="form-control" type="text" value= " {{  $Event->remarks }}" placeholder="Enter Remarks">
                                @error('remarks')
                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                                @enderror
                            </div>
                          </div> 

                        @if(!empty($Event->attachment))
                          <div class="form-group row">
                            <label class="col-sm-3" for="attachment">Attached File :</label>
                            <div class=" col-sm-9">   
                              @can('update', $Event)     
                               <a href="{{ route('eventattachment.view', [$Event->id]) }} " target="_blank">{{$Event->attachment}}</a>
                              @endcan
                              </div>
                          </div> 
                        @else
                          <div class="form-group row">
                            <label class="col-sm-3" for="attachment">Attachment : </label>
                            <div class="input-group col-sm-9">
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
                        @endif

                    </div>
                    <!-- /.card-body -->
                    @can('update', $Event)
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