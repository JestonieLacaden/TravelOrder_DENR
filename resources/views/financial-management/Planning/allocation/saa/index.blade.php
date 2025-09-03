@extends('financial-management.planning.index')

@section('fm-content')

<div class="col-md-9">
    <div class="card-header bg-primary">
      <h3 class="card-title ">Allocation per Activity (SAA)</h3>
      <h5></h5>
    </div>
    <!-- /.card-header -->
    <div class="card">       
      <!-- /.card-header -->
      <div class="card-header">
        
        @can('create', \App\Models\Allocation::class)
        <button type="button" class="btn btn-default" data-toggle="modal" data-target="#create-allocation-modal-lg" data-backdrop="static" data-keyboard="false">
          <i class="fas fa-plus"></i> {{__('New Allocation') }}
        </button> 
        @endcan
      </div>
      <div class="card-body">
        <table id="example1" class="table table-bordered table-striped">
           <thead>
          <tr>
            <th  class="text-center">For Year</th>
            {{-- <th  class="text-center">Office</th> --}}
            <th  class="text-center">Program </th>
            <th  class="text-center">Expense Class </th>
            <th  class="text-center">Office </th>
            <th  class="text-center">Activity </th>
            <th  class="text-center">Allocation</th>
            <th  class="text-center">Balance</th>
            <th style="width: 100px"  class="text-center">Action</th>
          </tr>
          </thead>
          <tbody>
           @foreach ($Allocations as $Allocation)
            <tr>
              <td>{{ $Allocation->year }}</td>
              {{-- <td>{{ $Allocation->papoffice }}</td> --}}
              <td>{{ $Allocation->pap->pap}}</td>
              <td>{{ $Allocation->expense_class}}</td>
              <td>{{ $Allocation->office }}</td>
              <td>{{ $Allocation->Activity->activity }}</td>
              <td>{{ number_format($Allocation->amount,2,'.',',')  }}</td>
              <td>{{ number_format($Allocation->rem_bal,2,'.',',')  }}</td>
              <td class="text-center">   
                
                @can('create', \App\Models\Allocation::class)
                <button type="button" title="Delete Allocation" class="btn btn-danger" data-toggle="modal" data-target="#delete-allocation-modal-lg{{ $Allocation->id }}"  data-backdrop="static" data-keyboard="false">
                  <i class="fas fa-trash"></i>
                </button>
                @endcan
              </td>
              </tr>
              @include('financial-management.planning.allocation.saa.delete') 
            @endforeach          
          </tbody>
        </table>
      </div>
    </div>
  </div>

  @include('financial-management.planning.allocation.saa.create')

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
          console.log(papid);
          $('#activityid_allocation').html('');
          $('#activityid_allocation').append('<option value="">Fetching Activity List</option>');
          $.ajax({
              url: '{{ route('fmplanning.getActivityAllocationSAA') }}?papid='+papid,
              type: 'GET',
              success: function (res) {
                  $('#activityid_allocation').html('<option value="">-- Choose Activity --</option>');
                  $.each(res, function (key, value) {
                      $('#activityid_allocation').append('<option value="' + value
                          .id + '">' + value.activity + '</option>');
                  });
              }
          });
      });
  });
</script>

<script type="text/javascript">
  $(document).ready(function () {

$('#papid').on('change', function () {
  var papid = this.value;
  // $('#rem_bal').html('');

  $('#year').html('');
  $('#papoffice').html('');
  $('#rem_bal').html('');
  $('#expense_class').html('');
  $('#year').append('<option value="">-- Choose Year --</option>');
  $('#papoffice').append('<option value="">-- Choose Office --</option>');
  $('#expense_class').append('<option value="">-- Fetching Expense Class --</option>');



  document.getElementById('rem_bal').value = '';
  $.ajax({
      url: '{{ route('fmplanning.getRemBalAllocPAPSAA') }}?papid='+papid,
      type: 'GET',      
      
      success: function(res) {
        // $('#year').html('<option value="">-- Choose Year --</option>');
        // $.each(res, function (key, value) {
        //   $('#year').append('<option value="' + value
        //                   .year + '">' + value.year + '</option>');
        // });
        $('#expense_class').html('<option value="">-- Choose Expense Class --</option>');
        $.each(res, function (key, value) {
          $('#expense_class').append('<option value="' + value
                          .expense_class + '">' + value.expense_class + '</option>');
        });
 
      }
  });
});
});
</script>



<script type="text/javascript">
  $(document).ready(function () {

$('#expense_class').on('change', function () {
  var papid = document.getElementById('papid').value;
  var expense_class = this.value; 
  // $('#rem_bal').html('');
  $('#year').html('');
  $('#papoffice').html('');
  $('#rem_bal').html('');
  $('#year').append('<option value="">-- Choose Year --</option>');
  $('#papoffice').append('<option value="">-- Fetching Office --</option>');
  document.getElementById('rem_bal').value = '';
  $.ajax({
      url: '{{ route('fmplanning.getRemBalAllocPAPofficeSAA') }}?papid='+papid+'&expense_class='+expense_class,
      type: 'GET',

      success: function(res) {
        // $('#year').html('<option value="">-- Choose Year --</option>');
        // $.each(res, function (key, value) {
        //   $('#year').append('<option value="' + value
        //                   .year + '">' + value.year + '</option>');
        // });
        $('#papoffice').html('<option value="">-- Choose Office --</option>');
        $.each(res, function (key, value) {
          $('#papoffice').append('<option value="' + value
                          .office + '">' + value.office + '</option>');
        });
 
      }
  });
});
});
</script>

<script type="text/javascript">
  $(document).ready(function () {

$('#papoffice').on('change', function () {
  var papid = document.getElementById('papid').value;
  var expense_class = document.getElementById('expense_class').value;
  var papoffice = this.value;
  // $('#rem_bal').html('');
  $('#year').html('');
  // $('#papoffice').html('');
  $('#rem_bal').html('');
  $('#year').append('<option value="">-- Fetching Year --</option>');
  // $('#papoffice').append('<option value="">Fetching Office</option>');
  document.getElementById('rem_bal').value = '';
  $.ajax({
      url: '{{ route('fmplanning.getRemBalAllocPAPyearSAA') }}?papid='+papid+'&papoffice='+papoffice+'&expense_class='+expense_class,
      type: 'GET',

      success: function(res) {
        $('#year').html('<option value="">-- Choose Year --</option>');
        $.each(res, function (key, value) {
          $('#year').append('<option value="' + value
                          .year + '">' + value.year + '</option>');
                          // console.log(value.year);
        });
        // $('#papoffice').html('<option value="">-- Choose Office --</option>');
        // $.each(res, function (key, value) {
        //   $('#papoffice').append('<option value="' + value
        //                   .office + '">' + value.office + '</option>');
        // });
 
      }
  });
});
});
</script>

<script type="text/javascript">
  $(document).ready(function () {

$('#year').on('change', function () {
  var papid =   document.getElementById('papid').value;
  var year = this.value;
  var papoffice = document.getElementById('papoffice').value;
  var expense_class = document.getElementById('expense_class').value;
  $('#rem_bal').html('');
  document.getElementById('rem_bal').value = '';
  $.ajax({
      url: '{{ route('fmplanning.getRemBalyearAllocPAPSAA') }}?papid='+papid+'&year='+year+'&papoffice='+papoffice+'&expense_class='+expense_class,
      type: 'GET',

      success: function(res) {
        $.each(res, function (key, value) {
          let rembalActivity = value.rem_bal_activity;
          var usFormat = rembalActivity.toLocaleString('en-US');
          document.getElementById('rem_bal').value =  usFormat;
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


     
  

