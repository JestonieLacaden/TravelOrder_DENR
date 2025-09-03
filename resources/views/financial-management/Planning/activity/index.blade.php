@extends('financial-management.planning.index')

@section('fm-content')

<div class="col-md-9">
    <div class="card-header bg-primary">
      <h3 class="card-title ">ACTIVITY   </h3>
      <h5></h5>
    </div>
    <!-- /.card-header -->
    <div class="card">       
      <!-- /.card-header -->
      <div class="card-header">
        
        @can('create', \App\Models\FMPlanningActivity::class)
        <button type="button" class="btn btn-default" data-toggle="modal" data-target="#create-activity-modal-lg" data-backdrop="static" data-keyboard="false">
          <i class="fas fa-plus"></i> New Activity
        </button> 
        @endcan
      </div>
      <div class="card-body">
        <table id="example1" class="table table-bordered table-striped">
           <thead>
          <tr>
            <th  class="text-center">PAP</th>
            <th  class="text-center">Activity</th>
            <th style="width: 100px"  class="text-center">Action</th>
          </tr>
          </thead>
          <tbody>
           @foreach ($Activities as $Activity)
            <tr>
              <td>{{ $Activity->pap->pap }}</td>
              <td>{{ $Activity->activity }}</td>
              <td class="text-center">   
                
                @can('delete',$Activity)
                <button type="button" title="Delete Activity" class="btn btn-danger" data-toggle="modal" data-target="#delete-activity-modal-lg{{ $Activity->id }}"  data-backdrop="static" data-keyboard="false">
                  <i class="fas fa-trash"></i>
                </button>
                @endcan
              </td>
              </tr>
              @include('financial-management.planning.activity.delete') 
            @endforeach          
          </tbody>
        </table>
      </div>
    </div>
  </div>

  @include('financial-management.planning.activity.create')

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


     
  

