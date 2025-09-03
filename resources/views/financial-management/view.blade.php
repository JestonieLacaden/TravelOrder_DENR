@extends('layouts.app')

@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">View Voucher</h1>
          </div><!-- /.col -->
          {{-- <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/">Home</a></li>
              <li class="breadcrumb-item"><a href="{{ route('document-tracking.index') }}">Document Tracking System</a></li>
              <li class="breadcrumb-item active"> View Document</li>
            </ol>
          </div><!-- /.col --> --}}
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
            <h5><i class="icon fas fa-check"></i>    {{ session()->get('message') }}</h5>
         
          </div>
          @endif

          @if(session()->has('failed'))
          <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fas fa-ban"></i> Failed to save!</h5>
              <div>
                <ul>
                  <li>
                    {{ session()->get('failed') }}
                  </li>
                </ul>
              </div>   
         
          </div>
          @endif

          <div class="card">
            <div class="card-header">
            
                <div class="row no-print">
                  <div class="col-12">     
                     
                    {{-- @can('printdv', $Voucher) --}}

                     <a href="{{ route('financial-management.printors',[$Voucher->id]) }}" rel="noopener" target="_blank" class="btn btn-default float-right"><i class="fas fa-print" ></i> Print ORS</a> 
                    
                     <a href="{{ route('financial-management.printdv',[$Voucher->id]) }}" rel="noopener" target="_blank" class="btn btn-default float-right"><i class="fas fa-print" ></i> Print DV</a> 
                    
                     {{-- @endcan --}}

              
                    @can('update', $Voucher)
                    <a href="{{ route('document-tracking.edit',[$Voucher->id]) }}"  class="btn btn-default"><i class="fas fa-edit" ></i> Edit</a>
                    @endcan
                 
                    {{-- @can('addAction', $Voucher)
                    <button type="button" class="btn btn-default bg-danger float-right" data-toggle="modal" data-target="#add-action-modal-lg{{ $Voucher->id }}" >
                      <i class="fas fa-times"></i> Mark as Close
                    </button>     
                    @endcan   --}}

                    @can('addPlanningAction', $Voucher)
                    <button type="button" class="btn btn-default" data-toggle="modal" data-target="#add-charging-modal-lg{{ $Voucher->id }}" >
                      <i class="fas fa-plus"></i> Charging (GAA)
                    </button>    
                    <button type="button" class="btn btn-default" data-toggle="modal" data-target="#add-charging-saa-modal-lg{{ $Voucher->id }}" >
                      <i class="fas fa-plus"></i> Charging (SAA)
                    </button>
                    @endcan  

                    @can('addBudgetAction', $Voucher)
                    <button type="button" class="btn btn-default" data-toggle="modal" data-target="#add-ors-modal-lg{{ $Voucher->id }}" >
                      <i class="fas fa-plus"></i> ORS Number
                    </button>    
                    @endcan  

                    @can('addAccountingAction', $Voucher)
                    <button type="button" class="btn btn-default" data-toggle="modal" data-target="#add-dv-modal-lg{{ $Voucher->id }}" >
                      <i class="fas fa-plus"></i> DV Number
                    </button>    
                    @endcan  

                    @can('addAccountingAction', $Voucher)
                    <button type="button" class="btn btn-default" data-toggle="modal" data-target="#add-accountingentry-modal-lg{{ $Voucher->id }}" >
                      <i class="fas fa-plus"></i> Accounting Entry
                    </button>    
                    @endcan  

                    @can('addAccountingAction', $Voucher)
                    <button type="button" class="btn btn-default" data-toggle="modal" data-target="#add-signatory-modal-lg{{ $Voucher->id }}" >
                      <i class="fas fa-plus"></i> Add Signatory(BOX D)
                    </button>    
                    @endcan  
                    @can('addAccountingAction', $Voucher)
                    <button type="button" class="btn btn-default" data-toggle="modal" data-target="#add-review-document-modal-lg{{ $Voucher->id }}" >
                      <i class="fas fa-plus"></i> Add Review of Documents
                    </button>  
                    @endcan  
                    @can('addCashierAction', $Voucher)
                    <button type="button" class="btn btn-default" data-toggle="modal" data-target="#add-cashier-modal-lg{{ $Voucher->id }}" >
                      <i class="fas fa-plus"></i> Check / ADA No.
                    </button>    
                    @endcan 
                    @can('addRoute', $Voucher)
                    <a data-toggle="modal" data-target="#route-voucher-modal-xl{{$Voucher->id}}" class="btn btn-default"><i class="fas fa-route"></i> Route</a>  
                    @endcan



    

                    {{-- @can('delete', $Voucher)
                    <button type="button" class="btn btn-default bg-danger float-right" data-toggle="modal" data-target="#delete-document-modal-lg{{ $Voucher->id }}" >
                      <i class="fas fa-trash"></i> Delete
                    </button>    
                    @endcan            --}}
                  </div>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <div class="row invoice-info mb-2">
                    <div class="col-sm-2 invoice-col">
                      <strong> Date Created : </strong>
                    </div>
                    <div class="col-sm-10 invoice-col">
                    {{ $Voucher->datereceived }}
                    </div>
                  </div>

             

                  <div class="row invoice-info mb-2">
                    <div class="col-sm-2 invoice-col">
                      <strong> Sequence ID : </strong>
                    </div>
                    <div class="col-sm-10 invoice-col">
                    {{ $Voucher->sequenceid }}
                    </div>
                  </div>

                  <div class="row invoice-info mb-2">
                    <div class="col-sm-2 invoice-col">
                      <strong> Certified By : </strong>
                    </div>
                    <div class="col-sm-10 invoice-col">
                    {{ $Voucher->BoxA->certified_by }}
                    </div>
                  </div>
              
                  <div class="row invoice-info mb-2">
                      <div class="col-sm-2 invoice-col">
                        <strong> Payee : </strong>
                      </div>
                      <div class="col-sm-10 invoice-col">
                      {{ $Voucher->AccountName->acct_name }}
                      </div>
                  </div>

              
                    <div class="row invoice-info mb-2">
                      <div class="col-sm-2 invoice-col">
                        <strong> Office: </strong>
                      </div>
                      <div class="col-sm-10 invoice-col">
                      {{ $Voucher->office }}
                      </div>
                    </div>
              
                    <div class="row invoice-info mb-2">
                      <div class="col-sm-2 invoice-col">
                        <strong>  Particulars : </strong>
                      </div>
                      <div class="col-sm-10 invoice-col">
                      {{ $Voucher->particulars }}
                      </div>
                    </div>

                    <div class="row invoice-info mb-2">
                        <div class="col-sm-2 invoice-col">
                          <strong>  Remarks : </strong>
                        </div>
                        <div class="col-sm-10 invoice-col">
                       @if(!empty($Voucher->remarks))
                            {{ $Voucher->remarks }}
                        @endif
                        </div>
                      </div>

                      <div class="row invoice-info mb-2">
                        <div class="col-sm-2 invoice-col">
                          <strong>  Amount : </strong>
                        </div>
                        <div class="col-sm-10 invoice-col">               
                            {{ $FloatAmount }}
                        </div>
                      </div>

                      <div class="dropdown-divider"></div>
                      
              

                      <div class="row invoice-info mb-2">
                        <div class="col-sm-2 invoice-col">
                          <strong>  DV Number : </strong>
                        </div>
                        <div class="col-sm-10 invoice-col">
                          @unless ($DVs->isempty()) 
                          @foreach($DVs as $DV)
                            <div>
                              {{$DV->dvno}}  
                              @can('addAccountingAction',$Voucher)
                              <button type="button" class="btn btn-danger text-xs p-1" data-toggle="modal" data-target="#delete-dv-modal-lg{{ $DV->id }}" >
                                Delete
                              </button> 
                              @endcan
                            </div>
                          @endforeach
                        @else
                        <span class=" p-1 rounded bg-danger text-xs" >N/A </span> 
                        @endunless
                        </div>
                      </div>
                

                      <div class="row invoice-info mb-2">
                        <div class="col-sm-2 invoice-col">
                          <strong>  ADA Number : </strong>
                        </div>
                        <div class="col-sm-10 invoice-col">
                          @unless ($Cashiers->isempty()) 
                          @foreach($Cashiers as $Cashier)
                            <div>
                              {{$Cashier->adano}}  
                              @can('addCashierAction',$Voucher)
                              <button type="button" class="btn btn-danger text-xs p-1" data-toggle="modal" data-target="#delete-cashier-modal-lg{{ $Cashier->id }}" >
                                Delete
                              </button> 
                              @endcan
                            </div>
                          @endforeach
                        @else
                        <span class=" p-1 rounded bg-danger text-xs" >N/A </span> 
                        @endunless
                        </div>
                      </div>


                      <div class="dropdown-divider"></div>

                      <div class="row invoice-info mb-2">
                        <div class="col-sm-2 invoice-col">
                          <strong>  Charging : </strong>
                          @if ($ChargingTotal != 0)
                            <span>Total : {{ number_format($ChargingTotal,2,'.',',') }}</span>
                          @endif

                        </div>
                      </div>
                          <div class="col-sm-12 invoice-col">
                            @unless ($Chargings->isempty()) 
                            <table id="example1" class="table table-bordered">
                              <thead>
                                <tr>  
                                  <th class="text-center">Category</th>
                                  <th class="text-center">PAP</th>
                                  <th class="text-center">Activity</th>
                                  <th class="text-center">Office</th>
                                  <th class="text-center">UACS</th>
                                  <th class="text-center">Office</th>
                                  <th class="text-center">Amount</th>
                                  @can('addPlanningAction',$Voucher)
                                  <th class="text-center" style="width: 20px">Action</th>
                                  @endcan
                                </tr>
                              </thead>
                              <tbody>
                                @foreach($Chargings as $Charging)
                                <tr>
                                  <td>{{ $Charging->category }} </td>   
                                  <td>{{ $Charging->PAP->pap }} </td>            
                                  <td>{{ $Charging->Activity->activity }} </td>
                                  <td>{{ $Charging->activityoffice }} </td>
                                  <td>{{ $Charging->UACS->uacs }} </td>
                                  <td>{{ $Charging->uacsoffice }} </td>
                                  <td class="text-center">{{ number_format($Charging->amount,2,'.',',') }} </td>
                                  @can('addPlanningAction',$Voucher)
                                  <td><button type="button" class="btn btn-danger text-xs p-1" data-toggle="modal" data-target="#delete-charging-modal-lg{{ $Charging->id }}" >
                                    Delete
                                  </button> 
                                  </td>
                                  @endcan
                                </tr>
                              @endforeach 
                              </tbody>
                            </table>   
                          @endunless
                        </div>
                      {{-- </div> --}}

                      <div class="dropdown-divider"></div>
                      
                      <div class="row invoice-info mb-2">
                        <div class="col-sm-2 invoice-col">
                          <strong>  Obligation : </strong>
                        </div>
                      </div>
                          <div class="col-sm-12 invoice-col">
                            @unless ($Obligations->isempty()) 
                            <table id="example2" class="table table-bordered">
                              <thead>
                                <tr>  
                                  <th class="text-center">Date</th>
                                  <th class="text-center">Particulars</th>
                                  <th class="text-center">ORS / JEV / CHECK / ADA / TRA No.</th>
                                  <th class="text-center">Obligation</th>
                                  <th class="text-center">Payable</th>
                                  <th class="text-center">Payment</th>
                                  <th class="text-center">Balance Not Yet Due</th>
                                  <th class="text-center">Balance Due and Demandable</th>

                                  @can('addBudgetAction',$Voucher)
                                  <th class="text-center" style="width: 20px">Action</th>
                                  @endcan
                                </tr>
                              </thead>
                              <tbody>
                                @foreach($Obligations as $Obligation)
                                <tr>
                                  <td>{{ $Obligation->created_at }} </td>
                                  <td>{{ $Obligation->particulars }} </td>
                                  <td>{{ $Obligation->orsno }} </td>
                                  @if ($Obligation->obligation != 0)
                                    <td class="text-center">{{ number_format($Obligation->obligation,2,'.',',') }} </td>
                                  @else
                                   <td></td>
                                  @endif      
                                  @if ($Obligation->payable != 0)
                                  <td class="text-center">{{ number_format($Obligation->payable,2,'.',',') }} </td>
                                  @else
                                  <td></td>
                                  @endif           
                                  @if ($Obligation->payment != 0)
                                  <td class="text-center">{{ number_format($Obligation->payment,2,'.',',') }} </td>
                                  @else
                                  <td></td>
                                  @endif                     
                              
                                  @if ($Obligation->nyd != 0)
                                  <td class="text-center">{{ number_format($Obligation->nyd,2,'.',',') }} </td>
                                  @else
                                  <td></td>
                                  @endif   
                                 
                                  @if ($Obligation->dd != 0)
                                  <td class="text-center">{{ number_format($Obligation->dd,2,'.',',') }} </td>
                                  @else
                                  <td></td>
                                  @endif
                                  
                                  @can('addBudgetAction',$Voucher)
                                  <td><button type="button" class="btn btn-danger text-xs p-1" data-toggle="modal" data-target="#delete-ors-modal-lg{{ $Obligation->id }}" >
                                    Delete
                                  </button> 
                                  </td>
                                  @endcan
                                </tr>
                              @endforeach 
                              </tbody>
                            </table>   
                          @endunless
                        </div>

                        <div class="dropdown-divider"></div>

                        <div class="row invoice-info mb-2">
                          <div class="col-sm-2 invoice-col">
                            <strong>  Accounting Entry : </strong>
                          </div>
                        </div>

                        <div class="col-sm-12 invoice-col">
                          @unless ($AccountingEntries->isempty()) 
                          <table id="example3" class="table table-bordered">
                            <thead>
                              <tr>  
                             
                                <th class="text-center">Account Title</th>
                                <th class="text-center">UACS</th>
                                <th class="text-center">Debit</th>
                                <th class="text-center">Credit</th>
                             
                                @can('addAccountingAction',$Voucher)
                                <th class="text-center" style="width: 20px">Action</th>
                                @endcan
                              </tr>
                            </thead>
                            <tbody>
                              @foreach($AccountingEntries as $AccountingEntry)
                              <tr>
                              
                                <td>{{ $AccountingEntry->AActivity->activity }} </td>
                                <td>{{ $AccountingEntry->AUACS->uacs }} </td>
                                @if ($AccountingEntry->debit != 0)
                                  <td class="text-center">{{ number_format($AccountingEntry->debit,2,'.',',') }} </td>
                                @else
                                 <td></td>
                                @endif      
                                @if ($AccountingEntry->credit != 0)
                                <td class="text-center">{{ number_format($AccountingEntry->debit,2,'.',',') }} </td>
                                @else
                                <td></td>
                                @endif
                              
                                  @can('addAccountingAction',$Voucher)
                                  <td><button type="button" class="btn btn-danger text-xs p-1" data-toggle="modal" data-target="#delete-accountingentry-modal-lg{{ $AccountingEntry->id }}" >
                                    Delete
                                  </button> 
                                  </td>
                                  @endcan
                              </tr>
                            @endforeach 
                            </tbody>
                          </table>   
                        @endunless
                      </div>
                       
                       
                     

            </div>
            <!-- /.card-body -->
          </div>
          <div class="card">
            <div class="card-header text-center">
                 <h5>ROUTE HISTORY</h5>
             </div>
            <!-- /.card-header -->


             <!-- Timelime example  -->
        <div class="row mt-4">
          <div class="col-md-12">
            <!-- The time line -->
            <div class="timeline">


             <!-- timeline item -->
             @foreach($Routes as $Route) 
                @switch($Route->action )
                    @case('ATTACHED A FILE')
                    <div>  
                      <i class="fas fa-paperclip bg-green"></i>
                      <div class="timeline-item">
                        <span class="time"><i class="fas fa-clock pr-2"></i> {{ $Route->created_at}} </span>
                        <h3 class="timeline-header"><a>{{$Route->action }} : </a>by {{ $Route->user->username }}  
                          @if($Route->is_active == 1) 
                          <span class=" p-1 rounded bg-success text-xs" >ACTIVE </span>
                          @else
                          <span class=" p-1 rounded bg-danger text-xs" >CLOSED </span>
                          @endif 
                        </h3> 
                        <div class="timeline-body p-4">
                       @if(is_null($Route->attachment))

                       <i class="fas fa-paperclip"></i> - <span class="text-danger"> Attachment Deleted</span>
                   
                        @else
                        {{ $Route->attachment->attachmentdetails }} -
                          <a href="{{ route('attachment.view', [$Route->remarks]) }} " target="_blank">   {{ $Route->attachment->attachment }}</a>  
                        
                      @endif
                        </div>
                   
                      </div>
                 
                    </div>
                        @break

                   @case('CLOSED')
                   <div>  
                     <i class="fas fa-times bg-danger"></i>
                     <div class="timeline-item">
                       <span class="time"><i class="fas fa-clock pr-2"></i> {{ $Route->created_at}} </span>
                       <h3 class="timeline-header"><a> {{$Route->action }} : </a>by {{ $Route->user->username }}  
                         @if($Route->is_active == 1) 
                         <span class=" p-1 rounded bg-success text-xs" >ACTIVE </span>
                         @else
                         <span class=" p-1 rounded bg-danger text-xs" >CLOSED </span>
                         @endif 
                      </h3> 
                       <div class="timeline-body p-4">
                         {{ $Route->remarks }}
                       </div>
                     </div>
                   </div>
                    @break

                    @case('VOUCHER CREATED')
                    <div>  
                      <i class="fas fa-map-marker-alt bg-success"></i>
                      <div class="timeline-item">
                        <span class="time"><i class="fas fa-clock pr-2"></i> {{ $Route->created_at}} </span>
                        <h3 class="timeline-header"><a> {{$Route->action }} : </a>by {{ $Route->user->username }}  
                          @if($Route->is_active == 1) 
                          <span class=" p-1 rounded bg-success text-xs" >ACTIVE </span>
                          @else
                          <span class=" p-1 rounded bg-danger text-xs" >CLOSED </span>
                          @endif 
                       </h3> 
                        <div class="timeline-body p-4">
                          {{ $Route->remarks }}
                        </div>
                      </div>
                    </div>
                        @break

                    @case('ACCEPTED')
                    <div>  
                      <i class="fas fa-map-marker-alt bg-success"></i>
                      <div class="timeline-item">
                        <span class="time"><i class="fas fa-clock pr-2"></i> {{ $Route->created_at}} </span>
                        <h3 class="timeline-header"><a> {{$Route->action }} : </a>by {{ $Route->user->username }}  
                          @if($Route->is_active == 1) 
                          <span class=" p-1 rounded bg-success text-xs" >ACTIVE </span>
                          @else
                          <span class=" p-1 rounded bg-danger text-xs" >CLOSED </span>
                          @endif 
                       </h3> 
                        <div class="timeline-body p-4">
                          {{ $Route->remarks }}
                        </div>
                      </div>
                    </div>
                        @break

                    @case('REJECTED')
                    <div>  
                      <i class="fas fa-times bg-danger"></i>
                      <div class="timeline-item">
                        <span class="time"><i class="fas fa-clock pr-2"></i> {{ $Route->created_at}} </span>
                        <h3 class="timeline-header"><a> {{$Route->action }} : </a>by {{ $Route->user->username }}  
                          @if($Route->is_active == 1) 
                          <span class=" p-1 rounded bg-success text-xs" >ACTIVE </span>
                          @else
                          <span class=" p-1 rounded bg-danger text-xs" >CLOSED </span>
                          @endif 
                       </h3> 
                        <div class="timeline-body p-4">
                          {{ $Route->remarks }}
                        </div>
                      </div>
                    </div>
                        @break

                    @case('CHARGING CREATED')
                    <div>  
                      <i class="fas fa-plus bg-info"></i>
                      <div class="timeline-item">
                        <span class="time"><i class="fas fa-clock pr-2"></i> {{ $Route->created_at}} </span>
                        <h3 class="timeline-header"><a> {{$Route->action }} : </a>by {{ $Route->user->username }}  
                          @if($Route->is_active == 1) 
                          <span class=" p-1 rounded bg-success text-xs" >ACTIVE </span>
                          @else
                          <span class=" p-1 rounded bg-danger text-xs" >CLOSED </span>
                          @endif 
                       </h3> 
                        <div class="timeline-body p-4">
                          {{ $Route->remarks }}
                        </div>
                      </div>
                    </div>
                        @break

                    @case('ORS CREATED')
                      <div>  
                        <i class="fas fa-plus bg-info"></i>
                        <div class="timeline-item">
                          <span class="time"><i class="fas fa-clock pr-2"></i> {{ $Route->created_at}} </span>
                          <h3 class="timeline-header"><a> {{$Route->action }} : </a>by {{ $Route->user->username }}  
                            @if($Route->is_active == 1) 
                            <span class=" p-1 rounded bg-success text-xs" >ACTIVE </span>
                            @else
                            <span class=" p-1 rounded bg-danger text-xs" >CLOSED </span>
                            @endif 
                        </h3> 
                          <div class="timeline-body p-4">
                            {{ $Route->remarks }}
                          </div>
                        </div>
                      </div>
                    @break

                    @case('DV CREATED')
                    <div>  
                      <i class="fas fa-plus bg-info"></i>
                      <div class="timeline-item">
                        <span class="time"><i class="fas fa-clock pr-2"></i> {{ $Route->created_at}} </span>
                        <h3 class="timeline-header"><a> {{$Route->action }} : </a>by {{ $Route->user->username }}  
                          @if($Route->is_active == 1) 
                          <span class=" p-1 rounded bg-success text-xs" >ACTIVE </span>
                          @else
                          <span class=" p-1 rounded bg-danger text-xs" >CLOSED </span>
                          @endif 
                      </h3> 
                        <div class="timeline-body p-4">
                          {{ $Route->remarks }}
                        </div>
                      </div>
                    </div>
                  @break

                  @case('CASHIER INFORMATION CREATED')
                  <div>  
                    <i class="fas fa-plus bg-info"></i>
                    <div class="timeline-item">
                      <span class="time"><i class="fas fa-clock pr-2"></i> {{ $Route->created_at}} </span>
                      <h3 class="timeline-header"><a> {{$Route->action }} : </a>by {{ $Route->user->username }}  
                        @if($Route->is_active == 1) 
                        <span class=" p-1 rounded bg-success text-xs" >ACTIVE </span>
                        @else
                        <span class=" p-1 rounded bg-danger text-xs" >CLOSED </span>
                        @endif 
                    </h3> 
                      <div class="timeline-body p-4">
                        {{ $Route->remarks }}
                      </div>
                    </div>
                  </div>
                @break

          

                    @default
                    <div>  
                      <i class="fas fa-route bg-warning"></i>
                      <div class="timeline-item">
                        <span class="time"><i class="fas fa-clock pr-2"></i> {{ $Route->created_at}} </span>
                        <h3 class="timeline-header"><a>{{$Route->action }} : </a>{{$Route->office->office }} - {{$Route->section->section }} - {{$Route->unit->unit }} by : {{$Route->user->username }}  </a>
                          @if($Route->is_active == 1) 
                          <span class=" p-1 rounded bg-success text-xs" >ACTIVE </span>
                          @else
                          <span class=" p-1 rounded bg-danger text-xs" >CLOSED </span>
                          @endif 
                       </h3> 
                        <div class="timeline-body p-4">
                          <a href=" " ></a>{{ $Route->remarks }}
                           
                        </div>
                      </div>
                    </div>
                @endswitch   

              @endforeach
              <div>
                <i class="fas fa-clock bg-gray"></i>
              </div>
            </div>
          </div>
          <!-- /.col -->
        </div>
      </div>
      <!-- /.timeline -->

      @can('addRoute', $Voucher)
        @include('financial-management.route')
      @endcan

      @can('addBudgetAction', $Voucher)

       @include('financial-management.budget.processing.ors') 

      @endcan

      @can('addAccountingAction', $Voucher)

        @include('financial-management.accounting.processing.dv') 
    
        @include('financial-management.accounting.processing.accountingentry') 

        @include('financial-management.accounting.processing.signatory') 

        @include('financial-management.accounting.processing.reviewofdocuments')  

      @endcan

  
      @can('addCashierAction', $Voucher)

        @include('financial-management.cashier.processing.cashier') 

      @endcan

      @can('addPlanningAction', $Voucher)
  
        @include('financial-management.planning.processing.charging')  
        @include('financial-management.planning.processing.chargingSAA')  
      @endcan

           
        <!-- /.col -->
      </div>
      <!-- /.row -->
      @foreach($ORSs as $FMORS)
        @include('financial-management.budget.processing.delete') 
      @endforeach

    @foreach($DVs as $DV)
      @include('financial-management.accounting.processing.delete') 
      
    @endforeach

    @foreach($AccountingEntries as $AccountingEntry)
    @include('financial-management.accounting.processing.deleteaccountingentry') 
  @endforeach

    @foreach($Cashiers as $FMCashier)
    @include('financial-management.cashier.processing.delete') 
    @endforeach

    @foreach($Chargings as $FMCharging)
    @include('financial-management.planning.processing.delete') 
    @endforeach



    </div>
    {{-- --}}
    {{-- @include('document-tracking.attachment') --}}


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
          <script>
            $(function () {
              $('#example1').DataTable({
                "paging": false,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
              });
              $('#example2').DataTable({
                "paging": false,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
              });
              $('#example3').DataTable({
                "paging": false,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
              });
            });
          </script>

