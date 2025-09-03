@extends('layouts.app')

@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Daily Time Record</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/">Home</a></li>
              <li class="breadcrumb-item active">MSD</li>
              <li class="breadcrumb-item active">Daily Time Record</li>
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
              <h5><i class="icon fas fa-ban"></i> Failed to Print!</h5>
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

            
            @if(session()->has('DtrError'))
            <div class="alert alert-danger alert-dismissible">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              <h5><i class="icon fas fa-ban"></i> Failed to save!</h5>
              <ul>
                <li>Error : Invalid Date Range!</li>
             </ul>
            </div>
            @endif
          

          <div class="card">
            <div class="card-header">

              @can('create', \App\Models\Dtr_History::class)    
              <a href="{{ route('daily-time-record.create') }}" class="btn btn-default"><i class="fas fa-plus" ></i> New DTR</a>
              @endcan


              @can('print', \App\Models\Dtr_History::class) 
                <button type="button" class="btn btn-default" data-toggle="modal" data-target="#print-dtr-modal-lg"  data-backdrop="static" data-keyboard="false">
                <i class="fas fa-eye" ></i>
                {{ __ ('View DTR')}}
              </button>          
              @endcan
              <button type="button" class="btn btn-default float-right"  
              value='Upload DTR' data-toggle="modal" data-target="#upload-dtr-modal-lg"  data-backdrop="static" data-keyboard="false">
                <i class="fas fa-upload"></i>
                Upload DTR
              </button> 
     
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                 <thead>
                <tr>
                  <th class="text-center">Date</th>
                  <th class="text-center">Employee Name</th>
                  <th class="text-center">Schedule</th>
                  <th class="text-center">Time</th>
                  <th class="text-center">Remarks</th>
                  <th class="text-center">Encoded By </th>
                   <th class="text-center">Action</th>
                </tr>
                </thead>
                <tbody>
                    {{-- @foreach($Dtrs as $Dtr)
                   
                      <tr>
                        <td class="text-center">{{ $Dtr->date}}</td>
                        <td class="text-center">{{ $Dtr->employee->firstname ." ".   $Dtr->employee->lastname}}</td>
                        <td class="text-center">{{ $Dtr->schedule}}</td>
                        <td class="text-center">{{ $Dtr->time}}</td>
                        <td class="text-center">{{ $Dtr->remarks}}</td>
                        <td class="text-center">{{ $Dtr->user->username}}</td>
                       
                        <td class="text-center">   
                            <a href="" class="btn btn-default"><i class="fas fa-edit" ></i> Edit</a>
                            <a href="" class="btn btn-default"><i class="fas fa-trash" ></i> Delete</a>
                                       
                        </td>
                      </tr>
                 
                 
                    @endforeach  --}}
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
@include('msd-panel.dtr.upload')
@include('msd-panel.dtr.print')

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
          <script src="{{asset('plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
          <script>
            $(function () {
              bsCustomFileInput.init();
            });
            </script>
         
          <script>
            $(function () {
              $("#example1").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
              
              }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
           
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


     
  

