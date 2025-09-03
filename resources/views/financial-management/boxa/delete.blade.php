<div class="modal fade" id="delete-certified-modal-lg{{ $BoxA->id }}" >
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Delete Account Name</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
    
        <form  method="POST" action="{{ route('financial-management.boxadelete',[$BoxA->id ])}}" enctype="multipart/form-data">
          {{ csrf_field() }}
            <input type="text" hidden  name="id" id="id" value="{{$BoxA->id }}">
          <div class="modal-body">
             You sure you want to Delete Certified : <b>{{ $BoxA->certified_by }}<b>?</div>
          <div class="modal-footer">
              <button type="button" class="btn gray btn-default" data-dismiss="modal"> Cancel </button>
              <button type="SUBMIT" class="btn gray btn-danger"> Delete</button>
          </div>
      </form>
    </div>
  </div></div>