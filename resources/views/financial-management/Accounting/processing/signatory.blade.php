<div class="modal fade" id="add-signatory-modal-lg{{ $Route->Voucher->id }}" >
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add Signatory on BOX D</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
  
        <form  method="POST" action="  {{ route('fmaccounting.updatesignatory',[$Route->Voucher->id ])}}" enctype="multipart/form-data">
         
          {{ csrf_field() }}

          @method('PUT')
          
          <div class="modal-body">

            <input type="text" hidden  name="fmid" id="fmid" value="{{$Route->Voucher->id }}">

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="signatory_id">Signatory : <span class="text-danger">*</span> </label>
                <div class="col-sm-10">
                  <select name="signatory_id" id="signatory_id" class="form-control select2" style="width: 100%;">
                    <option value="" disabled selected>-- Choose Signatory --</option>
                    @foreach($BoxAs as $BoxA)
                    <option value="{{$BoxA->id}}">{{ $BoxA->certified_by . ' - ' . $BoxA->position }}</option>
                    @endforeach
                  </select>
                </div>
            
                @error('signatory_id')
                <p class="text-danger text-xs mt-1">{{$message}}</p>
                @enderror
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