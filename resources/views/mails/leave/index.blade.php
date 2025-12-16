@extends('mails.employeerequest.index')

@section('mails')

<div class="col-md-12">
    <div class="card-header bg-primary">
        <h3 class="card-title ">Leave Request(s) </h3>
        <h5></h5>
    </div>
    <!-- /.card-header -->
    <div class="card">
        <!-- /.card-header -->
        <div class="card-body">
            <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="text-center">Date Range</th>
                        <th class="text-center">Employee Name</th>
                        <th class="text-center">Leave Type</th>
                        <th class="text-center">Date Received</th>
                        <th class="text-center">Leave Year</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($Leaves as $Index => $Leave)
                    @foreach($LeaveSignatories as $LeaveSignatory)
                    @if($Leave->is_approve1 != true && $Leave->is_rejected1 != true)
                    @if($LeaveSignatory->approver1 == $UserEmployee->id && auth()->check())
                    <tr>
                        <td> {{$Leave->daterange}}</td>
                        <td>{{ $Leave->employee->firstname . ' ' . $Leave->employee->lastname}}</td>
                        <td> {{$Leave->leave_type->leave_type}}</td>
                        <td> {{$Leave->created_at->diffForHumans() }}</td>
                        @if(!empty($LeaveYear))
                        @foreach($LeaveYear as $Year)
                        @if($Year['0'] == $Leave->id)
                        <td>{{ $Year['1']}}</td>
                        @endif
                        @endforeach
                        @endif
                        <td>
                            @can('accept', $Leave)
                            <button type="button" title="Edit Leave Credits (Approver 1)" class="btn btn-info" data-toggle="modal" data-target="#edit-leave-modal-lg{{ $Leave->id }} " data-backdrop="static" data-keyboard="false">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            @endcan
                            @can('accept', $Leave)
                            <button type="button" title="Approve Leave" class="btn btn-success" data-toggle="modal" data-target="#accept-leave-modal-lg{{ $Leave->id }} " data-backdrop="static" data-keyboard="false">
                                <i class="fas fa-check"></i> Approve
                            </button>
                            @endcan
                            @can('reject', $Leave)
                            <button type="button" title="Reject Leave" class="btn btn-danger" data-toggle="modal" data-target="#reject-leave-modal-lg{{ $Leave->id }} " data-backdrop="static" data-keyboard="false">
                                <i class="fas fa-times"></i> Reject
                            </button>
                            @endcan
                        </td>
                    </tr>
                    @endif
                    @endif

                    @if($Leave->is_approve1 == true && $Leave->is_rejected1 != true && $Leave->is_rejected2 != true && $Leave->is_approve2 != true )

                    @if($LeaveSignatory->approver2 == $UserEmployee->id && auth()->check())
                    <tr>
                        <td> {{$Leave->daterange}}</td>
                        <td>{{ $Leave->employee->firstname . ' ' . $Leave->employee->lastname}}</td>
                        <td> {{$Leave->leave_type->leave_type}}</td>
                        <td> {{$Leave->created_at->diffForHumans() }}</td>
                        @if(!empty($LeaveYear))
                        @foreach($LeaveYear as $Year)
                        @if($Year['0'] == $Leave->id)
                        <td>{{ $Year['1']}}</td>
                        @endif
                        @endforeach
                        @endif
                        <td>
                            @can('accept', $Leave)
                            <button type="button" title="Edit Recommendation (Approver 2)" class="btn btn-info" data-toggle="modal" data-target="#edit-leave-modal-approver2-{{ $Leave->id }}" data-backdrop="static" data-keyboard="false">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            @endcan
                            @can('accept', $Leave)
                            <button type="button" title="Approve Leave" class="btn btn-success" data-toggle="modal" data-target="#accept-leave-modal-lg{{ $Leave->id }} " data-backdrop="static" data-keyboard="false">
                                <i class="fas fa-check"></i> Approve
                            </button>
                            @endcan
                            @can('reject', $Leave)
                            <button type="button" title="Reject Leave" class="btn btn-danger" data-toggle="modal" data-target="#reject-leave-modal-lg{{ $Leave->id }} " data-backdrop="static" data-keyboard="false">
                                <i class="fas fa-times"></i> Reject
                            </button>
                            @endcan
                        </td>
                    </tr>
                    @endif
                    @endif

                    @if($Leave->is_approve1 == true && $Leave->is_rejected1 != true && $Leave->is_rejected2 != true && $Leave->is_approve2 == true && $Leave->is_approve3 != true && $Leave->is_rejected3 != true)

                    @if($LeaveSignatory->approver3 == $UserEmployee->id && auth()->check())
                    <tr>
                        <td> {{$Leave->daterange}}</td>
                        <td>{{ $Leave->employee->firstname . ' ' . $Leave->employee->lastname}}</td>
                        <td> {{$Leave->leave_type->leave_type}}</td>
                        <td> {{$Leave->created_at->diffForHumans() }}</td>
                        @if(!empty($LeaveYear))
                        @foreach($LeaveYear as $Year)
                        @if($Year['0'] == $Leave->id)
                        <td>{{ $Year['1']}}</td>
                        @endif
                        @endforeach
                        @endif
                        <td>
                            @can('accept', $Leave)
                            <button type="button" title="Edit Disapproval (Approver 3)" class="btn btn-info" data-toggle="modal" data-target="#edit-leave-modal-approver3-{{ $Leave->id }}" data-backdrop="static" data-keyboard="false">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            @endcan
                            @can('accept', $Leave)
                            <button type="button" title="Approve Leave" class="btn btn-success" data-toggle="modal" data-target="#accept-leave-modal-lg{{ $Leave->id }} " data-backdrop="static" data-keyboard="false">
                                <i class="fas fa-check"></i> Approve
                            </button>
                            @endcan
                            @can('reject', $Leave)
                            <button type="button" title="Reject Leave" class="btn btn-danger" data-toggle="modal" data-target="#reject-leave-modal-lg{{ $Leave->id }} " data-backdrop="static" data-keyboard="false">
                                <i class="fas fa-times"></i> Reject
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

@foreach($Leaves as $Leave)
@if(!empty($LeaveYear))
@foreach($LeaveYear as $Year)
@if($Year['0'] == $Leave->id)
@can('viewleave', $Leave)
@include('mails.leave.view')
@endcan
@endif
@endforeach
@endif

<!-- Include all modals unconditionally -->
@include('mails.leave.edit')
@include('mails.leave.edit-approver2')
@include('mails.leave.edit-approver3')

@can('accept', $Leave)
@include('mails.leave.accept')
@endcan
@can('reject', $Leave)
@include('mails.leave.reject')
@endcan
@endforeach

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
    $(function() {
        bsCustomFileInput.init();
    });

</script>
<script>
    $(function() {
        $("#example1").DataTable({
            "responsive": true
            , "lengthChange": false
            , "autoWidth": false
            , "buttons": ['excel']
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });

</script>
@endsection

@section('mails-layout')
<link rel="stylesheet" href="{{asset('/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{asset('/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{asset('/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endsection
