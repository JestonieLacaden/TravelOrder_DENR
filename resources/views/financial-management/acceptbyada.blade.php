<div class="modal fade" id="accept-bybatch-modal-lg{{ $Route->voucher->id }}" >
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Accept By LDDAP / ADA Number</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
    
        <form  method="POST" action="{{ route('fm.acceptbybatch',[$Route->Voucher->id ])}}" enctype="multipart/form-data">
         
          {{ csrf_field() }}
            <input type="text" hidden  name="batchid" id="batchid" value="{{ $Ada[1] }}">
          <div class="modal-body">
             You sure you want to Accept All Vouchers with LDDAP / ADA Number: <b>{{$Ada[1]}}</b>?</div>
          <div class="modal-footer">
              <button type="button" class="btn gray btn-default" data-dismiss="modal"> Cancel </button>
              <button type="SUBMIT" class="btn gray btn-success"> Accept</button>
          </div>
      </form>
    </div>
  </div></div>
  