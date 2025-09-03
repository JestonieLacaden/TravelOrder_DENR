<div class="modal fade" id="add-allocationuacs-modal-lg{{ $Allocation->id }}" >
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Create Allocation per UACS</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <form  method="POST" action="{{ route('fmplanning.allocationcreateUACS')}}" enctype="multipart/form-data">
         
          {{ csrf_field() }}

          <div class="modal-body">

            <div class="form-group row">
              <label class="col-sm-2" for="papid">PAP :<span class="text-danger">*</span></label>
              <div class=" col-sm-10">
              <input name="papid" id="papid" class="form-control" type="string" value="{{ $Allocation->PAP->pap }}" oninput="this.value = this.value.toUpperCase()" readonly>
              @error('papid')
              <p class="text-danger text-xs mt-1">{{$message}}</p>
              @enderror
              </div>
            </div> 

            

            <div class="form-group row">
              <label class="col-sm-2" for="year">Year :<span class="text-danger">*</span></label>
              <div class=" col-sm-10">
              <input name="year" id="year" class="form-control" type="string" value="{{ $Allocation->year }}" oninput="this.value = this.value.toUpperCase()" readonly>
              @error('year')
              <p class="text-danger text-xs mt-1">{{$message}}</p>
              @enderror
              </div>
            </div> 

            

          <div class="form-group row">
            <label class="col-sm-2" for="rem_bal">Remaining Balance :<span class="text-danger">*</span></label>
            <div class=" col-sm-10">
            <input name="rem_bal" id="rem_bal" class="form-control" type="string" value="{{ number_format($Allocation->rem_bal,2,'.',',')  }}" oninput="this.value = this.value.toUpperCase()" readonly>
            @error('rem_bal')
            <p class="text-danger text-xs mt-1">{{$message}}</p>
            @enderror
            </div>
          </div> 
     
          <div class="dropdown-divider"></div>

          <div class="form-group row">
            <label class="col-sm-2" for="uacsid">UACS :<span class="text-danger">*</span></label>
            <div class=" col-sm-10">
            <input name="uacsid" id="uacsid" class="form-control" type="string" value="{{ $Allocation->UACS->uacs }}" oninput="this.value = this.value.toUpperCase()" readonly>
            @error('uacsid')
            <p class="text-danger text-xs mt-1">{{$message}}</p>
            @enderror
            </div>
          </div> 
    
  
            <div class="form-group row">
              <label class="col-sm-2" for="amount">Amount :<span class="text-danger">*</span></label>
              <div class=" col-sm-10">
              <input name="amount" id="amount" class="form-control" type="number" step="0.01" placeholder="Enter Amount"  oninput="this.value = this.value.toUpperCase()">
              @error('amount')
              <p class="text-danger text-xs mt-1">{{$message}}</p>
              @enderror
              </div>
            </div> 
            

   

          <div class="modal-footer">
            <button type="SUBMIT" class="btn gray btn-success"> Create</button>
            <button type="button" class="btn gray btn-default" data-dismiss="modal"> Cancel </button>
          </div>
          </div>
      </form>
    </div>
  </div>
</div>
  