<div class="modal fade" id="reject-document-modal-lg{{ $Route->document->id  }}" >
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Reject Document</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
    
        <form  method="POST" action="{{ route('route.rejectIncoming',[ $Route->document->id])}}" enctype="multipart/form-data">
         
          {{ csrf_field() }}
            <input type="text" hidden  name="documentid" id="documentid" value="{{$Route->document->PDN}}">
          <div class="modal-body">
             You sure you want to Reject PDN: <b>{{ $Route->document->PDN }}<b>?</div>
          <div class="modal-footer">
              <button type="button" class="btn gray btn-default" data-dismiss="modal"> Cancel </button>
              <button type="SUBMIT" class="btn gray btn-danger"> Reject</button>
          </div>
      </form>
    </div>
  </div></div>
  