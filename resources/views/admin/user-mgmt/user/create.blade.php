<!-- /.modal -->

<div class="modal fade" id="new-user-modal-lg" >
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">New User</h4>
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
                  <form method="POST" action="{{ route('user.store') }}" enctype="multipart/form-data">

                    {{ csrf_field() }}
                  <div class="card-body">
                    <div class="form-group">
                      <label for="email">Email Address</label>
                      <select id="email"  name="email" class="form-control select2" style="width: 100%;">
                        <option value=""  selected>-- Choose Email --</option>
                       @foreach($Emails as $Email)
                        
                         <option value = "{{ $Email->email }}">{{ $Email->email }}</option>
                        @endforeach
                      </select>
                      @error('email')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                      @enderror
                  </div>
                    {{-- @livewire('email-employee') --}}
                      <div class="form-group">
                          <label for="username">Username</label>
                          <input name="username" id="username" class="form-control" type="text" placeholder="Enter Username">
                      </div>
                      @error('username')
                      <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror        
                      <div class="form-group">
                          <label for="password">Password</label>
                          <input type="password" name="password" class="form-control" id="password" placeholder="Password">
                      </div>
                      <div class="form-group">
                        <label for="password_confirmation">Confirm Password</label>
                        <input name="password_confirmation" id="password_confirmation" type="password" class="form-control" placeholder="Password">
                    </div>
                    
  
                 {{-- <input type="text" name="employeeid" id="employeeid" value="{{$Emails->employeeid}}" hidden> --}}
                      {{-- <input type="text" name="empid" id="empid"  hidden>  --}}
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
        {{-- <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
        </div> --}}
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->
