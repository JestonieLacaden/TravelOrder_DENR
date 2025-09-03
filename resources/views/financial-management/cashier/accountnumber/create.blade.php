<div class="modal fade" id="create-accountnumber-modal-lg">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Create Account Name / Payee</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {{-- --}}
            <form method="POST" action="{{ route('fmcashier.accountnumbercreate')}}" enctype="multipart/form-data">

                {{ csrf_field() }}

                <div class="modal-body">

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="acct_id">Account Name : <span
                                class="text-danger">*</span> </label>
                        <div class="col-sm-9">
                            <select name="acct_id" class="form-control select2" style="width: 100%;">
                                <option value="" disabled selected>-- Choose Account Name --</option> 
                                @foreach($AccountNames as $AccountName)
                                  <option value="{{$AccountName->id}}">{{ $AccountName->acct_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('acct_id')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                        @enderror
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3" for="bank_code">Bank Code : </label>
                        <div class=" col-sm-9">
                            <input name="bank_code" id="bank_code" class="form-control" type="text" maxlength="5"
                                placeholder="Enter Bank Code" value="{{old('bank_code')}}"
                                oninput="this.value = this.value.toUpperCase()">
                            @error('bank_code')
                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label class="col-sm-3" for="bank_name">Bank Name : </label>
                        <div class=" col-sm-9">
                            <input name="bank_name" id="bank_name" class="form-control" type="text"
                                placeholder="Enter Bank Name" value="{{old('bank_name')}}"
                                oninput="this.value = this.value.toUpperCase()">
                            @error('bank_name')
                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-sm-3" for="acct_no">Account Number : </label>
                      <div class=" col-sm-9">
                          <input name="acct_no" id="acct_no" class="form-control" type="text"
                              placeholder="Enter Account Number" value="{{old('acct_no')}}"
                              oninput="this.value = this.value.toUpperCase()">
                          @error('acct_no')
                          <p class="text-danger text-xs mt-1">{{$message}}</p>
                          @enderror
                      </div>
                  </div>

                    <div class="modal-footer">
                        <button type="SUBMIT" class="btn gray btn-success"> Create</button>
                        <button type="button" class="btn gray btn-default" data-dismiss="modal"> Cancel </button>
                    </div>
            </form>
        </div>
    </div>
</div>
