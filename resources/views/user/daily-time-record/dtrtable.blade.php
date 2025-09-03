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
            <li class="breadcrumb-item active">My Daily Time Record</li>
          
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

              @can('create',\App\Models\DtrRequest::class )
              {{-- <button type="button" class="btn btn-default">
                <a href="{{ route('my-daily-time-record.request') }}">
                <i class="fas fa-plus" ></i>
                {{ __ ('My Request')}}
              </a>
              </button> --}}
              @endcan
              {{-- <button type="button" class="btn btn-default float-right ml-1" data-toggle="modal" data-target="#new-leave-modal-lg"  data-backdrop="static" data-keyboard="false">
                <i class="fas fa-print" ></i>
                {{ __ ('Print')}}
              </button> --}}
              <button type="button" class="btn btn-default" data-toggle="modal" data-target="#new-leave-modal-lg"  data-backdrop="static" data-keyboard="false">
                <i class="fas fa-book" ></i>
                {{ __ ('View Log Book')}}
              </button>
        
            </div>
            <div class="card-header">
                
                <div class="row invoice-info mb-2 mt-4">
                    <div class="col-sm-2 invoice-col">
                      <strong>  Employee Name : </strong>
                    </div>
                    <div class="col-sm-10 invoice-col">
                    {{ $Employee->firstname . " " .$Employee->middlename . " " . $Employee->lastname }}
                    </div>
                </div>
      
              
                
                <div class="row invoice-info mb-2">
                    <div class="col-sm-2 invoice-col">
                      <strong>  Status : </strong>
                    </div>
                    <div class="col-sm-10 invoice-col">
                    {{ $Employee->empstatus }}
                    </div>
                </div>

                <div class="row invoice-info mb-2">
                    <div class="col-sm-2 invoice-col">
                      <strong>  Date Range : </strong>
                    </div>
                    <div class="col-sm-10 invoice-col">
                      {{ $FinalDateRage }}
                    </div>
                </div>

             
                <div class="card-body">
                   
                    <table id="example1" class="table table-bordered table-striped">
                         <thead>
                            <tr>
                                <th class="text-center">Date</th>
                                <th class="text-center">Morning In</th>
                                <th class="text-center">Morning Out</th>
                                <th class="text-center">Afternoon In</th>
                                <th class="text-center">Afternoon Out</th>
                                <th class="text-center">LT </th>
                                <th class="text-center">UT </th>
                                <th class="text-center">Remarks </th>
                                                  
                            </tr>
                        </thead>
                        <tbody>
                          @foreach ($all_dates as $Date)
                              <tr class="text-center">
                                    <td> {{ $Date }}</td>
                                    @foreach ($all_dtr as $Dtr)
                                        @if($Dtr['0'] == $Date)
                                            @switch($Dtr['1'])
                                                @case('TIME IN - MORNING')
                                                    <td hidden>{{ $TimeinMorning = $Dtr['2'] }}</td> 
                                                     @break
                                                @case('TIME OUT - MORNING')
                                                    <td hidden>{{ $TimeOutMorning = $Dtr['2'] }}</td>
                                                    @break
                                                @case('TIME IN - AFTERNOON')
                                                    <td hidden>{{ $TimeInAfternoon = $Dtr['2'] }}</td>  
                                                    @break    
                                                @case('TIME OUT - AFTERNOON')
                                                    <td hidden> {{ $TimeOutAfternoon = $Dtr['2'] }}</td>  
                                                    @break  
                                                @case('MORNING')
                                                    @if(!empty($Dtr['3'] && $Dtr['3' == 'ABSENT'])) 
                                                        <td hidden> {{ $TimeinMorning = 'ABSENT' }}</td>  
                                                        <td hidden> {{ $TimeOutMorning = 'ABSENT' }}</td>           
                                                    @endif
                                                    @break
                                                @case('AFTERNOON')
                                                    @if(!empty($Dtr['3'] && $Dtr['3' == 'ABSENT'])) 
                                                        <td hidden> {{ $TimeInAfternoon = 'ABSENT' }}</td>  
                                                        <td hidden> {{ $TimeOutAfternoon = 'ABSENT' }}</td>                                       
                                                    @endif
                                                    @break
                                                @case('WHOLE DAY')
                                                    @if(!empty($Dtr['3'] && $Dtr['3' == 'ABSENT'])) 
                                                        <td hidden> {{ $TimeinMorning = 'ABSENT' }}</td>  
                                                        <td hidden> {{ $TimeOutMorning = 'ABSENT' }}</td>  
                                                        <td hidden> {{ $TimeInAfternoon = 'ABSENT' }}</td>  
                                                        <td hidden> {{ $TimeOutAfternoon = 'ABSENT' }}</td>                 
                                                    @endif
                                                    @break
                                                @case('EVENT - MORNING')
                                                    @if(!empty($Dtr['3'])) 
                                                        <td hidden> {{ $TimeinMorning = 'EVENT' }}</td>  
                                                        <td hidden> {{ $TimeOutMorning = 'EVENT' }}</td>
                                                        <td hidden> {{ $EventRemarks = $Dtr['3'] }}</td>                                              
                                                    @endif
                                                    @break
                                                @case('EVENT - AFTERNOON')
                                                    @if(!empty($Dtr['3'])) 
                                                        <td hidden> {{ $TimeInAfternoon = 'EVENT' }}</td>  
                                                        <td hidden> {{ $TimeOutAfternoon =  'EVENT' }}</td>  
                                                        <td hidden> {{ $EventRemarks = $Dtr['3'] }}</td>
                                                   
                                                    @endif
                                                    @break
                                                @case('EVENT - WHOLE DAY')
                                                    @if(!empty($Dtr['3'])) 
                                                        <td hidden> {{ $TimeinMorning = 'EVENT' }}</td>  
                                                        <td hidden> {{ $TimeOutMorning = 'EVENT' }}</td>  
                                                        <td hidden> {{ $TimeInAfternoon = 'EVENT' }}</td>  
                                                        <td hidden> {{ $TimeOutAfternoon = 'EVENT' }}</td>  
                                                        <td hidden> {{ $EventRemarks = $Dtr['3'] }}</td>
                                                    @endif
                                                    @break
                                                @case('SATURDAY')
                                                    <td hidden> {{ $TimeinMorning = 'SATURDAY' }}</td>  
                                                    <td hidden> {{ $TimeOutMorning = 'SATURDAY' }}</td>  
                                                    <td hidden> {{ $TimeInAfternoon = 'SATURDAY' }}</td>  
                                                    <td hidden> {{ $TimeOutAfternoon = 'SATURDAY' }}</td>  
                                                    @break
                                                @case('SUNDAY')
                                                <td hidden> {{ $TimeinMorning = 'SUNDAY' }}</td>  
                                                <td hidden> {{ $TimeOutMorning = 'SUNDAY' }}</td>  
                                                <td hidden> {{ $TimeInAfternoon = 'SUNDAY' }}</td>  
                                                <td hidden> {{ $TimeOutAfternoon = 'SUNDAY' }}</td>  
                                                    @break
                                                @default                                                  
                                            @endswitch
                                        @endif
                                    @endforeach
                                    {{--  --}}

                                    @foreach ($FinalEvent as $Event)
                                      @if($Event['0']== $Date)
                                        <td hidden> {{ $TimeinMorning = 'HOLIDAY' }}</td>  
                                        <td hidden> {{ $TimeOutMorning = 'HOLIDAY' }}</td>  
                                        <td hidden> {{ $TimeInAfternoon =  'HOLIDAY' }}</td>  
                                        <td hidden> {{ $TimeOutAfternoon =  'HOLIDAY' }}</td> 
                                        <td hidden> {{ $EventRemarks = $Event['2'] }}</td>

                                      @endif
                                    @endforeach
                                    @foreach ($FinalLeaves as $Leave)
                                      @if($Leave['0'] == $Date)
                                        <td hidden> {{ $TimeinMorning = 'LEAVE' }}</td>  
                                        <td hidden> {{ $TimeOutMorning = 'LEAVE' }}</td>  
                                        <td hidden> {{ $TimeInAfternoon = 'LEAVE' }}</td>  
                                        <td hidden> {{ $TimeOutAfternoon = 'LEAVE' }}</td> 
                                        <td hidden> {{ $EventRemarks = $Leave['2'] }}</td>
                                      @endif
                                    @endforeach
                                    @foreach ($FinalTravelOrders as $TravelOrder)
                                    @if($TravelOrder['0'] == $Date)
                                      <td hidden> {{ $TimeinMorning = 'TRAVEL ORDER' }}</td>  
                                      <td hidden> {{ $TimeOutMorning = 'TRAVEL ORDER' }}</td>  
                                      <td hidden> {{ $TimeInAfternoon = 'TRAVEL ORDER' }}</td>  
                                      <td hidden> {{ $TimeOutAfternoon = 'TRAVEL ORDER' }}</td> 
                                      <td hidden> {{ $EventRemarks = $TravelOrder['2'] }}</td>
                                    @endif
                                  @endforeach
                                    @if(!empty($TimeinMorning))
                                      @if($TimeinMorning == 'TRAVEL ORDER' || $TimeinMorning == 'LEAVE' || $TimeinMorning == 'SATURDAY' || $TimeinMorning == 'SUNDAY' || $TimeinMorning == 'HOLIDAY' || $TimeinMorning == 'EVENT' )
                                      <td colspan="4" class="text-center" >{{ $TimeinMorning }}</td>  
                                      @else
                                      <td class="text-center" >{{ $TimeinMorning }}</td> 
                                      @endif                                 
                                    @else
                                    <td class="bg-warning"></td>
                                    @endif
                                    @if(!empty($TimeOutMorning))
                                      @if($TimeinMorning == 'TRAVEL ORDER' || $TimeinMorning == 'LEAVE' || $TimeinMorning == 'SATURDAY' || $TimeinMorning == 'SUNDAY' || $TimeinMorning == 'HOLIDAY' || $TimeinMorning == 'EVENT')
                                      @else
                                     <td class="text-center" >{{ $TimeOutMorning }}</td>    
                                     @endif                              
                                    @else
                                    <td class="bg-warning"></td>
                                    @endif
                                    @if(!empty($TimeInAfternoon))
                                    @if($TimeinMorning == 'TRAVEL ORDER' || $TimeinMorning == 'LEAVE' || $TimeinMorning == 'SATURDAY' || $TimeinMorning == 'SUNDAY' || $TimeinMorning == 'HOLIDAY' || $TimeinMorning == 'EVENT')
                                    @else
                                    <td class="text-center" >{{ $TimeInAfternoon }}</td>     
                                    @endif                             
                                    @else
                                    <td class="bg-warning"></td>
                                    @endif
                                    @if(!empty($TimeOutAfternoon))
                                    @if($TimeinMorning == 'TRAVEL ORDER' || $TimeinMorning == 'LEAVE' || $TimeinMorning == 'SATURDAY' || $TimeinMorning == 'SUNDAY' || $TimeinMorning == 'HOLIDAY' || $TimeinMorning == 'EVENT')
                                    @else
                                    <td class="text-center" >{{ $TimeOutAfternoon }}</td>           
                                    @endif                       
                                    @else
                                    <td class="bg-warning"></td>
                                    @endif
                                    @if(!empty($lates))
                                        @foreach ($lates as $Late)
                                            @if($Late['0'] == $Date)
                                                @if($Late['1'] != '0')  
                                                 <td class="text-center text-danger"> {{ $Late['1']}}</td>
                                                 @else
                                                 <td></td>
                                                @endif
                                            @endif
                                        @endforeach
                                    @endif
                                    @if(!empty($uts))
                                        @foreach ($uts as $UT)
                                            @if($UT['0'] == $Date)
                                                @if($UT['1'] != '00:00')  
                                                <td class="text-center text-danger"> {{ $UT['1']}}</td>
                                                @else
                                                <td></td>
                                                @endif
                                            @endif
                                        @endforeach
                                    @endif
                                    @if(!empty($EventRemarks))
                                    <td>{{ $EventRemarks }}</td>                                  
                                    @else
                                    <td></td>
                                    @endif 
                                    <td hidden> {{ $TimeOutAfternoon = '' , $TimeInAfternoon = '' , $TimeOutMorning = '' , $TimeinMorning= '' , $LateMorning = '' , $LateAfternoon = '', $EventRemarks = ''}}</td> 
                                  
                                  </tr>                              
                          @endforeach
                        </tbody>                        
                    </table>
                </div>           
            </div>   
          </div>
        </div>
      </div>
    </div>

   </section>
</div>

@include('user.daily-time-record.request')  

@endsection


 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 