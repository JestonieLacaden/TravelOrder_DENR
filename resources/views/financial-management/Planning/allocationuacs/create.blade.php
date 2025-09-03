<div class="modal fade" id="create-allocation-modal-lg" >
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Create Allocation per UACS (GAA)</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <form  method="POST" action="{{ route('fmplanning.allocationcreateUACS')}}" enctype="multipart/form-data">
         
          {{ csrf_field() }}

          <div class="modal-body">

            <div class="form-group row">
              <label class="col-sm-2 col-form-label" for="papid">PAP :<span class="text-danger">*</span> </label>
              <div class="col-sm-10">
                <select name="papid" id="papid" class="form-control select2" style="width: 100%;">
                  <option value="" disabled selected>-- Choose PAP --</option>
                  @foreach($PAPs as $PAP)
                  <option value="{{$PAP->id}}">{{ $PAP->pap }}</option>
                  @endforeach
                </select>
              </div>
           
              @error('papid')
              <p class="text-danger text-xs mt-1">{{$message}}</p>
              @enderror
            </div>

            <div class="form-group row">
              <label class="col-sm-2 col-form-label" for="expense_class">Expense Class</i>:  <span
                      class="text-danger">*</span> </label>
              <div class="col-sm-10">
                  <select name="expense_class" id="expense_class" class="form-control select2"
                      style="width: 100%;">
                      <option value="" disabled selected>-- Choose Expense Class --</option>
                      {{-- <option value="CAPITAL OUTLAY (CO)">CAPITAL OUTLAY (CO)</option>
                      <option value="MAINTENANCE AND OTHER OPERATING EXPENSES (MOOE)">MAINTENANCE AND OTHER OPERATING EXPENSES (MOOE)</option>
                      <option value="PERSONNEL SERVICES (PS)">PERSONNEL SERVICES (PS)</option>
   --}}
                  </select>
              </div>
            </div>


            <div class="form-group row">
              <label class="col-sm-2 col-form-label" for="papoffice1">Office </i> :  <span
                      class="text-danger">*</span> </label>
              <div class="col-sm-10">
                  <select name="papoffice1" id="papoffice1" class="form-control select2"
                      style="width: 100%;">
                      <option value="" disabled selected>-- Choose Office --</option>
                      {{-- <option value="ARNP - APO REEF NATURAL PARK">ARNP - APO REEF NATURAL PARK</option>
                      <option value="CENRO - SABLAYAN OCCIDENTAL MINDORO">CENRO - SABLAYAN OCCIDENTAL MINDORO</option>
                      <option value="CENRO - SAN JOSE OCCIDENTAL MINDORO">CENRO - SAN JOSE OCCIDENTAL MINDORO</option>
                      <option value="MCWS - MOUNT CALAVITE WILDLIFE SANCTUARY">MCWS - MOUNT CALAVITE WILDLIFE SANCTUARY</option>
                      <option value="MIBNP - MOUNT IGLIT BACO NATURAL PARK">MIBNP - MOUNT IGLIT BACO NATURAL PARK</option>
                      <option value="DENR - PENRO OCCIDENTAL MINDORO">DENR - PENRO OCCIDENTAL MINDORO</option>
                      <option value="TCP - TAMARAW CONSERVATION PROGRAM">TCP - TAMARAW CONSERVATION PROGRAM</option> --}}
                  </select>
        
              @error('papoffice1')
              <p class="text-danger text-xs mt-1">{{$message}}</p>
              @enderror
             </div>
            </div>


            
            <div class="form-group row">
              <label class="col-sm-2 col-form-label" for="year">Year : <span class="text-danger">*</span> </label>
              <div class="col-sm-10">
                <select name="year" id="year" class="form-control select2" style="width: 100%;">
                  <option value="" disabled selected>-- Choose Year --</option>
                </select>
              </div>
           
              @error('year')
              <p class="text-danger text-xs mt-1">{{$message}}</p>
              @enderror
            </div>

          <div class="form-group row">
            <label class="col-sm-2" for="rem_bal">Remaining Balance :<span class="text-danger">*</span></label>
            <div class=" col-sm-10">
            <input name="rem_bal" id="rem_bal" class="form-control" type="string" oninput="this.value = this.value.toUpperCase()" readonly>
            @error('rem_bal')
            <p class="text-danger text-xs mt-1">{{$message}}</p>
            @enderror
            </div>
          </div> 
     
          <div class="dropdown-divider"></div>

            <div class="form-group row">
              <label class="col-sm-2 col-form-label" for="uacsid">UACS :<span class="text-danger">*</span> </label>
              <div class="col-sm-10">
                <select name="uacsid" class="form-control select2" style="width: 100%;">
                  <option value="" disabled selected>-- Choose UACS --</option>
                  @foreach($UACSs as $UACS)
                  <option value="{{$UACS->id}}">{{ $UACS->uacs . ' - ' . $UACS->description }}</option>
                  @endforeach
                </select>
              </div>
           
              @error('uacsid')
              <p class="text-danger text-xs mt-1">{{$message}}</p>
              @enderror
            </div>

            <div class="form-group row">
              <label class="col-sm-2 col-form-label" for="office">Office </i> :  <span
                      class="text-danger">*</span> </label>
              <div class="col-sm-10">
                  <select name="office" id="office" class="form-control select2"
                      style="width: 100%;">
                      <option value="" disabled selected>-- Choose Office --</option>
                      <option value="ARNP - APO REEF NATURAL PARK">ARNP - APO REEF NATURAL PARK</option>
                      <option value="CENRO - SABLAYAN OCCIDENTAL MINDORO">CENRO - SABLAYAN OCCIDENTAL MINDORO</option>
                      <option value="CENRO - SAN JOSE OCCIDENTAL MINDORO">CENRO - SAN JOSE OCCIDENTAL MINDORO</option>
                      <option value="MCWS - MOUNT CALAVITE WILDLIFE SANCTUARY">MCWS - MOUNT CALAVITE WILDLIFE SANCTUARY</option>
                      <option value="MIBNP - MOUNT IGLIT BACO NATURAL PARK">MIBNP - MOUNT IGLIT BACO NATURAL PARK</option>
                      <option value="DENR - PENRO OCCIDENTAL MINDORO">DENR - PENRO OCCIDENTAL MINDORO</option>
                      <option value="TCP - TAMARAW CONSERVATION PROGRAM">TCP - TAMARAW CONSERVATION PROGRAM</option>
                  </select>
        
              @error('office')
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
  