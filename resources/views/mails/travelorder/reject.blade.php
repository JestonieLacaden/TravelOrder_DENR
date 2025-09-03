<div class="modal fade" id="reject-travelorder-modal-lg{{ $TravelOrder->id }}" >
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Reject Travel Order</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form  method="POST" action="{{ route('travel-order.reject',[ $TravelOrder->id])}}" enctype="multipart/form-data">
         
          {{ csrf_field() }}
          @method('PUT')
         
          <div class="modal-body">
             You sure you want to reject Travel Order of  <b>{{ $TravelOrder->employee->firstname . ' ' . $TravelOrder->employee->middlename . ' ' . $TravelOrder->employee->lastname }}<b>?</div>
          <div class="modal-footer">
              <button type="button" class="btn gray btn-default" data-dismiss="modal"> Cancel </button>
              @can('reject', $TravelOrder)
              <button type="SUBMIT" class="btn gray btn-danger"> Reject</button>
            @endcan
            </div>
      </form>
    </div>
  </div></div>
  
  