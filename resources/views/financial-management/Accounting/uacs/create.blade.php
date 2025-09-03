<div class="modal fade" id="create-uacs-modal-lg" >
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Create UACS</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
    {{--  --}}
        <form  method="POST" action="{{ route('fmaccounting.uacscreate')}}" enctype="multipart/form-data">
         
          {{ csrf_field() }}

          <div class="modal-body">

            <div class="form-group row">
              <label class="col-sm-2 col-form-label" for="a_activity_id">Account Title : <span class="text-danger">*</span> </label>
              <div class="col-sm-10">
                <select name="a_activity_id" class="form-control select2" style="width: 100%;">
                  <option value="" disabled selected>-- Choose Account Title --</option>
                  @foreach($Activities as $Activity)
                  <option value="{{$Activity->id}}">{{ $Activity->activity }}</option>
                  @endforeach
                </select>
              </div>
           
              @error('a_activity_id')
              <p class="text-danger text-xs mt-1">{{$message}}</p>
              @enderror
            </div>

      
            <div class="form-group row">
              <label  class="col-sm-2" for="uacs">UACS : </label>
              <div class=" col-sm-10">
              <input name="uacs" id="uacs" class="form-control" type="text" placeholder="Enter UACS" value="{{old('uacs')}}" oninput="this.value = this.value.toUpperCase()">
              @error('uacs')
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
  