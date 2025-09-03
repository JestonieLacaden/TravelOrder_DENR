<!-- /.modal -->

<div class="modal fade" id="edit-section-modal-lg{{ $Section->id }}"  >
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Update Section</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
          <div class="modal-body">
        
        
        <!-- Main content -->

        <section class="content">
          <div class="container-fluid">
            <div class="row">
              <!-- left column -->
              <div class="col-md-12">
                <!-- general form elements -->
                <div class="card card-primary">
                  <div class="card-header">
                    <h3 class="card-title">Section Information</h3>
                
                  </div>
                  
                  <!-- /.card-header -->
                  <!-- form start -->
                  <form method="POST" action="{{ route('section.update',[ $Section->id])}}" enctype="multipart/form-data">

                    {{ csrf_field() }}
                    @method('PUT')

                    <div class="card-body">
                      <div class="form-group">
                        <label for="officeid">Office <span class="text-danger">*</span></label>
                        <select name="officeid" class="form-control select2" style="width: 100%;">
                          {{-- <option value="{{ $Section->officeid }}" selected>{{ $Section->office->office }}</option> --}}
                          @foreach($Offices as $Office)
                          @if ($Office == '{{ $Section->office->office }}') {
                            <option value="{{$Office->id}}" selected>{{ $Office->office }}</option>
                          }
                          @endif
                          <option value="{{$Office->id}}">{{ $Office->office }}</option>
                          @endforeach
                        </select>
                        @error('officeid')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                        @enderror
                      </div>
                    
                      <div class="form-group">
                        <label for="section">Section Name</label>
                        <input name="section" id="section" class="form-control" type="text" placeholder="Enter Section Name" value="{{ $Section->section }}" oninput="this.value = this.value.toUpperCase()">
                        @error('section')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                        @enderror
                      </div>
                     
                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                      <button type="submit"  class="btn btn-primary">Submit</button>
                    </div>
                  </form>
                </div>
                <!-- /.card -->
              </div>
            </div>
          </div>
        </section>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->