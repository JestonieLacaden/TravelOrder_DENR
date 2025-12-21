@extends('mails.employeerequest.index')

@include('mails.travelorder.edit')

@section('mails')

<div class="col-md-12">
    <div class="card-header bg-primary">
        <h3 class="card-title ">Travel Order Request(s) </h3>
        <h5></h5>
    </div>
    <!-- /.card-header -->
    <div class="card">
        <div class="card-body">
            <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <td class="text-center">Date Created</td>
                        <td class="text-center">Date Range</td>
                        <td class="text-center">Employee Name</td>
                        <td class="text-center">Destination Office</td>
                        <td class="text-center">Purpose</td>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($TravelOrders as $TravelOrder)
                    @foreach($TravelOrderSignatories as $TravelOrderSignatory)

                    {{-- ✅ Only render if this TO belongs to THIS signatory --}}
                    @if($TravelOrder->travelordersignatoryid == $TravelOrderSignatory->id)

                    {{-- Approver 1 --}}
                    @if($TravelOrder->is_approve1 != true && $TravelOrder->is_rejected1 != true)
                    @if($TravelOrderSignatory->approver1 == $UserEmployee->id && auth()->check())
                    <tr>
                        <td> {{$TravelOrder->created_at}}</td>
                        <td> {{$TravelOrder->daterange}}</td>
                        <td>{{ $TravelOrder->employee->firstname . ' ' . $TravelOrder->employee->lastname}}</td>
                        <td> {{$TravelOrder->destinationoffice}}</td>
                        <td> {{$TravelOrder->purpose}}</td>
                        <td>
                            @can('accept', $TravelOrder)
                            <button type="button" title="Approve Travel Order" class="btn btn-success accept-to-btn" data-to-id="{{ $TravelOrder->id }}" data-employee-name="{{ $TravelOrder->employee->firstname }} {{ $TravelOrder->employee->lastname }}">
                                <i class="fas fa-check"></i>
                            </button>
                            @endcan
                            @can('reject', $TravelOrder)
                            <button type="button" title="Reject Travel Order" class="btn btn-danger reject-to-btn" data-to-id="{{ $TravelOrder->id }}" data-employee-name="{{ $TravelOrder->employee->firstname }} {{ $TravelOrder->employee->lastname }}">
                                <i class="fas fa-times"></i>
                            </button>
                            @endcan
                        </td>
                    </tr>
                    @endif
                    @endif

                    {{-- Approver 2 --}}
                    @if($TravelOrder->is_approve1 == true && $TravelOrder->is_rejected1 != true && $TravelOrder->is_rejected2 !=
                    true && $TravelOrder->is_approve2 != true )
                    @if($TravelOrderSignatory->approver2 == $UserEmployee->id && auth()->check())
                    <tr>
                        <td> {{$TravelOrder->created_at}}</td>
                        <td> {{$TravelOrder->daterange}}</td>
                        <td>{{ $TravelOrder->employee->firstname . ' ' . $TravelOrder->employee->lastname}}</td>
                        <td> {{$TravelOrder->destinationoffice}}</td>
                        <td> {{$TravelOrder->purpose}}</td>
                        <td>
                            @can('accept', $TravelOrder)
                            <button type="button" title="Approve Travel Order" class="btn btn-success accept-to-btn" data-to-id="{{ $TravelOrder->id }}" data-employee-name="{{ $TravelOrder->employee->firstname }} {{ $TravelOrder->employee->lastname }}">
                                <i class="fas fa-check"></i>
                            </button>
                            @endcan
                            @can('reject', $TravelOrder)
                            <button type="button" title="Reject Travel Order" class="btn btn-danger reject-to-btn" data-to-id="{{ $TravelOrder->id }}" data-employee-name="{{ $TravelOrder->employee->firstname }} {{ $TravelOrder->employee->lastname }}">
                                <i class="fas fa-times"></i>
                            </button>
                            @endcan
                        </td>
                    </tr>
                    @endif
                    @endif

                    {{-- Approver 3 (PENRO) --}}
                    @if($TravelOrder->is_approve1 == true && $TravelOrder->is_approve2 == true && $TravelOrder->is_rejected1 != true && $TravelOrder->is_rejected2 != true && $TravelOrder->is_rejected3 != true && $TravelOrder->is_approve3 != true)
                    @if($TravelOrderSignatory->approver3 == $UserEmployee->id && auth()->check())
                    <tr>
                        <td> {{$TravelOrder->created_at ?? 'N/A'}}</td>
                        <td> {{$TravelOrder->daterange}}</td>
                        <td>{{ $TravelOrder->employee->firstname . ' ' . $TravelOrder->employee->lastname}}</td>
                        <td> {{$TravelOrder->destinationoffice}}</td>
                        <td> {{$TravelOrder->purpose}}</td>
                        <td>
                            @can('accept', $TravelOrder)
                            <button type="button" title="Approve Travel Order" class="btn btn-success accept-to-btn" data-to-id="{{ $TravelOrder->id }}" data-employee-name="{{ $TravelOrder->employee->firstname }} {{ $TravelOrder->employee->lastname }}">
                                <i class="fas fa-check"></i>
                            </button>
                            @endcan
                            @can('reject', $TravelOrder)
                            <button type="button" title="Reject Travel Order" class="btn btn-danger reject-to-btn" data-to-id="{{ $TravelOrder->id }}" data-employee-name="{{ $TravelOrder->employee->firstname }} {{ $TravelOrder->employee->lastname }}">
                                <i class="fas fa-times"></i>
                            </button>
                            @endcan
                            <button class="btn btn-warning" title="Edit Date Range" data-toggle="modal" data-target="#edit-travelorder-modal-lg" data-id="{{ $TravelOrder->id }}" data-daterange="{{ $TravelOrder->daterange }}" data-employee="{{ $TravelOrder->employee->lastname.', '.$TravelOrder->employee->firstname.' '.$TravelOrder->employee->middlename }}" data-destination="{{ $TravelOrder->destinationoffice }}" data-purpose="{{ $TravelOrder->purpose }}" data-is-penro="1">
                                <i class="fas fa-calendar-alt"></i>
                            </button>
                        </td>
                    </tr>
                    @endif
                    @endif

                    @endif {{-- end: TO belongs to this signatory --}}

                    @endforeach
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Single Reusable Reject Modal -->
<div class="modal fade" id="reject-travelorder-modal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Reject Travel Order</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="rejectTravelOrderForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    You sure you want to reject Travel Order of <strong id="rejectEmployeeName"></strong>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Single Reusable Accept Modal -->
<div class="modal fade" id="accept-travelorder-modal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Accept Travel Order</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="acceptTravelOrderForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    You sure you want to accept Travel order of <strong id="acceptEmployeeName"></strong>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Accept</button>
                </div>
            </form>
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
<script src="{{asset('plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
<script>
    $(function() {
        bsCustomFileInput.init();
    });
    $(function() {
        $("#example1").DataTable({
            "responsive": true
            , "lengthChange": false
            , "autoWidth": false
            , "buttons": ['excel']
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

        // Handle reject button click - single modal for all
        // Use delegated event to work with DataTables dynamically generated rows
        $(document).on('click', '.reject-to-btn', function() {
            var toId = $(this).data('to-id');
            var employeeName = $(this).data('employee-name');

            // Update modal content
            $('#rejectEmployeeName').text(employeeName);

            // Update form action
            var actionUrl = '{{ route("travel-order.reject", ":id") }}';
            actionUrl = actionUrl.replace(':id', toId);
            $('#rejectTravelOrderForm').attr('action', actionUrl);

            // Show modal
            $('#reject-travelorder-modal').modal('show');
        });

        // Handle accept button click - single modal for all
        // Use delegated event to work with DataTables dynamically generated rows
        $(document).on('click', '.accept-to-btn', function() {
            var toId = $(this).data('to-id');
            var employeeName = $(this).data('employee-name');

            // Update modal content
            $('#acceptEmployeeName').text(employeeName);

            // Update form action
            var actionUrl = '{{ route("travel-order.accept", ":id") }}';
            actionUrl = actionUrl.replace(':id', toId);
            $('#acceptTravelOrderForm').attr('action', actionUrl);

            // Show modal
            $('#accept-travelorder-modal').modal('show');
        });
    });

</script>
@endsection

@section('mails-layout')
<link rel="stylesheet" href="{{asset('/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{asset('/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{asset('/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endsection
