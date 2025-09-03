@extends('financial-management.accounting.index')

@section('fm-content')

<div class="col-md-9">
    <div class="card-header bg-primary">
      <h3 class="card-title ">Incoming Voucher(s)  </h3>
      <h5></h5>
    </div>
    <!-- /.card-header -->
    <div class="card">       
      <!-- /.card-header -->
      <div class="card-body">
        <table id="example1" class="table table-bordered table-striped">
           <thead>
          <tr>
            <th  class="text-center">Date Created</th>
            <th  class="text-center">Sequence ID</th>
            <th  class="text-center">Payee</th>
            <th  class="text-center">Office</th>
            <th  class="text-center">Particulars </th>
            <th  class="text-center">Remarks </th>
            <th  class="text-center">Amount</th>
            <th  class="text-center">Route From</th>
            <th  class="text-center">LDDAP / ADA No. </th>
            <th  class="text-center">Type</th>
            <th  class="text-center">Action</th>
          </tr>
          </thead>
          <tbody>
          @foreach ($Routes as $Route)

          @if ($Route->action == 'FOR LDDAP / ADA SIGNATURE')
          <tr class="">
            <td>{{ $Route->actiondate }}</td>
            <td>{{ $Route->sequenceid }} </td>
            <td>{{ $Route->AccountName->acct_name }}</td>
            <td>{{ $Route->voucher->office }}</td>
            <td>{{ $Route->voucher->particulars }}</td>
            <td>{{ $Route->voucher->remarks }}</td>
            <td>{{number_format($Route->voucher->amount,2,'.',',')  }}</td>
            <td>{{ $Route->userunit->unit . ' by : ' . $Route->user->username }}</td>
            @if ($Route->action == 'FOR LDDAP / ADA SIGNATURE')
           

            <td>
              @if($FinalAda)
              
              @foreach ($FinalAda as $Ada)
                  @if ($Ada[0] == $Route->id)
                    <span> {{ $Ada[1] }}</span>  
                  @endif 
               @endforeach
              @endif
          </td>
            <td>FOR LDDAP / ADA SIGNATURE</td>
            @else
            <td></td>
            <td>VOUCHER</td>  
            @endif


            <td class="text-center">
              <button type="button" title="View Voucher" class="btn btn-default" onClick="location.href='{{ route('financial-management.view',[$Route->voucher->id]) }}'">
                <i class="fas fa-eye"></i>
              </button>
              @can('create', \App\Models\FMDV::class)
              <button type="button" title="Accept Voucher" class="btn btn-success" data-toggle="modal" data-target="#accept-signature-modal-lg{{ $Route->voucher->id }}"  data-backdrop="static" data-keyboard="false">
                <i class="fas fa-check"></i>
              </button>

              @if($FinalAda)
                  
              @foreach ($FinalAda as $Ada)
                  @if ($Ada[0] == $Route->id)
                  <button type="button" title="Accept All by LDDAP / ADA Number" class="btn btn-warning" data-toggle="modal" data-target="#accept-bybatch-modal-lg{{ $Route->voucher->id }}"  data-backdrop="static" data-keyboard="false">
                    <i class="fas fa-filter"></i>
                  </button>
                  @endif 
              @endforeach
              @endif


            @endcan
            @can('create', \App\Models\FMDV::class)
              <button type="button" title="Reject Voucher" class="btn btn-danger" data-toggle="modal" data-target="#reject-voucher-modal-lg{{ $Route->voucher->id }}"  data-backdrop="static" data-keyboard="false">
                <i class="fas fa-times"></i>
              </button>
              @endcan
            </td>
            </tr>

            @include('financial-management.accounting.incoming.signature')
            @include('financial-management.accounting.incoming.reject')
            @include('financial-management.acceptbyada')



          @else
          <tr>
            <td>{{ $Route->actiondate }}</td>
            <td>{{ $Route->sequenceid }}</td>
            <td>{{ $Route->AccountName->acct_name }}</td>
            <td>{{ $Route->voucher->office }}</td>
            <td>{{ $Route->voucher->particulars }}</td>
            <td>{{ $Route->voucher->remarks }}</td>
            <td>{{number_format($Route->voucher->amount,2,'.',',')  }}</td>
            <td>{{ $Route->userunit->unit . ' by : ' . $Route->user->username }}</td>
            <td></td>
            <td> VOUCHER</td>
            <td class="text-center">
              <button type="button" title="View Voucher" class="btn btn-default" onClick="location.href='{{ route('financial-management.view',[$Route->voucher->id]) }}'">
                <i class="fas fa-eye"></i>
              </button>
              @can('create', \App\Models\FMDV::class)
              <button type="button" title="Accept Voucher" class="btn btn-success" data-toggle="modal" data-target="#accept-voucher-modal-lg{{ $Route->voucher->id }}"  data-backdrop="static" data-keyboard="false">
                <i class="fas fa-check"></i>
              </button>
            @endcan
            @can('create', \App\Models\FMDV::class)
              <button type="button" title="Reject Voucher" class="btn btn-danger" data-toggle="modal" data-target="#reject-voucher-modal-lg{{ $Route->voucher->id }}"  data-backdrop="static" data-keyboard="false">
                <i class="fas fa-times"></i>
              </button>
              @endcan
            </td>
            </tr>
            @include('financial-management.accounting.incoming.accept')
            @include('financial-management.accounting.incoming.reject')
          @endif
            
            @endforeach
 
      
       
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endsection


@section('mails-script')
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
       "buttons": ['excel']
     }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
   });
 </script>
@endsection

@section('mails-layout')
<link rel="stylesheet" href="{{asset('/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{asset('/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{asset('/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">  
@endsection


     
  

