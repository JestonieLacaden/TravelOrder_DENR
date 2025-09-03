
<div class="modal fade" id="delete-TravelOrder-modal-lg{{ $TravelOrder->id }}" >
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Delete Travel Order</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form  method="POST" action="{{ route('travel-order.destroy',[ $TravelOrder->id])}}" enctype="multipart/form-data">
        {{ csrf_field() }}
        @method('DELETE')
        <div class="modal-body">
          You sure you want to delete travel order of <b>{{ $TravelOrder->employee->firstname . ' ' . $TravelOrder->employee->middlename . ' ' . $TravelOrder->employee->lastname }}<b>?</div>
      <div class="modal-footer">
          <button type="button" class="btn gray btn-default" data-dismiss="modal"> Cancel </button>
          @can('delete', $TravelOrder)
          <button type="SUBMIT" class="btn gray btn-danger"> Delete</button>
        @endcan
        </div>
      </form>
    </div>
  </div>
</div>
  
  