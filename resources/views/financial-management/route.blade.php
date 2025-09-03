<div class="modal fade" id="route-voucher-modal-xl{{ $Route->voucher->id }}" >
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Route</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
         </div>
         <form  method="POST" action="{{ route('fmroute.store') }}" enctype="multipart/form-data">
         
          {{ csrf_field() }}
              
          <div class="modal-body">
      
              <input name="sequenceid" id="sequenceid"  hidden readonly class="form-control" type="text" value="{{ $Route->voucher->sequenceid }}" oninput="this.value = this.value.toUpperCase()">
          
              <div class="form-group row" hidden>
                <label  class="col-sm-2 col-form-label" for="actiondate">Action Date :<span class="text-danger">*</span></label>
                <div class="col-sm-10">
                <input name="actiondate" class="form-control" min="1930-01-01" type="date" value={{ now() }}>
                @error('actiondate')
                <p class="text-danger text-xs mt-1">{{$message}}</p>
                @enderror
              </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="action">Action : <span class="text-danger">*</span></label>
                <div class="col-sm-10">
                  <select  name="action"  id="action" class="form-control select2" style="width: 100%;">

                    @if ($Route->remarks == 'ACCEPTED - FOR SIGNATURE' || $Route->action == 'FOR LDDAP / ADA SIGNATURE')
                    <option value="FOR LDDAP / ADA SIGNATURE" selected> FOR LDDAP / ADA SIGNATURE (LDDAP / ADA ONLY)</option>  
                    <option value="FORWARD TO"> FORWARD TO (VOUCHER ONLY)</option>  
                    @else
                    <option value="FOR LDDAP / ADA SIGNATURE"> FOR LDDAP / ADA SIGNATURE (LDDAP / ADA ONLY)</option>  
                    <option value="FORWARD TO" selected> FORWARD TO (VOUCHER ONLY)</option>  
                    @endif
                   
          
                   </select>
                  @error('action')
                  <p class="text-danger text-xs mt-1">{{$message}}</p>
                  @enderror
                </div>
              </div> 
             
              <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="officeid"> Destination Office : <span class="text-danger">*</span></label>
                <div class="col-sm-10">
                  <select  name="officeid"  id="officeid" class="form-control select2" style="width: 100%;">
                    <option value=""  selected>-- Choose office --</option>
                    @foreach($Offices as $Office) 
                      <div>
                         <optgroup label="{{$Office->office }}" class="bg-light text-center"></option> 
                            @foreach ($Sections as $Section)
                              @if ( $Section->officeid  == $Office->id );
                                <optgroup label="- {{$Section->section }}"><strong></strong></option>
                                  @foreach ($Units as $Unit)
                                     @if ( $Unit->sectionid  == $Section->id );
                                        @if ($Unit->id == 1 || $Unit->id == 2 || $Unit->id == 4 || $Unit->id == 6 || $Unit->id == 7 || $Unit->id == 8 || $Unit->id == 9 || $Unit->id == 14 || $Unit->id == 15)
                                          <option value="{{$Office->id }},{{$Section->id }},{{$Unit->id}}" class="bg-light pl-4"> {{$Unit->unit }}</option>
                                        @endif
                                    @endif
                                  @endforeach
                                </optgroup>   
                              @endif 
                            @endforeach
                          </optgroup>
                      </div>
                    @endforeach
                  </select>
                  @error('officeid')
                  <p class="text-danger text-xs mt-1">{{$message}}</p>
                  @enderror
                </div>
              </div>
              <div class="form-group row">
                <label  class="col-sm-2" for="remarks">Remarks : </label>
                <div class=" col-sm-10">
                <input name="remarks" id="remarks" class="form-control" type="text" placeholder="Enter Remarks" value="{{old('remarks')}}" oninput="this.value = this.value.toUpperCase()">
                @error('remarks')
                <p class="text-danger text-xs mt-1">{{$message}}</p>
                @enderror
                </div>
            </div>
              
              <div class="card-footer">
                <button type="submit" id="submit" class="btn btn-primary button-prevent-multiple-submits">Submit</button>
              </div>
          </div>
      
      </form> 
    </div>
</div>
</div>

