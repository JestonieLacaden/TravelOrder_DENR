<!-- /.modal -->

<div class="modal fade" id="edit-user-modal-lg{{ $User->id }}"  >
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Update User</h4>
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
                    <h3 class="card-title">User Information</h3>
                
                  </div>
                  
                  <!-- /.card-header -->
                  <!-- form start -->
                  <form method="POST" action="{{ route('user.update',[ $User->id])}}" enctype="multipart/form-data">

                    {{ csrf_field() }}
                    @method('PUT')

                    <div class="card-body">
                      <div class="form-group">
                        <label for="email">Email Address</label>
                        <select id="email"  name="email" class="form-control select2" style="width: 100%;">
                          <option value=" {{$User->email}}" selected> {{$User->email}}</option>
                        </select>
                        @error('email')
                          <p class="text-danger text-xs mt-1">{{$message}}</p>
                        @enderror
                    </div>
             
                    <div class="form-group">
                            <label for="username">Username</label>
                            <input name="username" id="username" class="form-control" type="text" placeholder="Enter Username" value= "{{$User->username }}">
                        </div>
                       
                      <div class="form-group">
                            <label for="password">Password   </label>
                            <input type="password" name="password" class="form-control" id="password" placeholder="Password">
                      </div>
                        <div class="form-group">
                          <label for="password_confirmation">Confirm Password</label>
                          <input name="password_confirmation" id="password_confirmation" type="password" class="form-control" placeholder="Password">
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