{{-- <script type="text/javascript">
  $(document).ready(function () {
      $('#papid').on('change', function () {
          var papid = this.value;
          $('#activity').html('');
          $('#activityoffice').html('');
          $('#year').html('');
          $('#rem_bal').html('');
          $('#activity').append('<option value="">Fetching Activity List</option>');
          document.getElementById('rem_bal').value = '';
          $.ajax({
              url: '{{ route('fmplanning.getActivity') }}?papid='+papid,
              type: 'GET',
              success: function (res) {
                  $('#activity').html('<option value="">-- Choose Activity --</option>');
                  $.each(res, function (key, value) {
                      $('#activity').append('<option value="' + value
                          .id + '">' + value.activity + '</option>');
                  });
              }
          });
      });
  });
</script> --}}

{{-- SAA --}}

<script type="text/javascript">
  $(document).ready(function () {

$('#papid_saa').on('change', function () {
  var papid = this.value;
  $('#year_saa').html('');
  $('#rem_bal_saa').html('');
  $('#activityoffice_saa').html('');
  $('#activity_saa').html('');
  $('#year_uacs_saa').html('');
  $('#rem_baluacs_saa').html('');
  $('#uacsoffice_saa').html('');
  $('#uacs_saa').html('');
  $('#activityoffice_saa').append('<option value="">-- Choose Office --</option>');
  $('#uacsoffice_saa').append('<option value="">-- Choose Office --</option>');
  $('#activity_saa').append('<option value="">-- Choose Activity --</option>');
  $('#uacs_saa').append('<option value="">-- Choose UACS --</option>');
  $('#year_uacs_saa').append('<option value="">-- Choose Year --</option>');
  $('#year_saa').append('<option value="">-- Choose Year --</option>');
  $('#expense_class_saa').append('<option value="">-- Fetching Expense Class --</option>');

  document.getElementById('rem_bal').value = '';
  $.ajax({
      url: '{{ route('fmplanning.getRemBalAllocPAPSAA') }}?papid='+papid,
      type: 'GET',      
      
      success: function(res) {
        // $('#year').html('<option value="">-- Choose Year --</option>');
        // $.each(res, function (key, value) {
        //   $('#year').append('<option value="' + value
        //                   .year + '">' + value.year + '</option>');
        // });
        console.log(papid);
        $('#expense_class_saa').html('<option value="">-- Choose Expense Class --</option>');
        $.each(res, function (key, value) {
          $('#expense_class_saa').append('<option value="' + value
                          .expense_class + '">' + value.expense_class + '</option>');
        });
 
      }
  });
});
});
</script>


