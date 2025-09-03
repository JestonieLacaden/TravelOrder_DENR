@extends('mails.employeerequest.index')

@include('mails.travelorder.edit')

@section('mails')

<div class="col-md-9">
  <div class="card-header bg-primary">
    <h3 class="card-title ">Travel Order Request(s) </h3>
    <h5></h5>
  </div>
  <!-- /.card-header -->
  <div class="card">
    <!-- /.card-header -->
    <div class="card-body">
      <table id="example1" class="table table-bordered table-striped">
        <thead>
          <tr>
            <td class="text-center">Date Created</td>
            <td class="text-center">Date Range</td>
            <td class="text-center">Employee Name</td>
            <td class="text-center">Destination Office</td>
            <td class="text-center">Purpose</td>
            <th class="text-center">Leave Year</th>
            <th class="text-center">Action</th>

          </tr>
        </thead>
        <tbody>
          @foreach($TravelOrders as $TravelOrder)
          @foreach($TravelOrderSignatories as $TravelOrderSignatory)
          @if($TravelOrder->is_approve1 != true && $TravelOrder->is_rejected1 != true)
          @if($TravelOrderSignatory->approver1 == $UserEmployee->id && auth()->check())
          <tr>
            <td> {{$TravelOrder->created_at}}</td>
            <td> {{$TravelOrder->daterange}}</td>
            <td>{{ $TravelOrder->employee->firstname . ' ' . $TravelOrder->employee->lastname}}</td>
            <td> {{$TravelOrder->destinationoffice}}</td>
            <td> {{$TravelOrder->purpose}}</td>

            <td> {{$TravelOrder->created_at->diffForHumans() }}</td>

            <td>
              @can('accept', $TravelOrder)
              <button type="button" title="Approve Travel Order" class="btn btn-success" data-toggle="modal"
                data-target="#accept-travelorder-modal-lg{{ $TravelOrder->id }}" data-backdrop="static"
                data-keyboard="false">
                <i class="fas fa-check"></i>
              </button>
              @endcan
              @can('reject', $TravelOrder)
              <button type="button" title="Reject Travel Order" class="btn btn-danger" data-toggle="modal"
                data-target="#reject-travelorder-modal-lg{{ $TravelOrder->id }} " data-backdrop="static"
                data-keyboard="false">
                <i class="fas fa-times"></i>
              </button>
              @endcan

              <button class="btn btn-info" data-toggle="modal" data-target="#edit-travelorder-modal-lg"
                data-id="{{ $TravelOrder->id }}" data-daterange="{{ $TravelOrder->daterange }}"
                data-employee="{{ $TravelOrder->Employee->lastname.', '.$TravelOrder->Employee->firstname.' '.$TravelOrder->Employee->middlename }}"
                data-destination="{{ $TravelOrder->destinationoffice }}" data-purpose="{{ $TravelOrder->purpose }}">
                {{-- <i class="bi bi-pencil-square"></i> --}}
                <i class="fa-solid fa-pen-to-square"></i>
                {{-- <i class="bi bi-pencil-square"></i> --}}
              </button>

              {{-- <button type="button" title="Edit Travel Order" class="btn btn-info" data-toggle="modal"
                data-target="#edit-travelorder-modal-lg" data-keyboard="false" data-backdrop="static">
                <i class="fa-solid fa-pen-to-square"></i>
              </button> --}}


            </td>

          </tr>
          @endif
          @endif

          @if($TravelOrder->is_approve1 == true && $TravelOrder->is_rejected1 != true && $TravelOrder->is_rejected2 !=
          true && $TravelOrder->is_approve2 != true )

          @if($TravelOrderSignatory->approver2 == $UserEmployee->id && auth()->check())
          <tr>
            <td> {{$TravelOrder->created_at}}</td>
            <td> {{$TravelOrder->daterange}}</td>
            <td>{{ $TravelOrder->employee->firstname . ' ' . $TravelOrder->employee->lastname}}</td>
            <td> {{$TravelOrder->destinationoffice}}</td>
            <td> {{$TravelOrder->purpose}}</td>

            <td> {{$TravelOrder->created_at->diffForHumans() }}</td>

            <td>
              @can('accept', $TravelOrder)
              <button type="button" title="Approve Travel Order" class="btn btn-success" data-toggle="modal"
                data-target="#accept-travelorder-modal-lg{{ $TravelOrder->id }}" data-backdrop="static"
                data-keyboard="false">
                <i class="fas fa-check"></i>
              </button>
              @endcan
              @can('reject', $TravelOrder)
              <button type="button" title="Reject Travel Order" class="btn btn-danger" data-toggle="modal"
                data-target="#reject-travelorder-modal-lg{{ $TravelOrder->id }} " data-backdrop="static"
                data-keyboard="false">
                <i class="fas fa-times"></i>
              </button>
              @endcan
            </td>
          </tr>
          @endif
          @endif
          @endforeach
          @endforeach

        </tbody>
      </table>
    </div>
  </div>
</div>
@foreach($TravelOrders as $TravelOrder)
@can('accept', $TravelOrder)
@include('mails.travelorder.accept')
@endcan
@can('reject', $TravelOrder)
@include('mails.travelorder.reject')
@endcan
@endforeach
@endsection




