@extends('layouts.app')

@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">New Voucher</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active">New Voucher</li>
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
                        <h5><i class="icon fas fa-check"></i> {{ session()->get('message') }}</h5>
                        <a href="{{ route('financial-management.viewvoucher') }}"><span class="text-"></span
                                class="pl-2"> View Voucher</a>
                    </div>
                    @endif

                    <div class="card">




                        <!-- /.card-header -->
                        <div class="card-body">
                            @can('create', \App\Models\FinancialManagement::class)
                            <form method="POST" action="{{ route('financial-management.store') }}"
                                enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="card-body">

                                    <div class="form-group row">
                                        <label class="col-sm-3" for="datereceived">Date Created :<span
                                                class="text-danger">*</span></label>
                                        <div class=" col-sm-9">
                                            <input name="datereceived" id="datereceived" class="form-control"
                                                type="date" value={{ now() }}
                                                oninput="this.value = this.value.toUpperCase()">
                                            @error('datereceived')
                                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                                            @enderror

                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label" for="office">Office Name :</i> :  <span
                                                class="text-danger">*</span> </label>
                                        <div class="col-sm-9">
                                            <select name="office" id="office" class="form-control select2"
                                                style="width: 100%;">
                                                <option value="" disabled selected>-- Choose Office --</option>
                                                <option value="ARNP - APO REEF NATURAL PARK">ARNP - APO REEF NATURAL PARK</option>
                                                <option value="CENRO - SABLAYAN OCCIDENTAL MINDORO">CENRO - SABLAYAN OCCIDENTAL MINDORO</option>
                                                <option value="CENRO - SAN JOSE OCCIDENTAL MINDORO">CENRO - SAN JOSE OCCIDENTAL MINDORO</option>
                                                <option value="MCWS - MOUNT CALAVITE WILDLIFE SANCTUARY">MCWS - MOUNT CALAVITE WILDLIFE SANCTUARY</option>
                                                <option value="MIBNP - MOUNT IGLIT BACO NATURAL PARK">MIBNP - MOUNT IGLIT BACO NATURAL PARK</option>
                                                <option value="DENR - PENRO OCCIDENTAL MINDORO">DENR - PENRO OCCIDENTAL MINDORO</option>
                                                <option value="TCP - TAMARAW CONSERVATION PROGRAM">TCP - TAMARAW CONSERVATION PROGRAM</option>
                                            </select>
                                  
                                        @error('office')
                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                        @enderror
                                       </div>
                                      </div>

                                    {{-- <div class="form-group row ">
                                        <label class="col-sm-2"> Office :<span class="text-danger">*</span></label>

                                        <datalist id="mylist2">
                                            <option value="ARNP - APO REEF NATURAL PARK">
                                            <option value="CENRO - SABLAYAN OCCIDENTAL MINDORO">
                                            <option value="CENRO - SAN JOSE OCCIDENTAL MINDORO">
                                            <option value="MCWS - MOUNT CALAVITE WILDLIFE SANCTUARY">
                                            <option value="MIBNP - MOUNT IGLIT BACO NATURAL PARK">
                                            <option value="DENR - PENRO OCCIDENTAL MINDORO">
                                            <option value="REGIONAL OFFICE">
                                            <option value="TCP - TAMARAW CONSERVATION PROGRAM">
                                        </datalist>
                                        <div class=" col-sm-10">
                                            <input id="office" type="text" class="form-control" name="office" list="mylist2"
                                                placeholder="- ADD OR SELECT OFFICE - "
                                                oninput="this.value = this.value.toUpperCase()"
                                                value="{{old('office')}}" />
                                            @error('office')
                                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div> --}}

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label" for="acct_id">Account Name : <span
                                                class="text-danger">*</span> </label>
                                        <div class="col-sm-9">
                                            <select name="acct_id" id="acct_id" class="form-control select2"
                                                style="width: 100%;">
                                                <option value="" disabled selected>-- Choose Account Name --</option>
                                                @foreach($AccountNames as $AccountName)
                                                <option value="{{$AccountName->id}}">{{ $AccountName->acct_name }}
                                                </option>
                                                @endforeach
                                            </select>
                                     
                                        @error('acct_id')
                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                        @enderror
                                    </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label" for="acct_no">Account Number : <span class="text-danger">*</span> </label>
                                        <div class="col-sm-9">
                                          <select name="acct_no" id="acct_no" class="form-control select2" style="width: 100%;">
                                            <option value="" disabled selected>-- Choose Account Number --</option>
                                            {{-- @foreach($Activities as $Activity)
                                            <option value="{{$Activity->id}}">{{ $Activity->activity }}</option>
                                            @endforeach--}}
                                          </select> 
                                    
                                    
                                        @error('acct_no')
                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                        @enderror
                                      </div>
                                    </div>



                                    <div class="form-group row">
                                        <label class="col-sm-3" for="address">Address :<span
                                                class="text-danger">*</span></label>
                                        <div class=" col-sm-9">
                                            <input name="address" id="address" class="form-control" type="text"
                                                placeholder="Enter Address" value="{{old('address')}}"
                                                oninput="this.value = this.value.toUpperCase()" readonly>
                                            @error('address')
                                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row ">
                                        <label class="col-sm-3"> Particulars :<span class="text-danger">*</span></label>

                                        <datalist id="mylist3">
                                            <option value="CASH ADVANCE">
                                            <option value="LOAD ALLOWANCE">
                                            <option value="REIMBURSEMENT">
                                            <option value="RATA">
                                            <option value="REFUND">
                                            <option value="TEV">
                                            <option value="WAGES">
                                        </datalist>
                                        <div class=" col-sm-9">
                                            <input type="text" class="form-control" name="particulars" list="mylist3"
                                                placeholder="- ADD OR SELECT PARTICULARS - "
                                                oninput="this.value = this.value.toUpperCase()"
                                                value="{{old('particulars')}}" />
                                            @error('particulars')
                                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3" for="remarks">Remarks</label>
                                        <div class=" col-sm-9">
                                            <input name="remarks" id="remarks" class="form-control" type="text"
                                                placeholder="Enter Remarks" value="{{old('remarks')}}"
                                                oninput="this.value = this.value.toUpperCase()">
                                            @error('remarks')
                                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3" for="amount">Amount :<span
                                                class="text-danger">*</span></label>
                                        <div class=" col-sm-9">
                                            <input name="amount" id="amount" class="form-control" type="number"
                                                step="0.01" placeholder="Enter Amount" value="{{old('amount')}}"
                                                oninput="this.value = this.value.toUpperCase()">
                                            @error('amount')
                                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                      <label class="col-sm-3 col-form-label" for="certified_by">Certified By <i>(Box A - DV)</i> :  <span
                                              class="text-danger">*</span> </label>
                                      <div class="col-sm-9">
                                          <select name="certified_by" id="certified_by" class="form-control select2"
                                              style="width: 100%;">
                                              <option value="" disabled selected>-- Choose Certified By --</option>
                                              @foreach($BoxAs as $BoxA)
                                              <option value="{{$BoxA->id}}">{{ $BoxA->certified_by . ' - ' . $BoxA->position  }}
                                              </option>
                                              @endforeach
                                          </select>
                                
                                      @error('certified_by')
                                      <p class="text-danger text-xs mt-1">{{$message}}</p>
                                      @enderror
                                     </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label" for="certified_by">Certified By <i>(Box A - ORS)</i> :  <span
                                                class="text-danger">*</span> </label>
                                        <div class="col-sm-9">
                                            <select name="certified_by_ors" id="certified_by_ors" class="form-control select2"
                                                style="width: 100%;">
                                                <option value="" disabled selected>-- Choose Certified By --</option>
                                                @foreach($BoxAs as $BoxA)
                                                <option value="{{$BoxA->id}}">{{ $BoxA->certified_by . ' - ' . $BoxA->position  }}
                                                </option>
                                                @endforeach
                                            </select>
                                  
                                        @error('certified_by_ors')
                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                        @enderror
                                       </div>
                                      </div>


                                </div>
                                <!-- /.card-body -->

                                <div class="card-footer">
                                    <button type="submit" id="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                            @endcan

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

