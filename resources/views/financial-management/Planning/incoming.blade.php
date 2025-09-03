@extends('financial-management.planning.index')

@section('fm-content')

  <div class="col-md-9">
   
      <div class="card-header bg-primary">
        <h3 class="card-title">Incoming Vouchers</h3>

    
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        <div class="table-responsive mailbox-messages">
            <table id="example1" class="table table-bordered table-striped">
                <thead>
               <tr>
                 
                 <th  class="text-center">Date Created</th>
                 <th  class="text-center">Sequence ID</th>
                 <th  class="text-center">Payee</th>
                 <th  class="text-center">Office</th>
                 <th  class="text-center">Particulars </th>
                 <th  class="text-center">Remarks </th>
                 <th  class="text-center">Amount</th>
                 <th  class="text-center">Route From</th>
                 <th  class="text-center">Action</th>
               </tr>
               </thead>
               <tbody>
                   @foreach($IncomingVouchers as $Voucher)
                   <tr>
                     
                     <td class="text-center">{{$Voucher['0']}}</td>
                     <td> {{ $Voucher['1'] }}</td>
                     <td> {{ $Voucher['2'] }}</td>
                     <td> {{ $Voucher['3'] }}</td>
                     <td> {{ $Voucher['4'] }}</td>
                     <td> {{ $Voucher['5'] }}</td>
                     <td> {{ $Voucher['6'] }}</td>
                     <td> {{$Voucher['7'] }}</td>
                   
                     <td class="text-center">                   
                       <button type="button" class="btn btn-default" onClick="location.href='{{ route('financial-management.view',[$Voucher['8']]) }}'" style="margin-right: 5px;">
                         <i class="fas fa-eye"></i>
                       </button>
                
                      {{-- @can('acceptIncoming', $Voucher->voucher)  --}}
                       <button type="button" class="btn btn-success" data-toggle="modal" data-target="#accept-voucher-modal-lg{{ $Voucher['8']}}"  data-backdrop="static" data-keyboard="false" style="margin-right: 5px;">
                        <i class="fas fa-check"></i>
                       </button>
                       {{-- @endcan --}}
                       <button type="button" tittle="Reject Voucher" class="btn btn-danger" data-toggle="modal" data-target="#reject-voucher-modal-lg{{ $Voucher['8'] }}"  data-backdrop="static" data-keyboard="false" style="margin-right: 5px;">
                        <i class="fas fa-times"></i>
                       </button>
                  
                     </td>
                   </tr> 
                   @include('financial-management.accept')
                   @include('financial-management.reject')   
                   @endforeach 
               </tbody>
               
             </table>
          <!-- /.table -->
        </div>
        <!-- /.mail-box-messages -->
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
@endsection


