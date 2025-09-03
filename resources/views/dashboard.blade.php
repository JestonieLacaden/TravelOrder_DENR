@extends('layouts.app')

@section('content')

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">
                Dasboard</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
        <section class="content">
            <div class="container-fluid">
              <div class="row">
                <div class="col-12">
                  <div class="card  callout callout-info p-2">
                   
                    <div class="card-header">
                     
                      <h3 class="card-title"><i class="fas fa-calendar"></i> Upcoming Events
                        @if(!empty($EventCount))
                        <span class="badge badge-success right">{{ $EventCount }}</span>
                        @endif
                        </span></h3>
                      <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                          <i class="fas fa-minus"></i>
                        </button>
                      </div>
                    
                    </div>
                    <div class="card-body p-0">
                      <ul class="nav nav-pills flex-column">
                        <li class="nav-item">
                          @foreach ($Events as $Event)
                    
                        Posted : <i>[{{ $Event->created_at->diffForHumans() }}]</i>  <br>
                        
                        <span class="text-bold">Event Information : </span>{{ $Event->date }} - {{$Event->subject}} 
                        @if(!empty($Event->attachment))
                        <a href="{{ route('eventattachment.view', [$Event->id])}}" target="_blank"  class="text-primary"> <i class="fas fa-paperclip">Attachment</i></a>
                        @endif
                       <span class="text-xs">by : {{ $Event->user->username }}</span> <br>
                        @if(!empty($Event->remarks))
                        <span class="text-bold">Remarks : </span><i>{{$Event->remarks}}</i><br>
                        @endif
                        <div class="dropdown-divider"></div>
                        @endforeach
                          
                        </li>
          
                        
                      </ul>
                    </div>
                
                    <!-- /.card-body -->
                  </div>
                    

                    {{-- <div class="callout callout-success">
                        <h5><i class="fas fa-info"></i> Notice :</h5>
                        2022-02-25 - SPECIAL NON WORKING HOLIDAY - posted by: Administrator 02-02-2002<br>
                        2022-02-25 - SPECIAL NON WORKING HOLIDAY - posted by: Administrator 02-02-2002
                    </div> --}}
                </div>
            </div>
                {{-- <div class="row">
                    <div class=col-4>
                        <div class="card card-primary card-outline">
                            <div class="card-header d-flex p-0">
                                <div class="card-header">
                                    <h3 class="card-title">Task(s)</h3>
                                </div>     
                            </div>
                            <div class="card-body p-0">
                                <table class="table">
                                  <thead>
                                    <tr>
                                      <th style="width: 10px">#</th>
                                      <th>Task</th>
                                      <th>Progress</th>
                                      <th style="width: 40px">Label</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr>
                                      <td>1.</td>
                                      <td>Update software</td>
                                      <td>
                                        <div class="progress progress-xs">
                                          <div class="progress-bar progress-bar-danger" style="width: 55%"></div>
                                        </div>
                                      </td>
                                      <td><span class="badge bg-danger">55%</span></td>
                                    </tr>
                                    <tr>
                                      <td>2.</td>
                                      <td>Clean database</td>
                                      <td>
                                        <div class="progress progress-xs">
                                          <div class="progress-bar bg-warning" style="width: 70%"></div>
                                        </div>
                                      </td>
                                      <td><span class="badge bg-warning">70%</span></td>
                                    </tr>
                                    <tr>
                                      <td>3.</td>
                                      <td>Cron job running</td>
                                      <td>
                                        <div class="progress progress-xs progress-striped active">
                                          <div class="progress-bar bg-primary" style="width: 30%"></div>
                                        </div>
                                      </td>
                                      <td><span class="badge bg-primary">30%</span></td>
                                    </tr>
                                    <tr>
                                      <td>4.</td>
                                      <td>Fix and squish bugs</td>
                                      <td>
                                        <div class="progress progress-xs progress-striped active">
                                          <div class="progress-bar bg-success" style="width: 90%"></div>
                                        </div>
                                      </td>
                                      <td><span class="badge bg-success">90%</span></td>
                                    </tr>
                                  </tbody>
                                </table>
                              </div>
                        </div>
                    </div>
                    
                    <!-- /.col -->
                  </div> --}}
              </div>
        </section>


    </div>
   
@endsection