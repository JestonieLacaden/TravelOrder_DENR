<div class="modal fade" id="create-activity-modal-lg" >
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Create Activity</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
    {{--  --}}
        <form  method="POST" action="{{ route('fmaccounting.activitycreate')}}" enctype="multipart/form-data">
         
          {{ csrf_field() }}

          <div class="modal-body">
      
            <div class="form-group row">
              <label  class="col-sm-2" for="activity">Activity Title : </label>
              <div class=" col-sm-10">
              <input name="activity" id="activity" class="form-control" type="text" placeholder="Enter Activity Title" value="{{old('activity')}}" oninput="this.value = this.value.toUpperCase()">
              @error('activity')
              <p class="text-danger text-xs mt-1">{{$message}}</p>
              @enderror
              </div>
          </div>
            
          <div class="modal-footer">
            <button type="SUBMIT" class="btn gray btn-success"> Create</button>
            <button type="button" class="btn gray btn-default" data-dismiss="modal"> Cancel </button>
          </div>
      </form>
    </div>
  </div></div>
  