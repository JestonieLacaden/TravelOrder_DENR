<div class="modal fade" id="add-ors-modal-lg{{ $Route->Voucher->id }}">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add ORS Number</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form method="POST" action="  {{ route('fmbudget.ors',[$Route->Voucher->id ])}}"
                enctype="multipart/form-data">

                {{ csrf_field() }}

                <div class="modal-body">

                    <input type="text" hidden name="fmid" id="fmid" value="{{$Route->Voucher->id }}">

                    <div class="form-group row" hidden>
                        <label class="col-sm-2 col-form-label" for="actiondate">Action Date :<span
                                class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input name="actiondate" class="form-control" min="1930-01-01" type="date"
                                value={{ now() }}>
                            @error('actiondate')
                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2" for="fc">Fund Cluster :<span class="text-danger">*</span></label>
                        <div class=" col-sm-10">
                            <input name="fc" id="fc" class="form-control" type="text"
                                placeholder="Enter Fund Cluster"  maxlength="20" value="{{old('fc')}}"
                                oninput="this.value = this.value.toUpperCase()">
                            @error('fc')
                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2" for="particulars">Particulars :<span
                                class="text-danger">*</span></label>
                        <div class=" col-sm-10">
                            <input name="particulars" id="particulars" class="form-control" type="text"
                                placeholder="Enter Particulars"  maxlength="15" value="{{old('particulars')}}"
                                oninput="this.value = this.value.toUpperCase()">
                            @error('particulars')
                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2" for="orsno">ORS Number :<span class="text-danger">*</span></label>
                        <div class=" col-sm-10">
                            <input name="orsno" id="orsno" class="form-control" type="text"
                                placeholder="Enter ORS Number"  maxlength="20" value="{{old('orsno')}}"
                                oninput="this.value = this.value.toUpperCase()">
                            @error('orsno')
                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2" for="obligation">Obligation :<span class="text-danger">*</span></label>
                        <div class=" col-sm-10">
                            <input name="obligation" id="obligation" class="form-control" type="number"
                                step="0.01" placeholder="Enter Obligation" value="{{old('obligation')}}" max="{{$Route->voucher->amount}}"
                                oninput="this.value = this.value.toUpperCase()">
                            @error('obligation')
                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2" for="payable">Payable :<span class="text-danger">*</span></label>
                        <div class=" col-sm-10">
                            <input name="payable" id="payable" class="form-control"  type="number"
                                step="0.01" placeholder="Enter Payable" value="{{old('payable')}}" max="{{$Route->voucher->amount}}"
                                oninput="this.value = this.value.toUpperCase()">
                            @error('payable')
                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2" for="payment">Payment :</label>
                        <div class=" col-sm-10">
                            <input name="payment" id="payment" class="form-control" type="number"
                                step="0.01" placeholder="Enter Payment" value="{{old('payment')}}" max="{{$Route->voucher->amount}}"
                                oninput="this.value = this.value.toUpperCase()">
                            @error('payment')
                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                            @enderror
                        </div>
                    </div>

                    <div>Balance</div>
                    <div class="dropdown-divider"></div>

                    <div class="form-group row">
                        <label class="col-sm-2" for="nyd">Not Yet Due : </label>
                        <div class=" col-sm-10">
                            <input name="nyd" id="nyd" class="form-control" type="number" step="0.01"
                                placeholder="Enter Not Yet Due" value="{{old('nyd')}}"
                                oninput="this.value = this.value.toUpperCase()">
                            @error('nyd')
                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2" for="dd">Due and Demandable :</label>
                        <div class=" col-sm-10">
                            <input name="dd" id="dd" class="form-control" type="number" step="0.01"
                                placeholder="Enter Due and Demandable" value="{{old('dd')}}"
                                oninput="this.value = this.value.toUpperCase()">
                            @error('dd')
                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                            @enderror
                        </div>
                    </div>


                    <div class="modal-footer">
                        <button type="button" class="btn gray btn-default" data-dismiss="modal"> Cancel </button>
                        <button type="SUBMIT" class="btn gray btn-success"> Create and Obligate</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