<script type="text/javascript">
  $(document).ready(function () {

$('#expense_class_saa').on('change', function () {
  var papid =  document.getElementById('papid_saa').value;
  var expense_class = this.value;
  $('#year_saa').html('');
  $('#rem_bal_saa').html('');
  $('#activityoffice_saa').html('');
  $('#activity_saa').html('');
  $('#year_uacs_saa').html('');
  $('#rem_baluacs_saa').html('');
  $('#uacsoffice_saa').html('');
  $('#uacs_saa').html('');
  $('#activityoffice_saa').append('<option value="">-- Choose Office --</option>');
  $('#uacsoffice_saa').append('<option value="">-- Choose Office --</option>');
  $('#activity_saa').append('<option value="">-- Fetching Activity --</option>');
  // $('#uacs').append('<option value="">-- Choose UACS --</option>');
  $('#uacs_saa').append('<option value="">-- Fetching UACS --</option>');
  $('#year_uacs_saa').append('<option value="">-- Choose Year --</option>');
  $('#year_saa').append('<option value="">-- Choose Year --</option>');
  document.getElementById('rem_bal_saa').value = '';
  $.ajax({
      url: '{{ route('fmplanning.ChargingExpenseClassSAA') }}?papid='+papid+'&expense_class='+expense_class,
      type: 'GET',      
      
      success: function(res) {
  
        $('#activity_saa').html('<option value="">-- Choose Activity --</option>');

        $.each(res, function (key, value) {

          // $('#activity').append('<option value="' + value
          //                 .activityid + '">' + value.activityid + '</option>');

          $('#activity_saa').append('<option value="' + value[0] + '">' + value[1] + '</option>');
        });
 
      }

  
  });

  $.ajax({

    url: '{{ route('fmplanning.ChargingExpenseClassUACSSAA') }}?papid='+papid+'&expense_class='+expense_class,
      type: 'GET',      
      
      success: function(res) {

        $('#uacs_saa').html('<option value="">-- Choose Activity --</option>');
        $.each(res, function (key, value) {

          // $('#activity').append('<option value="' + value
          //                 .activityid + '">' + value.activityid + '</option>');

          $('#uacs_saa').append('<option value="' + value[0] + '">' + value[1] + ' - ' + value[2] + '</option>');
        });
 
      }
  });
});
});
</script>


