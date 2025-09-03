@extends('layouts.app')

@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Box A Management</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active">Financial Management</li>
                        <li class="breadcrumb-item active">Box A Management</li>
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

                    @if(session()->has('failed'))
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h5><i class="icon fas fa-ban"></i>Failed to save!</h5>
                        <div>
                            <ul>
                                <li>
                                    {{ session()->get('error') }}
                                </li>
                            </ul>
                        </div>
                    </div>
                    @endif
 
                    <div class="card">
                        <!-- /.card-header -->
                        <div class="card-header">
                            @can('create', \App\Models\BoxA::class)
                            <button type="button" class="btn btn-default" data-toggle="modal" data-target="#create-boxa-modal-lg"  data-backdrop="static" data-keyboard="false">
                              {{ __ ('New')}}
                            </button>
                            @endcan
                        </div>
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>

                                        <th class="text-center">ID</th>
                                        <th class="text-center">Certified By</th>
                                        <th class="text-center">Position</th>
                                        <th class="text-center">Box</th>
                                        <th  class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($BoxAs as $BoxA)
                                    <tr>
                                        <td class="text-center">{{$BoxA->id}}</td>      
                                        <td>{{ $BoxA->certified_by}}</td>
                                        <td>{{$BoxA->position}}</td>      
                                        <td class="text-center">{{$BoxA->box}}</td>    
                                        <td class="text-center">   
                                            @can('delete',$BoxA)
                                            <button type="button" title="Delete Certified" class="btn btn-danger" data-toggle="modal" data-target="#delete-certified-modal-lg{{ $BoxA->id }}"  data-backdrop="static" data-keyboard="false">
                                              <i class="fas fa-trash"></i>
                                            </button>
                                            @endcan
                                          </td>
                                    </tr>

                                    @include('financial-management.boxa.delete')

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
  
        @include('financial-management.boxa.create')

        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
    
</div>


@endsection

@section('specific-scipt')

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
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["excel"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        });
    });

</script>

@include('partials.flashmessage')
@endsection

@section('specific-layout')
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{asset('/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{asset('/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endsection
