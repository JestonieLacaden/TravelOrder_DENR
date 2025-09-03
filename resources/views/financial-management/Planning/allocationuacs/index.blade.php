@extends('financial-management.planning.index')

@section('fm-content')

<div class="col-md-9">
    <div class="card-header bg-primary">
      <h3 class="card-title ">Allocation per UACS (GAA) </h3>
      <h5></h5>
    </div>
    <!-- /.card-header -->
    <div class="card">       
      <!-- /.card-header -->
      <div class="card-header">
        
        @can('create', \App\Models\FMAllocationUACS::class)
        <button type="button" class="btn btn-default" data-toggle="modal" data-target="#create-allocation-modal-lg" data-backdrop="static" data-keyboard="false">
          <i class="fas fa-plus"></i> {{__('New Allocation') }}
        </button> 
        @endcan

        @can('create', \App\Models\FMAllocationUACS::class)
        <button type="button" class="btn btn-default" data-toggle="modal" data-target="#realignment-allocation-modal-lg" data-backdrop="static" data-keyboard="false">
          <i class="fas fa-route"></i> {{__('Realignment') }}
        </button> 
        @endcan
      </div>
      <div class="card-body">
        <table id="example1" class="table table-bordered table-striped">
           <thead>
          <tr>
            <th  class="text-center">For Year</th>
            {{-- <th  class="text-center">Office </th> --}}
            <th  class="text-center">Program </th>  
            <th  class="text-center">Expense Class </th>
            <th  class="text-center">Office </th>
            <th  class="text-center"> UACS</th>
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
              <td>{{$Allocation->PAP->pap}}</td>
              <td>{{$Allocation->expense_class}}</td>
              <td>{{$Allocation->office}}</td>
              <td>{{$Allocation->UACS->uacs . ' - ' . $Allocation->UACS->description }}</td>
              <td>{{ number_format($Allocation->amount,2,'.',',')  }}</td>
              <td>{{ number_format($Allocation->rem_bal,2,'.',',')  }}</td>
              <td class="text-center">   

                {{-- @can('create', \App\Models\FMAllocationUACS::class)
                <button type="button" title="Add Allocation" class="btn btn-success" data-toggle="modal" data-target="#add-allocationuacs-modal-lg{{ $Allocation->id }}"  data-backdrop="static" data-keyboard="false">
                  <i class="fas fa-plus"></i>
                </button>
                @endcan --}}
                
                @can('delete',$Allocation)
                <button type="button" title="Delete Allocation" class="btn btn-danger" data-toggle="modal" data-target="#delete-allocationuacs-modal-lg{{ $Allocation->id }}"  data-backdrop="static" data-keyboard="false">
                  <i class="fas fa-trash"></i>
                </button>
                @endcan

              </td>
              </tr>

              @include('financial-management.planning.allocationuacs.delete') 
              {{-- @include('financial-management.planning.allocationuacs.addallocation') --}}
            @endforeach          
          </tbody>
        </table>
      </div>
    </div>
  </div>

  @include('financial-management.planning.allocationuacs.realignment')

  @include('financial-management.planning.allocationuacs.create')


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