<script type="text/javascript">
  $(document).ready(function () {

$('#activity_saa').on('change', function () {
  var papid =  document.getElementById('papid_saa').value;
  var expense_class = document.getElementById('expense_class_saa').value;
  var activityid = this.value;
  $('#year_saa').html('');
  $('#rem_bal_saa').html('');
  $('#activityoffice_saa').html('');
  $('#activityoffice_saa').append('<option value="">-- Choose Office --</option>');
  $('#year_saa').append('<option value="">-- Choose Year --</option>');
  document.getElementById('rem_bal_saa').value = '';
  $.ajax({
      url: '{{ route('fmplanning.ChargingActivitySAA') }}?papid='+papid+'&expense_class='+expense_class+'&activityid='+activityid,
      type: 'GET',      
      
      success: function(res) {
        console.log(expense_class);
        $('#year_saa').html('<option value="">-- Choose Year --</option>');
        $.each(res, function (key, value) {

          // $('#activity').append('<option value="' + value
          //                 .activityid + '">' + value.activityid + '</option>');

          $('#year_saa').append('<option value="' + value.year + '">' + value.year + '</option>');
        });
 
      }
  });
});
});
</script>


<script type="text/javascript">
  $(document).ready(function () {

$('#year_saa').on('change', function () {
  var papid =  document.getElementById('papid_saa').value;
  var expense_class = document.getElementById('expense_class_saa').value;
  var activityid = document.getElementById('activity_saa').value;
  var year = this.value;
  $('#rem_bal_saa').html('');
  $('#activityoffice_saa').html('');
  $('#activityoffice_saa').append('<option value="">-- Choose Office --</option>');

  document.getElementById('rem_bal').value = '';
  $.ajax({
      url: '{{ route('fmplanning.ChargingYearSAA') }}?papid='+papid+'&expense_class='+expense_class+'&activityid='+activityid+'&year='+year,
      type: 'GET',      
      
      success: function(res) {
     
        $('#activityoffice_saa').html('<option value="">-- Choose Year --</option>');
        $.each(res, function (key, value) {

          // $('#activity').append('<option value="' + value
          //                 .activityid + '">' + value.activityid + '</option>');

          $('#activityoffice_saa').append('<option value="' + value.office + '">' + value.office + '</option>');
        });
 
      }
  });
});
});
</script>


