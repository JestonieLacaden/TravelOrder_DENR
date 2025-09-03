@extends('layouts.app')

@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Employee Management</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/">Home</a></li>
              <li class="breadcrumb-item active">MSD</li>
              <li class="breadcrumb-item active">Employee Management</li>
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

            @can('create', \App\Models\Employee::class)
              <button type="button" class="btn btn-default" onClick="location.href='{{ route('employee.create') }}'"
              value='New Employee'>
                <i class="fas fa-user-plus"></i>
                New Employee
              </button>           
            @endcan
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                 <thead>
                <tr>
                  <th style="width: 20px" class="text-center">Employee ID</th>
                  <th class="text-center">Picture</th>
                  <th class="text-center">Employee Name</th>
                  <th class="text-center">Office</th>
                  <th class="text-center">Position</th>
                  <th class="text-center">Status</th>
                  <th style="width: 120px" class="text-center">Date Created</th>
                  <th style="width: 80px" class="text-center">Action</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($Employees as $Employee)
                   
                      <tr>
                        <td class="text-center">{{ $Employee->employeeid}}</td>
                        @if(!empty($Employee->picture))
                        <td><img  class="brand-image img-circle elevation-3" style="opacity: .8; height: 50px" src="{{ $Employee->picture ? asset('storage/'.  $Employee->picture ) : asset('storage/picture/user.png') }}" >  </td>
                        @else
                        <td></td>
                        @endif
                        <td>{{ $Employee->firstname . " " . $Employee->middlename . " " . $Employee->lastname }}</td>
                        <td>
                        
                            <span class="">{{ $Employee->office->office}}</span> , 
                            <span class="">{{ $Employee->section->section}}</span> ,
                            <span class="">{{ $Employee->unit->unit}}</span>
                        
                        </td>
                      
                        <td>{{ $Employee->position}}</td>
                        <td>{{ $Employee->empstatus}}</td>
                        <td>{{ $Employee->created_at}}</td>
                        <td class="text-center">

                          
                            @can('update', $Employee)
                                <button type="button" class="btn btn-default" title="Edit" style="background-color: #ff851b" onClick="location.href='{{ route('employee.edit', [$Employee->id]) }}'">
                                <i class="fas fa-edit" style="color:white"></i>
                                </button> 
                            @endcan
                            @can('delete', $Employee)
                                <button type="button" class="btn btn-default" title="Delete" style="background-color: #d81b60" data-toggle="modal"  data-target="#delete-employee-modal-lg{{ $Employee->id }} " data-backdrop="static" data-keyboard="false">
                                  <i class="fas fa-trash-alt" style="color:white"></i>
                                </button>
                            @endcan
                            {{-- @can('view', $Employee)
                          <button type="button" class="btn btn-default" title="View" style="background-color: #ff851b" onClick="location.href='{{ route('employee.view', [$Employee->id]) }}'">
                          <i class="fas fa-eye" style="color:white"></i>
                          </button> 
                        @endcan --}}
                        </td>
                      </tr>
                 
                    @include('admin.employee-mgmt.employee.delete')
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
          
          <!-- Page specific script -->
          <script>
            $(function () {
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
          


                    
@include('partials.flashmessage')
@endsection

@section('specific-layout')
 <!-- DataTables -->
 <link rel="stylesheet" href="{{asset('/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
 <link rel="stylesheet" href="{{asset('/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
 <link rel="stylesheet" href="{{asset('/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">  
@endsection


     
  

