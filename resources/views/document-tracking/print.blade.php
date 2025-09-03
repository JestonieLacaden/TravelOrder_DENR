
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ config('app.name', 'Information System') }}</title>

  {{-- <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback"> --}}
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
</head>
<body>
<div class="wrapper">
  <!-- Main content -->
  <section class="invoice">
    <!-- title row -->
    <div class="row">
      <div class="col-12 text-center p-0"> 
        <img src="{{ asset('images/logo.png') }}" class="brand-image img-circle elevation-3 " style="height: 50px">
        <div>Republic of the Philippines
            </div> 
            <div>
                Department of Environment and Natural Resources
            </div>
            <div>
                <strong>PENRO - Occidental Mindoro</strong>
            </div>
      </div>
    </div>
    <div class="row">
        <div class="col-12 text-center p-0 bx-solid"> 
               <div>
                  <h4><strong>DOCUMENT TRACKING SLIP</strong></h4>
              </div>
        </div>
      </div>
      {{-- <div class="col-9 text-center">
        <h6 class="page-header">
             Republic of the Philippines

      
        </h6>
      </div> --}}
      <!-- /.col -->
    </div>
    <!-- info row -->
    <div class="row invoice-info mb-2">
      <div class="col-sm-2 invoice-col">
        <strong> Document Number : </strong>
      </div>
      <div class="col-sm-10 invoice-col">
      {{ $Document->PDN }}
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
        @if($Document->is_urgent)
        <span class="badge bg-danger ml-1">  URGENT</span>
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
          <strong>  Attachment Details : </strong>
        </div>
        <div class="col-sm-10 invoice-col">
          
          @if(count($AttachmentDetails) > 0)
          @foreach($AttachmentDetails as $AttachmentDetail)
            <div>
                {{$AttachmentDetail->attachmentdetails . ' - ' . $AttachmentDetail->attachment }} 
            </div>
          @endforeach
        @endif
        </div>
      </div>



    <!-- Table row -->
    <div class="row mt-2">
      <div class="col-12 table-responsive">
        <div class ="text-center bg-light color-palette">
            <h5>ROUTING AND ACTION INFORMATION</h5>

        </div>
        <table class="table table-stripped">
          <thead>
          <tr class ="text-center">
            <th style="width: 50px">FROM</th>
            <th style="width: 80px">DATE / TIME OF ACTION</th>
            <th style="width: 50px"> FOR / TO</th>
            <th style="width: 200px">ACTION</th>
          </tr>
          </thead>
          <tbody>
            @foreach($Routes as $Route)
             @foreach($Employees as $Employee)
              @if($Employee->id == $Route->userid) 

              <tr class="border-bottom">
                <td> {{ $Employee->office->office . ' - '. $Route->userUnit->unit }}</td>
                <td>{{$Route->created_at}}</td>
                <td>{{ $Route->office->office . ' - ' . $Route->unit->unit}}</td>
                <td>
                    <div>
                      Status : {{ $Route->action }}
                    </div>
                    <div>
                      By : {{ $Route->user->username }}
                    </div>
                    <div>
                      @if(!empty($Route->remarks))
                      Remarks : {{ $Route->remarks }}
                      @endif
                    </div>
              </tr>
              @endif
              @endforeach
            @endforeach
          </tbody>
        </table>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
<!-- Page specific script -->
<script>
  window.addEventListener("load", window.print());
</script>
</body>
</html>
