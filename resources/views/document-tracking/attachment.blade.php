<div class="modal fade" id="attachment-document-modal-xl{{ $Document->id }}" >
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Attachment</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
         </div>
        <form  method="POST" action="{{ route('attachment.store', [$Document->id]) }}" enctype="multipart/form-data">
         
          {{ csrf_field() }}
                  
          <div class="modal-body">
      
              <input name="documentid" id="documentid"  hidden readonly class="form-control" type="text"  value="{{ $Document->id }}" oninput="this.value = this.value.toUpperCase()">

              <div class="form-group row">
                  <label  class="col-sm-2" for="attachmentdetails">Attachment Details : <span class="text-danger">*</span></label>
                  <div class=" col-sm-10">
                  <input name="attachmentdetails" id="attachmentdetails" class="form-control" type="text" placeholder="Enter Attachment Details" value="{{old('attachmentdetails')}}" oninput="this.value = this.value.toUpperCase()">
                  @error('attachmentdetails')
                  <p class="text-danger text-xs mt-1">{{$message}}</p>
                  @enderror
                  </div>
              </div>
            <div class="form-group row">
              <label class="col-sm-2" for="attachment">Attachment : <span class="text-danger">*</span></label>
              <div class="input-group col-sm-10">
                <div class="custom-file">
                    <input type="file" id="attachment" accept="application/vnd.ms-word, application/vnd.ms-excel, application/vnd.ms-powerpoint,
                    text/plain, application/pdf, image/*" name="attachment" class="custom-file-input">
                    <label class="custom-file-label" for="attachment">Choose file</label>
                    @error('attachment')
                    <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                </div>
                <div class="input-group-append">
                    <span class="input-group-text">Upload</span>
                </div>
              </div>
            </div>
            <div class="card-footer">
              <button type="submit" id="submit" class="btn btn-primary">Submit</button>
            </div>
          
        </form>
      </div>
      <div class="form-group row">
        <label  class="col-sm-2 text-center" for="attachmentdetails">Current Attachment :</label>
        <div class=" col-sm-10">
          @foreach($Document->attachment as $Attachment)
         <div class="col-sm-12" style="overflow: hidden; text-overflow: ellipsis;">
          <form  method="POST" action="{{ route('attachment.destroy', [$Attachment->id]) }}">
            {{$Attachment->attachmentdetails}} - <a href="{{ route('attachment.view', [$Attachment->id]) }} ">{{$Attachment->attachment}}</a>
            {{ csrf_field() }}
            @can('delete',$Attachment)
            @method('DELETE')
            <span>
              <button type="SUBMIT" class="btn btn-danger p-1 text-xs">
                Delete
             </button>   
            </span>  
            @endcan
          </form>

         </div>  
        {{-- </div>  --}}
          @endforeach
        </div>
    </div>
    </div>
  </div>
</div>

  