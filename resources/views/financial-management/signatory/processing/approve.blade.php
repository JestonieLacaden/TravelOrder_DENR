<div class="modal fade" id="approve-voucher-modal-xl{{ $Route->voucher->id }}" >
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Approve Voucher</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
    
        <form  method="POST" action="{{ route('fm.signatoryapprove',[$Route->Voucher->id ])}}" enctype="multipart/form-data">
         
          {{ csrf_field() }}
            <input type="text" hidden  name="sequenceid" id="sequenceid" value="{{$Route->sequenceid }}">
            <input type="text" hidden  name="fmid" id="fmid" value="{{$Route->voucher->id }}">
          <div class="modal-body">
             You sure you want to Approve Sequence ID: <b>{{$Route->sequenceid }}<b>?</div>
          <div class="modal-footer">
              <button type="button" class="btn gray btn-default" data-dismiss="modal"> Cancel </button>
              <button type="SUBMIT" class="btn gray btn-success"> Approve</button>
          </div>
      </form>
    </div>
  </div>
</div>
  