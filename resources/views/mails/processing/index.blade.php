@extends('mails.index')

@section('mails')

<div class="col-md-9">
    <div class="card-header bg-primary">
      <h3 class="card-title ">Processing Document(s)  </h3>
      <h5></h5>
    </div>
    <!-- /.card-header -->
    <div class="card">       
      <!-- /.card-header -->
      <div class="card-body">
        <table id="example1" class="table table-bordered table-striped">
           <thead>
          <tr>
            <th  class="text-center">PDN</th>
            <th  class="text-center">Document Type</th>
            <th  class="text-center">Subject</th>
            <th  class="text-center">Accepted By</th>
            <th  class="text-center">Action</th>
          </tr>
          </thead>
          <tbody>
            @foreach ($Routes as $Route)
            <tr>
              <td>{{$Route->documentid}}</td>
              <td>{{$Route->document->doc_type}}</td>
              <td>{{$Route->document->subject}}</td>
              <td>{{$Route->User->username}}</td>
              <td class="text-center">
                <button type="button" title="View Document" class="btn btn-default" onClick="location.href='{{ route('document-tracking.view',[$Route->document->id]) }}'">
                  <i class="fas fa-eye"></i>
                </button>
                <button type="button" title="Route Document" class="btn btn-success" data-toggle="modal" data-target="#route-document-modal-xl{{ $Route->document->id }}"  data-backdrop="static" data-keyboard="false">
                  <i class="fas fa-route"></i>
                </button>
                <button type="button" title="Mark Document as Close" class="btn btn-danger" data-toggle="modal" data-target="#close-document-modal-lg{{ $Route->document->id }}"  data-backdrop="static" data-keyboard="false">
                  <i class="fas fa-times"></i>
                </button>
              </td>
              </tr>
              @include('mails.processing.close')
              @include('mails.processing.route')
            @endforeach    
            
          
            {{-- @foreach ($Routes1 as $Route)
            <tr>
              @if($Route->unitid == $Employee->unitid)
                  <td>{{$Route->documentid}}</td>
                  <td>{{$Route->document->doc_type}}</td>
                  <td>{{$Route->document->subject}}</td>
                  <td>{{$Route->User->username}}</td>
                  <td class="text-center">
                    <button type="button" title="View Document" class="btn btn-default" onClick="location.href='{{ route('document-tracking.view',[$Route->document->id]) }}'">
                      <i class="fas fa-eye"></i>
                    </button>
                    <button type="button" title="Route Document" class="btn btn-success" data-toggle="modal" data-target="#route-document-modal-xl{{ $Route->document->id }}"  data-backdrop="static" data-keyboard="false">
                      <i class="fas fa-route"></i>
                    </button>
                    <button type="button" title="Mark Document as Close" class="btn btn-danger" data-toggle="modal" data-target="#close-document-modal-lg{{ $Route->document->id }}"  data-backdrop="static" data-keyboard="false">
                      <i class="fas fa-times"></i>
                    </button>
                  </td>
                  @include('mails.processing.close')
                  @include('mails.processing.route')
              @endif
            </tr>
            @endforeach   --}}

          </tbody>
        </table>
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


     
  