<script type="text/javascript">
  $(document).ready(function () {

$('#activityoffice_saa').on('change', function () {
  var year = document.getElementById('year_saa').value;
  var activityid =   document.getElementById('activity_saa').value;
  var papid =document.getElementById('papid_saa').value;
  var office = this.value;
  var expense_class = document.getElementById('expense_class_saa').value;
  $('#rem_bal_saa').html('');
  document.getElementById('rem_bal_saa').value = '';
  $.ajax({
      url: '{{ route('fmplanning.ChargingOfficeSAA') }}?activityid='+activityid+'&year='+year+'&office='+office+'&papid='+papid+'&expense_class='+expense_class,
      type: 'GET',

      success: function(res) {
        $.each(res, function (key, value) {
          let num = value.rem_bal;
          var usFormat = num.toLocaleString('en-US');
          document.getElementById('rem_bal_saa').value =  usFormat;
        });
 
      }
  });
});
});
</script>


<script type="text/javascript">
  $(document).ready(function () {

$('#uacs_saa').on('change', function () {
  var uacsid = this.value;
  var papid =  document.getElementById('papid_saa').value;
  var expense_class = document.getElementById('expense_class_saa').value;

  $('#year_uacs_saa').html('');
  $('#rem_baluacs_saa').html('');
  $('#uacsoffice_saa').html('');
  $('#uacsoffice_saa').append('<option value="">-- Choose Office --</option>');
  $('#year_uacs_saa').append('<option value="">-- Fetching Year --</option>');
  document.getElementById('rem_baluacs_saa').value = '';

  $.ajax({
      url: '{{ route('fmplanning.ChargingUACSSAA') }}?uacsid='+uacsid+'&papid='+papid+'&expense_class='+expense_class,
      type: 'GET',

      success: function(res) {
        $('#year_uacs_saa').html('<option value="">-- Choose Year --</option>');
        $.each(res, function (key, value) {
          $('#year_uacs_saa').append('<option value="' + value
                          .year + '">' + value.year + '</option>');
        });
 
      }
  });
});
});
</script>




