<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Information System') }}</title>

        {{-- <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback"> --}}
        <!-- Font Awesome -->
        <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
        <!-- Theme style -->
        <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    </head>

    <body>
        <div class="wrapper">
            <!-- Main content -->
            <section class="invoice">
                <!-- title row -->
               
                <table class="">
                    <tbody>
                        <tr style="border: 2px solid black; border-bottom: none">
                            <td style="width: 650px;border-right: 2px solid black;" class="m-4">
                                <div class="text-center"  >
                                    <div>
                                        <h5>OBLIGATION REQUEST AND STATUS</h5>
                                    </div>
                                    <div>
                                        <label> {{ $Voucher->office }} </label>
                                    </div>
                                    <div>
                                        <h6>Entity Name</h6>
                                    </div>
                                </div>

                            </td>

                            <td style="width: 350px" class="pl-2 m-4">
                                <div class="pt-2">
                                  Serial No. :
                                    @foreach($Obligations as $Obligation)
                                    <small class="text-bold">{{ $Obligation->orsno  }}</small>
                                @endforeach
                                </div>
                                <div class="pt-0">
                                    <div>
                                        Date :
                                        @foreach($Obligations as $Obligation)
                                        <small class="text-bold">{{ date_format($Obligation->created_at, 'm/d/Y')  }}</small>
                                        @endforeach
                                    </div>
                                 
                                </div>

                                <div class="pt-0">
                                   Fund Cluster :
                                    @foreach($Obligations as $Obligation)
                                      <small class="text-bold">{{ $Obligation->fc  }}</small>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table>
                    <tbody>
                        <tr style="border: 2px solid black; border-bottom: none">
                            <td style="width: 150px; border-right: 2px solid black;" class="m-4">
                                <div class="text-center">
                                    <div>
                                        Payee
                                    </div>
                                </div>
                            </td>

                            <td style="width: 850px" class="pl-2 m-4">
                                <div class="pt-2">
                                    <small class="text-bold"> {{ $Voucher->AccountName->acct_name}}</small>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table>
                    <tbody>
                        <tr style="border: 2px solid black;border-bottom: none">
                            <td style="width: 150px; border-right: 2px solid black;" class="m-4">
                                <div class="text-center">
                                    <div>
                                        Office
                                    </div>
                                </div>
                            </td>

                            <td style="width: 850px" class="pl-2 m-4">
                                <div class="pt-2">
                                    <small class="text-bold">{{ $Voucher->office }}</small>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table>
                    <tbody>
                        <tr style="border: 2px solid black;border-bottom: none">
                            <td style="width: 150px; border-right: 2px solid black;" class="m-4">
                                <div class="text-center">
                                    <div>
                                        Address
                                    </div>
                                </div>
                            </td>

                            <td style="width: 850px" class="pl-2 m-4">
                                <div class="pt-2">
                                    <small class="text-bold"> {{ $AccountName->address }}</small>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <table class="" style=" border: 2px solid black; border-top: none; height: 40px">
                    <tbody>
                        <tr style="border: 2px solid black;height: 40px">
                            <td style="width: 150px; border-right: 2px solid black;" class="m-4">
                                <div class="text-center">
                                    <div>
                                        Responsibility Center
                                    </div>
                                </div>
                            </td>
                            <td style="width: 350px; border-right: 2px solid black;" class="m-4">
                                <div class="text-center">
                                    <div>
                                        Particulars
                                    </div>
                                </div>
                            </td>
                            <td style="width: 150px; border-right: 2px solid black;" class="m-4">
                                <div class="text-center">
                                    <div>
                                        MFO / PAP
                                    </div>
                                </div>
                            </td>
                            <td style="width: 200px; border-right: 2px solid black;" class="m-4">
                                <div class="text-center">
                                    <div>
                                        UACS Object Code
                                    </div>
                                </div>
                            </td>
                            <td style="width: 150px; border-right: 2px solid black;" class="m-4">
                                <div class="text-center">
                                    <div>
                                        Amount
                                    </div>
                                </div>
                            </td>
                        </tr>
                    
                    </tbody>
                </table>

                <table class="" style=" border: 2px solid black; border-top: none;border-bottom: none;  height: 70px">
                    <tbody>
                <tr>
                    <td style="width: 150px; border-right: 2px solid black;" class="m-4">
                        <small class="text-bold">{{$Voucher->office}}</small>
                    </td>

                    <td style="width: 350px; border-right: 2px solid black;" class="m-4 text-justify">
                        <small class="text-bold">{{$Voucher->particulars}}</small>
                    </td>

                    <td style="width: 150px; border-right: 2px solid black;" class="m-4">

                    </td>

                    <td style="width: 200px; border-right: 2px solid black;" class="m-4">

                    </td>

                    <td style="width: 150px; border-right: 2px solid black;" class="m-4 text-center">
                       P <span class="float-right pr-4 text-bold"> {{$FloatAmount}}</span>
                    </td>
                </tr>

                    </tbody>
                </table>

                @if(count($Chargings) > 0)

                    @foreach ($Chargings as $Charging)
  
                        <table class="" style=" border: 2px solid black; border-top: none; border-bottom:none; height: 50px">
                            <tbody>
                                <tr>
                                    <td style="width: 150px; border-right: 2px solid black;" class="m-4">

                                    </td>

                                    <td style="width: 350px; border-right: 2px solid black;" class="m-4 text-center">
                                    
                                    </td>

                                    <td style="width: 150px; border-right: 2px solid black;" class="m-4 pl-2">
                                        <small class="text-bold"> {{$Charging->PAP->pap . ' - ' . $Charging->Activity->activity}} </small>
                                    </td>

                                    <td style="width: 200px; border-right: 2px solid black;" class="m-4 pl-2 text-center">
                                        <small class="text-bold"> {{$Charging->UACS->uacs}}</small> 
                                    </td>

                                    <td style="width: 150px; border-right: 2px solid black;" class="m-4 text-center">
                                        <small class="text-bold"><span class="float-right pr-4"> {{number_format($Charging->amount,2,'.',',')}}</span></small>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                    @endforeach
                    
                @else

                    <table class="" style=" border: 2px solid black; border-top: none; border-bottom:none; height: 100px">
                        <tbody>
                            <tr>
                                <td style="width: 150px; border-right: 2px solid black;" class="m-4">

                                </td>

                                <td style="width: 350px; border-right: 2px solid black;" class="m-4 text-center">
                                
                                </td>

                                <td style="width: 150px; border-right: 2px solid black;" class="m-4 pl-2">
                          
                                </td>

                                <td style="width: 200px; border-right: 2px solid black;" class="m-4 pl-2 text-center">
                           
                                </td>

                                <td style="width: 150px; border-right: 2px solid black;" class="m-4 text-center">
                           
                                </td>
                            </tr>
                        </tbody>
                    </table>

                @endif
            

                <table class="" style=" border: 2px solid black; border-top: none;  height: 40px">
                    <tbody>
                <tr>

                    <td style="width: 150px; border-right: 2px solid black;" class="m-4">

                    </td>

                    <td style="width: 350px; border-right: 2px solid black;" class="m-4 text-center">
                      <div class="float-right pr-1">
                        Total
                      </div>
                    </td>

                    <td style="width: 150px; border-right: 2px solid black;" class="m-4">

                    </td>

                    <td style="width: 200px; border-right: 2px solid black;" class="m-4">

                    </td>
                    <td style="width: 150px; border-right: 2px solid black;" class="m-4 text-center">
                        @if( $Chargings->pluck('amount')->sum()  > 0)
                          P <span class="float-right pr-4 text-bold">  {{number_format($Chargings->pluck('amount')->sum() ,2,'.',',')}}</span>
                       @endif
                          
                    </td>
                </tr>

                    </tbody>
                </table>


                <table class="" style=" border: 2px solid black; border-top: none">
                    <tbody>
                        <tr>
                            <td style="width: 50px;  border-right: 2px solid black;" class="m-4 text-center" style="height: 50px">
                                <div>
                                    <strong>A.</strong>
                                </div>
                            </td>
                            <td style="width: 450px" class="m-4 px-2 text-justify">
                                <div>
                                    <div style="height: 30px">
                                        <span>
                                            Certified : Charges to Appropriation / allotment are necessary, lawful and
                                            under my direct supervision: and supporting documents valid, proper and
                                            legal.

                                        </span>
                                    </div>
                                    <br>
                                    <br>
                                 
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="pl-2">
                                                
                                                <div class="mt-2">
                                                   <small>Signature :</small> 
                                                </div>
                                                <div class="mt-2">
                                                    <small>Printed :</small> 
                                                    
                                                </div>
                                                <div class="mt-2 mb-4">
                                                    <small>Position : </small>
                                                </div>

                                                <div class="mt-4 pt-3">
                                                    <br>
                                                    <small>Date :</small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-9 text-center pr-4">
                                            <div class="mt-4 pl-2">
                                                
                                                <div class="mt-2" style="border-bottom: 2px solid black">
                                                
                                                </div>
                                                <div class="mt-2" style="border-bottom: 2px solid black">
                                                    <h6>{{$Voucher->BoxAORS->certified_by}}</h6>
                                                </div>
                                                <div class="mt-2" style="border-bottom: 2px solid black">
                                                    <small class="text-bold">{{$Voucher->BoxAORS->position}}</small>
                                                </div>
                                                <div class="">
                                                    <small> Head / Requesting Office / Authorized Representative</small>
                                                </div>
                                                <div class="mt-2" style="border-bottom: 2px solid black">
                                                    <br>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td style="width: 50px;  border-right: 2px solid black; border-left: 2px solid black" class="m-4 text-center" style="height: 50px">
                                <div>
                                    <strong>B.</strong>
                                </div>
                            </td>
                            <td style="width: 450px" class="m-4 px-2 text-justify">
                                <div>
                                    <div style="height: 30px">
                                        <span>
                                          Certified : Allotment available  and obligated<br>
                                          for the purpose / adjustment necessarry as <br>
                                          indicated above.
                                        </span>
                                    </div>
                                    <br>
                                    <br>
                                    
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="pl-2">
                                                
                                                <div class="mt-2">
                                                   <small>Signature :</small> 
                                                </div>
                                                <div class="mt-2">
                                                    <small>Printed :</small> 
                                                    
                                                </div>
                                                <div class="mt-2 mb-4">
                                                    <small>Position : </small>
                                                </div>

                                                <div class="mt-4 pt-3">
                                                    <br>
                                                    <small>Date :</small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-9 text-center pr-4">
                                            <div class="mt-4 pl-2">
                                                
                                                <div class="mt-2" style="border-bottom: 2px solid black">
                                             
                                                </div>
                                                <div class="mt-2" style="border-bottom: 2px solid black">
                                                    <h6>JOSEPHINE S. TAÑADA </h6>
                                                </div>
                                                <div class="mt-2" style="border-bottom: 2px solid black">
                                                    <small class="text-bold"> Budget Officer II</small>
                                                </div>
                                                <div class="">
                                                    <small> Head , Budget Division / Unit / Authorized Representative</small>
                                                </div>
                                                <div class="mt-2" style="border-bottom: 2px solid black">
                                                    <br>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                       </tr>
                    </tbody>
                </table>

                <table>
                    <tbody>
                        <tr style="border-right: 2px solid black">
                            <td style="width: 50px;  border: 2px solid black; border-top: none; border-bottom:none" class="">
                                <div class="text-center">
                                    <div>
                                        C.
                                    </div>
                                </div>
                            </td>

                            <td style="width: 950px" class="text-center">
                                <div class="">
                                    <label>STATUS OF OBLIGATION</label>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table style=" border: 2px solid black;">
                    <tbody>
                        <tr style="border-right: 2px solid black">
                            <td style="width: 400px;  border: 2px solid black; border-top: none; border-bottom: none"class="">
                                <div class="text-center">
                                    <div>
                                    Reference
                                    </div>
                                </div>
                            </td>

                            <td style="width: 600px" class="text-center">
                                <div class="">
                                   Amount
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <table class="" style=" border: 2px solid black; border-top: none;table-layout:fixed;">
                    <tbody>
                        <tr style="border-right: 2px solid black">
                            <td style="width: 80px; border-right: 2px solid black" class="text-center">
                          
                            
                                    Date
                                 
                            </td>

                            <td style="width: 170px; border-right: 2px solid black" class="text-center">
                        
                                   Particulars
                              
                            </td>

                            <td style="width: 150px; border-right: 2px solid black; " class="text-center">
                       
                                 <small>ORS/JEV/Check/ADA/TRA No.</small>  
                         
                            </td>
                            
                            <td style="width: 150px; border-right: 2px solid black" class="text-center">
                                <div>
                                    <div class="">
                                        Obligation
                                     </div>
                                     <div>
                                        (a)
                                     </div>
                                </div> 
                            </td>

                            <td style="width: 100px; border-right: 2px solid black" class="text-center">
                                <div>
                                    <div class="">
                                        Payable
                                     </div>
                                     <div>
                                        (b)
                                     </div>
                                </div> 
                            </td>

                            <td style="width: 150px; border-right: 2px solid black" class="text-center">
                                <div>
                                    <div class="">
                                        Payment
                                     </div>
                                     <div>
                                        (c)
                                     </div>
                                </div> 
                            </td>
                            
                            <td style="width: 100px; border-right: 2px solid black" class="text-center">
                                <div>
                                    <div class="">
                                        Balance - Not Yet Due
                                     </div>
                                     <div>
                                        (a-b)
                                     </div>
                                </div> 
                            </td>

                            <td style="width: 100px; border-right: 2px solid black" class="text-center">
                                <div>
                                    <div class="">
                                        Balance - Due and Demandable
                                     </div>
                                     <div>
                                        (b-c)
                                     </div>
                                </div> 
                            </td>

                        </tr>

                        @if(count($Obligations) > 0)

                            @foreach ($Obligations as $Obligation)

                                <tr style="height: 100px;  border: 2px solid black">
                                    <td style="width: 65px; border-right: 2px solid black;overflow: hidden;" class="text-center">
                                    
                                        <small class="text-bold">{{ date_format($Obligation->created_at, 'm/d/Y')  }}</small> 
                                    
                                    </td>

                                    <td style="width: 150px; border-right: 2px solid black;overflow: hidden;" class="text-center  p-2">
                                    
                                        <small class="text-bold"> {{$Obligation->particulars }}</small>
                                    
                                    </td>

                                    <td style="width: 150px; border-right: 2px solid black;overflow: hidden;" class="text-center p-2">
                            
                                        <small class="text-bold"> {{$Obligation->orsno }}</small>
                                
                                    </td>
                                    
                                    <td style="width: 150px; border-right: 2px solid black" class="text-center  p-2">
                                
                                        <small class="text-bold">{{number_format($Obligation->obligation,2,'.',',')}}</small> 
                                        
                                    </td>

                                    <td style="width: 100px; border-right: 2px solid black" class="text-center  p-2">
                                    
                                        <small class="text-bold">{{number_format($Obligation->payable,2,'.',',')}}</small> 
                                        
                                    </td>

                                    <td style="width: 150px; border-right: 2px solid black" class="text-center  p-2">
                                
                                    
                                        <small class="text-bold">{{number_format($Obligation->payment,2,'.',',')}}</small> 
                                    
                                    </td>
                                    
                                    <td style="width: 100px; border-right: 2px solid black" class="text-center  p-2">
                                    
                                        <small class="text-bold">{{number_format($Obligation->nyd,2,'.',',')}}</small> 
                                    
                                    </td>

                                    <td style="width: 100px; border-right: 2px solid black" class="text-center  p-2">
                                    
                                        <small class="text-bold">{{number_format($Obligation->dd,2,'.',',')}}</small> 
                                        
                                    </td>

                                </tr>

                            @endforeach

                        @else

                        <tr style="height: 100px;  border: 2px solid black">
                            <td style="width: 65px; border-right: 2px solid black;overflow: hidden;" class="text-center">
                            
                              
                            
                            </td>

                            <td style="width: 150px; border-right: 2px solid black;overflow: hidden;" class="text-center  p-2">
                            
                         
                            
                            </td>

                            <td style="width: 150px; border-right: 2px solid black;overflow: hidden;" class="text-center p-2">
                    
                        
                            </td>
                            
                            <td style="width: 150px; border-right: 2px solid black" class="text-center  p-2">
                        
                                  
                                
                            </td>

                            <td style="width: 100px; border-right: 2px solid black" class="text-center  p-2">
                            
                            
                                
                            </td>

                            <td style="width: 150px; border-right: 2px solid black" class="text-center  p-2">
                        
                  
                            
                            </td>
                            
                            <td style="width: 100px; border-right: 2px solid black" class="text-center  p-2">
                            
                            </td>

                            <td style="width: 100px; border-right: 2px solid black" class="text-center  p-2">
                            
                                   
                                
                            </td>

                        </tr>

                        @endif


                    </tbody>
                </table>


                <!-- /.col -->
        </div>
    </body>
    <!-- info row -->
   
        <div>
            <i>Sequence ID : <span><strong>    {{ $Voucher->sequenceid}}</strong></span></i> 
        </div>

        <script>
            window.addEventListener("load", window.print());

        </script>



</html>
