<div class="modal fade" id="add-charging-saa-modal-lg{{ $Route->Voucher->id }}" >
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add Charging (SAA)</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
  
        <form  method="POST" action="  {{ route('fmplanning.chargingSAA',[$Route->Voucher->id ])}}" enctype="multipart/form-data">
         
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
              <label class="col-sm-2 col-form-label" for="papid_saa">PAP : <span class="text-danger">*</span> </label>
              <div class="col-sm-10">
                <select name="papid_saa" id="papid_saa" class="form-control select2" style="width: 100%;">
                  <option value="" disabled selected>-- Choose PAP --</option>
                  @foreach($PAPs as $PAP)
                  <option value="{{$PAP->id}}" >{{ $PAP->pap }}</option>
                  @endforeach
                </select>
              </div>
          
              @error('papid')
              <p class="text-danger text-xs mt-1">{{$message}}</p>
              @enderror
            </div>


            <div class="form-group row">
              <label class="col-sm-2 col-form-label" for="expense_class_saa">Expense Class</i>:  <span
                      class="text-danger">*</span> </label>
              <div class="col-sm-10">
                  <select name="expense_class_saa" id="expense_class_saa" class="form-control select2"
                      style="width: 100%;">
                      <option value="" disabled selected>-- Choose Expense Class --</option>
                      {{-- <option value="CAPITAL OUTLAY (CO)">CAPITAL OUTLAY (CO)</option>
                      <option value="MAINTENANCE AND OTHER OPERATING EXPENSES (MOOE)">MAINTENANCE AND OTHER OPERATING EXPENSES (MOOE)</option>
                      <option value="PERSONNEL SERVICES (PS)">PERSONNEL SERVICES (PS)</option>
   --}}
                  </select>
        
              @error('expense_class')
              <p class="text-danger text-xs mt-1">{{$message}}</p>
              @enderror
             </div>
            </div>

            
        
            <div class="dropdown-divider"></div>
          <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="Activity_saa">Activity : <span class="text-danger">*</span> </label>
            <div class="col-sm-10">
              <select name="activityid_saa" id="activity_saa" class="form-control select2" style="width: 100%;">
                <option value="" disabled selected>-- Choose Activity --</option>

              </select>
            </div>
        
            @error('activityid')
            <p class="text-danger text-xs mt-1">{{$message}}</p>
            @enderror
          </div>


               
          <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="year_saa">Year : <span class="text-danger">*</span> </label>
            <div class="col-sm-10">
              <select name="year_saa" id="year_saa" class="form-control select2" style="width: 100%;">
                <option value="" disabled selected>-- Choose Year --</option>

              </select>
            </div>
        
            @error('year')
            <p class="text-danger text-xs mt-1">{{$message}}</p>
            @enderror
          </div>

 
          <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="activityoffice_saa">Office : <span class="text-danger">*</span> </label>
            <div class="col-sm-10">
              <select name="activityoffice_saa" id="activityoffice_saa" class="form-control select2" style="width: 100%;">
                <option value="" disabled selected>-- Choose Office --</option>

              </select>
            </div>
        
            @error('activityoffice')
            <p class="text-danger text-xs mt-1">{{$message}}</p>
            @enderror
          </div>

          <div class="form-group row">
            <label class="col-sm-2" for="rem_bal_saa">Remaining Balance :<span class="text-danger">*</span></label>
            <div class=" col-sm-10">
            <input name="rem_bal_saa" id="rem_bal_saa" class="form-control" type="string" value="{{old('amount')}}" oninput="this.value = this.value.toUpperCase()" readonly>
            @error('rem_bal')
            <p class="text-danger text-xs mt-1">{{$message}}</p>
            @enderror
            </div>
          </div> 
     
      
          <div class="dropdown-divider"></div>
        <div class="form-group row">
          <label class="col-sm-2 col-form-label" for="uacsid_saa">UACS : <span class="text-danger">*</span> </label>
          <div class="col-sm-10">
            <select name="uacsid_saa"  id="uacs_saa" class="form-control select2" style="width: 100%;">
              <option value="" disabled selected>-- Choose UACS --</option>
              @foreach($UACSs as $UACS)
              <option value="{{$UACS->id}}">{{ $UACS->uacs }}</option>
              @endforeach
            </select>
          </div>
      
          @error('uacsid')
          <p class="text-danger text-xs mt-1">{{$message}}</p>
          @enderror
        </div>

        <div class="form-group row">
          <label class="col-sm-2 col-form-label" for="year_uacs_saa">Year : <span class="text-danger">*</span> </label>
          <div class="col-sm-10">
            <select name="year_uacs_saa" id="year_uacs_saa" class="form-control select2" style="width: 100%;">
              <option value="" disabled selected>-- Choose Year --</option>

            </select>
          </div>
      
          @error('year_uacs')
          <p class="text-danger text-xs mt-1">{{$message}}</p>
          @enderror
        </div>

         
        <div class="form-group row">
          <label class="col-sm-2 col-form-label" for="uacsoffice_saa">Office : <span class="text-danger">*</span> </label>
          <div class="col-sm-10">
            <select name="uacsoffice_saa" id="uacsoffice_saa" class="form-control select2" style="width: 100%;">
              <option value="" disabled selected>-- Choose Office --</option>

            </select>
          </div>
      
          @error('uacsoffice')
          <p class="text-danger text-xs mt-1">{{$message}}</p>
          @enderror
        </div>

        <div class="form-group row">
          <label class="col-sm-2" for="rem_baluacs_saa">Remaining Balance :<span class="text-danger">*</span></label>
          <div class=" col-sm-10">
          <input name="rem_baluacs_saa" id="rem_baluacs_saa" class="form-control" type="string" value="{{old('amount')}}" oninput="this.value = this.value.toUpperCase()" readonly>
          @error('rem_baluacs')
          <p class="text-danger text-xs mt-1">{{$message}}</p>
          @enderror
          </div>
        </div> 

        <div class="dropdown-divider"></div>
        <div class="form-group row">
          <label class="col-sm-2" for="amount_saa">Amount :<span class="text-danger">*</span></label>
          <div class=" col-sm-10">
          <input name="amount_saa" id="amount_saa" class="form-control" type="number" step="0.01" placeholder="Enter Amount" value="{{old('amount')}}" oninput="this.value = this.value.toUpperCase()">
          @error('amount')
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