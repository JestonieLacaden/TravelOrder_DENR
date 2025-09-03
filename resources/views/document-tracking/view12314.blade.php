
<div class="modal fade" id="view-document-modal-xl{{$Document->id}}" >
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">View Document</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <div class="modal-body">
      
      
      <!-- Main content -->

      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <!-- left column -->
            <div class="col-md-12">
              <!-- general form elements -->
              <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">Document Information</h3>
              
                </div>
                
                <!-- /.card-header -->
                <!-- form start -->

                {{-- {{ route('office.store') }} --}}
               <!-- Main content -->
            <div class="invoice p-3 mb-3">
              <!-- title row -->
              <div class="row">
                <div class="col-12">
                  <h5>
                  
                    <i class="fas fa-envelope"></i>
                    @if ( $Document->is_urgent  != 0)
                    <span class=" p-1 rounded bg-danger text-xs">URGENT</span>
                    @endif {{ $Document->subject }}
                    <i class="text-sm"> Dated : {{ $Document->datereceived }}</i>
             
                
                  </h5>
                </div>
                <!-- /.col -->
              </div>
              <!-- info row -->
              <div class="row invoice-info">
                <div class="col-sm-4 invoice-col">
                  From
                  <address>
                    <strong>{{ $Document->originatingoffice }}.</strong><br>
                    {{ $Document->sendername }}<br>
                    {{ $Document->senderaddress }}<br>
                    </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                  To
                  <address>
                    <strong>{{ $Document->addressee }}</strong><br>
                    Occidental Mindoro<br>
                  </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                  <b>PDN :  {{ $Document->PDN }}</b><br>
                  <b>Document Type :</b> {{ $Document->doc_type }}<br>
                
                  
                  {{-- 2 Pages<br>
                  <b>File(s):</b> <span class=" p-1 rounded bg-secondary text-xs"> 2.222.png</span> --}}
                </div>
                <!-- /.col -->
                
              </div>
              <b>Attachment(s):</b> <br>
                  @foreach($Document->attachment as $Attachment)
                   {{$Attachment->attachmentdetails}}-
                   <a href="{{ route('attachment.view', [$Attachment->id]) }}">{{$Attachment->attachment}}<br></a>

                  @endforeach
              <!-- /.row -->  
            </div>
            
            <!-- /.invoice -->
          </div><!-- /.col -->
          @can('view',$Document)
            <div class="row no-print">
              <div class="col-12">
                <form  method="POST" action="{{ route('document-tracking.destroy', [$Document->id]) }}">
                {{ csrf_field() }}
                @method('DELETE')
                  <a href="{{ route('document-tracking.print',[$Document->id]) }}" rel="noopener" target="_blank" class="btn btn-default"><i class="fas fa-print" ></i> Print</a>
                  <a href="{{ route('document-tracking.edit',[$Document->id]) }}"  class="btn btn-default"><i class="fas fa-edit" ></i> Edit</a>
                <a data-toggle="modal" data-target="#attachment-document-modal-xl{{$Document->id}}" class="btn btn-default"><i class="fas fa-paperclip"></i> Attachment</a>
                <button type="SUBMIT" class="btn btn-danger float-right" style="margin-right: 5px;">
                  <i class="fas fa-trash"></i> Delete
                </button>
              </form>
            </div>
            </div>
          @endcan
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
        
