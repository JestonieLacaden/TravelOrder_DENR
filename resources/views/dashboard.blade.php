@extends('layouts.app')

@section('content')

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">
                Dashboard</h1>
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
              <!-- Leave Balance Section -->
              <div class="row">
                <div class="col-md-3">
                  <div class="small-box bg-success">
                    <div class="inner">
                      <h3>{{ number_format(auth()->user()->Employee->vacation_leave_balance ?? 0, 3) }}</h3>
                      <p>Vacation Leave</p>
                    </div>
                    <div class="icon">
                      <i class="fas fa-umbrella-beach"></i>
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="small-box bg-info">
                    <div class="inner">
                      <h3>{{ number_format(auth()->user()->Employee->sick_leave_balance ?? 0, 3) }}</h3>
                      <p>Sick Leave</p>
                    </div>
                    <div class="icon">
                      <i class="fas fa-notes-medical"></i>
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="small-box bg-warning">
                    <div class="inner">
                      <h3>{{ number_format(auth()->user()->Employee->force_leave_balance ?? 0, 3) }}</h3>
                      <p>Force Leave</p>
                    </div>
                    <div class="icon">
                      <i class="fas fa-calendar-times"></i>
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="small-box bg-primary">
                    <div class="inner">
                      <h3>{{ number_format(auth()->user()->Employee->special_privilege_leave_balance ?? 0, 3) }}</h3>
                      <p>Special Privilege</p>
                    </div>
                    <div class="icon">
                      <i class="fas fa-gift"></i>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="small-box bg-secondary">
                    <div class="inner">
                      <h3>{{ number_format(auth()->user()->Employee->solo_parent_leave_balance ?? 0, 3) }}</h3>
                      <p>Solo Parent Leave
                        @if(auth()->user()->Employee && !auth()->user()->Employee->solo_parent_eligible)
                          <span class="badge badge-danger">Not Eligible</span>
                        @endif
                      </p>
                    </div>
                    <div class="icon">
                      <i class="fas fa-user-friends"></i>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="small-box bg-teal">
                    <div class="inner">
                      <h3>{{ number_format(auth()->user()->Employee->wellness_leave_balance ?? 0, 3) }}</h3>
                      <p>Wellness Leave</p>
                    </div>
                    <div class="icon">
                      <i class="fas fa-heartbeat"></i>
                    </div>
                  </div>
                </div>
              </div>
              <!-- End Leave Balance Section -->

              {{-- Upcoming Events section removed --}}


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
