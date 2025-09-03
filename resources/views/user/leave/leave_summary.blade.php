@extends('layouts.app')

@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Summary</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('dashboard') }}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{route('userleave.index') }}">Leave Management</a></li>
              <li class="breadcrumb-item active">Summary</li>
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

          
          <div class="card">
            <div class="card-header">
                <form  method="GET" action="{{ route('leave.summaryfilter') }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="form-group row">     
                        <div class="col-sm-2">
                            <select name="year" id="year" class="select2 form-control" style="width: 100%;">
                                @for($i = 2021; $i<=$YearNow; $i++)
                                    @if($i == $YearNow)
                                    <option selected value="{{$i }}"> {{ $i }}</option>
                                    @else
                                    <option value="{{$i }}"> {{ $i }}</option>
                                    @endif
                                @endfor
                            </select>
                        </div>   
                        <button type="SUBMIT" class="btn gray btn-success"> Search</button>       
                    </div>
                </form>
            </div>

            <!-- /.card-header -->
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                 <thead>
              
                        <td class="text-center"> Leave Type </td>
                        <td class="text-center"> Available</td>
                        <td class="text-center"> Used</td>
                        <td class="text-center"> Remaining</td>
              
                </thead>
                <tbody>

                    @if(!empty($EmployeeLeaveCount))
                     
                        @foreach($Leave_Types as $Leave_type)   
                        <td hidden>{{ $Count = 0, $Available = 0}}</td> 
                            @foreach($EmployeeLeaveCount as $EmployeeLeave)
                                @if($EmployeeLeave['1'] == $Leave_type->id)
                                    @if($EmployeeLeave['0'] == $Employee->id && $YearNow == $EmployeeLeave['3']  && $EmployeeLeave['0'] == $Employee->id)
                                    <tr>
                                        {{-- <td>{{ $Leave_type->leave_type}}</td> --}}
                                        <td hidden class="text-center"> {{$Leave_type->available}}</td>
                                        <td  hidden class="text-center"> {{ $Count = $EmployeeLeave['2'] }}</td>
                                        <td  hidden class="text-center"> {{ $Available = $Leave_type->available - $Count }}</td>
                                    </tr>  
                                        @break;           
                                                       
                                    @endif
                                @endif             
                            @endforeach
                            <tr>                     
                          <td>{{ $Leave_type->leave_type}}</td>
                          <td  class="text-center"> {{$Leave_type->available}}</td>
                         
                          <td  class="text-center"> {{ $Count }}</td>
                          <td  class="text-center"> {{$Available }}</td>
                        </tr>
                        @endforeach
                    @else
                        @foreach($Leave_Types as $Leave_type)   
                        <tr>
                          <td>{{ $Leave_type->leave_type}}</td>
                          <td  class="text-center"> {{$Leave_type->available}}</td>
                          <td   class="text-center"> 0</td>
                          <td   class="text-center"> {{  $Leave_type->available  }}</td>
                        </tr>
                        @endforeach
                    @endif
               
           
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
        

@include('partials.flashmessage')
@endsection

@section('specific-layout')
 <!-- DataTables -->
 <link rel="stylesheet" href="{{asset('/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
 <link rel="stylesheet" href="{{asset('/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
 <link rel="stylesheet" href="{{asset('/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">  
@endsection



      
     
  

