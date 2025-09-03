<div class="modal fade" id="close-document-modal-lg{{ $Route->document->id}}" >
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Close Document</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
    
        <form  method="POST" action="{{ route('route.close') }}" enctype="multipart/form-data">
         
          {{ csrf_field() }}
            <input type="text" hidden  name="documentid" id="documentid" value="{{$Route->document->PDN}}">
          <div class="modal-body">
             You sure you want to Close PDN: <b>{{ $Route->document->PDN }}<b>?</div>
          <div class="modal-footer">
              <button type="button" class="btn gray btn-default" data-dismiss="modal"> Cancel </button>
              {{-- @can('addAction', $Document) --}}
              <button type="SUBMIT" class="btn gray btn-danger"> Submit</button>
              {{-- @endcan --}}
          </div>
      </form>
    </div>
  </div></div>