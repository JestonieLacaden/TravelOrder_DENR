<div class="modal fade" id="create-accountname-modal-lg" >
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Create Account Name / Payee</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
    {{--  --}}
        <form  method="POST" action="{{ route('fmcashier.accountnamecreate')}}" enctype="multipart/form-data">
         
          {{ csrf_field() }}

          <div class="modal-body">
      
            <div class="form-group row">
              <label  class="col-sm-2" for="acct_name">Account Name : </label>
              <div class=" col-sm-10">
              <input name="acct_name" id="acct_name" class="form-control" type="text" placeholder="Enter Account Name / Payee" value="{{old('acct_name')}}" oninput="this.value = this.value.toUpperCase()">
              @error('acct_name')
              <p class="text-danger text-xs mt-1">{{$message}}</p>
              @enderror
              </div>
            </div>

              <div class="form-group row">
                <label  class="col-sm-2" for="address">Address : </label>
                <div class=" col-sm-10">
                <input name="address" id="address" class="form-control" type="text" placeholder="Enter Account Address" value="{{old('address')}}" oninput="this.value = this.value.toUpperCase()">
                @error('address')
                <p class="text-danger text-xs mt-1">{{$message}}</p>
                @enderror
                </div>
              </div>

              <div class="form-group row">
                <label  class="col-sm-2" for="tinno">Tin Number : </label>
                <div class=" col-sm-10">
                <input name="tinno" id="tinno" class="form-control" type="text" placeholder="Enter Tin Number" value="{{old('tinno')}}" oninput="this.value = this.value.toUpperCase()">
                @error('tinno')
                <p class="text-danger text-xs mt-1">{{$message}}</p>
                @enderror
                </div>
              </div>

          </div>
            
          <div class="modal-footer">
            <button type="SUBMIT" class="btn gray btn-success"> Create</button>
            <button type="button" class="btn gray btn-default" data-dismiss="modal"> Cancel </button>
          </div>
      </form>
    </div>
  </div></div>
  