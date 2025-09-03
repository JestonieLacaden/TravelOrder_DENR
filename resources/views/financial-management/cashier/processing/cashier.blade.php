<div class="modal fade" id="add-cashier-modal-lg{{ $Route->Voucher->id }}" >
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add Cashier Informoration</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
  
        <form  method="POST" action="  {{ route('fmcashier.cashier',[$Route->Voucher->id ])}}" enctype="multipart/form-data">
         
          {{ csrf_field() }}
          
          <div class="modal-body">

            <input type="text" hidden  name="fmid" id="fmid" value="{{$Route->Voucher->id }}">
            <input type="text" hidden  name="payee" id="payee" value="{{$Route->Voucher->payee }}">

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
            <label class="col-sm-2" for="adano">Check / ADA Number :<span class="text-danger">*</span></label>
            <div class=" col-sm-10">
            <input name="adano" id="adano" class="form-control" type="text" placeholder="Enter Check / ADA Number" value="{{old('adano')}}" oninput="this.value = this.value.toUpperCase()">
            @error('adano')
            <p class="text-danger text-xs mt-1">{{$message}}</p>
            @enderror
            </div>
          </div>

          <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="mop">Mode of Payment :  <span
                    class="text-danger">*</span> </label>
            <div class="col-sm-10">
                <select name="mop" id="mop" class="form-control select2"
                    style="width: 100%;">
                    <option value="" disabled selected>-- Choose Mode of Payment --</option>
                    <option value="MDS CHECK">MDS CHECK</option>
                    <option value="COMMERCIAL CHECK">COMMERCIAL CHECK</option>
                    <option value="ADA">ADA</option>
                    <option value="OTHERS">OTHERS</option>
                </select>
      
            @error('mop')
            <p class="text-danger text-xs mt-1">{{$message}}</p>
            @enderror
           </div>
          </div>

          <div class="modal-footer">
              <button type="button" class="btn gray btn-default" data-dismiss="modal"> Cancel </button>
              <button type="SUBMIT" class="btn gray btn-success"> Create & Disburse</button>
          </div>
          </div>
      </form>
    </div>
  </div>
</div>