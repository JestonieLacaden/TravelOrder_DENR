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
                                        <h5>DISBURSEMENT VOUCHER</h5>
                                    </div>
                                    <div>
                                        <label> {{ $Voucher->office }}</label>
                                    </div>
                                    <div>
                                        <h6>Entity Name</h6>
                                    </div>
                                </div>

                            </td>

                            <td style="width: 350px" class="m-4">
                                <div class="pl-2" style="border-bottom: 2px solid black">
                                    Fund Cluster :
                                    @foreach ($Obligations as $Obligation)
                                    <label>{{$Obligation->fc}}</label>
                                
                                @endforeach
                                </div>
                                <div class="pl-2">
                                    <div>
                                        Date :
                                        <span>
                                            @foreach ($DVs as $DV)
                                            <label>{{ date_format($DV->created_at, 'm/d/Y')  }}</label>
                                        
                                            @endforeach
                                        </span>
                               
                                    </div>
                                 
                                </div>

                                <div class="pl-2">
                                   DV No. :
                                    <span>
                                        @foreach ($DVs as $DV)
                                       <label>{{ $DV->dvno  }}</label>
                              
                                        @endforeach
                                    </span>
                                </div>
                            </td>
                        </tr>   
                    </tbody>
                </table>

                <table>
                    <tbody>
                        <tr style="border: 2px solid black; border-bottom: none">
                            <td style="width: 160px; border-right: 2px solid black;" class="m-4">
                                <div class="text-center">
                                    <div>
                                        Mode of Payment
                                    </div>
                                </div>
                            </td>

                            <td style="width: 180px; border-right: none" class="m-4">
                                <div class="text-center">
                                    <div>
                                         @if (count($Cashiers) == 1)
                                                @foreach($Cashiers as $Cashier)
                                                    @if ($Cashier->mop == "MDS CHECK")
                                                    <input type="checkbox" checked disabled>
                                                    <small class="pl-2">MDS Check</small>
                                                    @else
                                                <input type="checkbox"  disabled>
                                                <small class="pl-2">MDS Check</small>
                                                    @endif
                                                @endforeach
                                        @else
                                            <input type="checkbox" disabled>
                                            <small class="pl-2">MDS Check</small>
                                         @endif
                                     
             
                                    </div>
                                </div>
                            </td>

                            <td style="width: 180px; border-right: none" class="m-4">
                                <div class="text-center">
                                    <div>
                                        @if (count($Cashiers) == 1)
                                        @foreach($Cashiers as $Cashier)
                                            @if ($Cashier->mop == "COMMERCIAL CHECK")
                                                <input type="checkbox" checked disabled>
                                                <small class="pl-2">Commercial Check</small>
                                            @else
                                                <input type="checkbox" disabled>
                                                <small class="pl-2">Commercial Check</small>
                                             @endif
                            
                                        
                                        @endforeach
                                     @else
                                     <input type="checkbox" disabled>
                                     <small class="pl-2">Commercial Check</small>
                                     @endif
                                    </div>
                                </div>
                            </td>

                            <td style="width: 180px; border-right: none" class="m-4">
                                <div class="text-center">
                                    <div>
                                        @if (count($Cashiers) == 1)
                                            @foreach($Cashiers as $Cashier)
                                                @if ($Cashier->mop == "ADA")
                                                    <input type="checkbox" checked disabled>
                                                    <small class="pl-2">ADA</small>
                                                    @else
                                                    <input type="checkbox" disabled>
                                                    <small class="pl-2">ADA</small>
                                                @endif
                                            @endforeach
                                        @else
                                            <input type="checkbox" disabled>
                                            <small class="pl-2">ADA</small>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <td style="width: 300px; border-right: none" class="m-4">
                                <div class="">
                                    <div>
                                        @if (count($Cashiers) == 1)
                                            @foreach($Cashiers as $Cashier)
                                                @if ($Cashier->mop == "OTHERS")
                                                    <input type="checkbox" checked disabled>
                                                    <small class="pl-2">Others (Please Specify) ________________</small>
                                                @else
                                                    <input type="checkbox" disabled>
                                                    <small class="pl-2">Others (Please Specify) ________________</small>
                                                @endif
                                            @endforeach
                                        @else

                                            <input type="checkbox" disabled>
                                            <small class="pl-2">Others (Please Specify) ________________</small>
                                        @endif
                                    </div>
                                  
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <table>
                    <tbody>
                        <tr style="border: 2px solid black; border-bottom: none">
                            <td style="width: 160px; border-right: 2px solid black;" class="m-4">
                                <div class="text-center">
                                    <div>
                                        Payee
                                    </div>
                                </div>
                            </td>

                            <td style="width: 350px; border-right: 2px solid black;" class="m-4">
                                <div class="pl-2">
                                    <div>
                                        <small class="text-bold">{{ $Voucher->AccountName->acct_name}}</small> 
                                    </div>
                                </div>
                            </td>

                            <td style="width: 180px; border-right: 2px solid black;" class="m-4">
                                <div class="text-center">
                                    <div>
                                      TIN/Employee No. 
                                    </div>
                                    <div>
                                        <small class="text-bold">{{ $Voucher->AccountName->tinno}}</small>
                                    </div>
                                </div>
                            </td>

                            <td style="width: 310px; border-right: 2px solid black;" class="m-4 p-2">
                                <div class="">
                                    <div>
                                           ORS / BURS No. 
                                        <div class="text-center">
                                            @foreach ($Obligations as $Obligation)
                                           <small class="text-bold">{{$Obligation->orsno }}</small> 
                                            @endforeach
                                        </div>
                                    
                                    </div>
                                </div>
                            </td>

                           
                        </tr>
                    </tbody>
                </table>

                <table>
                    <tbody>
                        <tr style="border: 2px solid black;border-bottom: none">
                            <td style="width: 160px; border-right: 2px solid black;" class="m-4">
                                <div class="text-center">
                                    <div>
                                        Address
                                    </div>
                                </div>
                            </td>

                            <td style="width: 840px" class="m-4">
                                <div class="pl-2">
                                    <small class="text-bold">{{ $AccountName->address }}</small> 
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <table class="" style=" border: 2px solid black; border-top: none; height: 40px">
                    <tbody>
                        <tr style="border: 2px solid black;height: 40px">
                         
                            <td style="width: 510px; border-right: 2px solid black;" class="m-4">
                                <div class="text-center">
                                    <div>
                                        Particulars
                                    </div>
                                </div>
                            </td>
                            <td style="width: 180px; border-right: 2px solid black;" class="m-4">
                                <div class="text-center">
                                    <div>
                                        Responsibility Center
                                    </div>
                                </div>
                            </td>
                            <td style="width: 160px; border-right: 2px solid black;" class="m-4">
                                <div class="text-center">
                                    <div>
                                       PREXC
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

                <table class="" style=" border: 2px solid black; border-top: none; border-bottom: none; height: 40px">
                    <tbody>
                        <tr style="">
                         
                            <td style="width: 510px; border-right: 2px solid black;" class="m-4">
                                <div class="text-justify p-2">
                                    <div>
                                        <small class="text-bold">{{$Voucher->particulars}}</small>
                                    </div>
                                </div>
                            </td>
                            <td style="width: 180px; border-right: 2px solid black;" class="m-4">
                                <div class="text-center">
                                    <div>
                                        <small class="text-bold">{{$Voucher->office }}</small>
                                    </div>
                                </div>
                            </td>
                            <td style="width: 160px; border-right: 2px solid black;" class="m-4">
                                <div class="text-center">
                                    <div>
                                     
                                    </div>
                                </div>
                            </td>
                            <td style="width: 150px; border-right: 2px solid black; border-bottom: none;" class="m-4">
                                <div class="text-center">
                                    <div>
                                        P <span class="float-right pr-4 text-bold"> {{$FloatAmount}}</span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    
                    </tbody>
                </table>

                @if(count($Chargings) > 0)

                    @foreach ($Chargings as $Charging)

                    <table class="" style=" border: 2px solid black; border-top: none; border-bottom: none; height: 40px">
                        <tbody>
                            <tr style="">
                             
                                <td style="width: 510px; border-right: 2px solid black;" class="m-4">
                                    <div class="text-justify p-2">
                                        <div>
                                        
                                        </div>
                                    </div>
                                </td>
                                <td style="width: 180px; border-right: 2px solid black;" class="m-4">
                                    <div class="text-center">
                                        <div>
                                         
                                        </div>
                                    </div>
                                </td>
                                <td style="width: 160px; border-right: 2px solid black;" class="m-4">
                                    <div class="text-center">
                                        <div>
                                            <small class="text-bold"> {{$Charging->PAP->pap . ' = ' . $Charging->Activity->activity}}</small>
                                        </div>
                                    </div>
                                </td>
                                <td style="width: 150px; border-right: 2px solid black; border-bottom: none;" class="m-4">
                                    <div class="float-right pr-4">
                                        <div>
                                            <small class="text-bold">{{number_format($Charging->amount,2,'.',',')}}</small>       
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        
                        </tbody>
                    </table>


                    @endforeach
                
                @else

                <table class="" style=" border: 2px solid black; border-top: none; border-bottom: none; height: 40px">
                    <tbody>
                        <tr style="">
                         
                            <td style="width: 510px; border-right: 2px solid black;" class="m-4">
                                <div class="text-justify p-2">
                                    <div>
                                    
                                    </div>
                                </div>
                            </td>
                            <td style="width: 180px; border-right: 2px solid black;" class="m-4">
                                <div class="text-center">
                                    <div>
                                     
                                    </div>
                                </div>
                            </td>
                            <td style="width: 160px; border-right: 2px solid black;" class="m-4">
                                <div class="text-center">
                                    <div>
                                 
                                    </div>
                                </div>
                            </td>
                            <td style="width: 150px; border-right: 2px solid black; border-bottom: none;" class="m-4">
                                <div class="text-center">
                                    <div>
                                        
                                    </div>
                                </div>
                            </td>
                        </tr>
                    
                    </tbody>
                </table>

                @endif

                <table class="" style=" border: 2px solid black; border-top: none;  height: 40px">
                    <tbody>
                        <tr style="">
                         
                            <td style="width: 510px; border-right: 2px solid black;" class="m-4">
                                <div class="float-right p-2">
                                    <div>
                                   Amount Due
                                    </div>
                                </div>
                            </td>
                            <td style="width: 180px; border-right: 2px solid black;" class="m-4">
                                <div class="text-center">
                                    <div>
                                     
                                    </div>
                                </div>
                            </td>
                            <td style="width: 160px; border-right: 2px solid black;" class="m-4">
                                <div class="text-center">
                                    <div>
                                   
                                    </div>
                                </div>
                            </td>
                            <td style="width: 150px; border-right: 2px solid black; border-bottom: none; border-top: 2px solid black ">
                                <div class="text-center ">
                                    <div>
                                        P <span class="float-right pr-4 text-bold"> {{$FloatAmount}}</span>             
                                    </div>
                                </div>
                            </td>
                        </tr>

                    </tbody>
                </table>

                <table>
                    <tbody>
                        <tr style="border: 2px solid black;border-bottom: none">
                            <td style="width: 50px; border-right: 2px solid black;" class="m-4">
                                <div class="text-center">
                                    <div>
                                        A.
                                    </div>
                                </div>
                            </td>

                            <td style="width: 950px" class="">
                                <div>
                                    <div class="pl-2">
                                        <strong>Certified :</strong> Expenses / Cash Advance necessary, lawful and incurred under my direct supervision.
                                    </div>
                                    <br>
                                    <br>
                                    <div class="text-center p-0 m-0">
                                        <h6>{{$Voucher->BoxA->certified_by}}</h6>
                                    </div>
                                    <div class="text-center p-0 m-0">
                                        <h6>{{$Voucher->BoxA->position}}</h6>
                                    </div>
                                    <div class="text-center p-0 m-0">
                                        <small>Printed Name, Designation and Signature of Supervisor</small>
                                    </div>
                                </div>
                             
                            </td>
                        </tr>
                    </tbody>
                </table>

                <table>
                    <tbody>
                        <tr style="border: 2px solid black;">
                            <td style="width: 20px;border-right: none" class="m-4">
                                <div class="text-center">
                                    <div>
                                        B.
                                    </div>
                                </div>
                            </td>
                            <td style="width: 980px;border-right: 2px solid black;" class="m-4">
                                <div class="text-center">
                                    <div>
                                        Accounting Entry
                                    </div>
                                </div>
                            </td>

                         
                        </tr>
                    </tbody>
                </table>

                <table class="" style=" border: 2px solid black; border-top: none; height: 40px">
                    <tbody>
                        <tr style="border: 2px solid black;height: 40px">
                         
                            <td style="width: 510px; border-right: 2px solid black;" class="m-4">
                                <div class="text-center">
                                    <div>
                                        Account Title
                                    </div>
                                </div>
                            </td>
                            <td style="width: 180px; border-right: 2px solid black;" class="m-4">
                                <div class="text-center">
                                    <div>
                                        UACS Code
                                    </div>
                                </div>
                            </td>
                            <td style="width: 160px; border-right: 2px solid black;" class="m-4">
                                <div class="text-center">
                                    <div>
                                        Debit
                                    </div>
                                </div>
                            </td>
                            <td style="width: 150px; border-right: 2px solid black;" class="m-4">
                                <div class="text-center">
                                    <div>
                                        Credit
                                    </div>
                                </div>
                            </td>
                        </tr>
                    
                    </tbody>
                </table>


                @if(count($AccountingEntries) > 0)

                @foreach ($AccountingEntries as $AccountingEntry)

                 <table class="" style="border: 2px solid black; border-top: none; border-bottom: none; height: 20px">
                    <tbody>
                        <tr style=" border-bottom: none; height: 20px">
                         
                            <td style="width: 510px; border-right: 2px solid black;" class="m-4">
                                <div class="text-center">
                                    <div>
                                        <small class="text-bold">{{$AccountingEntry->AActivity->activity}}</small>  
                                    </div>
                                </div>
                            </td>
                            <td style="width: 180px; border-right: 2px solid black; border-bottom: none;" class="m-4">
                                <div class="text-center">
                                    <div>
                                        <small class="text-bold">{{$AccountingEntry->AUACS->uacs}}</small> 
                                    </div>
                                </div>
                            </td>
                            <td style="width: 160px; border-right: 2px solid black; border-bottom: none;" class="m-4">
                                <div class="text-center">
                                    <div>
                                        @if ($AccountingEntry->debit != 0)
                                        <small class="text-bold"><strong>{{ number_format($AccountingEntry->debit,2,'.',',') }} </strong></small>
                                    
                                      @endif      
                                   
                                    </div>
                                </div>
                            </td>
                            <td style="width: 150px; border-right: 2px solid black; border-bottom: none;" class="m-4">
                                <div class="text-center">
                                    <div>
                                        @if ($AccountingEntry->credit != 0)
                                      <small><strong>{{ number_format($AccountingEntry->credit,2,'.',',') }} </strong></small>  
                                       
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    
                    </tbody>
                </table>
                @endforeach

                @else

                    <table class="" style=" border: 2px solid black; border-top: none; height: 100px">
                        <tbody>
                            <tr style="border: 2px solid black;height: 40px">
                            
                                <td style="width: 510px; border-right: 2px solid black;" class="m-4">
                                    <div class="text-center">
                                        <div>
                                        
                                        </div>
                                    </div>
                                </td>
                                <td style="width: 180px; border-right: 2px solid black;" class="m-4">
                                    <div class="text-center">
                                        <div>
                                        
                                        </div>
                                    </div>
                                </td>
                                <td style="width: 160px; border-right: 2px solid black;" class="m-4">
                                    <div class="text-center">
                                        <div>
                                    
                                        </div>
                                    </div>
                                </td>
                                <td style="width: 150px; border-right: 2px solid black;" class="m-4">
                                    <div class="text-center">
                                        <div>
                                        
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        
                        </tbody>
                    </table>

                @endif


                <table class="" style=" border: 2px solid black; border-top: none; height: 40px">
                    <tbody>
                        <tr style="border: 2px solid black;height: 40px">
                         
                            <td style="width: 510px; border-right: 2px solid black;">
                                <div class="text-center">
                                    <div class="text-left p-2">
                                      <span><strong>C. </strong>Certified :</span> 
                                    </div>
                
                                     <div class="text-left pl-4">
                                        @if( !empty($Review))
                                            @if ($Review->is_available == true )
                                                <input type="checkbox" checked disabled>
                                                <small class="pl-2">Cash available</small>
                                            @else
                                            <input type="checkbox" disabled>
                                            <small class="pl-2">Cash available</small>
                                            @endif
                                        @else
                                            <input type="checkbox" disabled>
                                            <small class="pl-2">Cash available</small>
                                        @endif 
                                    </div>
                                    <div class="text-left pl-4">
                                        @if( !empty($Review))
                                            @if ($Review->is_subject == true ) 
                                                <input type="checkbox"  checked disabled>
                                                <small class="pl-2">Subject to Authority to Debit Account (when applicable)</small> 
                                            @else
                                            <input type="checkbox" disabled>
                                            <small class="pl-2">Subject to Authority to Debit Account (when applicable)</small> 
                                            @endif
                                        @else
                                            <input type="checkbox" disabled>
                                            <small class="pl-2">Subject to Authority to Debit Account (when applicable)</small> 
                                        @endif
                                        
                                    </div>
                                    <div class="text-left pl-4">
                                        @if( !empty($Review))
                                            @if ($Review->is_supporting == true ) 
                                                <input type="checkbox" checked disabled>
                                            <small class="pl-2">Supporting Documents completed and amount clamied proper</small> 
                                            @else
                                            <input type="checkbox"  disabled>
                                            <small class="pl-2">Supporting Documents completed and amount clamied proper</small>
                                            @endif
                                        @else
                                            <input type="checkbox"  disabled>
                                            <small class="pl-2">Supporting Documents completed and amount clamied proper</small>
                                        @endif
                                    </div>
                             
                                </div>
                            </td>
                            <td style="width: 490px; border-right: 2px solid black;">
                                <div class="text-center">
                                    <div class="text-left p-2">
                                      <span><strong>D. </strong>Approved for Payment :</span> 
                                    </div>
                                    <div class="text-left pl-4">
                                        <br>
                                    </div>
                                    <div class="text-left pl-4">
                                        <br>
                                    </div>
                                    <div class="text-left pl-4">
                                        <br>
                                    </div>
                                    <div class="text-left pl-4">
                                        <br>
                                    </div>
                                </div>
                            </td>
                       
                        </tr>
                    
                    </tbody>
                </table>

                <table>
                    <tbody>
                        <tr style="border: 2px solid black;border-bottom: none">
                            <td style="width: 160px; border-right: 2px solid black;" class="m-4">
                                <div class="pl-2">
                                    <div>
                                        Signature
                                    </div>
                                </div>
                            </td>

                            <td style="width: 350px;border-right: 2px solid black;" class="m-4">
                                <div class="pl-2">
                                    
                                </div>
                            </td>

                            <td style="width: 150px; border-right: 2px solid black;" class="m-4">
                                <div class="pl-2">
                                    <div>
                                        Signature
                                    </div>
                                </div>
                            </td>

                            <td style="width: 340px" class="m-4">
                                <div class="pl-2">
                                    
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <table>
                    <tbody>
                        <tr style="border: 2px solid black;border-bottom: none">
                            <td style="width: 160px; border-right: 2px solid black;" class="m-4">
                                <div class="pl-2">
                                    <div>
                                        Printed Name
                                    </div>
                                </div>
                            </td>

                            <td style="width: 350px;border-right: 2px solid black;" class="m-4">
                                <div class="pl-2 text-center" >
                                    <strong>DONEBELLE S. MESINA</strong>
                                </div>
                            </td>

                            <td style="width: 150px; border-right: 2px solid black;" class="m-4">
                                <div class="pl-2">
                                    <div>
                                        Printed Name
                                    </div>
                                </div>
                            </td>

                            <td style="width: 340px" class="m-4"> 
                                <div class="pl-2 text-center">
                                    @if( !empty($Voucher->boxD->certified_by))
                                    <strong>{{$Voucher->BoxD->certified_by}}</strong>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                
                <table>
                    <tbody>
                        <tr style="border: 2px solid black;border-bottom: none">
                            <td style="width: 160px; border-right: 2px solid black;" class="m-4">
                                <div class="pl-2">
                                    <div>
                                       Position
                                    </div>
                                </div>
                            </td>

                            <td style="width: 350px;border-right: 2px solid black;" class="m-4">
                                <div class="">
                                    <div style="border-bottom: 2px solid black " class="text-center">
                                        <strong>Accountant III</strong>
                                    </div>
                                    <div  class="text-center">
                                        <small> Head, Accounting Unit / Authorized Representative</small>
                                    </div>
                                </div>
                            </td>

                            <td style="width: 150px; border-right: 2px solid black;" class="m-4">
                                <div class="pl-2">
                                    <div>
                                       Position
                                    </div>
                                </div>
                            </td>

                            <td style="width: 340px" class="m-4">
                                <div class="text-center">
                                    <div style="border-bottom: 2px solid black">
                                        @if( !empty($Voucher->boxD->position))
                                      <small> <strong>{{$Voucher->BoxD->position}}</strong></small> 
                                      @endif
                                    </div>
                                    <div>
                                        <br>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <table>
                    <tbody>
                        <tr style="border: 2px solid black;border-bottom: none">
                            <td style="width: 160px; border-right: 2px solid black;" class="m-4">
                                <div class="pl-2">
                                    <div>
                                       Date
                                    </div>
                                </div>
                            </td>

                            <td style="width: 350px;border-right: 2px solid black;" class="m-4">
                                <div class="text-center">

                                    @foreach ($DVs as $Dv)
                                       <small><strong>{{ date_format( $Dv->created_at, 'm/d/Y')  }}</strong></small> 
                                    @endforeach
                                </div>
                            </td>

                            <td style="width: 150px; border-right: 2px solid black;" class="m-4">
                                <div class="pl-2">
                                    <div>
                                       Date
                                    </div>
                                </div>
                            </td>

                            <td style="width: 340px" class="m-4">
                                <div class="text-center">
                                    @if( !empty($BoxD->date_approved))
                                    <small><strong>{{ date_format( $BoxD->created_at, 'm/d/Y')  }}</strong></small> 
                                    @endif
                            
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <table>
                    <tbody>
                        <tr style="border: 2px solid black;border-bottom: none">
                            <td style="width: 20px; border-right: 2px solid black;border-bottom: 2px solid black" class="m-4">
                                <div class="text-center">
                                    <div>
                                        E. 
                                    </div>
                                </div>
                            </td>

                            <td style="width: 750px; border-right: 2px solid black;border-bottom: 2px solid black" class="m-4">
                                <div class="pl-2">
                                 Receipt of Payment
                                </div>
                            </td>

                            <td style="width: 230px; border-bottom: none" class="m-4">
                                <div class="pl-2">
                                  JEV No.
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <table>
                    <tbody>
                        <tr style="border: 2px solid black;border-bottom: none;border-top:none">
                            <td style="width: 80px; border-right: 2px solid black;" class="m-4">
                                <div class="text-center">
                                    <div>
                                    <small>Check/ADA No.</small> 
                                  
                                </div>
                            </td>

                            <td style="width: 200px; border-right: 2px solid black" class="m-4">
                                <div class="pl-2">
                                  @foreach($Cashiers as $Cashier)
                                    <small><strong>{{$Cashier->adano }}</strong></small>
                                  @endforeach
                                </div>
                            </td>


                            <td style="width: 80px;">
                                <div  class="text-left pl-2">
                                    <div>
                                    <small>Date : <span> 
                                        @foreach($Cashiers as $Cashier)
                                        <small><strong>{{date_format($Cashier->created_at,'m/d/Y') }}</strong></small>
                                      @endforeach
                                        
                                    </div>
                                    <div><br></div>
                                </div>
                            </td>

                             <td style="width: 150px; border-right: 2px solid black; " class="m-4">
                                <div class="pl-2">
                                  
                                </div>
                            </td>
                            <td style="width: 260px; border-right: 2px solid black;" class="m-4">
                                <div class="text-left pl-2">
                                    <div>
                                       <small>Bank Name & Account No.</small>
                                    </div>
                                    <div class="text-center">
                           
                                       <small><strong>{{$Voucher->AccountNumber->bank_code . ' - ' .  $Voucher->AccountNumber->acct_no }}</strong></small> 
                                    </div>
                                </div>
                            </td>
                            

                            <td style="width: 230px;border-top: none" class="m-4">
                                <div class="pl-2 text-center">
                                    @foreach ($DVs as $DV)
                                    <small><strong>{{$DV->jevno}}</strong></small>
                                
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <table>
                    <tbody>
                        <tr style="border: 2px solid black;border-bottom: none">
                            <td style="width: 80px; border-right: 2px solid black;" class="m-4">
                                <div class="text-center">
                                    <div>
                                    <small>Signature</small> 
                                  
                                </div>
                            </td>

                            <td style="width: 200px; border-right: 2px solid black" class="m-4">
                                <div class="pl-2">
                                  
                                </div>
                            </td>


                            <td style="width: 80px;">
                                <div  class="text-left pl-2">
                                    <div>
                                    <small>Date : <span> 
                                        @foreach($Cashiers as $Cashier)
                                        <small><strong>{{date_format($Cashier->created_at,'m/d/Y') }}</strong></small>
                                      @endforeach
                                        </span></small> 
                                    </div>
                                    <div><br></div>
                                </div>
                            </td>

                             <td style="width: 150px; border-right: 2px solid black; " class="m-4">
                                <div class="pl-2">
                                  
                                </div>
                            </td>
                            <td style="width: 260px; border-right: 2px solid black;" class="m-4">
                                <div class="text-left pl-2">
                                    <div>
                                       <small>Printed Name</small>
                                    </div>
                                    <div class="text-center"><small class="text-bold">{{$Voucher->AccountName->acct_name}}</small></div>
                                </div>
                            </td>
                            

                            <td style="width: 230px;border-top: none" class="m-4">
                                <div class="pl-2">
                                    Date:
                                </div>
                                <div><br></div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <table>
                    <tbody>
                        <tr style="border: 2px solid black;">
                            <td style="width: 1000px; border-right: 2px solid black;" class="m-4">
                                <div class="text-left pl-2">
                                    <div>
                                        <small>Official recepit No. & Date / Other Documents</small> 
                                    </div>
                                </div>
                            </td>

                           
                        </tr>
                    </tbody>
                </table>



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
