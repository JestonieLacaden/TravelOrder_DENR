@extends('financial-management.cashier.index')

@section('fm-content')

<div class="col-md-9">
    <div class="card-header bg-primary">
      <h3 class="card-title ">Account Name(s) / Payee(s) </h3>
      <h5></h5>
    </div>
    <!-- /.card-header -->
    <div class="card">       
      <!-- /.card-header -->
      <div class="card-header">
        
        @can('create', \App\Models\FMCash::class)
        <button type="button" class="btn btn-default" data-toggle="modal" data-target="#create-accountname-modal-lg" data-backdrop="static" data-keyboard="false" >
          <i class="fas fa-plus"></i> New Account Name / Payee
        </button> 
        @endcan
      </div>
      <div class="card-body">
        <table id="example1" class="table table-bordered table-striped">
           <thead>
          <tr>
            <th  class="text-center">Account Name</th>
            <th  class="text-center">Address</th>
            <th  class="text-center">Tin Number</th>
            <th class="text-center">Action</th>
          </tr>
          </thead>
          <tbody>
           @foreach ($AccountNames as $AccountName)
            <tr>
              <td>{{ $AccountName->acct_name }}</td>
              <td>{{ $AccountName->address }}</td>
              <td class="text-center">{{ $AccountName->tinno }}</td>
              <td class="text-center">   
                
                @can('delete',$AccountName)
                <button type="button" title="Delete Account Name" class="btn btn-danger" data-toggle="modal" data-target="#delete-accountname-modal-lg{{ $AccountName->id }}"  data-backdrop="static" data-keyboard="false">
                  <i class="fas fa-trash"></i>
                </button>
                @endcan

                @if ($AccountName->is_active == true)
                @can('update',$AccountName)
                {{-- #delete-accountname-modal-lg{{ $AccountName->id }} --}}
                <button type="button" title="Deactivate Account" class="btn btn-warning" data-toggle="modal" data-target="#deactivate-account-modal-lg{{ $AccountName->id }}"  data-backdrop="static" data-keyboard="false">
                  Deactivate
                </button>
                @endcan

                @else
                @can('update',$AccountName)
              
                <button type="button" title=" Activate" class="btn btn-success" data-toggle="modal" data-target="  #activate-account-modal-lg{{ $AccountName->id }}"  data-backdrop="static" data-keyboard="false">
                  Activate
                </button>
                @endcan

                @endif

          
              </td>
              </tr>

            @endforeach          
          </tbody>
        </table>
      </div>
    </div>
  </div>


  @foreach ($AccountNames as $AccountName)
  @include('financial-management.cashier.accountname.delete') 
    @if ($AccountName->is_active == 1) 
      @include ('financial-management.cashier.accountname.deactivate')
    @else
    @include ('financial-management.cashier.accountname.activate')
    @endif

@endforeach


  @include('financial-management.cashier.accountname.create')

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


     
  

