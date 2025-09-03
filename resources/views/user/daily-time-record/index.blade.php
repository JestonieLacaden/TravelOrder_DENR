@extends('layouts.app')

@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">My Daily Time Record</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/">Home</a></li>
              <li class="breadcrumb-item active">My Daily Time Record</li>
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

            @if(session()->has('DTRError1'))
          <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fas fa-ban"></i> Failed to save!</h5>
            <ul>
              <li>Duplicate DTR Entry!</li>
           </ul>
          </div>
          @endif
          @if(session()->has('DTRError2'))
          <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fas fa-ban"></i> Failed to save!</h5>
            <ul>
              <li>Error: Date is Saturday!</li>
           </ul>
          </div>
          @endif
          @if(session()->has('DTRError3'))
          <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fas fa-ban"></i> Failed to save!</h5>
            <ul>
              <li>Error: Date is Sunday!</li>
           </ul>
          </div>
          @endif
          @if(session()->has('DTRError4'))
          <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fas fa-ban"></i> Failed to save!</h5>
            <ul>
              <li>Error : Date has record!</li>
           </ul>
          </div>
          @endif

          

          <div class="card">
            <div class="card-header">
                <div class="form-group">

                  <form method="POST" action="{{ route('my-daily-time-record.print') }}" enctype="multipart/form-data">

                    {{ csrf_field() }}
                 
                    <div class="form-group row">
                      <label class="col-sm-2" for="fromdate">DTR Date (from) :<span class="text-danger">*</span></label>
                      <div class=" col-sm-10">
                      <input name="fromdate" id="fromdate" class="form-control" type="date"  value={{ now() }} oninput="this.value = this.value.toUpperCase()">
                      @error('fromdate')
                      <p class="text-danger text-xs mt-1">{{$message}}</p>
                      @enderror
                      </div>
                  </div>
                  <div class="form-group row">
                      <label class="col-sm-2" for="todate">DTR Date (to) :<span class="text-danger">*</span></label>
                      <div class=" col-sm-10">
                      <input name="todate" id="todate" class="form-control" type="date"  value={{ now() }} oninput="this.value = this.value.toUpperCase()">
                      @error('todate')
                      <p class="text-danger text-xs mt-1">{{$message}}</p>
                      @enderror
                      </div>
                  </div>  
                 
                    <div class="mt-2">
                      <button type="submit"  class="btn btn-primary float-right" >Search</button>
                    </div>
                </div>
              </div>
            </div>
            <!-- /.card-header -->
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
  
{{-- @include('msd-panel.dtr.print') --}}

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
    <!-- InputMask -->
    <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('plugins/inputmask/jquery.inputmask.min.js') }}"></script>
    <!-- date-range-picker -->
    <script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="{{ asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>

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


     
  