$('#papid').on('change',function () {
  var papid = this.value;
  // $('#rem_bal').html('');
  $('#year').html('');
  // $('#papoffice').html('');
  $('#rem_bal').html('');
  // $('#year').append('<option value="">Fetching Year</option>');
  $('#papoffice1').append('<option value="">-- Fetching Office --</option>');
  // $('#papoffice').append('<option value="">Fetching Office</option>');
  $('#year').append('<option value="">-- Choose Year --</option>');
  $('#expense_class').append('<option value="">-- Fetching Expense Class --</option>');


  document.getElementById('rem_bal').value = '';
  $.ajax({
      url: '{{ route('fmplanning.getRemBalAllocUACS') }}?papid='+papid,
      type: 'GET',

      success: function(res) {
 
        $('#papoffice1').html('<option value="">-- Choose Office --</option>');
        $('#expense_class').html('<option value="">-- Choose Expense Class --</option>');
        $.each(res, function (key, value) {
          // $('#year').append('<option value="' + value
          //                 .year + '">' + value.year + '</option>');
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
  $('#papoffice1').html('');
  $('#rem_bal').html('');
  $('#year').append('<option value="">-- Choose Year --</option>');
  $('#papoffice1').append('<option value="">-- Fetching Office --</option>');
  document.getElementById('rem_bal').value = '';
  $.ajax({
      url: '{{ route('fmplanning.getRemBalAllocPAPoffice') }}?papid='+papid+'&expense_class='+expense_class,
      type: 'GET',

      success: function(res) {
        // $('#year').html('<option value="">-- Choose Year --</option>');
        // $.each(res, function (key, value) {
        //   $('#year').append('<option value="' + value
        //                   .year + '">' + value.year + '</option>');
        // });
        $('#papoffice1').html('<option value="">-- Choose Office --</option>');
        $.each(res, function (key, value) {
          $('#papoffice1').append('<option value="' + value
                          .office + '">' + value.office + '</option>');
        });
 
      }
  });
});
});
</script>


<script type="text/javascript">
  $(document).ready(function () {

$('#papoffice1').on('change', function () {
  var papid =   document.getElementById('papid').value;
  var expense_class =   document.getElementById('expense_class').value;
  var papoffice = this.value;
  $('#year').html('');
  $('#rem_bal').html('');
  $('#year').append('<option value="">-- Fetching Year --</option>');

  document.getElementById('rem_bal').value = '';
  $.ajax({
      url: '{{ route('fmplanning.getRemBalAllocUACSyear') }}?papid='+papid+'&papoffice='+papoffice+'&expense_class='+expense_class,
      type: 'GET',

      success: function(res) {
        $('#year').html('<option value="">-- Choose Year --</option>');
        $.each(res, function (key, value) {
          $('#year').append('<option value="' + value
                          .year + '">' + value.year + '</option>');
                          
        });
 
      }
  });
});
});
</script>



<script type="text/javascript">
  $(document).ready(function () {

$('#year').on('change', function () {
  var papid =   document.getElementById('papid').value;
  var papoffice =   document.getElementById('papoffice1').value;
  var expense_class =   document.getElementById('expense_class').value;
  var year = this.value;
  $('#rem_bal').html('');
  document.getElementById('rem_bal').value = '';
  $.ajax({
      url: '{{ route('fmplanning.getRemBalyearAllocUACS') }}?papid='+papid+'&year='+year+'&papoffice='+papoffice+'&expense_class='+expense_class,
      type: 'GET',

      success: function(res) {
        $.each(res, function (key, value) {
          let rembalUACS = value.rem_bal_uacs;
          var usFormat = rembalUACS.toLocaleString('en-US');
          document.getElementById('rem_bal').value =  usFormat;
        });
 
      }
  });
});
});
</script>

<script type="text/javascript">
  $(document).ready(function () {

$('#papidrealign1').on('change', function () {
  var papid = this.value;
  // $('#rem_bal').html('');
  $('#yearrealign1').html('');
  $('#papoffice').html('');
  $('#rem_balrealign1').html('');
  $('#expense_class_realign').html('');
  $('#papoffice').append('<option value="">Fetching Office</option>');
  $('#uacsidrealign1').append('<option value="">-- Choose UACS --</option>');
  $('#yearrealign1').append('<option value="">-- Choose Year --</option>');
  $('#office1').append('<option value="">-- Fetching Office --</option>');
  $('#expense_class_realign').append('<option value="">-- Fetching Expense Class --</option>');
  document.getElementById('rem_balrealign1').value = '';
  $.ajax({
      url: '{{ route('fmplanning.getRemBalrealignyear') }}?papid='+papid,
      type: 'GET',

      success: function(res) {
        // $('#yearrealign1').html('<option value="">-- Choose Year --</option>');
        $('#expense_class_realign').html('<option value="">-- Choose Expense Class --</option>');
        $('#papoffice').html('<option value="">-- Choose Office --</option>');
        $('#uacsidrealign1').html('<option value="">-- Choose UACS --</option>'); 
        // $('#office1').html('<option value="">-- Choose Office --</option>'); 
        $.each(res, function (key, value) {
  
          //  $('#papoffice').append('<option value="' + value
          //                 .office + '">' + value.office + '</option>');
          $('#expense_class_realign').append('<option value="' + value
                          .expense_class + '">' + value.expense_class + '</option>');
        });
 
      }
  });
});
});
</script>


<script type="text/javascript">
  $(document).ready(function () {

$('#expense_class_realign').on('change', function () {
  var papid =  document.getElementById('papidrealign1').value;
  var expense_class = this.value;
  // $('#rem_bal').html('');
  $('#yearrealign1').html('');
  $('#papoffice').html('');
  $('#rem_balrealign1').html('');
  $('#yearrealign1').append('<option value="">-- Choose Year --</option>');
  $('#papoffice').append('<option value="">Fetching Office</option>');
  $('#uacsidrealign1').append('<option value="">-- Choose UACS --</option>');
  $('#office1').append('<option value="">-- Fetching Office --</option>');

  document.getElementById('rem_balrealign1').value = '';
  $.ajax({
      url: '{{ route('fmplanning.getRemBalrealignyexpense') }}?papid='+papid+'&expense_class='+expense_class,
      type: 'GET',

      success: function(res) {
        // $('#yearrealign1').html('<option value="">-- Choose Year --</option>');

        $('#papoffice').html('<option value="">-- Choose Office --</option>');
        $('#uacsidrealign1').html('<option value="">-- Choose UACS --</option>'); 
        // $('#office1').html('<option value="">-- Choose Office --</option>'); 
        $.each(res, function (key, value) {
  
          //  $('#papoffice').append('<option value="' + value
          //                 .office + '">' + value.office + '</option>');
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
  var papid = document.getElementById('papidrealign1').value;
  var papoffice = this.value;
  var expense_class = document.getElementById('expense_class_realign').value;
  // $('#rem_bal').html('');
  $('#yearrealign1').html('');
  $('#rem_balrealign1').html('');
  $('#yearrealign1').append('<option value="">Fetching Year</option>');
  $('#uacsidrealign1').append('<option value="">-- Choose UACS --</option>');
  $('#office1').append('<option value="">-- Fetching Office --</option>');
  document.getElementById('rem_balrealign1').value = '';
  $.ajax({
      url: '{{ route('fmplanning.getRemBalrealignoffice') }}?papid='+papid+'&papoffice='+papoffice+'&expense_class='+expense_class,
      type: 'GET',

      success: function(res) {
        $('#yearrealign1').html('<option value="">-- Choose Year --</option>');
        $('#uacsidrealign1').html('<option value="">-- Choose UACS --</option>'); 
        $('#office1').html('<option value="">-- Choose Office --</option>'); 
        $.each(res, function (key, value) {
          $('#yearrealign1').append('<option value="' + value
                          .year + '">' + value.year + '</option>');

        });
 
      }
  });
});
});
</script>

<script type="text/javascript">
  $(document).ready(function () {

$('#yearrealign1').on('change', function () {
  var papid =   document.getElementById('papidrealign1').value;
  var year = this.value;
  var expense_class = document.getElementById('expense_class_realign').value;
  var papoffice = document.getElementById('papoffice').value;
  $('#uacsidrealign1').html('');
  $('#rem_balrealign1').html('');
  document.getElementById('rem_balrealign1').value = '';
  $('#uacsidrealign1').append('<option value="">-- Fetching UACS --</option>');
  $('#office1').append('<option value="">-- Fetching Office --</option>');
  $.ajax({
      url: '{{ route('fmplanning.getRemBalrealign_year') }}?papid='+papid+'&year='+year+'&papoffice='+papoffice+'&expense_class='+expense_class,
      type: 'GET',

      success: function(res) {
        $('#office1').html('<option value="">-- Choose Office --</option>'); 
        $('#uacsidrealign1').html('<option value="">-- Choose UACS --</option>');
        $.each(res, function (key, value) {
          $('#uacsidrealign1').append('<option value="' + value[0] + '">' + value[1] + ' - ' + value[2] + '</option>');
        });
 
      }
  });
});
});
</script>

<script type="text/javascript">
  $(document).ready(function () {

$('#uacsidrealign1').on('change', function () {
  var papid =   document.getElementById('papidrealign1').value;
  var allocuacsid =  this.value;
  var year =  document.getElementById('yearrealign1').value;
  var expense_class = document.getElementById('expense_class_realign').value;
  var papoffice = document.getElementById('papoffice').value;
  $('#rem_balrealign1').html('');
  document.getElementById('rem_balrealign1').value = '';
  // $('#office1').append('<option value="">-- Fetching Office --</option>');
  $.ajax({
      url: '{{ route('fmplanning.getRemBalrealignbal') }}?allocuacsid='+allocuacsid+'&year='+year+'&papid='+papid+'&expense_class='+expense_class+'&office='+papoffice,
      type: 'GET',

      success: function(res) {

        $.each(res, function (key, value) {

          $('#office1').append('<option value="' + value.office + '">' + value.office + '</option>');
 
        });
 
      }
  });
});
});
</script>


<script type="text/javascript">
  $(document).ready(function () {

$('#office1').on('change', function () {
  var papid =   document.getElementById('papidrealign1').value;
  var allocuacsid =  document.getElementById('uacsidrealign1').value;
  var year =  document.getElementById('yearrealign1').value;
  var office = this.value;
  $('#rem_balrealign1').html('');
  document.getElementById('rem_balrealign1').value = '';
  console.log(office);
  $.ajax({
      url: '{{ route('fmplanning.getRemBalrealignbalOffice') }}?allocuacsid='+allocuacsid+'&year='+year+'&papid='+papid+'&office='+office,
      type: 'GET',

      success: function(res) {
        $.each(res, function (key, value) {
          let rembalUACS = value.rem_bal;
          var usFormat = rembalUACS.toLocaleString('en-US');
          document.getElementById('rem_balrealign1').value =  usFormat;
        });
 
      }
  });
});
});
</script>


{{-- 
<script type="text/javascript">
  $(document).ready(function () {

$('#papidrealign2').on('change', function () {
  var papid = this.value;
  // $('#rem_bal').html('');
  $('#yearrealign2').html('');
  $('#rem_balrealign2').html('');
  $('#yearrealign2').append('<option value="">Fetching Year</option>');

  document.getElementById('rem_balrealign2').value = '';
  $.ajax({
      url: '{{ route('fmplanning.getRemBalrealignyear') }}?papid='+papid,
      type: 'GET',

      success: function(res) {
        $('#yearrealign2').html('<option value="">-- Choose Year --</option>');
        $.each(res, function (key, value) {
          $('#yearrealign2').append('<option value="' + value
                          .year + '">' + value.year + '</option>');
        });
 
      }
  });
});
});
</script>

<script type="text/javascript">
  $(document).ready(function () {

$('#yearrealign2').on('change', function () {
  var papid =   document.getElementById('papidrealign2').value;
  var year = this.value;
  $('#uacsidrealign2').html('');
  $('#rem_balrealign2').html('');
  document.getElementById('rem_balrealign2').value = '';
  $('#uacsidrealign2').append('<option value="">-- Fetching UACS --</option>');
  $.ajax({
      url: '{{ route('fmplanning.getRemBalrealignuacs') }}?papid='+papid+'&year='+year,
      type: 'GET',

      success: function(res) {

        $('#uacsidrealign2').html('<option value="">-- Choose UACS --</option>');
        $.each(res, function (key, value) {
          $('#uacsidrealign2').append('<option value="' + value[0] + '">' + value[1] + '</option>');
        });
 
      }
  });
});
});
</script>

<script type="text/javascript">
  $(document).ready(function () {

$('#uacsidrealign2').on('change', function () {
  var papid =   document.getElementById('papidrealign2').value;
  var allocuacsid =  this.value;
  var year =  document.getElementById('yearrealign2').value;
  console.log(allocuacsid);
  $('#rem_balrealign2').html('');
  document.getElementById('rem_balrealign2').value = '';
  $.ajax({
    url: '{{ route('fmplanning.getRemBalrealignbal') }}?allocuacsid='+allocuacsid+'&year='+year+'&papid='+papid,
      type: 'GET',

      success: function(res) {
        $.each(res, function (key, value) {
          let rembalUACS = value.rem_bal;
          var usFormat = rembalUACS.toLocaleString('en-US');
          document.getElementById('rem_balrealign2').value =  usFormat;
        });
 
      }
  });
});
});
</script> --}}

@endsection 

@section('mails-layout')
<link rel="stylesheet" href="{{asset('/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{asset('/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{asset('/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">  
@endsection


     
  

