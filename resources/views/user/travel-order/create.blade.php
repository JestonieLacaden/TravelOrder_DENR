
<!-- /.modal -->

<div class="modal fade" id="new-travelorder-modal-lg" >
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add Travel Order</h4>
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
                    <h3 class="card-title">Travel Order Information</h3>
                
                  </div>
                  
                  <!-- /.card-header -->
                  <!-- form start -->
               
                  <form method="POST" action="{{ route('userTravelOrder.storeUserTravelOrder') }}" enctype="multipart/form-data">

                    {{ csrf_field() }}
                  <div class="card-body">

                    <div class="card-body">
                    

                    <div class="form-group row">
                      <label class="col-sm-3" for="daterange">Date Range : <span class="text-danger">*</span></label>
                        <div class="input-group col-sm-9">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                <i class="far fa-calendar-alt"></i>
                                </span>
                            </div>
                            <input type="text"  name="daterange" id="daterange" class="form-control float-right" oninput="this.value = this.value.toUpperCase()">
                            </div>
                            <!-- /.input group -->
                        </div>

                        {{-- <div class="form-group row">
                          <label class="col-sm-3" for="travelordersignatoryid">
                            Signatory: <span class="text-danger">*</span>
                          </label>
                          <div class="col-sm-9">
                            <select id="travelordersignatoryid" name="travelordersignatoryid" class="form-control select2" style="width:100%;">
                              <option value="" disabled selected>-- Choose Signatory --</option>
                              @foreach($SignatoryOptions as $opt)
                              <option value="{{ $opt->travelordersignatoryid }}">
                                {{ $opt->TravelOrderSignatory->name }}
                              </option>
                              @endforeach
                            </select>
                            @error('travelordersignatoryid')
                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                            @enderror
                          </div>
                        </div> --}}

                        <div class="form-group row">
                            <label class="col-sm-3" for="destinationoffice">Destination : <span class="text-danger">*</span></label>
                            <div class=" col-sm-9">
                                <input name="destinationoffice" id="destinationoffice" class="form-control" type="text"  placeholder="Enter Destination" oninput="this.value = this.value.toUpperCase()">
                                @error('destination')
                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                                @enderror
                            </div>
                          </div>   
                        <div class="form-group row">
                            <label class="col-sm-3" for="purpose">Purpose of travel : <span class="text-danger">*</span></label>
                            <div class=" col-sm-9">
                                <input name="purpose" id="purpose" class="form-control" type="text"  placeholder="Enter Purpose of Travel" oninput="this.value = this.value.toUpperCase()">
                                @error('purpose')
                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                                @enderror
                            </div>
                        </div> 
                        <div class="form-group row">
                            <label class="col-sm-3" for="perdime">Per Dime : <span class="text-danger">*</span></label>
                            <div class=" col-sm-9">
                                <input name="perdime" id="perdime" class="form-control" type="number"  placeholder="Enter Per Dime" oninput="this.value = this.value.toUpperCase()">
                                @error('perdime')
                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                                @enderror
                            </div>
                        </div> 
                        <div class="form-group row">
                            <label class="col-sm-3" for="appropriation">Appropriation : <span class="text-danger">*</span></label>
                            <div class=" col-sm-9">
                                <input name="appropriation" id="appropriation" class="form-control" type="text"  placeholder="Enter Appropriation" oninput="this.value = this.value.toUpperCase()">
                                @error('appropriation')
                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                                @enderror
                            </div>
                        </div> 
                        <div class="form-group row">
                            <label class="col-sm-3" for="remarks">Remarks : <span class="text-danger">*</span></label>
                            <div class=" col-sm-9">
                                <input name="remarks" id="remarks" class="form-control" type="text"  placeholder="Enter Remarks" oninput="this.value = this.value.toUpperCase()" value="SUBMIT REPORT UPON COMPLETION OF TRAVEL">
                                @error('remarks')
                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                                @enderror
                            </div>
                        </div> 
                    </div>
                  </div>
                    <!-- /.card-body -->
                     <div class="card-footer">
                    @can('AddUserTravelOrder', \App\Models\TravelOrder::class)
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
