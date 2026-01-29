<div class="modal fade" id="return-leave-modal-lg{{ $Leave->id }}" >
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header bg-warning">
          <h4 class="modal-title">Return Leave Request to User</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form  method="POST" action="{{ route('leave.return',[ $Leave->id])}}" enctype="multipart/form-data">

          {{ csrf_field() }}
          @method('PUT')

          <div class="modal-body">
             <p>You are about to return the <i class="text-bold">{{ $Leave->leave_type->leave_type }} </i> request of  <b>{{ $Leave->employee->firstname . ' ' . $Leave->employee->middlename . ' ' . $Leave->employee->lastname }}</b> for revision.</p>
             <p class="text-info"><i class="fas fa-info-circle"></i> The request will go back to the employee for corrections.</p>
             <div class="form-group">
                <label for="returnReason{{ $Leave->id }}">Reason for Return: <span class="text-danger">*</span></label>
                <textarea name="return_reason" id="returnReason{{ $Leave->id }}" class="form-control" rows="4" placeholder="Please specify what needs to be corrected or changed..." required></textarea>
             </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn gray btn-default" data-dismiss="modal"> Cancel </button>
              @can('reject', $Leave)
              <button type="SUBMIT" class="btn gray btn-warning"> Return to User</button>
            @endcan
            </div>
      </form>
    </div>
  </div></div>