<script type="text/javascript">
  $(document).ready(function () {

$('#year_uacs_saa').on('change', function () {
  var year_uacs = this.value;
  var  uacsid = document.getElementById('uacs_saa').value;
  var papid =  document.getElementById('papid_saa').value;
  var expense_class = document.getElementById('expense_class_saa').value;
 
  $('#rem_baluacs_saa').html('');
  $('#uacsoffice_saa').html('');
  $('#uacsoffice_saa').append('<option value="">-- Fetching Office --</option>');
  document.getElementById('rem_baluacs_saa').value = '';

  $.ajax({
      url: '{{ route('fmplanning.ChargingUACSyearSAA') }}?uacsid='+uacsid+'&papid='+papid+'&expense_class='+expense_class+'&year_uacs='+year_uacs,
      type: 'GET',

      success: function(res) {
        $('#uacsoffice_saa').html('<option value="">-- Choose Office --</option>');
        $.each(res, function (key, value) {
          $('#uacsoffice_saa').append('<option value="' + value
                          .office + '">' + value.office + '</option>');
        });
 
      }
  });
});
});
</script>



<script type="text/javascript">
  $(document).ready(function () {

$('#uacsoffice_saa').on('change', function () {
  var uacsoffice = this.value;
  var year_uacs = document.getElementById('year_uacs_saa').value;
  var  uacsid = document.getElementById('uacs_saa').value;
  var papid =  document.getElementById('papid_saa').value;
  var expense_class = document.getElementById('expense_class_saa').value;
  $('#rem_baluacs_saa').html('');
  document.getElementById('rem_baluacs_saa').value = '';

  $.ajax({
      url: '{{ route('fmplanning.ChargingUACSofficeSAA') }}?uacsid='+uacsid+'&papid='+papid+'&expense_class='+expense_class+'&year_uacs='+year_uacs+'&uacsoffice='+uacsoffice,
      type: 'GET',

      success: function(res) {
        $.each(res, function (key, value) {
          let num = value.rem_bal;
          var usFormat = num.toLocaleString('en-US');
          document.getElementById('rem_baluacs_saa').value =  usFormat;
        });
 
      }
  });
});
});
</script>

