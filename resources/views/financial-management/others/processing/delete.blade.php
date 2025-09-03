<div class="modal fade" id="close-voucher-modal-lg{{ $Route->voucher->id }}" >
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Close Voucher</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
   
        <form  method="POST" action="{{ route('fmothers.close',[ $Route->voucher->id ])}}" enctype="multipart/form-data">
          {{ csrf_field() }}
            <input type="text" hidden  name="sequenceid" id="sequenceid" value="{{ $Route->sequenceid }}">
          <div class="modal-body">
             You sure you want to close Sequence ID : <b>{{ $Route->sequenceid }}<b>?</div>
          <div class="modal-footer">
              <button type="button" class="btn gray btn-default" data-dismiss="modal"> Cancel </button>
              <button type="SUBMIT" class="btn gray btn-danger"> Close</button>
          </div>
      </form>
    </div>
  </div></div>