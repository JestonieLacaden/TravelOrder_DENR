@extends('layouts.app')

@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Realignment Report</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/">Home</a></li>
              <li class="breadcrumb-item active">Financial Management System</li>
              <li class="breadcrumb-item active">Realignment Report</li>
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
                  
                  <th  class="text-center">Category</th>
                  <th  class="text-center">Program</th>
                  <th  class="text-center">Expense Class</th>
                  <th  class="text-center">Office</th>
                  <th  class="text-center">Year</th>
                  <th  class="text-center">UACS (from)</th>
                  <th  class="text-center">Office (from)</th>
                  <th  class="text-center">Balance Before (from)</th>
                  <th  class="text-center">UACS (To)</th>
                  <th  class="text-center">Office (To)</th>
                  <th  class="text-center">Amount</th>
                  <th  class="text-center">User</th>
                  <th  class="text-center">Date Created</th>
                  {{-- <th  class="text-center">Action</th> --}}
                </tr>
                </thead>

                <tbody>
                  @foreach ($Realignments as $Realignment)
                  <td class="text-center">{{ $Realignment->category}}</td>
                  <td class="text-center">{{ $Realignment->PAP->pap}}</td> 
                  <td class="text-center">{{ $Realignment->expense_class}}</td>
                  <td class="text-center">{{ $Realignment->office}}</td>
                  <td class="text-center">{{ $Realignment->year}}</td>
                  <td class="text-center">{{ $Realignment->UACSfrom->uacs}}</td>
                  <td class="text-center">{{ $Realignment->from_office}}</td>
                  <td class="text-center">{{number_format($Realignment->from_balance,2,'.',',')  }}</td>
                  <td class="text-center">{{ $Realignment->UACSto->uacs}}</td>
                  <td class="text-center">{{ $Realignment->to_office}}</td>
                  <td class="text-center">{{number_format($Realignment->realign_amount,2,'.',',')  }}</td>
                  <td class="text-center">{{ $Realignment->User->username}}</td>
                  <td class="text-center">{{ $Realignment->created_at}}</td>
                  @endforeach

                </tbody>
                {{-- <tbody>
                    <tr>
                        <td class="text-center">Accounting Section</td>
                        <td class="text-center">{{ $AccountingIncoming}}</td>
                        <td class="text-center">{{ $AccountingProcessing}}</td>
                        <td class="text-center">{{ $AccountingOutgoing}}</td>
                    </tr>
                    <tr>
                        <td class="text-center">Budget Section</td>
                        <td class="text-center">{{ $BudgetIncoming}}</td>
                        <td class="text-center">{{ $BudgetProcessing}}</td>
                        <td class="text-center">{{ $BudgetOutgoing}}</td>
                    </tr>
                    <tr>
                        <td class="text-center">Cashier Section</td>
                        <td class="text-center">{{ $CashierIncoming}}</td>
                        <td class="text-center">{{ $CashierProcessing}}</td>
                        <td class="text-center">{{ $CashierOutgoing}}</td>
                    </tr>
                    <tr>
                        <td class="text-center">Planning Section</td>
                        <td class="text-center">{{ $PlanningIncoming}}</td>
                        <td class="text-center">{{ $PlanningProcessing}}</td>
                        <td class="text-center">{{ $PlanningOutgoing}}</td>
                    </tr>
                    <tr>
                        <td class="text-center">Records Section</td>
                        <td class="text-center">{{ $RecordsIncoming}}</td>
                        <td class="text-center">{{ $RecordsProcessing}}</td>
                        <td class="text-center">{{ $RecordsOutgoing}}</td>
                    </tr>
                    <tr>
                        <td class="text-center">Office of the MSD Chief</td>
                        <td class="text-center">{{ $MsdIncoming}}</td>
                        <td class="text-center">{{ $MsdProcessing}}</td>
                        <td class="text-center">{{ $MsdOutgoing}}</td>
                    </tr>
                    <tr>
                        <td class="text-center">Office of the TSD Chief</td>
                        <td class="text-center">{{ $TsdIncoming}}</td>
                        <td class="text-center">{{ $TsdProcessing}}</td>
                        <td class="text-center">{{ $TsdOutgoing}}</td>
                    </tr>
                    <tr>
                        <td class="text-center">Office of the PENRO</td>
                        <td class="text-center">{{ $PenroIncoming}}</td>
                        <td class="text-center">{{ $PenroProcessing}}</td>
                        <td class="text-center">{{ $PenroOutgoing}}</td>
                    </tr>
                </tbody> --}}
                
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



      
     
  