{{-- gaa --}}


<script type="text/javascript">
  $(document).ready(function () {

$('#papid').on('change', function () {
  var papid = this.value;
  $('#year').html('');
  $('#rem_bal').html('');
  $('#activityoffice').html('');
  $('#activity').html('');
  $('#year_uacs').html('');
  $('#rem_baluacs').html('');
  $('#uacsoffice').html('');
  $('#uacs').html('');
  $('#activityoffice').append('<option value="">-- Choose Office --</option>');
  $('#uacsoffice').append('<option value="">-- Choose Office --</option>');
  $('#activity').append('<option value="">-- Choose Activity --</option>');
  $('#uacs').append('<option value="">-- Choose UACS --</option>');
  $('#year_uacs').append('<option value="">-- Choose Year --</option>');
  $('#year').append('<option value="">-- Choose Year --</option>');
  $('#expense_class').append('<option value="">-- Fetching Expense Class --</option>');

  document.getElementById('rem_bal').value = '';
  $.ajax({
      url: '{{ route('fmplanning.getRemBalAllocPAP') }}?papid='+papid,
      type: 'GET',      
      
      success: function(res) {
        // $('#year').html('<option value="">-- Choose Year --</option>');
        // $.each(res, function (key, value) {
        //   $('#year').append('<option value="' + value
        //                   .year + '">' + value.year + '</option>');
        // });
        console.log(papid);
        $('#expense_class').html('<option value="">-- Choose Expense Class --</option>');
        $.each(res, function (key, value) {
          $('#expense_class').append('<option value="' + value
                          .expense_class + '">' + value.expense_class + '</option>');
        });
 
      }
  });
});
});
</script>

<script type="text/javascript">
  $(document).ready(function () {

$('#expense_class').on('change', function () {
  var papid =  document.getElementById('papid').value;
  var expense_class = this.value;
  $('#year').html('');
  $('#rem_bal').html('');
  $('#activityoffice').html('');
  $('#activity').html('');
  $('#year_uacs').html('');
  $('#rem_baluacs').html('');
  $('#uacsoffice').html('');
  $('#uacs').html('');
  $('#activityoffice').append('<option value="">-- Choose Office --</option>');
  $('#uacsoffice').append('<option value="">-- Choose Office --</option>');
  $('#activity').append('<option value="">-- Fetching Activity --</option>');
  // $('#uacs').append('<option value="">-- Choose UACS --</option>');
  $('#uacs').append('<option value="">-- Fetching UACS --</option>');
  $('#year_uacs').append('<option value="">-- Choose Year --</option>');
  $('#year').append('<option value="">-- Choose Year --</option>');
  document.getElementById('rem_bal').value = '';
  $.ajax({
      url: '{{ route('fmplanning.ChargingExpenseClass') }}?papid='+papid+'&expense_class='+expense_class,
      type: 'GET',      
      
      success: function(res) {
  
        $('#activity').html('<option value="">-- Choose Activity --</option>');

        $.each(res, function (key, value) {

          // $('#activity').append('<option value="' + value
          //                 .activityid + '">' + value.activityid + '</option>');

          $('#activity').append('<option value="' + value[0] + '">' + value[1] + '</option>');
        });
 
      }

  
  });

  $.ajax({

    url: '{{ route('fmplanning.ChargingExpenseClassUACS') }}?papid='+papid+'&expense_class='+expense_class,
      type: 'GET',      
      
      success: function(res) {

        $('#uacs').html('<option value="">-- Choose Activity --</option>');
        $.each(res, function (key, value) {

          // $('#activity').append('<option value="' + value
          //                 .activityid + '">' + value.activityid + '</option>');

          $('#uacs').append('<option value="' + value[0] + '">' + value[1] + ' - ' + value[2] + '</option>');
        });
 
      }
  });
});
});
</script>



<script type="text/javascript">
  $(document).ready(function () {

$('#activity').on('change', function () {
  var papid =  document.getElementById('papid').value;
  var expense_class = document.getElementById('expense_class').value;
  var activityid = this.value;
  $('#year').html('');
  $('#rem_bal').html('');
  $('#activityoffice').html('');
  $('#activityoffice').append('<option value="">-- Choose Office --</option>');
  $('#year').append('<option value="">-- Choose Year --</option>');
  document.getElementById('rem_bal').value = '';
  $.ajax({
      url: '{{ route('fmplanning.ChargingActivity') }}?papid='+papid+'&expense_class='+expense_class+'&activityid='+activityid,
      type: 'GET',      
      
      success: function(res) {
        console.log(expense_class);
        $('#year').html('<option value="">-- Choose Year --</option>');
        $.each(res, function (key, value) {

          // $('#activity').append('<option value="' + value
          //                 .activityid + '">' + value.activityid + '</option>');

          $('#year').append('<option value="' + value.year + '">' + value.year + '</option>');
        });
 
      }
  });
});
});
</script>


<script type="text/javascript">
  $(document).ready(function () {

$('#year').on('change', function () {
  var papid =  document.getElementById('papid').value;
  var expense_class = document.getElementById('expense_class').value;
  var activityid = document.getElementById('activity').value;
  var year = this.value;
  $('#rem_bal').html('');
  $('#activityoffice').html('');
  $('#activityoffice').append('<option value="">-- Choose Office --</option>');

  document.getElementById('rem_bal').value = '';
  $.ajax({
      url: '{{ route('fmplanning.ChargingYear') }}?papid='+papid+'&expense_class='+expense_class+'&activityid='+activityid+'&year='+year,
      type: 'GET',      
      
      success: function(res) {
        console.log(expense_class);
        $('#activityoffice').html('<option value="">-- Choose Year --</option>');
        $.each(res, function (key, value) {

          // $('#activity').append('<option value="' + value
          //                 .activityid + '">' + value.activityid + '</option>');

          $('#activityoffice').append('<option value="' + value.office + '">' + value.office + '</option>');
        });
 
      }
  });
});
});
</script>




