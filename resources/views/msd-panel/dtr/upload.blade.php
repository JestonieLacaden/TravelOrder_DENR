<div class="modal fade" id="upload-dtr-modal-lg" >
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Upload DTR</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
         </div>
         {{-- {{ route('attachment.store') }} --}}
        <form  method="POST" action="{{ route('upload.dtr') }}" enctype="multipart/form-data">
         
          {{ csrf_field() }}
                  
          <div class="modal-body">
      
              {{-- <input name="documentid" id="documentid"  hidden readonly class="form-control" type="text"  value="{{ $Document->id }}" oninput="this.value = this.value.toUpperCase()"> --}}

            <div class="form-group row">
              <label class="col-sm-2" for="attachment">File : <span class="text-danger">*</span></label>
              <div class="input-group col-sm-10">
                <div class="custom-file">
                    <input type="file" id="uploadedDTR" accept="application/vnd.ms-word, application/vnd.ms-excel, application/vnd.ms-powerpoint,
                    text/plain, application/pdf, image/*" name="uploadedDTR" class="custom-file-input">
                    <label class="custom-file-label" for="uploadedDTR">Choose file</label>
                    @error('uploadedDTR')
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
     
    </div>
    </div>
  </div>
</div>

  