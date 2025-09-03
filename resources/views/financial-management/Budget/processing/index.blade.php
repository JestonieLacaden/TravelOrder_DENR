@extends('financial-management.budget.index')

@section('fm-content')

<div class="col-md-9">
    <div class="card-header bg-primary">
      <h3 class="card-title ">Processing Voucher(s)  </h3>
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
            <th  class="text-center">Accepted By</th>
            <th  class="text-center">Action</th>
          </tr>
          </thead>
          <tbody>
          @foreach ($Routes as $Route)
            <tr>
              <td>{{ $Route->actiondate }}</td>
              <td>{{ $Route->sequenceid }}</td>
              <td>{{ $Route->AccountName->acct_name }}</td>
              <td>{{ $Route->voucher->office }}</td>
              <td>{{ $Route->voucher->particulars }}</td>
              <td>{{ $Route->voucher->remarks }}</td>
              <td>{{number_format($Route->voucher->amount,2,'.',',')  }}</td>
              <td>{{ $Route->user->username }}</td>
              <td class="text-center">
                <button type="button" title="View Voucher" class="btn btn-default" onClick="location.href='{{ route('financial-management.view',[$Route->voucher->id]) }}'">
                  <i class="fas fa-eye"></i>
                </button>
                <button type="button" title="Add ORS" class="btn btn-warning" data-toggle="modal" data-target="#add-ors-modal-lg{{ $Route->voucher->id }}"  data-backdrop="static" data-keyboard="false">
                  <i class="fas fa-coins"></i>
                </button>
                <button type="button" title="Route Document" class="btn btn-success" data-toggle="modal" data-target="#route-voucher-modal-xl{{ $Route->voucher->id }}"  data-backdrop="static" data-keyboard="false">
                  <i class="fas fa-route"></i>
                </button>
              </td>
              </tr>
             @include('financial-management.budget.processing.ors')
             @include('financial-management.route')
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
 <script type="text/javascript">
  $(document).ready(function () {
      $('#papid').on('change', function () {
          var papid = this.value;
          $('#activity').html('');
          $('#activity').append('<option value="">Fetching Activity List</option>');
          $.ajax({
              url: '{{ route('fmplanning.getActivity') }}?papid='+papid,
              type: 'GET',
              success: function (res) {
                  $('#activity').html('<option value="">-- Choose Activity --</option>');
                  $.each(res, function (key, value) {
                      $('#activity').append('<option value="' + value
                          .id + '">' + value.activity + '</option>');
                  });
              }
          });
      });
  });
</script>
@endsection

@section('mails-layout')
<link rel="stylesheet" href="{{asset('/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{asset('/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{asset('/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">  
@endsection


     
  

