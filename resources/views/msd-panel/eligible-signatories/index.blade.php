@extends('layouts.app')

@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Eligible Signatories</h1>
                    <p class="text-sm text-muted">Manage employees eligible to be signatories for Travel Order & Leave</p>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item">MSD - Approver</li>
                        <li class="breadcrumb-item active">Eligible Signatories</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    @if(session()->has('message'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h5><i class="icon fas fa-check"></i> {{ session()->get('message') }}</h5>
                    </div>
                    @endif

                    <div class="card">
                        <div class="card-header">
                            <button type="button" class="btn btn-default" data-toggle="modal" data-target="#add-signatory-modal">
                                <i class="fas fa-plus"></i> Add Eligible Signatory
                            </button>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th class="text-center">Employee Name</th>
                                        <th class="text-center">Position</th>
                                        <th class="text-center">Eligible Role</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($eligibleSignatories as $eligible)
                                    <tr>
                                        <td>{{ $eligible->employee->lastname }}, {{ $eligible->employee->firstname }} {{ $eligible->employee->middlename }}</td>
                                        <td>{{ $eligible->employee->position }}</td>
                                        <td class="text-center">
                                            @if($eligible->role === 'section_chief')
                                            <span class="badge badge-primary">Section Chief</span>
                                            @elseif($eligible->role === 'division_chief')
                                            <span class="badge badge-info">Division Chief</span>
                                            @else
                                            <span class="badge badge-success">PENRO</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <form method="POST" action="{{ route('eligible-signatories.destroy', $eligible->id) }}" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-default btn-sm" onclick="return confirm('Are you sure?')">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
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
<!-- /.content-wrapper -->

<!-- Add Signatory Modal -->
<div class="modal fade" id="add-signatory-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Eligible Signatory</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('eligible-signatories.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="employee_id">Employee <span class="text-danger">*</span></label>
                        <select name="employee_id" id="employee_id" class="form-control select2" required style="width: 100%;">
                            <option value="">-- Select Employee --</option>
                            @foreach($allEmployees as $employee)
                            <option value="{{ $employee->id }}">
                                {{ $employee->lastname }}, {{ $employee->firstname }} - {{ $employee->position }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="role">Eligible Role <span class="text-danger">*</span></label>
                        <select name="role" id="role" class="form-control" required>
                            <option value="">-- Select Role --</option>
                            <option value="section_chief">Section Chief</option>
                            <option value="division_chief">Division Chief</option>
                            <option value="penro">PENRO</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Signatory</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $(function() {
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false
        });

        $('.select2').select2({
            theme: 'bootstrap4',
            dropdownParent: $('#add-signatory-modal')
        });
    });
</script>
@endsection
