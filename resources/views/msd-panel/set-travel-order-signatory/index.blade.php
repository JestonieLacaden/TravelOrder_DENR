@extends('layouts.app')

@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Set Travel Order Signatory</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/">Home</a></li>
              <li class="breadcrumb-item active">MSD Management</li>
              <li class="breadcrumb-item active">Settings</li>
              <li class="breadcrumb-item active">Travel Order Settings</li>
              <li class="breadcrumb-item active">Set Travel Order Signatories</li>
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

            @if(session()->has('SetSignatoryError'))
            <div class="alert alert-danger alert-dismissible">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              <h5><i class="icon fas fa-ban"></i> Failed to save!</h5>
              <ul>
                <li>Error : Office / Section already had a Travel Order Signatory!</li>
             </ul>
            </div>
            @endif

          <div class="card">
            <div class="card-header">

            @can('create', \App\Models\SetTravelOrderSignatory::class)
            <button type="button" class="btn btn-default" data-toggle="modal" data-target="#create-signatory-modal-lg"  data-backdrop="static" data-keyboard="false">
                <i class="fas fa-plus" ></i>
                {{ __ ('Add Signatory')}}
              </button>         
            @endcan
           
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                 <thead>
                <tr>
                  <th class="text-center">Office Name</th>
                  <th class="text-center">Section Name</th>
                  <th class="text-center">Travel Order Signatory</th>
                  <th class="text-center">Action</th>
                </tr>
                </thead>
                <tbody>
                    @if(!empty($SetTravelOrderSignatories))
                        @foreach($SetTravelOrderSignatories as $SetTravelOrderSignatory)
                            <tr>        
                                <td>{{ $SetTravelOrderSignatory->office->office}}</td>    
                                <td>{{ $SetTravelOrderSignatory->section->section}}</td> 
                                <td class='text-center'> <span class ="bg-success p-2 rounded ml-1"> {{ $SetTravelOrderSignatory->TravelOrderSignatory->name }}</span></td>
                                <td class="text-center">   
                                    @can('edit', \App\Models\SetTravelOrderSignatory::class)
                                    <button type="button" class="btn btn-default" title="Delete" data-toggle="modal"  data-target="#edit-signatory-modal-lg{{ $SetTravelOrderSignatory->id }} " data-backdrop="static" data-keyboard="false">
                                    <i class="fas fa-edit"></i>
                                    {{__('Update')}}
                                    </button>
                                    @endcan 
                                </td> 
                            </tr>   
                            @include('msd-panel.set-travel-order-signatory.edit') 
                        @endforeach
                    @endif 
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
@include('msd-panel.set-travel-order-signatory.create');
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

@can('create', \App\Models\SetTravelOrderSignatory::class)
@include('partials.flashmessage')
@endcan
@endsection

@section('specific-layout')
 <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
 <link rel="stylesheet" href="{{ asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
 <!-- DataTables -->
 <link rel="stylesheet" href="{{asset('/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
 <link rel="stylesheet" href="{{asset('/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
 <link rel="stylesheet" href="{{asset('/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">  

@endsection


     
  

