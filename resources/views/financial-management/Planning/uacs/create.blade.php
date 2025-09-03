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
        <form  method="POST" action="{{ route('fmplanning.uacscreate')}}" enctype="multipart/form-data">
         
          {{ csrf_field() }}

          <div class="modal-body">
      
            <div class="form-group row">
              <label  class="col-sm-2" for="uacs">UACS : </label>
              <div class=" col-sm-10">
              <input name="uacs" id="uacs" class="form-control" type="text" placeholder="Enter UACS" value="{{old('uacs')}}" oninput="this.value = this.value.toUpperCase()">
              @error('uacs')
              <p class="text-danger text-xs mt-1">{{$message}}</p>
              @enderror
              </div>
          </div>

          <div class="form-group row">
            <label  class="col-sm-2" for="description">Description : </label>
            <div class=" col-sm-10">
            <input name="description" id="description" class="form-control" type="text" placeholder="Enter Description" value="{{old('description')}}" oninput="this.value = this.value.toUpperCase()">
            @error('description')
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
  