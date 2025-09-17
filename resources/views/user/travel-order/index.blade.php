@extends('layouts.app')

@section('content')

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Travel Order Management</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active">Travel Order Management</li>
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
            <h5><i class="icon fas fa-check"></i> {{ session()->get('message') }}</h5>

          </div>
          @endif

          @if(session()->has('EventError'))
          <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fas fa-ban"></i> Failed to save!</h5>
            <ul>
              <li>Error : Date has record!</li>
            </ul>
          </div>
          @endif

          @if(session()->has('DateError1'))
          <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fas fa-ban"></i> Failed to save!</h5>
            <ul>
              <li>Error : Date Range must be on the same year!</li>
            </ul>
          </div>
          @endif


          <div class="card">
            <div class="card-header">

              @can('AddUserTravelOrder', \App\Models\TravelOrder::class)
              <button type="button" class="btn btn-default" data-toggle="modal" data-target="#new-travelorder-modal-lg"
                data-backdrop="static" data-keyboard="false">
                <i class="fas fa-plus"></i>
                {{ __ ('Add Travel Order')}}
              </button>
              @endcan

            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th class="text-center">Created At</th>
                    <th class="text-center">Date Range</th>
                    <th class="text-center">Destination</th>
                    <th class="text-center">Purpose of Travel</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Action</th>
                  </tr>
                </thead>
                <tbody>
                  @if(!empty($TravelOrders))
                  @foreach($TravelOrders as $TravelOrder)

                  <tr>
                    <td> {{$TravelOrder->created_at}}</td>
                    <td> {{$TravelOrder->daterange}}</td>
                    <td> {{$TravelOrder->destinationoffice}}</td>
                    <td> {{$TravelOrder->purpose}}</td>
                    <td class="text-center">
                        @php
                        // primary: relation ->approved->travelorderid
                        // fallback: column from LEFT JOIN (approved_code) kung meron
                        $approvedCode = optional($TravelOrder->approved)->travelorderid
                        ?? ($TravelOrder->approved_code ?? null);
                        @endphp

                        @if($TravelOrder->is_rejected1)
                        <span class="bg-danger p-2 rounded">Rejected First Approval</span>

                        @elseif(!$TravelOrder->is_approve1)
                        <span class="bg-warning p-2 rounded">Pending : 1st Approval</span>

                        @elseif($TravelOrder->is_rejected2)
                        <span class="bg-danger p-2 rounded">Rejected Final Approval</span>

                        @elseif(!$TravelOrder->is_approve2)
                        <span class="bg-warning p-2 rounded">Pending : 2nd Approval</span>

                        @else
                        @if($approvedCode)
                        <span class="bg-success p-2 rounded">Approved ( {{ $approvedCode }} )</span>
                        @else
                        <span class="bg-success p-2 rounded">Approved</span>
                        @endif
                        @endif
                    </td>




                    <td class="text-center">
                      {{-- @can('update', $Leave)
                      <button type="button" class="btn btn-default" title="Delete" data-toggle="modal"
                        data-target="#edit-leave-modal-lg{{ $Leave->id }} " data-backdrop="static"
                        data-keyboard="false">
                        <i class="fas fa-edit"></i>
                        {{__('Edit')}}
                      </button> --}}
                      {{-- @endcan --}}
                      @can('delete', $TravelOrder)
                      <button type="button" class="btn btn-default" title="Delete" data-toggle="modal"
                        data-target="#delete-travelorder-modal-lg{{ $TravelOrder->id }} " data-backdrop="static"
                        data-keyboard="false">
                        <i class="fas fa-trash-alt"></i>
                        {{__('Delete')}}
                      </button>
                      @endcan
                      {{-- @can('print', $TravelOrder)

                      <a href="  {{ route('travelorder.print',[$TravelOrder->id]) }}"
                        class="btn btn-default"><i class="fas fa-print"></i> Print</a>
                      @endcan --}}


                      @can('print', $TravelOrder)
                      <button type="button" class="btn btn-default" onclick="printTO('{{ route('travelorder.print', [$TravelOrder->id]) }}')">
                          <i class="fas fa-print"></i> Print
                      </button>
                      @endcan

                    </td>
                  </tr>
                  {{-- @include('msd-panel.event-panel.event.edit')
                  @include('msd-panel.event-panel.event.delete') --}}
                  @endforeach
                  @endif
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
@if(!empty($TravelOrders))
@foreach($TravelOrders as $TravelOrder)
@include('user.travel-order.delete')
@endforeach
@endif

@include('user.travel-order.create')
@endsection

@section('specific-scipt')
<!-- InputMask -->
<script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('plugins/inputmask/jquery.inputmask.min.js') }}"></script>
<!-- date-range-picker -->
<script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{ asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
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
<!-- bs-custom-file-input -->
<script src="{{asset('plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>

<script>
  $(function () {
                bsCustomFileInput.init();
              });
</script>
<!-- Page specific script -->

<script>
  $(function () {
              $("#example1").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
              
              }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
           
            });

          
</script>

<script>
  $(function () {
              $('#daterange').daterangepicker()   
            });
</script>


<script>
    function printTO(url) {
        // sabihin sa print view na naka-embed tayo para hindi ito mag history.back()
        const src = url + (url.includes('?') ? '&' : '?') + 'embed=1';

        const iframe = document.createElement('iframe');
        iframe.style.position = 'fixed';
        iframe.style.right = '0';
        iframe.style.bottom = '0';
        iframe.style.width = '0';
        iframe.style.height = '0';
        iframe.style.border = '0';
        iframe.src = src;

        document.body.appendChild(iframe);

        iframe.onload = function() {
            try {
                iframe.contentWindow.focus();
                iframe.contentWindow.print();
            } finally {
                // linisin pagkatapos mag-open ang print dialog
                setTimeout(() => iframe.remove(), 2000);
            }
        };
    }

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