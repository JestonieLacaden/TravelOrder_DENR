<div class="modal fade" id="add-review-document-modal-lg{{ $Route->Voucher->id }}" >
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Review of Documents</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
  
        <form  method="POST" action="  {{ route('fmaccounting.review',[$Route->Voucher->id ])}}" enctype="multipart/form-data">
         
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
            
          <div class="form-group">
            <div class="text-left pl-4">
                <input type="checkbox"  name="is_available">
             Cash Available
            </div>
    
          </div>
          <div class="form-group">

            <div class="text-left pl-4">
                <input type="checkbox" name="is_subject">
              Subject to Authority to Debit Account (when applicable)
            </div>

          </div>
          <div class="form-group">
        
            <div class="text-left pl-4">
                <input type="checkbox" name="is_supporting">
                Supporting Documents completed and amount clamied proper
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