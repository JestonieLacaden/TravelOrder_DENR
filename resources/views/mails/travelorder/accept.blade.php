<div class="modal fade" id="accept-travelorder-modal-lg{{ $TravelOrder->id }}" >
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Accept Travel Order</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form  method="POST" action="{{ route('travel-order.accept',[ $TravelOrder->id])}}" enctype="multipart/form-data">
         
          {{ csrf_field() }}
          @method('PUT')
         
          <div class="modal-body">
             You sure you want to accept Travel order of  <b>{{ $TravelOrder->employee->firstname . ' ' . $TravelOrder->employee->middlename . ' ' . $TravelOrder->employee->lastname }}<b>?</div>
          <div class="modal-footer">
              <button type="button" class="btn gray btn-default" data-dismiss="modal"> Cancel </button>
              @can('accept', $TravelOrder)
              <button type="SUBMIT" class="btn gray btn-success"> Accept</button>
              @endcan
          </div>
      </form>
    </div>
  </div></div>
  
  