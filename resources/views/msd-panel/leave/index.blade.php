@extends('layouts.app')

@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Leave Management</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/">Home</a></li>
              <li class="breadcrumb-item active">MSD Management</li>
              <li class="breadcrumb-item active">Encoder</li>
              <li class="breadcrumb-item active">Leave Management</li>
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

            @if ($errors->any())
            <div class="alert alert-danger alert-dismissible">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              <h5><i class="icon fas fa-ban"></i> Failed to save!</h5>
              <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            </div>
            @endif
            @if(session()->has('message'))
            <div class="alert alert-success alert-dismissible">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              <h5><i class="icon fas fa-check"></i>    {{ session()->get('message') }}</h5>
           
            </div>
            @endif

            @if(session()->has('EventError'))
            <div class="alert alert-danger alert-dismissible">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              <h5><i class="icon fas fa-ban"></i> Failed to save!</h5>
              <ul>
                <li>Error : Date has record!</li>
             </ul>
            </div>
            @endif
            @if(session()->has('DateError1'))
            <div class="alert alert-danger alert-dismissible">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              <h5><i class="icon fas fa-ban"></i> Failed to save!</h5>
              <ul>
                <li>Error : Date Range must be on the same year!</li>
             </ul>
            </div>
            @endif
            @if(session()->has('SignatoryError'))
            <div class="alert alert-danger alert-dismissible">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              <h5><i class="icon fas fa-ban"></i> Failed to save!</h5>
              <ul>
                <li>Error : Signatory not Set! Please Contact Administrator!</li>
             </ul>
            </div>
            @endif
          

          <div class="card">
            <div class="card-header">
              @can('create', \App\Models\Leave::class)
              <button type="button" class="btn btn-default" data-toggle="modal" data-target="#new-leave-modal-lg"  data-backdrop="static" data-keyboard="false">
                <i class="fas fa-plus" ></i>
                {{ __ ('Add Leave')}}
              </button>
              @endcan
            </div>
          </div>
            <!-- /.card-header -->
          <div class="card">
            <div class="card-body">
              <form method="POST" action="{{ route('leave.advance') }}" enctype="multipart/form-data">

                {{ csrf_field() }}
                <div class="card">
                  <div class="card-body">
                    <div class="form-group row">
                      <label class="col-sm-3" for="employeeid">Employee Name : <span class="text-danger">*</span></label>
                      <div class="col-sm-9">
                        <select id="employeeid"  name="employeeid" class="form-control select2" aria-placeholder="-- Choose Employee Name --" style="width: 100%;">
                        @foreach($Employees as $Employee)
                          <option value = "{{ $Employee->id }}">{{ $Employee->lastname . ', ' . $Employee->firstname . ' ' . $Employee->middlename }}</option>
                          @endforeach
                        </select>
                        @error('employeeid')
                          <p class="text-danger text-xs mt-1">{{$message}}</p>
                        @enderror
                      </div>
                    </div>
                    <div>
                      <button type="submit" class="btn btn-success float-right">
                          <i class="fas fa-search" ></i>
                          {{ __ ('Search')}}
                      </button>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>

          <div class="card">
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                 <thead>
                <tr>
                  <th class="text-center">Employee Name</th>
                  <th class="text-center">Date Range</th>
                  <th class="text-center">Type of Leave</th>
                  <th class="text-center">Status</th>
                  <th class="text-center">Created By</th>
                  <th class="text-center">Action</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($Leaves as $Leave)
                   
                      <tr>
                        <td>{{ $Leave->employee->lastname .', '. $Leave->employee->firstname . ' ' . $Leave->employee->middlename }}</td>
                        <td>{{ $Leave->daterange}}</td>
                        <td>{{ $Leave->leave_type->leave_type }}</td>

                        <td>
                          @if($Leave->is_approve1 == true)
                            @if($Leave->is_rejected1 == false)
                              @if($Leave->is_approve2 == true)
                                @if($Leave->is_rejected2 == false)
                                  @if($Leave->is_approve3 == true)
                                    @if($Leave->is_rejected3 == false)
                                    <span class="bg-success p-2 rounded">Approved</span> 
                                    @endif
                                  @else
                                  @if ($Leave->is_rejected3 == true)
                                  <span class="bg-danger p-2 rounded">Rejected by PENRO</span>
                                @else
                                  <span class="bg-warning p-2 rounded">Pending : Final Approval</span>
                              @endif  
                                  @endif
                                @endif

                              @else
                                @if ($Leave->is_rejected2 == true)
                                  <span class="bg-danger p-2 rounded">Rejected by MSD - CHIEF</span>
                                @else
                                  <span class="bg-warning p-2 rounded">Pending : 2nd Approval</span>
                              @endif  
                              @endif
                            @endif 
                          @else
                            @if ($Leave->is_rejected1 == true)
                            <span class="bg-danger p-2 rounded">Rejected by AO</span>
                            @else
                            <span class="bg-warning p-2 rounded">Pending : 1st Approval</span>
                            @endif  
                          @endif
                        </td>
                      
                        <td>{{ $Leave->user->username}}</td>          
                             <td class="text-center">   
                         
                            @can('delete', $Leave)
                            <button type="button" class="btn btn-default" title="Delete" data-toggle="modal"  data-target="#delete-leave-modal-lg{{ $Leave->id }} " data-backdrop="static" data-keyboard="false">
                              <i class="fas fa-trash-alt"></i>
                             {{__('Delete')}}
                            </button>
                            @endcan
                            @can('print', $Leave)
                            <a href="{{ route('leave.print',[$Leave->id]) }}" rel="noopener" target="_blank" class="btn btn-default"><i class="fas fa-print" ></i> Print</a>
                            @endcan
                        </td> 
                      </tr>
           
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
  
@if(!empty($Leaves))
@foreach($Leaves as $Leave)
@include('msd-panel.leave.delete')
@endforeach
@endif
@include('msd-panel.leave.create')

@endsection

@section('specific-scipt')
        <!-- InputMask -->
        <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
        <script src="{{ asset('plugins/inputmask/jquery.inputmask.min.js') }}"></script>
        <!-- date-range-picker -->
        <script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
        <!-- Tempusdominus Bootstrap 4 -->
        <script src="{{ asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
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
        <!-- bs-custom-file-input -->
        <script src="{{asset('plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
     
            <script>
              $(function () {
                bsCustomFileInput.init();
              });
              </script>
          <!-- Page specific script -->
         
          <script>
            $(function () {
              $("#example1").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
              
              }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
           
            });

          
          </script>

          <script>
            $(function () {
              $('#daterange').daterangepicker()   
            });
          </script>
                    
@include('partials.flashmessage')
@endsection

@section('specific-layout')
 <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
 <link rel="stylesheet" href="{{ asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
 <!-- DataTables -->
 <link rel="stylesheet" href="{{asset('/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
 <link rel="stylesheet" href="{{asset('/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
 <link rel="stylesheet" href="{{asset('/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">  

@endsection


     
  

