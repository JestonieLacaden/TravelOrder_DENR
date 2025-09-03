@extends('financial-management.planning.index')

@section('fm-content')

  <div class="col-md-9">
    <div class="card card-primary card-outline">
      <div class="card-header">
        <h3 class="card-title">Accepted Vouchers</h3>

    
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
                 <th  class="text-center">Accepted By</th>
                 <th  class="text-center">Action</th>
               </tr>
               </thead>
               <tbody>
                   @foreach($AcceptedVouchers as $Voucher)
                   <tr>
                     
                     <td class="text-center">{{$Voucher['0']}}</td>
                     <td> <a href="{{ route('financial-management.view',[$Voucher['8']]) }}"> {{ $Voucher['1'] }} </a></td>
                     <td> {{ $Voucher['2'] }}</td>
                     <td> {{ $Voucher['3'] }}</td>
                     <td> {{ $Voucher['4'] }}</td>
                     <td> {{ $Voucher['5'] }}</td>
                     <td> {{ $Voucher['6'] }}</td>
                     <td> {{$Voucher['9'] }}</td>
                   
                     <td class="text-center">                   
                       <button type="button" class="btn btn-default" onClick="location.href='{{ route('financial-management.view',[$Voucher['8']]) }}'" style="margin-right: 5px;">
                         <i class="fas fa-eye"></i>View
                       </button>
                     </td>
                   </tr> 
             
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
  </div>
  <!-- /.col -->

@endsection