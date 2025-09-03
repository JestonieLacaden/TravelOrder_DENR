<div class="modal fade" id="accept-leave-modal-lg{{ $Leave->id }}" >
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Accept Leave</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form  method="POST" action="{{ route('leave.accept',[ $Leave->id])}}" enctype="multipart/form-data">
         
          {{ csrf_field() }}
          @method('PUT')
         
          <div class="modal-body">
             You sure you want to accept <i class="text-bold">{{ $Leave->leave_type->leave_type }} </i>of  <b>{{ $Leave->employee->firstname . ' ' . $Leave->employee->middlename . ' ' . $Leave->employee->lastname }}<b>?</div>
          <div class="modal-footer">
              <button type="button" class="btn gray btn-default" data-dismiss="modal"> Cancel </button>
              @can('accept', $Leave)
              <button type="SUBMIT" class="btn gray btn-success"> Accept</button>
              @endcan
          </div>
      </form>
    </div>
  </div></div>
  
  