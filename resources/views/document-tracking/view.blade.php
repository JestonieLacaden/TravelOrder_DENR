@extends('layouts.app')

@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">View Document</h1>
          </div><!-- /.col -->
          {{-- <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/">Home</a></li>
              <li class="breadcrumb-item"><a href="{{ route('document-tracking.index') }}">Document Tracking System</a></li>
              <li class="breadcrumb-item active"> View Document</li>
            </ol>
          </div><!-- /.col --> --}}
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
            <h5><i class="icon fas fa-check"></i>    {{ session()->get('message') }}</h5>
         
          </div>
          @endif

          <div class="card">
            <div class="card-header">
            
                <div class="row no-print">
                  <div class="col-12">                        
                    <a href="{{ route('document-tracking.print',[$Document->id]) }}" rel="noopener" target="_blank" class="btn btn-default"><i class="fas fa-print" ></i> Print</a>
                    @can('addAttachment', $Document)
                    <a data-toggle="modal" data-target="#attachment-document-modal-xl{{$Document->id}}" class="btn btn-default"><i class="fas fa-paperclip"></i> Add Attachment</a>                 
                    @endcan
                    @can('addRoute', $Document)
                    <a data-toggle="modal" data-target="#route-document-modal-xl{{$Document->id}}" class="btn btn-default"><i class="fas fa-route"></i> Route</a>  
                    @endcan
                    @can('update', $Document)
                    <a href="{{ route('document-tracking.edit',[$Document->id]) }}"  class="btn btn-default"><i class="fas fa-edit" ></i> Edit</a>
                    @endcan
                 
                    @can('addAction', $Document)
                    <button type="button" class="btn btn-default bg-danger float-right" data-toggle="modal" data-target="#add-action-modal-lg{{ $Document->id }}" >
                      <i class="fas fa-times"></i> Mark as Close
                    </button>    
                    @endcan  

                    @can('delete', $Document)
                    <button type="button" class="btn btn-default bg-danger float-right" data-toggle="modal" data-target="#delete-document-modal-lg{{ $Document->id }}" >
                      <i class="fas fa-trash"></i> Delete
                    </button>    
                    @endcan           
                  </div>
                </div>
        

             

            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row invoice-info mb-2">
                    <div class="col-sm-2 invoice-col">
                      <strong> PDN : </strong>
                    </div>
                    <div class="col-sm-10 invoice-col">
                    {{ $Document->PDN }}
                    </div>
                  </div>
              
                  <div class="row invoice-info mb-2">
                      <div class="col-sm-2 invoice-col">
                        <strong> Originating Office : </strong>
                      </div>
                      <div class="col-sm-10 invoice-col">
                      {{ $Document->originatingoffice }}
                      </div>
                  </div>
              
                    <div class="row invoice-info mb-2">
                      <div class="col-sm-2 invoice-col">
                        <strong> Sender Name : </strong>
                      </div>
                      <div class="col-sm-10 invoice-col">
                      {{ $Document->sendername }}
                      </div>
                    </div>
              
                    <div class="row invoice-info mb-2">
                      <div class="col-sm-2 invoice-col">
                        <strong>  Address : </strong>
                      </div>
                      <div class="col-sm-10 invoice-col">
                      {{ $Document->senderaddress }}
                      </div>
                    </div>
              
                    <div class="row invoice-info mb-2">
                      <div class="col-sm-2 invoice-col">
                        <strong>  Subject : </strong>
                      </div>
                      <div class="col-sm-10 invoice-col">
                      {{ $Document->subject }}
                      @if ( $Document->is_urgent  != 0)
                      <span class=" p-1 rounded bg-danger text-xs">URGENT</span>
                      @endif
                      </div>
                    </div>
              
                    <div class="row invoice-info mb-2">
                      <div class="col-sm-2 invoice-col">
                        <strong>  Document Type : </strong>
                      </div>
                      <div class="col-sm-10 invoice-col">
                      {{ $Document->doc_type }}
                      </div>
                    </div>
              
                    <div class="row invoice-info mb-2">
                      <div class="col-sm-2 invoice-col">
                        <strong>  Addressee : </strong>
                      </div>
                      <div class="col-sm-10 invoice-col">
                      {{ $Document->addressee }}
                      </div>
                    </div>
              
                    <div class="row invoice-info mb-2">
                      <div class="col-sm-2 invoice-col">
                        <strong>  Date Received : </strong>
                      </div>
                      <div class="col-sm-10 invoice-col">
                      {{ $Document->datereceived }}
                      </div>
                    </div>  
                    <div class="row invoice-info mb-2">
                        <div class="col-sm-2 invoice-col">
                          <strong>  Attachment(s) : </strong>
                        </div>
                        <div class="col-sm-10 invoice-col"> 
                        @unless ($Document->attachment->isempty())
                         @foreach($Document->attachment as $Attachment)
                            {{ $Attachment->attachmentdetails }} - 
                            <a href="{{ route('attachment.view', [$Attachment->id]) }}" target="_blank">{{$Attachment->attachment}} <br></a>  
                         @endforeach 
                         @else 
                         <strong>  No Attachment </strong>
                         @endunless
                       
                                          
                    </div>
                  </div>
            </div>
            <!-- /.card-body -->
          </div>
          <div class="card">
            <div class="card-header text-center">
                 <h5>ROUTE HISTORY</h5>
             </div>
            <!-- /.card-header -->


             <!-- Timelime example  -->
        <div class="row mt-4">
          <div class="col-md-12">
            <!-- The time line -->
            <div class="timeline">


             <!-- timeline item -->
             @foreach($Routes as $Route) 
                @switch($Route->action )
                    @case('ATTACHED A FILE')
                    <div>  
                      <i class="fas fa-paperclip bg-info"></i>
                      <div class="timeline-item">
                        <span class="time"><i class="fas fa-clock pr-2"></i> {{ $Route->created_at}} </span>
                        <h3 class="timeline-header"><a>{{$Route->action }} : </a>by {{ $Route->user->username }}  
                          @if($Route->is_active == 1) 
                          <span class=" p-1 rounded bg-success text-xs" >ACTIVE </span>
                          @else
                          <span class=" p-1 rounded bg-danger text-xs" >CLOSED </span>
                          @endif 
                        </h3> 
                        <div class="timeline-body p-4">
                       @if(is_null($Route->attachment))

                       <i class="fas fa-paperclip"></i> - <span class="text-danger"> Attachment Deleted</span>
                   
                        @else
                        {{ $Route->attachment->attachmentdetails }} -
                          <a href="{{ route('attachment.view', [$Route->remarks]) }} " target="_blank">   {{ $Route->attachment->attachment }}</a>  
                        
                      @endif
                        </div>
                   
                      </div>
                 
                    </div>
                        @break

                   @case('CLOSED')
                   <div>  
                     <i class="fas fa-times bg-danger"></i>
                     <div class="timeline-item">
                       <span class="time"><i class="fas fa-clock pr-2"></i> {{ $Route->created_at}} </span>
                       <h3 class="timeline-header"><a> {{$Route->action }} : </a>by {{ $Route->user->username }}  
                         @if($Route->is_active == 1) 
                         <span class=" p-1 rounded bg-success text-xs" >ACTIVE </span>
                         @else
                         <span class=" p-1 rounded bg-danger text-xs" >CLOSED </span>
                         @endif 
                      </h3> 
                       <div class="timeline-body p-4">
                         {{ $Route->remarks }}
                       </div>
                     </div>
                   </div>
                    @break

                    @case('DOCUMENT CREATED')
                    <div>  
                      <i class="fas fa-map-marker-alt bg-success"></i>
                      <div class="timeline-item">
                        <span class="time"><i class="fas fa-clock pr-2"></i> {{ $Route->created_at}} </span>
                        <h3 class="timeline-header"><a> {{$Route->action }} : </a>by {{ $Route->user->username }}  
                          @if($Route->is_active == 1) 
                          <span class=" p-1 rounded bg-success text-xs" >ACTIVE </span>
                          @else
                          <span class=" p-1 rounded bg-danger text-xs" >CLOSED </span>
                          @endif 
                       </h3> 
                        <div class="timeline-body p-4">
                          {{ $Route->remarks }}
                        </div>
                      </div>
                    </div>
                        @break

                    @case('ACCEPTED')
                    <div>  
                      <i class="fas fa-map-marker-alt bg-success"></i>
                      <div class="timeline-item">
                        <span class="time"><i class="fas fa-clock pr-2"></i> {{ $Route->created_at}} </span>
                        <h3 class="timeline-header"><a> {{$Route->action }} : </a>by {{ $Route->user->username }}  
                          @if($Route->is_active == 1) 
                          <span class=" p-1 rounded bg-success text-xs" >ACTIVE </span>
                          @else
                          <span class=" p-1 rounded bg-danger text-xs" >CLOSED </span>
                          @endif 
                       </h3> 
                        <div class="timeline-body p-4">
                          {{ $Route->remarks }}
                        </div>
                      </div>
                    </div>
                        @break

                    @case('REJECTED')
                    <div>  
                      <i class="fas fa-times bg-danger"></i>
                      <div class="timeline-item">
                        <span class="time"><i class="fas fa-clock pr-2"></i> {{ $Route->created_at}} </span>
                        <h3 class="timeline-header"><a> {{$Route->action }} : </a>by {{ $Route->user->username }}  
                          @if($Route->is_active == 1) 
                          <span class=" p-1 rounded bg-success text-xs" >ACTIVE </span>
                          @else
                          <span class=" p-1 rounded bg-danger text-xs" >CLOSED </span>
                          @endif 
                       </h3> 
                        <div class="timeline-body p-4">
                          {{ $Route->remarks }}
                        </div>
                      </div>
                    </div>
                        @break

                     
                    @default
                    <div>  
                      <i class="fas fa-route bg-warning"></i>
                      <div class="timeline-item">
                        <span class="time"><i class="fas fa-clock pr-2"></i> {{ $Route->created_at}} </span>
                        <h3 class="timeline-header"><a>{{$Route->action }} : </a>{{$Route->office->office }} - {{$Route->section->section }} - {{$Route->unit->unit }} by : </a>{{ $Route->user->username }} 
                          @if($Route->is_active == 1) 
                          <span class=" p-1 rounded bg-success text-xs" >ACTIVE </span>
                          @else
                          <span class=" p-1 rounded bg-danger text-xs" >CLOSED </span>
                          @endif 
                          @can ('delete', $Route)
                     
                          <a data-toggle="modal" data-target="#delete-route-modal-lg{{ $Route->id }}" class=" p-1 mx-1 rounded bg-danger text-sm float-right" >Delete</a>
                          
                          @endcan
                          @can ('update', $Route)
                          <a data-toggle="modal" data-target="#update-route-modal-xl{{ $Route->id }}" class=" p-1 mx-1 rounded bg-warning text-sm float-right" >Update</a>
                          
                          {{-- <span class=" p-1 rounded bg-warning text-sm float-right" >Update</span> --}}
                          {{-- <button type="button" title="Delete Route" class="btn btn-danger float-right" data-toggle="modal" data-target="#route-document-modal-xl{{ $Route->document->id }}"  data-backdrop="static" data-keyboard="false">
                            <i class="fas fa-trash"></i>
                          </button> --}}

                          

                          @endcan

                       </h3> 
                        <div class="timeline-body p-4">
                          <a href=" " ></a>{{ $Route->remarks }} 
                        </div>
                      </div>
                    </div>
                @endswitch 
              @endforeach
              <div>
                <i class="fas fa-clock bg-gray"></i>
              </div>
            </div>
          </div>
          <!-- /.col -->
        </div>
      </div>
      <!-- /.timeline -->
 
        </div>
        
        @include('document-tracking.attachment')
        @include('document-tracking.route')
        @include('document-tracking.delete')
        @include('document-tracking.action')
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </div>
    @foreach($Routes as $Route)
          @if($Route->is_forwarded == true)
            @include('document-tracking.routedelete')
            @include('document-tracking.routeupdate')
          @endif
        @endforeach



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
                "responsive": true, "lengthChange": false, "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
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