{{-- @section('specific-layout')
<link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{asset('/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{asset('/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

@endsection --}}

<script>
  $(function () {
    $('#daterangeEdit').daterangepicker()   
  });
</script>

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
{{-- DatePicker --}}

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



{{-- @extends('mails.index')

@section('mails')

<div class="col-9">
  <div class="card card-primary card-outline">
    <div class="card-header">
      <h3 class="card-title">Travel Order Request(s)</h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body p-0">

      <div class="card-body">
        <div class="table-responsive mailbox-messages">
          <table id="example1" class="table table-hover table-striped">
            <thead>
              <tr>
                <td class="text-center">Date Created</td>
                <td class="text-center">Date Range</td>
                <td class="text-center">Employee Name</td>
                <td class="text-center">Destination Office</td>
                <td class="text-center">Purpose</td>
                <td></td>
                <td></td>

              </tr>
            </thead>
            <tbody>
              @foreach($TravelOrders as $TravelOrder)
              @foreach($TravelOrderSignatories as $TravelOrderSignatory)
              @if($TravelOrder->is_approve1 != true && $TravelOrder->is_rejected1 != true)
              @if($TravelOrderSignatory->approver1 == $UserEmployee->id && auth()->check())
              <tr>
                <td> {{$TravelOrder->created_at}}</td>
                <td> {{$TravelOrder->daterange}}</td>
                <td>{{ $TravelOrder->employee->firstname . ' ' . $TravelOrder->employee->lastname}}</td>
                <td> {{$TravelOrder->destinationoffice}}</td>
                <td> {{$TravelOrder->purpose}}</td>

                <td> {{$TravelOrder->created_at->diffForHumans() }}</td>

                <td>
                  @can('accept', $TravelOrder)
                  <div class="btn-group">
                    <button type="button" class="btn" data-toggle="dropdown">
                      <i class="fas fa-ellipsis-v"></i>
                    </button>
                    <div class="dropdown-menu" role="menu">
                      @can('accept', $TravelOrder)
                      <a class="dropdown-item" type="SUBMIT" data-toggle="modal"
                        data-target="#accept-travelorder-modal-lg{{ $TravelOrder->id }} " data-backdrop="static"
                        data-keyboard="false">Approve</a>
                      @endcan
                      @can('reject', $TravelOrder)
                      <a class="dropdown-item" type="SUBMIT" data-toggle="modal"
                        data-target="#reject-travelorder-modal-lg{{ $TravelOrder->id }} " data-backdrop="static"
                        data-keyboard="false">Reject</a>
                      @endcan
                    </div>
                    @endcan
                </td>

              </tr>
              @endif
              @endif

              @if($TravelOrder->is_approve1 == true && $TravelOrder->is_rejected1 != true && $TravelOrder->is_rejected2
              != true && $TravelOrder->is_approve2 != true )

              @if($TravelOrderSignatory->approver2 == $UserEmployee->id && auth()->check())
              <tr>
                <td> {{$TravelOrder->created_at}}</td>
                <td> {{$TravelOrder->daterange}}</td>
                <td>{{ $TravelOrder->employee->firstname . ' ' . $TravelOrder->employee->lastname}}</td>
                <td> {{$TravelOrder->destinationoffice}}</td>
                <td> {{$TravelOrder->purpose}}</td>

                <td> {{$TravelOrder->created_at->diffForHumans() }}</td>

                <td>
                  @can('accept', $TravelOrder)
                  <div class="btn-group">
                    <button type="button" class="btn" data-toggle="dropdown">
                      <i class="fas fa-ellipsis-v"></i>
                    </button>
                    <div class="dropdown-menu" role="menu">
                      @can('accept', $TravelOrder)
                      <a class="dropdown-item" type="SUBMIT" data-toggle="modal"
                        data-target="#accept-travelorder-modal-lg{{ $TravelOrder->id }} " data-backdrop="static"
                        data-keyboard="false">Approve</a>
                      @endcan
                      @can('reject', $TravelOrder)
                      <a class="dropdown-item" type="SUBMIT" data-toggle="modal"
                        data-target="#reject-travelorder-modal-lg{{ $TravelOrder->id }} " data-backdrop="static"
                        data-keyboard="false">Reject</a>
                      @endcan
                    </div>
                    @endcan
                </td>

              </tr>
              @endif
              @endif
              @endforeach
              @endforeach

            </tbody>
          </table>
          <!-- /.table -->
        </div>
      </div>
      <!-- /.mail-box-messages -->
    </div>
    <!-- /.card-body -->
    @foreach($TravelOrders as $TravelOrder)
    @can('accept', $TravelOrder)
    @include('mails.travelorder.accept')
    @endcan
    @can('reject', $TravelOrder)
    @include('mails.travelorder.reject')
    @endcan
    @endforeach

    @endsection

    @section('page-script')
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
           "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
         }).buttons().container().appendTo('#incoming_wrapper .col-md-6:eq(0)');
         $("#outgoing").DataTable({
           "responsive": true, "lengthChange": false, "autoWidth": false,
           "buttons": [ "excel", "pdf", "print"]
         }).buttons().container().appendTo('#outgoing_wrapper .col-md-6:eq(0)');
     
       });
    </script>

    @endsection
    --}}