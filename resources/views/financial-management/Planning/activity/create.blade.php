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
        <form  method="POST" action="{{ route('fmplanning.activitycreate')}}" enctype="multipart/form-data">
         
          {{ csrf_field() }}

          <div class="modal-body">

            <div class="form-group row">
              <label class="col-sm-2 col-form-label" for="papid">PAP : <span class="text-danger">*</span> </label>
              <div class="col-sm-10">
                <select name="papid" class="form-control select2" style="width: 100%;">
                  <option value="" disabled selected>-- Choose PAP --</option>
                  @foreach($PAPs as $PAP)
                  <option value="{{$PAP->id}}">{{ $PAP->pap }}</option>
                  @endforeach
                </select>
              </div>
           
              @error('papid')
              <p class="text-danger text-xs mt-1">{{$message}}</p>
              @enderror
            </div>
      
            <div class="form-group row">
              <label  class="col-sm-2" for="activity">Activity : <span class="text-danger">*</span> </label>
              <div class=" col-sm-10">
              <input name="activity" id="activity" class="form-control" type="text" placeholder="Enter Activity" value="{{old('activity')}}" oninput="this.value = this.value.toUpperCase()">
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
  