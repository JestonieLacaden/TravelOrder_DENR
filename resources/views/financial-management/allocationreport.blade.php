@extends('layouts.app')

@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Per Activity Report</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/">Home</a></li>
              <li class="breadcrumb-item active">Financial Management System</li>
              <li class="breadcrumb-item active">Per Activity Report</li>
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

          <div class="card">
        
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                 <thead>
                <tr>
                  
                  <th  class="text-center">Year</th>
                  <th  class="text-center">Program</th>
                  <th  class="text-center">Expense Class</th>
                  <th  class="text-center">Activity </th>
                  <th  class="text-center">Office</th>
                  <th  class="text-center">Allotment</th>
                  <th  class="text-center">Obligation</th>
                  <th  class="text-center">Disbursement</th>
                  <th  class="text-center">Balance</th>
                  <th  class="text-center">%Obligation/Allotment</th>
                  <th  class="text-center">%Disbursement/Obligation</th>
                  <th  class="text-center">%Disbursement/Allotment</th>
                  {{-- <th  class="text-center">Action</th> --}}
                </tr>
                </thead>
                <tbody>
                  @foreach($ActivityReports as $Activity)
                  
                  
                    <tr> 
                    <td> {{ $Activity[0] }}</td>
                    <td> {{ $Activity[6] }}</td>
                    <td> {{ $Activity[10] }}</td>
                    <td> {{ $Activity[1] }}</td>
                    <td> {{ $Activity[9] }}</td>
                    <td class="text-center"> {{ $Activity[2] }}</td>
                    <td class="text-center">{{ $Activity[4] }}  </td>
                    <td class="text-center"> {{ $Activity[7] }}</td>
                    <td class="text-center">{{ $Activity[3] }}  </td>
                    <td class="text-center">{{ $Activity[5] }}  </td>
                    <td class="text-center"> {{ $Activity[8] }}</td>
                    <td class="text-center"> {{ $Activity[11] }}</td>

            
                    </tr> 
   
                  @endforeach

                    {{-- <!-- @foreach($Vouchers as $Voucher)
                    <tr>
                      
                      <td class="text-center">{{$Voucher->datereceived}}</td>
                      <td> <a href="{{ route('financial-management.view',[$Voucher->id]) }}"> {{ $Voucher->sequenceid }}  </a></td>
                      <td> {{ $Voucher->AccountName->acct_name }}</td>
                      <td> {{ $Voucher->office }}</td>
                      <td> {{ $Voucher->particulars }}</td>
                      <td> {{ $Voucher->remarks }}</td>
                      <td> {{ number_format($Voucher->amount,2,'.',',') }}</td> --}}
                    
                      {{-- <td class="text-center">                    --}}
                        {{-- @can('view', $Document)
                            <button type="button" class="btn btn-default" onClick="location.href=''" style="margin-right: 5px;">
                              <i class="fas fa-eye"></i>View
                            </button>
                        @endcan
                        
                        @can('update', $Document)

                        <a href="{{ route('document-tracking.edit',[$Document->id]) }}" class="btn btn-default"><i class="fas fa-edit" ></i> Edit</a>
                          
                        @endcan
                        @can('print', $Document)
                            <a href="{{ route('document-tracking.print',[$Document->id]) }}" rel="noopener" target="_blank" class="btn btn-default"><i class="fas fa-print" ></i> Print</a>
                        @endcan --}}
                  
                          
                      {{-- </td> --}}
        
                    {{-- @endforeach  --> --}}
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
          <script src="{{asset('/plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
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
          <script>
            $(function () {
              $("#example1").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
                "buttons": ["excel"]
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



      
     
  

