<div class="modal fade" id="delete-dv-modal-lg{{ $DV->id }}" >
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Delete Voucher</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
    
        <form  method="POST" action="{{ route('fmaccounting.dvdelete',[$DV->id ])}}" enctype="multipart/form-data">
          {{ csrf_field() }}
            {{-- <input type="text" hidden  name="sequenceid" id="sequenceid" value="{{$DV->id }}"> --}}
          <div class="modal-body">
             You sure you want to delete DV Number : <b>{{ $DV->dvno }}<b>?</div>
          <div class="modal-footer">
              <button type="button" class="btn gray btn-default" data-dismiss="modal"> Cancel </button>
              <button type="SUBMIT" class="btn gray btn-danger"> Delete</button>
          </div>
      </form>
    </div>
  </div></div>