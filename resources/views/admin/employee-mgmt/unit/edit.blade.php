<!-- /.modal -->

<div class="modal fade" id="edit-unit-modal-lg{{ $Unit->id }}"  >
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Unit Section</h4>
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
                    <h3 class="card-title">Unit Information</h3>
                
                  </div>
                  
                  <!-- /.card-header -->
                  <!-- form start -->
                  <form method="POST" action="{{ route('unit.update',[ $Unit->id])}}" enctype="multipart/form-data">

                    {{ csrf_field() }}
                    @method('PUT')

                    <div class="card-body">

                      <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="officeid">Office / Section : <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                          <select  name="officesection"  id="officesection" class="form-control select2" style="width: 100%;">
                            <option value=""  selected>-- Choose Office / Section --</option>
                            @foreach($Offices as $Office) 
                            <div>
                               {{-- @foreach ($Sections as $Section) --}}
                               <optgroup label="{{$Office->office }}"></option>
                                @foreach ($Sections as $Section)
                                @if ( $Section->officeid  == $Office->id );
                                  <option value="{{$Office->id }},{{$Section->id }}" class="text-indent: 40px"><strong>{{$Section->section }}</strong></option>
                                @endif
                              
                            </div>
                              @endforeach
                            </optgroup>
                            
                            @endforeach
                            </select>
                        </div>
                        
                        @error('officesection')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                        @enderror
                      </div> 
                    
                      <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="unit">Unit Name</label>
                        <div class="col-sm-9">
                        <input name="unit" id="unit" class="form-control" type="text" placeholder="Enter Unit Name" value="{{ $Unit->unit }}" oninput="this.value = this.value.toUpperCase()">
                        </div>
                        @error('unit')
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
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->