@extends('layouts.app')

@section('content')

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">My Daily Time Record</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('daily-time-record.index') }}">My Daily Time Record</a></li>
            <li class="breadcrumb-item active">My Request</li>
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
            <h5><i class="icon fas fa-check"></i> Successfully save!</h5>
            <ul>
              <li> {{ session()->get('message') }}</li>
            </ul>
         
         
          </div>
          @endif
          @if(session()->has('DTRError1'))
          <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fas fa-ban"></i> Failed to save!</h5>
            <ul>
              <li>Duplicate DTR Entry!</li>
           </ul>
          </div>
          @endif
          @if(session()->has('DTRError2'))
          <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fas fa-ban"></i> Failed to save!</h5>
            <ul>
              <li>Error: Date is Saturday!</li>
           </ul>
          </div>
          @endif
          @if(session()->has('DTRError3'))
          <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fas fa-ban"></i> Failed to save!</h5>
            <ul>
              <li>Error: Date is Sunday!</li>
           </ul>
          </div>
          @endif
          @if(session()->has('DTRError4'))
          <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fas fa-ban"></i> Failed to save!</h5>
            <ul>
              <li>Error : Date has record!</li>
           </ul>
          </div>
          @endif

          <div class="card">
            <div class="card-header">

              <button type="button" class="btn btn-default" title="request" data-toggle="modal"  data-target="#request-modal-lg" data-backdrop="static" data-keyboard="false">
                <i class="fas fa-plus" ></i>
                {{ __ ('My Request')}}
              </button>

        
     
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                 <thead>
                <tr>
                  <th class="text-center">Date</th>
                  <th class="text-center">Type</th>
                  <th class="text-center">Subject</th>
                  <th class="text-center">Remarks</th>
                  <th class="text-center">Attachment</th>
                  <th class="text-center">By</th>
                  <th class="text-center">Action</th>
                </tr>
                </thead>
                <tbody>
                    {{-- @foreach($Events as $Event)
                   
                      <tr>
                        <td>{{ $Event->date}}</td>
                        <td>{{ $Event->type}}</td>
                        <td>{{ $Event->subject}}</td>
                        <td>{{ $Event->remarks}}</td>
                        <td class="text-center">
                          @if(!empty($Event->attachment))
                          <a href="{{ route('eventattachment.view', [$Event->id])}}" target="_blank" class=" bg-success p-2 rounded">View Attachment</a>
                          @endif
                        </td>
                        <td>{{ $Event->user->username}}</td>
                       
                             <td class="text-center">   
                            @can('update', $Event)
                            <button type="button" class="btn btn-default" title="Delete" data-toggle="modal"  data-target="#edit-event-modal-lg{{ $Event->id }} " data-backdrop="static" data-keyboard="false">
                              <i class="fas fa-edit"></i>
                             {{__('Edit')}}
                            </button>
                            @endcan
                            @can('delete', $Event)
                            <button type="button" class="btn btn-default" title="Delete" data-toggle="modal"  data-target="#delete-event-modal-lg{{ $Event->id }} " data-backdrop="static" data-keyboard="false">
                              <i class="fas fa-trash-alt"></i>
                             {{__('Delete')}}
                            </button>
                            @endcan
                        </td> 
                      </tr>
                      @can('create', \App\Models\Event::class)
                      @include('msd-panel.event-panel.event.edit')
                      @endcan
                      @can('update', $Event)
                      @include('msd-panel.event-panel.event.delete')
                      @endcan
                    @endforeach  --}}
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
  

@include('user.daily-time-record.request')  

@endsection

@section('specific-scipt')
        

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


                    
@include('partials.flashmessage')
@endsection

@section('specific-layout')
 <!-- DataTables -->
 <link rel="stylesheet" href="{{asset('/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
 <link rel="stylesheet" href="{{asset('/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
 <link rel="stylesheet" href="{{asset('/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">  
@endsection


     
  

