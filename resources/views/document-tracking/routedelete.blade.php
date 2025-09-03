<div class="modal fade" id="delete-route-modal-lg{{ $Route->id }}" >
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Delete</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form  method="POST" action="{{ route('route.destroy',[ $Route->id])}}" enctype="multipart/form-data">
            @method('DELETE')
          {{ csrf_field() }}
      
         
          <div class="modal-body">
             You sure you want to delete Route: <b>{{ $Route->office->office . ' - ' . $Route->section->section . ' - ' . $Route->unit->unit . ' by : ' . $Route->user->username  }} <b>?</div>
          <div class="modal-footer">
              <button type="button" class="btn gray btn-default" data-dismiss="modal"> Cancel </button>
              <button type="SUBMIT" class="btn gray btn-danger"> Delete</button>
          </div>
      </form>
    </div>
  </div></div>