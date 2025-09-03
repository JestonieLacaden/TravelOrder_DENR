@extends('layouts.app')

@section('content')


 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Financial Management </h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Financial Management</li>
            <li class="breadcrumb-item active">Accounting Section</li>
          </ol>
        </div>
      </div>
      
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

        @if(session()->has('error'))
        <div class="alert alert-danger alert-dismissible">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          <h5><i class="icon fas fa-ban"></i>  Failed to save!  </h5> 
            <div>
              {{ session()->get('error') }}
              </div>
        </div>
        @endif

        
        @if(session()->has('failed'))
        <div class="alert alert-danger alert-dismissible">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          <h5><i class="icon fas fa-ban"></i>  Failed to save!  </h5> 
            <div>
              {{ session()->get('failed') }}
              </div>
        </div>
        @endif
      </div>

      <section class="content">
        <div class="row">
          <div class="col-md-3">

            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Folders</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <div class="card-body p-0">
                <ul class="nav nav-pills flex-column">
                  <li class="nav-item active">       
                    <a href="{{ route('fmaccounting.incoming') }}" class="nav-link">
                      <i class="fas fa-download"></i> Incoming Voucher(s)
                      @if($Count['0'] != 0)
                      <span class="badge bg-info float-right">{{ $Count['0'] }}</span>
                      @endif
                    </a>
                  </li>
                  <li class="nav-item">
                 
                    <a href="{{ route('fmaccounting.processing') }}" class="nav-link">
                      <i class="fas fa-sync"></i> Processing Voucher(s)
                      @if($Count['1'] != 0)
                      <span class="badge bg-warning float-right">{{ $Count['1'] }}</span>
                      @endif
                    </a>
                  </li>
                  <li class="nav-item">        
                    <a href="{{ route('fmaccounting.outgoing') }}" class="nav-link">
                      <i class="fas fa-upload"></i> Outgoing Voucher(s)
                      @if($Count['3']!= 0)
                      <span class="badge bg-success float-right">{{ $Count['3'] }}</span>
                      @endif
                    </a>
                  </li>
                  <li class="nav-item">
                
                    <a href="{{ route('fmaccounting.rejected') }}" class="nav-link">
                      <i class="fas fa-trash"></i> Rejected Voucher(s)
                      @if($Count['2'] != 0)
                      <span class="badge bg-danger float-right">{{ $Count['2'] }}</span>
                      @endif
                    </a>
                  </li>
                </ul>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Account Title</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <div class="card-body p-0">
                <ul class="nav nav-pills flex-column">
                  <li class="nav-item active">       
                    <a href="{{ route('fmaccounting.activity') }}" class="nav-link">
                     Account Title
                      @if($Count['6'] != 0)
                      <span class="badge bg-info float-right">{{ $Count['6'] }}</span>
                      @endif
                    </a>
                  </li>
                  <li class="nav-item">
                 
                    <a href="{{ route('fmaccounting.uacs') }}" class="nav-link">
                       UACS
                      @if($Count['7'] != 0)
                      <span class="badge bg-warning float-right">{{ $Count['7'] }}</span>
                      @endif
                    </a>
                  </li>
                </ul>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

    


          </div>
          <!-- /.col -->
          @yield('fm-content')
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </section>
      <!-- /.content -->
    </div>
  </section>
 </div>
@endsection


@section('specific-scipt')

  @yield('mails-script')

@endsection


@section('specific-layout')
<!-- DataTables -->
  @yield('mails-layout')
@endsection