<script type="text/javascript">
  $(document).ready(function () {

$('#activityoffice').on('change', function () {
  var year = document.getElementById('year').value;
  var activityid =   document.getElementById('activity').value;
  var papid =document.getElementById('papid').value;
  var office = this.value;
  var expense_class = document.getElementById('expense_class').value;
  $('#rem_bal').html('');
  document.getElementById('rem_bal').value = '';
  $.ajax({
      url: '{{ route('fmplanning.ChargingOffice') }}?activityid='+activityid+'&year='+year+'&office='+office+'&papid='+papid+'&expense_class='+expense_class,
      type: 'GET',

      success: function(res) {
        $.each(res, function (key, value) {
          let num = value.rem_bal;
          var usFormat = num.toLocaleString('en-US');
          document.getElementById('rem_bal').value =  usFormat;
        });
 
      }
  });
});
});
</script>


<script type="text/javascript">
  $(document).ready(function () {

$('#uacs').on('change', function () {
  var uacsid = this.value;
  var papid =  document.getElementById('papid').value;
  var expense_class = document.getElementById('expense_class').value;

  $('#year_uacs').html('');
  $('#rem_baluacs').html('');
  $('#uacsoffice').html('');
  $('#uacsoffice').append('<option value="">-- Choose Office --</option>');
  $('#year_uacs').append('<option value="">-- Fetching Year --</option>');
  document.getElementById('rem_baluacs').value = '';

  $.ajax({
      url: '{{ route('fmplanning.ChargingUACS') }}?uacsid='+uacsid+'&papid='+papid+'&expense_class='+expense_class,
      type: 'GET',

      success: function(res) {
        $('#year_uacs').html('<option value="">-- Choose Year --</option>');
        $.each(res, function (key, value) {
          $('#year_uacs').append('<option value="' + value
                          .year + '">' + value.year + '</option>');
        });
 
      }
  });
});
});
</script>



<script type="text/javascript">
  $(document).ready(function () {

$('#year_uacs').on('change', function () {
  var year_uacs = this.value;
  var  uacsid = document.getElementById('uacs').value;
  var papid =  document.getElementById('papid').value;
  var expense_class = document.getElementById('expense_class').value;
 
  $('#rem_baluacs').html('');
  $('#uacsoffice').html('');
  $('#uacsoffice').append('<option value="">-- Fetching Office --</option>');
  document.getElementById('rem_baluacs').value = '';

  $.ajax({
      url: '{{ route('fmplanning.ChargingUACSyear') }}?uacsid='+uacsid+'&papid='+papid+'&expense_class='+expense_class+'&year_uacs='+year_uacs,
      type: 'GET',

      success: function(res) {
        $('#uacsoffice').html('<option value="">-- Choose Office --</option>');
        $.each(res, function (key, value) {
          $('#uacsoffice').append('<option value="' + value
                          .office + '">' + value.office + '</option>');
        });
 
      }
  });
});
});
</script>



<script type="text/javascript">
  $(document).ready(function () {

$('#uacsoffice').on('change', function () {
  var uacsoffice = this.value;
  var year_uacs = document.getElementById('year_uacs').value;
  var  uacsid = document.getElementById('uacs').value;
  var papid =  document.getElementById('papid').value;
  var expense_class = document.getElementById('expense_class').value;
  $('#rem_baluacs').html('');
  document.getElementById('rem_baluacs').value = '';

  $.ajax({
      url: '{{ route('fmplanning.ChargingUACSoffice') }}?uacsid='+uacsid+'&papid='+papid+'&expense_class='+expense_class+'&year_uacs='+year_uacs+'&uacsoffice='+uacsoffice,
      type: 'GET',

      success: function(res) {
        $.each(res, function (key, value) {
          let num = value.rem_bal;
          var usFormat = num.toLocaleString('en-US');
          document.getElementById('rem_baluacs').value =  usFormat;
        });
 
      }
  });
});
});
</script>


{{-- <script type="text/javascript">
  $(document).ready(function () {

$('#year_uacs').on('change', function () {
  var year = this.value;
  var papid =document.getElementById('papid').value;
  var uacsid =document.getElementById('uacs').value;
  // $('#rem_bal').html('');
  $('#uacsoffice').html('');
  $('#rem_baluacs').html('');
  document.getElementById('rem_baluacs').value = '';
  $.ajax({
    url: '{{ route('fmplanning.getRemBalUACSOffice') }}?year='+year+'&papid='+papid+'&uacsid='+uacsid,
      type: 'GET',

      success: function(res) {
        $('#uacsoffice').html('<option value="">-- Choose Office --</option>');
        $.each(res, function (key, value) {
          $('#uacsoffice').append('<option value="' + value
                          .office + '">' + value.office + '</option>');
        });
 
      }
  });
});
});
</script> --}}


{{-- <script type="text/javascript">
  $(document).ready(function () {

$('#uacsoffice').on('change', function () {
  var year = document.getElementById('year_uacs').value;
  var uacsid =  document.getElementById('uacs').value;
  var papid =document.getElementById('papid').value;
  var office = this.value;
  $('#rem_baluacs').html('');
  document.getElementById('rem_baluacs').value = '';
  $.ajax({
      url: '{{ route('fmplanning.getRemBalyearUACS') }}?uacsid='+uacsid+'&year='+year+'&office='+office+'&papid='+papid,
      type: 'GET',

      success: function(res) {
        $.each(res, function (key, value) {
          let num = value.rem_bal;
          var usFormat = num.toLocaleString('en-US');
          document.getElementById('rem_baluacs').value =  usFormat;
        });
 
      }
  });
});
});
</script> --}}




<script type="text/javascript">
  $(document).ready(function () {
      $('#activity_id_acctg').on('change', function () {
          var activity_id = this.value;
          $('#uacs_id_acctg').html('');
          $('#uacs_id_acctg').append('<option value="">Fetching UACS Lists</option>');
          $.ajax({
              url: '{{ route('fmaccounting.getuacs') }}?activity_id='+activity_id,
              type: 'GET',
              success: function (res) {
                  $('#uacs_id_acctg').html('<option value="">-- Choose UACS --</option>');
                  $.each(res, function (key, value) {
                      $('#uacs_id_acctg').append('<option value="' + value
                          .id + '">' + value.uacs + '</option>');
                  });
              }
          });
      });
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

