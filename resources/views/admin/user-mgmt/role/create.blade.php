<!-- /.modal -->

<div class="modal fade" id="new-role-modal-lg" >
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">New Role</h4>
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
                    <h3 class="card-title">Role Information</h3>
                
                  </div>
                  
                  <!-- /.card-header -->
                  <!-- form start -->
                  
                  <form method="POST" action="{{ route('role.store') }}" enctype="multipart/form-data">

                    {{ csrf_field() }}
                      <div class="card-body">
                        <div class="form-group">
                          <label for="userid">Username :</label>
                          <select id="userid"  name="userid" data-placeholder="Choose Username"  class="form-control select2" style="width: 100%;">

                          @foreach($Users as $User)
                            @if($User->id != '1')    
                            <option value = "{{ $User->id }}">{{ $User->email }}</option>
                            @endif
                            @endforeach
                          </select>
                          @error('userid')
                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                          @enderror
                      </div> 
                      <div class="form-group">
                        <label for="roleid[]">Role</label>
                        <select name="roleid[]" id="roleid[]" multiple="multiple" class="form-control select2" data-placeholder="Choose Role" style="width: 100%;">
                          @foreach($Roles as $Role)
                           
                          <option value = "{{ $Role->id }} ">{{ $Role->rolename }}</option>
                     
                          @endforeach
                        </select>
                        @error('roleid[]')
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
