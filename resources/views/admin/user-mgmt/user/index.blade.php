@extends('layouts.app')

@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">User Management</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/">Home</a></li>
              <li class="breadcrumb-item active">Data Management</li>
              <li class="breadcrumb-item active">User</li>
              <li class="breadcrumb-item active">User Management</li>
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
            @can('create', \App\Models\User::class)
              <button type="button" class="btn btn-default" data-toggle="modal" data-target="#new-user-modal-lg"  data-backdrop="static" data-keyboard="false">
                {{ __ ('New')}}
              </button>
            @endcan
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                 <thead>
                <tr>
                  <th style="width: 20px" class="text-center">User ID</th>
                  <th class="text-center">Employee Name</th>
                  <th  class="text-center">User Name</th>
                  <th  class="text-center">Email Address</th>
                  <th  class="text-center">Date Created</th>
                  <th style="width: 80px" class="text-center">Action</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($Users as $User)
                      @if($User->id != '1')
                    <tr>
                      <td class="text-center">{{$User->id}}</td>
                      <td>{{ $User->username }}</td>
                      <td>{{ $User->username }}</td>
                      <td>{{ $User->email }}</td>  
                      <td>{{ $User->created_at }}</td>
                      <td class="text-center">
                          @can('update', $User)
                              <button type="button" class="btn btn-default" title="Edit" style="background-color: #ff851b" data-toggle="modal"  data-target="#edit-user-modal-lg{{ $User->id }} " data-backdrop="static" data-keyboard="false">
                              <i class="fas fa-edit" style="color:white"></i>
                              </button>
                   
                          @endcan
                          @can('delete', $User)
                          @if($User->id != 1)
                              <button type="button" class="btn btn-default" title="Delete" style="background-color: #d81b60" data-toggle="modal"  data-target="#delete-user-modal-lg{{ $User->id }} " data-backdrop="static" data-keyboard="false">
                                <i class="fas fa-trash-alt" style="color:white"></i>
                              </button>
                          @endif
                          @endcan
                      </td>
                    </tr>
                    @include('admin.user-mgmt.user.edit')
                    @include('admin.user-mgmt.user.delete')
                    @endif
                    @endforeach 
                </tbody>
                
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>
  
@include('admin.user-mgmt.user.create')

@endsection

@section('specific-scipt')

          <!-- DataTables  & Plugins -->
          <script src="{{ asset('/plugins/datatables/jquery.dataTables.min.js') }}"></script>
          <script src="{{ asset('/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
          <script src="{{ asset('/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
          <script src="{{ asset('/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
          <script src="{{ asset('/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
          <script src="{{ asset('/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
          <script src="{{ asset('/plugins/jszip/jszip.min.js') }}"></script>
          <script src="{{ asset('/plugins/pdfmake/pdfmake.min.js') }}"></script>
          <script src="{{ asset('/plugins/pdfmake/vfs_fonts.js') }}"></script>
          <script src="{{ asset('/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
          <script src="{{ asset('/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
          <script src="{{ asset('/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
          <!-- Select2 -->
          <script src="../../plugins/select2/js/select2.full.min.js"></script>

          <!-- Page specific script -->
          <script>
            
            $(function () {

              $('.select2').select2()
          
              $("#example1").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
              }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
              $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
              });
            });
          </script>
       
          {{-- {{$error = Session::get('error') }}
          
          @if(!empty($error) )
            <script>
              $(function() {
                  $('#new-section-modal-lg').modal('show');
              });
            </script>
          @endif --}}
          
@include('partials.flashmessage')
@endsection

@section('specific-layout')
 <!-- DataTables -->
 <link rel="stylesheet" href="{{asset('/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
 <link rel="stylesheet" href="{{asset('/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
 <link rel="stylesheet" href="{{asset('/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">  
 <!-- Select2 -->
 <link rel="stylesheet" href="../../plugins/select2/css/select2.min.css">
 <link rel="stylesheet" href="../../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
@endsection



      
     
  

