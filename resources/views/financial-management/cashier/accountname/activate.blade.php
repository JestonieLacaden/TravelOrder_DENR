<div class="modal fade" id="activate-account-modal-lg{{ $AccountName->id }}" >
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Activate Account</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form  method="POST" action="{{ route('account.activate',$AccountName->id)}}" enctype="multipart/form-data">
         
          {{ csrf_field() }}
          @method('PUT')
         
          <div class="modal-body">
             You sure you want to activate Account Name : <b>{{ $AccountName->acct_name }}?</b></div>
          <div class="modal-footer">
              <button type="button" class="btn gray btn-default" data-dismiss="modal"> Cancel </button>
              <button type="SUBMIT" class="btn gray btn-success"> Activate</button>
          </div>
      </form>
    </div>
  </div>
</div>