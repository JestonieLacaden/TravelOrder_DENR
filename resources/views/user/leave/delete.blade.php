<div class="modal fade" id="delete-leave-modal-lg{{ $Leave->id }}" >
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Delete Leave</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      
        <form  method="POST" action="  {{ route('leave-management.destroy',[ $Leave->id])}}" enctype="multipart/form-data">
         
          {{ csrf_field() }}
          @method('DELETE')
         
          <div class="modal-body">
             You sure you want to delete <i class="text-bold">{{ $Leave->leave_type->leave_type }}</i> with date range : {{ $Leave->daterange }}<b>?</div>
          <div class="modal-footer">
              <button type="button" class="btn gray btn-default" data-dismiss="modal"> Cancel </button>
              @can('delete', $Leave)
              <button type="SUBMIT" class="btn gray btn-danger"> Delete</button>
            @endcan
            </div>
      </form>
    </div>
  </div></div>
  
  