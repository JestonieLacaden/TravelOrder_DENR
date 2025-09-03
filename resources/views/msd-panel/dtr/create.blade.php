@extends('layouts.app')

@section('content')

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">New DTR</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">MSD Panel</li>
            <li class="breadcrumb-item"><a href="{{ route('daily-time-record.index') }}">Daily Time Record</a></li>
            <li class="breadcrumb-item active">Create</li>
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
          
                <div class="card-body">

                  <div class="row ">
                    <nav class="w-100">
                      <div class="nav nav-tabs" id="product-tab" role="tablist">
                        <a class="nav-item nav-link active" id="Timein-tab" data-toggle="tab" href="#Timein" role="tab" aria-controls="Timein" aria-selected="true"> <i class="fas fa-user"></i>  Time In / Time Out </a> 
                        <a class="nav-item nav-link" id="absent-tab" data-toggle="tab" href="#absent" role="tab" aria-controls="absent" aria-selected="false"><i class="fas fa-user"></i>  Absent</a>
                        <a class="nav-item nav-link" id="event-tab" data-toggle="tab" href="#event" role="tab" aria-controls="event" aria-selected="false"> <i class="fas fa-calendar"></i>  Events</a>
                      </div>
                    </nav>
                    <div class="tab-content p-3 col-12" id="nav-tabContent">
                      <div class="tab-pane fade show active" id="Timein" role="tabpanel"> 
                        <form method="POST" action="{{ route('daily-time-record.store') }}" id="myForm" enctype="multipart/form-data">
                          {{ csrf_field() }}
                            <div class="form-group row">
                              <label class="col-sm-2" for="date">DTR Date :<span class="text-danger">*</span></label>
                              <div class=" col-sm-10">
                                <input name="date" id="date" class="form-control" type="date"  value={{ old('date') }} oninput="this.value = this.value.toUpperCase()">
                                @error('date')
                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                                @enderror
                              </div>
                          </div>
    
                        <div class="form-group row">
                            <label class="col-sm-2" for="schedule">Schedule  :<span class="text-danger">*</span></label>
                            <div class="col-sm-10" >
                              <select  id="schedule"  name="schedule" class="form-control select2" style="width: 100%;">
                                @if ($errors->any() || session()->has('message'))
                           
                                @else
                                <option  value="" selected>-- Choose Schedule --</option>
                                @endif
                              
                                <option value = "TIME IN - MORNING"> TIME IN - MORNING</option>     
                                <option value = "TIME OUT - MORNING"> TIME OUT - MORNING</option>   
                                <option value = "TIME IN - AFTERNOON"> TIME IN - AFTERNOON</option>   
                                <option value = "TIME OUT - AFTERNOON"> TIME OUT - AFTERNOON</option>      
                                  
                            </select>
                            @error('schedule')
                              <p class="text-danger text-xs mt-1">{{$message}}</p>
                            @enderror
                            </div>                          
                          </div>
                        
                        <div class="form-group row">
                            <label class="col-sm-2" for="employeeid">Employee Name :<span class="text-danger">*</span></label>
                            <div class="col-sm-10" >
                              <select  id="employeeid"  name="employeeid" class="form-control select2" style="width: 100%;">
                                <option value=""  selected>-- Choose Employee --</option>
                                @foreach($Employees as $Employee)
                                <option value = "{{ $Employee->id}}"> {{ $Employee->firstname . " " . $Employee->middlename . " " . $Employee->lastname . " -  " . $Employee->office->office}}</option>
                                @endforeach
                            </select>
                            @error('employeeid')
                              <p class="text-danger text-xs mt-1">{{$message}}</p>
                            @enderror
                            </div>                          
                          </div>
                           <div class="form-group row" >
                                  <label class="col-sm-2" for="time">Time :<span class="text-danger">*</span></label>
                                  <div class=" col-sm-10">
                                  <input type="time" name="time" id="time" class="form-control" type="text" placeholder="Enter Time"  value={{ old('time') }}  oninput="this.value = this.value.toUpperCase()">
                                  @error('time')
                                  <p class="text-danger text-xs mt-1">{{$message}}</p>
                                  @enderror
                                  </div>
                            </div>
                            @can('create', \App\Models\Dtr_History::class)    
                            <div class="card-footer">
                              <button type="submit" id="submit" class="btn btn-primary">Submit</button>
                            </div>
                            @endcan
                          </form>
                      </div>

                      <div class="tab-pane fade" id="absent" role="tabpanel" aria-labelledby="mail-processing-tab"> 
                        <form method="POST" action="{{ route('daily-time-record.storeabsent') }}" id="myForm" enctype="multipart/form-data">
                          {{ csrf_field() }}
                            <div class="form-group row">
                              <label class="col-sm-2" for="date">DTR Date :<span class="text-danger">*</span></label>
                              <div class=" col-sm-10">
                                <input name="date" id="date" class="form-control" type="date"  value={{ old('date') }} oninput="this.value = this.value.toUpperCase()">
                                @error('date')
                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                                @enderror
                              </div>
                          </div>
                        <div class="form-group row">
                            <label class="col-sm-2" for="schedule">Schedule  :<span class="text-danger">*</span></label>
                            <div class="col-sm-10" >
                              <select  id="schedule"  name="schedule" class="form-control select2" style="width: 100%;">
                                @if ($errors->any() || session()->has('message'))
                               
                                @else
                                <option  value="" selected>-- Choose Schedule --</option>
                                @endif
                                <option value = "MORNING"> MORNING</option>     
                                <option value = "AFTERNOON"> AFTERNOON</option>   
                                <option value = "WHOLE DAY"> WHOLE DAY</option>   
                            </select>
                            @error('schedule')
                              <p class="text-danger text-xs mt-1">{{$message}}</p>
                            @enderror
                            </div>                          
                          </div>
                        <div class="form-group row">
                            <label class="col-sm-2" for="employeeid">Employee Name :<span class="text-danger">*</span></label>
                            <div class="col-sm-10" >
                              <select  id="employeeid"  name="employeeid" class="form-control select2" style="width: 100%;">
                                <option value=""  selected>-- Choose Employee --</option>
                                @foreach($Employees as $Employee)
                                <option value = "{{ $Employee->id}}"> {{ $Employee->firstname . " " . $Employee->middlename . " " . $Employee->lastname . " -  " . $Employee->office->office}}</option>
                                @endforeach
                            </select>
                            @error('employeeid')
                              <p class="text-danger text-xs mt-1">{{$message}}</p>
                            @enderror
                            </div>                          
                          </div>

                          <input hidden readonly type="time" name="time" id="time" class="form-control" type="text" placeholder="Enter Time"  value='00:00:00'>
                          
                          @can('create', \App\Models\Dtr_History::class)  
                            <div class="card-footer">
                              <button type="submit" id="submit" class="btn btn-primary">Submit</button>
                            </div>
                          @endcan
                          </form>

                      </div>

                      <div class="tab-pane fade" id="event" role="tabpanel" aria-labelledby="mail-processing-tab"> 
                        <form method="POST" action="{{ route('daily-time-record.storeevent') }}" id="myForm" enctype="multipart/form-data">
                          {{ csrf_field() }}
                            <div class="form-group row">
                              <label class="col-sm-2" for="date">DTR Date :<span class="text-danger">*</span></label>
                              <div class=" col-sm-10">
                                <input name="date" id="date" class="form-control" type="date"  value={{ old('date') }} oninput="this.value = this.value.toUpperCase()">
                                @error('date')
                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                                @enderror
                              </div>
                          </div>
                        <div class="form-group row">
                            <label class="col-sm-2" for="schedule">Schedule  :<span class="text-danger">*</span></label>
                            <div class="col-sm-10" >
                              <select  id="schedule"  name="schedule" class="form-control select2" style="width: 100%;">
                                @if ($errors->any() || session()->has('message'))
                        
                                @else
                                <option  value="" selected>-- Choose Schedule --</option>
                                @endif
                                <option value = "EVENT - MORNING"> MORNING</option>     
                                <option value = "EVENT - AFTERNOON"> AFTERNOON</option>   
                                <option value = "EVENT - WHOLE DAY"> WHOLE DAY</option>   
                            </select>
                            @error('schedule')
                              <p class="text-danger text-xs mt-1">{{$message}}</p>
                            @enderror
                            </div>                          
                          </div>
                        <div class="form-group row">
                            <label class="col-sm-2" for="employeeid">Employee Name :<span class="text-danger">*</span></label>
                            <div class="col-sm-10" >
                              <select  id="employeeid"  name="employeeid" class="form-control select2" style="width: 100%;">
                                <option value=""  selected>-- Choose Employee --</option>
                                @foreach($Employees as $Employee)
                                <option value = "{{ $Employee->id}}"> {{ $Employee->firstname . " " . $Employee->middlename . " " . $Employee->lastname . " -  " . $Employee->office->office}}</option>
                                @endforeach
                            </select>
                            @error('employeeid')
                              <p class="text-danger text-xs mt-1">{{$message}}</p>
                            @enderror
                            </div>                          
                          </div>

                          <div class="form-group row">
                            <label  class="col-sm-2" for="remarks">Remarks :<span class="text-danger">*</span></label>
                           <div class="col-sm-10">
                            <input name="remarks" id="remarks" class="form-control" type="text" placeholder="Enter Remarks" value="{{old('remarks')}}" oninput="this.value = this.value.toUpperCase()">
                            @error('remarks')
                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                            @enderror
                           </div>
                        
                        </div> 

                        <input hidden readonly type="time" name="time" id="time" class="form-control" type="text" placeholder="Enter Time"  value='00:00:00'>
                        @can('create', \App\Models\Dtr_History::class)    
                        <div class="card-footer">
                            <button type="submit" id="submit" class="btn btn-primary">Submit</button>
                         </div>
                        @endcan
                          </form>
                      </div>

                    </div>
                  </div>
       
                </div>
                        <!-- /.card-body -->
                  
            </div>
          </div>
        </div>
      </div>
    </div>
   </section>
</div>
@endsection