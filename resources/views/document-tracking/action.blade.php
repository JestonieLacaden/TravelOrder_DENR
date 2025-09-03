<div class="modal fade" id="add-action-modal-lg{{ $Document->id }}" >
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
            
            <input name="documentid" id="documentid"  hidden readonly class="form-control" type="text" value="{{ $Document->id }}" oninput="this.value = this.value.toUpperCase()">
          
        
          <div class="modal-body">
             You sure you want to close PDN: <b>{{ $Document->PDN }}<b>?</div>
          <div class="modal-footer">
              <button type="button" class="btn gray btn-default" data-dismiss="modal"> Cancel </button>
              @can('addAction', $Document)
              <button type="SUBMIT" id="submit" class="btn gray btn-success"> Accept</button>
              @endcan
          </div>
      </form>
    </div>
  </div></div>
  
  