<div class="modal fade" id="delete-cashier-modal-lg{{ $FMCashier->id }}" >
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Delete Voucher</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
    
        <form  method="POST" action="{{ route('fmcashier.cashierdelete',[$FMCashier->id ])}}" enctype="multipart/form-data">
          {{ csrf_field() }}
            <input type="text" hidden  name="sequenceid" id="sequenceid" value="{{$FMCashier->id }}">
          <div class="modal-body">
             You sure you want to delete Cashier info with LDDAP Number : <b>{{ $FMCashier->lddapno }}<b> , Account Number of : <b>{{ $FMCashier->accountno }}<b> and  ADA Number of : <b>{{ $FMCashier->adano }}<b>? </div>
          <div class="modal-footer">
              <button type="button" class="btn gray btn-default" data-dismiss="modal"> Cancel </button>
              <button type="SUBMIT" class="btn gray btn-danger"> Delete</button>
          </div>
      </form>
    </div>
  </div></div>