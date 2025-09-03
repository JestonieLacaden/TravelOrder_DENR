

<div class="modal fade" id="add-accountingentry-modal-lg{{ $Route->Voucher->id }}" >
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add Accounting Entry</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
  
    
        <form  method="POST" action="{{ route('fmaccounting.accountingentry',[$Route->Voucher->id ])}}" enctype="multipart/form-data">
         
          {{ csrf_field() }}
          
          <div class="modal-body">

            <input type="text" hidden  name="fmid" id="fmid" value="{{$Route->Voucher->id }}">

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
              <label class="col-sm-2 col-form-label" for="activity_id">Account Title : <span class="text-danger">*</span> </label>
              <div class="col-sm-10">
                <select name="activity_id" id="activity_id_acctg" class="form-control select2" style="width: 100%;">
                  <option value="" disabled selected>-- Choose Account Title --</option>
                  @foreach($AccountingActivities as $Activity)
                  <option value="{{$Activity->id}}">{{ $Activity->activity }}</option>
                  @endforeach
                </select>
              </div>
          
              @error('activity_id')
              <p class="text-danger text-xs mt-1">{{$message}}</p>
              @enderror
            </div>
        

          <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="uacs_id">UACS : <span class="text-danger">*</span> </label>
            <div class="col-sm-10">
              <select name="uacs_id" id="uacs_id_acctg" class="form-control select2" style="width: 100%;">
                <option value="" disabled selected>-- Choose UACS --</option>
                {{-- @foreach($Activities as $Activity)
                <option value="{{$Activity->id}}">{{ $Activity->activity }}</option>
                @endforeach--}}
              </select> 
            </div>
        
            @error('uacs_id')
            <p class="text-danger text-xs mt-1">{{$message}}</p>
            @enderror
          </div>
      
        
      
        <div class="dropdown-divider"></div>
        <div class="form-group row">
            <label class="col-sm-2">Amount :<span class="text-danger">*</span></label>
            </div>

        <div class="form-group row">
          <label class="col-sm-2" for="debit">Debit :</label>
          <div class=" col-sm-10">
          <input name="debit" id="debit" class="form-control" type="number" step="0.01" placeholder="Enter Debit Amount" value="{{old('debit')}}" oninput="this.value = this.value.toUpperCase()">
          @error('debit')
          <p class="text-danger text-xs mt-1">{{$message}}</p>
          @enderror
          </div>
        </div> 

  

        <div class="form-group row">
            <label class="col-sm-2" for="credit">Credit :</label>
            <div class=" col-sm-10">
            <input name="credit" id="credit" class="form-control" type="number" step="0.01" placeholder="Enter Credit Amount" value="{{old('credit')}}" oninput="this.value = this.value.toUpperCase()">
            @error('credit')
            <p class="text-danger text-xs mt-1">{{$message}}</p>
            @enderror
            </div>
          </div> 
  
              
          <div class="modal-footer">
              <button type="button" class="btn gray btn-default" data-dismiss="modal"> Cancel </button>
              <button type="SUBMIT" class="btn gray btn-success"> Confirm</button>
          </div>
          </div>
      </form>
    </div>
  </div>
</div>