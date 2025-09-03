@extends('layouts.app')

@section('content')

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Update Role</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Data Management</li>
            <li class="breadcrumb-item"><a href="{{ route('role.index') }}">Roles</a></li>
            <li class="breadcrumb-item active">Edit</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

   <!-- Main content -->
   <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
                 
                <div class="card-body">
                 

                    <form method="POST" action="{{ route('role.update',[ $User->id])}}" enctype="multipart/form-data">

                        {{ csrf_field() }}
                         @method('PUT')
                     
                            <div class="form-group">
                              <label for="userid">Username :</label>
                              <select id="userid"  name="userid" data-placeholder="Choose Username"  class="form-control select2" style="width: 100%;">
                                <option selected value = "{{ $User->id }}">{{ $User->username }}</option>
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
          </div>
        </div>
      </div>
    </div>
   </section>
</div>

@endsection

@section('specific-scipt')
   <!-- Select2 -->
   <script src="{{ asset('/plugins/select2/js/select2.full.min.js') }}"></script>

   <script>
     $(function () {
        $('.select2').select2()
     });
   </script>

@endsection

@section('specific-layout')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{asset('/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection



