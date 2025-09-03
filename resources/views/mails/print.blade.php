
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

    <!-- Table row -->
    <div class="row mt-2">
      <div class="col-12 table-responsive">
        <div class ="text-center bg-light color-palette">
            <h5>ROUTING AND ACTION INFORMATION</h5>

        </div>
        <table class="table table-bordered">
          <thead>
          <tr class ="text-center">
            <th style="width: 50px">FROM</th>
            <th style="width: 80px">DATE / TIME OF ACTION</th>
            <th style="width: 50px"> FOR / TO</th>
            <th style="width: 200px">ACTION</th>
          </tr>
          </thead>
          <tbody>
            {{-- @foreach($Routes as $Route) 
              <tr>
                <td>{{$Route->user->username }}</td>
                <td>{{$Route->created_at}}</td>
                <td>{{ $Route->office->office . ' - ' .  $Route->section->section . ' - ' . $Route->unit->unit}}</td>
                <td>{{$Route->action . " / Remarks : " . $Route->remarks  }}</td>

            
              </tr>
            @endforeach --}}
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
