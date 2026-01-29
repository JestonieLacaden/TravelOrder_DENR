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
                            <button type="button" class="btn btn-default" data-toggle="modal" data-target="#new-travelorder-modal-lg" data-backdrop="static" data-keyboard="false">
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

                                    <tr data-to-id="{{ $TravelOrder->id }}">
                                        <td> {{$TravelOrder->created_at}}</td>
                                        <td> {{$TravelOrder->daterange}}</td>
                                        <td> {{$TravelOrder->destinationoffice}}</td>
                                        <td> {{$TravelOrder->purpose}}</td>
                                        <td class="text-center">
                                            @php
                                            // Get the approved code (relation first, then optional joined column)
                                            $approvedCode = optional($TravelOrder->approved)->travelorderid
                                            ?? ($TravelOrder->approved_code ?? null);

                                            // Decide initial text + color (JS will update these via websockets)
                                            $statusClass = '';
                                            $statusText = '';

                                            // Check for returned status first
                                            if ($TravelOrder->is_returned1) {
                                            $statusClass = 'bg-info';
                                            $statusText = 'Returned by Immediate Supervisor';
                                            $rejectionReason = $TravelOrder->returned1_reason;
                                            } elseif ($TravelOrder->is_returned2) {
                                            $statusClass = 'bg-info';
                                            $statusText = 'Returned by Recommending Approval';
                                            $rejectionReason = $TravelOrder->returned2_reason;
                                            } elseif ($TravelOrder->is_returned3) {
                                            $statusClass = 'bg-info';
                                            $statusText = 'Returned by PENRO';
                                            $rejectionReason = $TravelOrder->returned3_reason;
                                            } elseif ($TravelOrder->is_rejected1) {
                                            $statusClass = 'bg-danger';
                                            $statusText = 'Rejected by Immediate Supervisor';
                                            $rejectionReason = $TravelOrder->rejected1_reason;
                                            } elseif (!$TravelOrder->is_approve1) {
                                            $statusClass = 'bg-warning';
                                            $statusText = 'For Immediate Supervisor Approval';
                                            $rejectionReason = null;
                                            } elseif ($TravelOrder->is_rejected2) {
                                            $statusClass = 'bg-danger';
                                            $statusText = 'Rejected by Recommending Approval';
                                            $rejectionReason = $TravelOrder->rejected2_reason;
                                            } elseif (!$TravelOrder->is_approve2) {
                                            $statusClass = 'bg-warning';
                                            $statusText = 'For Recommending Approval';
                                            $rejectionReason = null;
                                            } elseif ($TravelOrder->is_rejected3) {
                                            $statusClass = 'bg-danger';
                                            $statusText = 'Rejected by PENRO';
                                            $rejectionReason = $TravelOrder->rejected3_reason;
                                            } elseif (!$TravelOrder->is_approve3) {
                                            $statusClass = 'bg-warning';
                                            $statusText = 'For PENRO Approval';
                                            $rejectionReason = null;
                                            } else {
                                            $statusClass = 'bg-success';
                                            $statusText = $approvedCode ? "Approved ($approvedCode)" : 'Approved';
                                            $rejectionReason = null;
                                            }
                                            @endphp

                                            <div style="max-width:220px;margin:0 auto;">
                                                <span class="js-status p-1 rounded {{ $statusClass }}" style="display:block;white-space:normal;word-wrap:break-word;font-size:0.8rem;">{{ $statusText }}</span>

                                                @if(!empty($rejectionReason))
                                                <div style="font-size:0.75em;color:#17a2b8;margin-top:6px;padding:6px;background:#e7f7f9;border-left:2px solid #17a2b8;text-align:left;">
                                                    <strong>Remarks:</strong> {{ $rejectionReason }}
                                                </div>
                                                @elseif($TravelOrder->is_rejected1 || $TravelOrder->is_rejected2 || $TravelOrder->is_rejected3)
                                                {{-- Debug: Show if rejection exists but no reason --}}
                                                <div style="font-size:0.7em;color:#999;margin-top:4px;font-style:italic;">
                                                    (No rejection reason provided - Old rejection before feature was added)
                                                </div>
                                                @endif
                                            </div>

                                            @php
                                            // compute display datetime and label for current stage
                                            $displayDt = null;
                                            $displayLabel = '';
                                            $displayName = null;

                                            if (! $TravelOrder->is_approve1) {
                                            // Created stage: show the creator's name (created_by_name preferred)
                                            $displayDt = $TravelOrder->created_at;
                                            $displayLabel = 'CREATED BY';
                                            $displayName = $TravelOrder->created_by_name
                                            ?? optional($TravelOrder->employeeid)->firstname
                                            ?? optional($TravelOrder->employee)->firstname
                                            ?? null;
                                            } elseif (! $TravelOrder->is_approve2) {
                                            // Check if approver1 was skipped (auto-forwarded)
                                            if ($TravelOrder->approve1_by) {
                                                // Actual approval by section chief
                                                $displayDt = $TravelOrder->approve1_at ?? $TravelOrder->created_at;
                                                $displayLabel = 'APPROVED BY IMMEDIATE SUPERVISOR';
                                                $displayName = $TravelOrder->approve1_by_name ?? null;
                                            } else {
                                                // Skipped approver1, still created stage
                                                $displayDt = $TravelOrder->created_at;
                                                $displayLabel = 'CREATED BY';
                                                $displayName = $TravelOrder->created_by_name
                                                ?? optional($TravelOrder->employeeid)->firstname
                                                ?? optional($TravelOrder->employee)->firstname
                                                ?? null;
                                            }
                                            } elseif (! $TravelOrder->is_approve3) {
                                            // Check if approver2 was skipped (auto-forwarded to PENRO)
                                            if ($TravelOrder->approve2_by) {
                                                // Actual approval by division chief
                                                $displayDt = $TravelOrder->approve2_at ?? $TravelOrder->approve1_at ?? $TravelOrder->created_at;
                                                $displayLabel = 'APPROVED BY RECOMMENDING APPROVER';
                                                $displayName = $TravelOrder->approve2_by_name ?? $TravelOrder->approve1_by_name ?? null;
                                            } else {
                                                // Skipped approver2 (and possibly approver1), still created stage
                                                $displayDt = $TravelOrder->created_at;
                                                $displayLabel = 'CREATED BY';
                                                $displayName = $TravelOrder->created_by_name
                                                ?? optional($TravelOrder->employeeid)->firstname
                                                ?? optional($TravelOrder->employee)->firstname
                                                ?? null;
                                            }
                                            } else {
                                            $displayDt = $TravelOrder->approve3_at ?? $TravelOrder->approve2_at ?? $TravelOrder->approve1_at ?? $TravelOrder->created_at;
                                            $displayLabel = 'APPROVED BY PENRO';
                                            $displayName = $TravelOrder->approve3_by_name ?? $TravelOrder->approve2_by_name ?? $TravelOrder->approve1_by_name ?? null;
                                            }
                                            @endphp

                                            @if($displayDt)
                                            <div style="font-size:0.75em;font-style:italic;opacity:0.8;text-align:center;margin-top:4px;">
                                                {{ $displayLabel }}
                                                @if(!empty($displayName))
                                                {{ $displayName }}
                                                @endif
                                                <br>
                                                {{ \Carbon\Carbon::parse($displayDt)->format('m/d/Y') }} -
                                                {{ \Carbon\Carbon::parse($displayDt)->format('g:ia') }}
                                            </div>
                                            @endif
                                        </td>






                                        <td class="text-center">
                                            @can('update', $TravelOrder)
                                            <button type="button" class="btn btn-default" title="Edit" data-toggle="modal" data-target="#edit-travelorder-modal-{{ $TravelOrder->id }}" data-backdrop="static" data-keyboard="false">
                                                <i class="fas fa-edit"></i>
                                                {{__('Edit')}}
                                            </button>
                                            @endcan

                                            @can('delete', $TravelOrder)
                                            <button type="button" class="btn btn-default" title="Delete" data-toggle="modal" data-target="#delete-travelorder-modal-lg{{ $TravelOrder->id }} " data-backdrop="static" data-keyboard="false">
                                                <i class="fas fa-trash-alt"></i>
                                                {{__('Delete')}}
                                            </button>
                                            @endcan
                                            {{-- @can('print', $TravelOrder)

                      <a href="  {{ route('travelorder.print',[$TravelOrder->id]) }}"
                                            class="btn btn-default"><i class="fas fa-print"></i> Print</a>
                                            @endcan --}}


                                            @can('print', $TravelOrder)
                                            <div style="display:inline-block">
                                                <button type="button" class="btn btn-sm btn-default" style="padding: 4px 8px; font-size: 0.85rem; width: 80px;" onclick="printTO('{{ route('travelorder.print', [$TravelOrder->id]) }}')" title="Print">
                                                    <i class="fas fa-print"></i><br><small>Print</small>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-info" style="padding: 4px 8px; font-size: 0.85rem; width: 80px;" onclick="downloadTOPDF('{{ route('travelorder.print', [$TravelOrder->id]) }}', '{{ $TravelOrder->id }}')" title="Download PDF - Select 'Save as PDF' as printer destination">
                                                    <i class="fas fa-download"></i><br><small>Download</small>
                                                </button>
                                            </div>
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
@include('user.travel-order.edit')
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
    $(function() {
        bsCustomFileInput.init();
    });

</script>
<!-- Page specific script -->

<script>
    $(function() {
        $("#example1").DataTable({
            responsive: true
            , lengthChange: false
            , autoWidth: false
            , order: [
                [0, 'desc']
            ]
        });
    });

</script>

<script>
    $(function() {
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

    function downloadTOPDF(url, id) {
        window.open(url, '_blank');
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
