<div class="modal fade" id="create-boxa-modal-lg">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Create Box A</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form method="POST" action="{{ route('financial-management.boxacreate')}}" enctype="multipart/form-data">

                {{ csrf_field() }}

                <div class="modal-body">

                    <div class="form-group row">
                        <label class="col-sm-2" for="certified_by">Certified By : </label>
                        <div class=" col-sm-10">
                            <input name="certified_by" id="certified_by" class="form-control" type="text"
                                placeholder="Enter Certified By" value="{{old('certified_by')}}"
                                oninput="this.value = this.value.toUpperCase()">
                            @error('certified_by')
                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2" for="position">Position : </label>
                        <div class=" col-sm-10">
                            <input name="position" id="position" class="form-control" type="text"
                                placeholder="Enter Position" value="{{old('position')}}"
                                oninput="this.value = this.value.toUpperCase()">
                            @error('position')
                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label" for="box">Box : <span class="text-danger">*</span> </label>
                        <div class="col-sm-10">
                          <select name="box" class="form-control select2" style="width: 100%;">
                            <option value="" disabled selected>-- Choose Box Signatory --</option>
                            <option value="A">BOX A</option>
                            <option value="D">BOX D</option>
                          </select>
                        </div>

                    <div class="modal-footer">
                        <button type="SUBMIT" class="btn gray btn-success"> Create</button>
                        <button type="button" class="btn gray btn-default" data-dismiss="modal"> Cancel </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


