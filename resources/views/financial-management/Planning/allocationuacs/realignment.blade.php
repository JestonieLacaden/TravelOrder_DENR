<div class="modal fade" id="realignment-allocation-modal-lg" >
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Realignment - UACS (GAA)</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
    {{--  --}}
        <form  method="POST" action="{{ route('fmplanning.realignmentUACS')}}" enctype="multipart/form-data">
         
          {{ csrf_field() }}

      
          <div class="modal-body">

          <div class="text-center bg-info">From</div>
          <div class="dropdown-divider"></div>

            <div class="form-group row">
              <label class="col-sm-2 col-form-label" for="papidrealign1">PAP :<span class="text-danger">*</span> </label>
              <div class="col-sm-10">
                <select name="papidrealign1" id="papidrealign1" class="form-control select2" style="width: 100%;">
                  <option value="" disabled selected>-- Choose PAP --</option>
                  @foreach($PAPs as $PAP)
                  <option value="{{$PAP->id}}">{{ $PAP->pap }}</option>
                  @endforeach
                </select>
              </div>
           
              @error('papidrealign1')
              <p class="text-danger text-xs mt-1">{{$message}}</p>
              @enderror
            </div>


            <div class="form-group row">
              <label class="col-sm-2 col-form-label" for="expense_class_realign">Expense Class</i>:  <span
                      class="text-danger">*</span> </label>
              <div class="col-sm-10">
                  <select name="expense_class_realign" id="expense_class_realign" class="form-control select2"
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
            <label class="col-sm-2 col-form-label" for="papoffice">Office </i> :  <span
                    class="text-danger">*</span> </label>
            <div class="col-sm-10">
                <select name="papoffice" id="papoffice" class="form-control select2"
                    style="width: 100%;">
                <option value="" disabled selected>-- Choose Office --</option>
                       {{--  <option value="ARNP - APO REEF NATURAL PARK">ARNP - APO REEF NATURAL PARK</option>
                    <option value="CENRO - SABLAYAN OCCIDENTAL MINDORO">CENRO - SABLAYAN OCCIDENTAL MINDORO</option>
                    <option value="CENRO - SAN JOSE OCCIDENTAL MINDORO">CENRO - SAN JOSE OCCIDENTAL MINDORO</option>
                    <option value="MCWS - MOUNT CALAVITE WILDLIFE SANCTUARY">MCWS - MOUNT CALAVITE WILDLIFE SANCTUARY</option>
                    <option value="MIBNP - MOUNT IGLIT BACO NATURAL PARK">MIBNP - MOUNT IGLIT BACO NATURAL PARK</option>
                    <option value="DENR - PENRO OCCIDENTAL MINDORO">DENR - PENRO OCCIDENTAL MINDORO</option>
                    <option value="TCP - TAMARAW CONSERVATION PROGRAM">TCP - TAMARAW CONSERVATION PROGRAM</option> --}}
                </select>
      
            @error('papoffice')
            <p class="text-danger text-xs mt-1">{{$message}}</p>
            @enderror
           </div>
          </div>

            <div class="form-group row">
              <label class="col-sm-2 col-form-label" for="yearrealign1">Year : <span class="text-danger">*</span> </label>
              <div class="col-sm-10">
                <select name="yearrealign1" id="yearrealign1" class="form-control select2" style="width: 100%;">
                  <option value="" disabled selected>-- Choose Year --</option>
                </select>
              </div>
           
              @error('yearrealign1')
              <p class="text-danger text-xs mt-1">{{$message}}</p>
              @enderror
            </div>

          
          <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="uacsidrealign1">UACS :<span class="text-danger">*</span> </label>
            <div class="col-sm-10">
              <select name="uacsidrealign1" id="uacsidrealign1" class="form-control select2" style="width: 100%;">
                <option value="" disabled selected>-- Choose UACS --</option>
              </select>
            </div>
         
            @error('uacsidrealign1')
            <p class="text-danger text-xs mt-1">{{$message}}</p>
            @enderror
          </div>

          <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="office1">Office </i> :  <span
                    class="text-danger">*</span> </label>
            <div class="col-sm-10">
                <select name="office1" id="office1" class="form-control select2"
                    style="width: 100%;">
                <option value="" disabled selected>-- Choose Office --</option>
                       {{--  <option value="ARNP - APO REEF NATURAL PARK">ARNP - APO REEF NATURAL PARK</option>
                    <option value="CENRO - SABLAYAN OCCIDENTAL MINDORO">CENRO - SABLAYAN OCCIDENTAL MINDORO</option>
                    <option value="CENRO - SAN JOSE OCCIDENTAL MINDORO">CENRO - SAN JOSE OCCIDENTAL MINDORO</option>
                    <option value="MCWS - MOUNT CALAVITE WILDLIFE SANCTUARY">MCWS - MOUNT CALAVITE WILDLIFE SANCTUARY</option>
                    <option value="MIBNP - MOUNT IGLIT BACO NATURAL PARK">MIBNP - MOUNT IGLIT BACO NATURAL PARK</option>
                    <option value="DENR - PENRO OCCIDENTAL MINDORO">DENR - PENRO OCCIDENTAL MINDORO</option>
                    <option value="TCP - TAMARAW CONSERVATION PROGRAM">TCP - TAMARAW CONSERVATION PROGRAM</option> --}}
                </select>
      
            @error('office1')
            <p class="text-danger text-xs mt-1">{{$message}}</p>
            @enderror
           </div>
          </div>

                                
          <div class="form-group row">
            <label class="col-sm-2" for="rem_balrealign1">Remaining Balance :<span class="text-danger">*</span></label>
            <div class=" col-sm-10">
            <input name="rem_balrealign1" id="rem_balrealign1" class="form-control" type="string" oninput="this.value = this.value.toUpperCase()" readonly>
            @error('rem_balrealign1')
            <p class="text-danger text-xs mt-1">{{$message}}</p>
            @enderror
            </div>
          </div> 


          <div class="dropdown-divider"></div>
          <div class="text-center bg-info">To</div>
          <div class="dropdown-divider"></div>

          <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="uacsidrealign2">UACS :<span class="text-danger">*</span> </label>
            <div class="col-sm-10">
              <select name="uacsidrealign2" class="form-control select2" style="width: 100%;">
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
            <label class="col-sm-2 col-form-label" for="office2">Office </i> :  <span
                    class="text-danger">*</span> </label>
            <div class="col-sm-10">
                <select name="office2" id="office2" class="form-control select2"
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


          {{-- <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="papidrealign2">PAP :<span class="text-danger">*</span> </label>
            <div class="col-sm-10">
              <select name="papidrealign2" id="papidrealign2" class="form-control select2" style="width: 100%;">
                <option value="" disabled selected>-- Choose PAP --</option>
                @foreach($PAPs as $PAP)
                <option value="{{$PAP->id}}">{{ $PAP->pap }}</option>
                @endforeach
              </select>
            </div>
         
            @error('papidrealign2')
            <p class="text-danger text-xs mt-1">{{$message}}</p>
            @enderror
          </div>

          <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="yearrealign2">Year : <span class="text-danger">*</span> </label>
            <div class="col-sm-10">
              <select name="yearrealign2" id="yearrealign2" class="form-control select2" style="width: 100%;">
                <option value="" disabled selected>-- Choose Year --</option>
              </select>
            </div>
         
            @error('yearrealign2')
            <p class="text-danger text-xs mt-1">{{$message}}</p>
            @enderror
          </div>

        
        <div class="form-group row">
          <label class="col-sm-2 col-form-label" for="uacsidrealign2">UACS :<span class="text-danger">*</span> </label>
          <div class="col-sm-10">
            <select name="uacsidrealign2" id="uacsidrealign2" class="form-control select2" style="width: 100%;">
              <option value="" disabled selected>-- Choose UACS --</option>
            </select>
          </div>
       
          @error('uacsidrealign2')
          <p class="text-danger text-xs mt-1">{{$message}}</p>
          @enderror
        </div>

                              
        <div class="form-group row">
          <label class="col-sm-2" for="rem_balrealign2">Remaining Balance :<span class="text-danger">*</span></label>
          <div class=" col-sm-10">
          <input name="rem_balrealign2" id="rem_balrealign2" class="form-control" type="string" oninput="this.value = this.value.toUpperCase()" readonly>
          @error('rem_balrealign2')
          <p class="text-danger text-xs mt-1">{{$message}}</p>
          @enderror
          </div>
        </div>  --}}

          <div class="dropdown-divider"></div>

            <div class="form-group row">
              <label class="col-sm-2" for="amount">Amount :<span class="text-danger">*</span></label>
              <div class=" col-sm-10">
              <input name="amount" id="amount" class="form-control" type="number" step="0.01" placeholder="Enter Amount" oninput="this.value = this.value.toUpperCase()">
              @error('amount')
              <p class="text-danger text-xs mt-1">{{$message}}</p>
              @enderror
              </div>
            </div> 
            

   

          <div class="modal-footer">
            <button type="SUBMIT" class="btn gray btn-success"> Realign</button>
            <button type="button" class="btn gray btn-default" data-dismiss="modal"> Cancel </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
  