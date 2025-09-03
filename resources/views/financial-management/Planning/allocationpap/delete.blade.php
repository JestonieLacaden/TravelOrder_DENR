<div class="modal fade" id="delete-allocationpap-modal-lg{{ $Allocation->id }}" >
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Delete Allocation per PAP (GAA)</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
    
        <form  method="POST" action="{{ route('fmplanning.allocationdeletePAP',[$Allocation->id ])}}" enctype="multipart/form-data">
          {{ csrf_field() }}
          <div class="modal-body">
             You sure you want to Delete Allocation: <b>{{ $Allocation->PAP->pap }}</b> with the amount of <b>{{ number_format($Allocation->amount,2,'.',',')  }} </b> for the year <b> {{ $Allocation->year}} ?</b></div> 
          <div class="modal-footer">
              <button type="button" class="btn gray btn-default" data-dismiss="modal"> Cancel </button>
              <button type="SUBMIT" class="btn gray btn-danger"> Delete</button>
          </div>
      </form>
    </div>
  </div></div>