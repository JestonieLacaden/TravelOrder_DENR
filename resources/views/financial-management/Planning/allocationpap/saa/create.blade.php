<div class="modal fade" id="create-allocation-modal-lg" >
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Create Allocation per PAP (SAA)</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
    {{--  --}}
        <form  method="POST" action="{{ route('fmplanning.allocationcreatePAPSAA')}}" enctype="multipart/form-data">
         
          {{ csrf_field() }}

          <div class="modal-body">

            <div class="form-group row">
              <label class="col-sm-3 col-form-label" for="papid">PAP :<span class="text-danger">*</span> </label>
              <div class="col-sm-9">
                <select name="papid" class="form-control select2" style="width: 100%;">
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
              <label class="col-sm-3 col-form-label" for="expense_class">Expense Class </i> :  <span
                      class="text-danger">*</span> </label>
              <div class="col-sm-9">
                  <select name="expense_class" id="expense_class" class="form-control select2"
                      style="width: 100%;">
                      <option value="" disabled selected>-- Choose Expense Class --</option>
                      <option value="CAPITAL OUTLAY (CO)">CAPITAL OUTLAY (CO)</option>
                      <option value="MAINTENANCE AND OTHER OPERATING EXPENSES (MOOE)">MAINTENANCE AND OTHER OPERATING EXPENSES (MOOE)</option>
                      <option value="PERSONNEL SERVICES (PS)">PERSONNEL SERVICES (PS)</option>
  
                  </select>
        
              @error('expense_class')
              <p class="text-danger text-xs mt-1">{{$message}}</p>
              @enderror
             </div>
            </div>

            <div class="form-group row">
              <label class="col-sm-3 col-form-label" for="office">Office </i> :  <span
                      class="text-danger">*</span> </label>
              <div class="col-sm-9">
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
              <label class="col-sm-3" for="amount">Amount :<span class="text-danger">*</span></label>
              <div class=" col-sm-9">
              <input name="amount" id="amount" class="form-control" type="number" step="0.01" placeholder="Enter Amount" value="{{old('amount')}}" oninput="this.value = this.value.toUpperCase()">
              @error('amount')
              <p class="text-danger text-xs mt-1">{{$message}}</p>
              @enderror
              </div>
            </div> 
            

            <div class="form-group row">
              <label  class="col-sm-3" for="year">Year :<span class="text-danger">*</span> </label>
              <div class=" col-sm-9">
              <input name="year" id="year" class="form-control" type="number" placeholder="Enter Year" maxlength="4" value="{{ date("Y") }}" oninput="this.value = this.value.toUpperCase()">
              @error('year')
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
  </div></div>
  