{{-- <script type="text/javascript">
    $(document).ready(function () {
        $('#office').on('change', function () {
            var office = this.value;
            var txt = $('#address');

            if (office == 'PENRO') {
                txt.val("BARANGAY PAYOMPON, MAMBURAO, OCCIDENTAL MINDORO"); 
            }     
            
            if (office == 'ARNP - APO REEF NATURAL PARK') {
                txt.val("BARANGAY STO. NIÑO, SABLAYAN, OCCIDENTAL MINDORO"); 
            }  
            if (office == 'CENRO - SABLAYAN') {
                txt.val("BARANGAY STO. NIÑO, SABLAYAN, OCCIDENTAL MINDORO"); 
            } 
            if (office == 'MCWS - MOUNT CALAVITE WILDLIFE SANCTUARY') {
                txt.val("PALUAN, OCCIDENTAL MINDORO"); 
            } 
            if (office == 'CENRO - SAN JOSE') {
                txt.val("BARANGAY LABANGAN, SAN JOSE, OCCIDENTAL MINDORO"); 
            } 
            if (office == 'MIBNP - MOUNT IGLIT BACO NATURAL PARK') {
                txt.val("BARANGAY SAN ROQUE, SAN JOSE, OCCIDENTAL MINDORO"); 
            } 
            if (office == 'TCP - TAMARAW CONSERVATION PROGRAM') {
                txt.val("BARANGAY SAN ROQUE, SAN JOSE, OCCIDENTAL MINDORO"); 
            }

        });
  });

</script> --}}

<script type="text/javascript">
    $(document).ready(function () {
        $('#acct_id').on('change', function () {
            var acct_id = this.value;
            $('#acct_no').html('');
            $('#acct_no').append('<option value="">Fetching Account Number</option>');
            $.ajax({
                url: '{{ route('financialmanagement.getAccountNumber') }}?acct_id='+acct_id,
                type: 'GET',
                success: function (res) {
                    $('#acct_no').html('<option value="">-- Choose Activity --</option>');
                    $.each(res, function (key, value) {
                        $('#acct_no').append('<option value="' + value
                            .id + '">' + value.bank_name + ' - ' + value.acct_no + '</option>');
                    });
                }
            });

            $.ajax({
                url: '{{ route('financialmanagement.getAccountAddress') }}?acct_id='+acct_id,
                type: 'GET',
                success: function (res) {
                      $('#address').html('');
                    document.getElementById('address').value = '';
                    $.each(res, function (key, value) {
                        document.getElementById('address').value =  value.address;
                    });
                }
            });
        });
  });

  </script>

@endsection

