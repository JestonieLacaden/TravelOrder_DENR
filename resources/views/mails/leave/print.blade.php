
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

  <style>
    .sig{
        width:auto; 
        max-width: 60px; 
        min-width:40px;

    }
  </style>
</head>
@php
use Illuminate\Support\Facades\Storage;

$a1 = optional($Leave->approvals->firstWhere('step', 1));
$a2 = optional($Leave->approvals->firstWhere('step', 2));
$a3 = optional($Leave->approvals->firstWhere('step', 3));

$paths = [
1 => $a1->signature_path
?? optional($signatory ?? null)->signature1_path
?? optional(optional($signatory ?? null)->employee1)->signature_path,
2 => $a2->signature_path
?? optional($signatory ?? null)->signature2_path
?? optional(optional($signatory ?? null)->employee2)->signature_path,
3 => $a3->signature_path
?? optional($signatory ?? null)->signature3_path
?? optional(optional($signatory ?? null)->employee3)->signature_path,
];

$src = [];
foreach ([1,2,3] as $i) {
$p = $paths[$i] ?? null;
$src[$i] = ($p && Storage::disk('public')->exists($p)) ? asset('storage/'.$p) : null;
}
@endphp

<body>
<div class="wrapper">

  <!-- Main content -->
  <section class="invoice">
    <!-- title row -->

        <i>System Generated Leave id: {{$Leave->id }}
   
        <table class="table table-bordered border border-black">
            <tbody>
                <tr>
                    <td colspan="5">
                        <div>
                            CSC Form No. 6
                        </div>
                        <div>
                            Revised 2020
                        </div>
                        <div class="text-center">
                            <h4>APPLICATION FOR LEAVE</h4>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td  colspan="5">
                        <div class="row"> 
                            <div class="col-sm-3">
                                1. OFFICE / DEPARTMENT
                            </div>
                            <div class="col-sm-1 text-center">
                                2. NAME
                            </div>
                            <div class="col-sm-3 text-center">
                                (Last)
                            </div>
                            <div class="col-sm-3 text-center">
                                (First)
                            </div>
                            <div class="col-sm-2 text-center">
                                (Middle)
                            </div>
                        </div>
                        <div class="row text-center text-bold text-sm"> 
                            <div class="col-sm-3">
                             {{$Employee->office->office}}
                            </div>
                            <div class="col-sm-1">
                          
                            </div>
                            <div class="col-sm-3">
                             {{ $Employee->lastname }}  
                            </div>
                            <div class="col-sm-3">
                                {{ $Employee->firstname }}  
                            </div>
                            <div class="col-sm-2">
                                {{ $Employee->middlename }}  
                            </div>
                        </div>
                    </td>
                   
                </tr>
                <tr>
                    <td colspan="5">
                        <div class="row text-sm">
                            <div class="pl-2">
                                3. DATE OF FILING :
                            </div>
                            <div class="col-sm-fill px-2 text-bold text-left">
                                {{ $Leave->created_at }}
                            </div>
                            <div class="col-sm-fill px-2 text-center ">
                                4. POSITION :
                            </div>
                            <div class="col-sm-6 text-bold">
                               {{ $Employee->position }}  
                            </div>
                            <div class="col-sm-1" >
                                5. SALARY :
                            </div>
                            <div class="col-sm-fill px-2 text-bold text-left">
                            
                            </div>

                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="5" class="p-0 m-0">
                        <div class=" text-center text-sm">
                           <h6>6. DETAILS OF APPLICATION</h6> 
                        </div>
                    </td>
                </tr>
               <tr>
                   <td>
                    <div class="">
                        6.A. TYPE OF LEAVE TO BE AVAILED OF
                    </div>
                    @foreach($Leave_types as $LeaveType)
                        @if($LeaveType->id == $Leave->leaveid)
                            <div class="icheck-primary text-sm">
                                <input type="checkbox" checked value="" id="check1">
                                <label for="check1" class="text-xs"> {{ $LeaveType->leave_type}}</label>
                            </div>
                      @else
                        <div class="icheck-primary text-sm">
                            <input type="checkbox" value="" id="check1">
                            <label for="check1" class="text-xs"> {{ $LeaveType->leave_type}}</label>
                        </div>
                      @endif
                    @endforeach
                    </td>
                    <td>
                        <div class="">
                            6.B. DETAILS OF LEAVE
                        </div>
                        <div class="">
                          <i class="text-sm">In case of Vacation/Special Privilege Leave:</i>
                        </div>
                        <div class="form-group row check-primary text-sm">
                            <input type="checkbox"  value="" id="check1">
                            <label class="col-sm-4" for="datereceived">Within the Philippines </label>
                            <div class=" col-sm-7">
                                <input type="text" class ="form-control" value="" oninput="this.value = this.value.toUpperCase()">
                            </div>
                          
                          </div>
                          <div class="form-group row check-primary text-sm">
                            <input type="checkbox"  value="" id="check1">
                            <label class="col-sm-4" for="datereceived">Abroad (Specify) </label>
                            <div class=" col-sm-7">
                                <input type="text" class ="form-control" value="" oninput="this.value = this.value.toUpperCase()">
                            </div>
                          </div>
                       
                        <div class="">
                            <i class="text-sm">In case of Vacation/Special Privilege Leave:</i>
                          </div>
                          <div class="form-group row check-primary text-sm">
                            <input type="checkbox"  value="" id="check1">
                            <label class="col-sm-4" for="datereceived"> In Hospital (Specify Illness) </label>
                            <div class=" col-sm-7">
                                <input type="text" class ="form-control" value="" oninput="this.value = this.value.toUpperCase()">
                            </div>
                          </div>
                          <div class="form-group row check-primary text-sm">
                            <input type="checkbox"  value="" id="check1">
                            <label class="col-sm-4" for="datereceived">  Out Patient (Specify Illness) </label>
                            <div class=" col-sm-7">
                                <input type="text" class ="form-control" value="" oninput="this.value = this.value.toUpperCase()">
                            </div>
                          </div>
                    
                        <div class="pt-2">
                            <i class="text-sm">In case of Study Leave:</i>
                          </div>
                          <div class="icheck-primary text-sm">
                            <input type="checkbox"  value="" id="check1">
                            <label for="check1">  Completion of Master's Degree</label>
                        </div>
                        <div class="icheck-primary text-sm">
                            <input type="checkbox"  value="" id="check1">
                            <label for="check1">  BAR/Board Examination Review</label>
                        </div>
                        <div class="pt-2">
                            <i class="text-sm">Other Purpose:</i>
                          </div>
                          <div class="icheck-primary text-sm">
                            <input type="checkbox"  value="" id="check1">
                            <label for="check1">   Monetization of Leave Credits</label>
                        </div>
                        <div class="icheck-primary text-sm">
                            <input type="checkbox"  value="" id="check1">
                            <label for="check1">   Terminal Leave</label>
                        </div>
                     
                    </td>
                </tr> 
                <tr>
                    <td>
                        <div class="">
                           6.C. NUMBER OF WORKING DAYS APPLIED FOR
                        </div>
                        <div class="">
                            <h6><u>{{$Count . ' ' . '- DAY/S'}} </u> </h6>
                        </div>
                        
                        <div class="pl-1">
                            INCLUSIVE DATES
                         </div>
                         <div class="">
                            <h6><u>{{$Leave->daterange }}</u> </h6>
                        </div>
                    </td>
                    <td>
                        <div class="">
                            6.D.  COMMUTATION
                         </div>
                         <div class="icheck-primary text-sm">
                            <input type="checkbox"  value="" id="check1">
                            <label for="check1"> Not Requested</label>
                        </div>
                        <div class="icheck-primary text-sm">
                            <input type="checkbox"  value="" id="check1">
                            <label for="check1"> Requested</label>
                        </div>
                        <div class="text-center pt-2">
                            <img src="{{asset('images/dummySign.png')}}" class="sig" alt="Signature">
                            <div class="text-bold">
                                {{$Employee->firstname . ' ' . $Employee->middlename . ' ' .  $Employee->lastname }}
                            </div>
                            <div class="">
                                (Signature of Applicant)
                            </div>
                         </div>
                    </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="5"  class="p-0 m-0">
                        <div class="p-0 text-center text-sm">
                           <h6>7. DETAILS OF ACTION ON APPLICATION</h6> 
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="">
                            7.A. CERTIFICATION OF LEAVE CREDITS
                         </div>
                         <div class="">
                            as of <u class="text-bold">{{ now() }}</u>
                         </div>
                         <div>
                            <table class="table table-bordered">
                                <thead class="p-0 m-0 text-center">
                                 <th class="p-0 m-0"></th>
                                  <th  class="p-0 m-0">Vacation Leave</th>
                                  <th class="p-0 m-0">Sick Leave</th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="p-0 m-0">Total Earned</td>
                                        <td class="p-0 m-0"></td>
                                        <td class="p-0 m-0"></td>
                                    </tr>
                                    <tr>
                                        <td class="p-0 m-0">Less this Application</td>
                                        <td class="p-0 m-0"></td>
                                        <td class="p-0 m-0"></td>
                                    </tr>
                                    <tr>
                                        <td class="p-0 m-0">Balance</td>
                                        <td class="p-0 m-0"></td>
                                        <td class="p-0 m-0"></td>
                                    </tr>
                                </tbody>

                            </table>
                         </div>
                         <div class="text-center pt-2">
                             @if($src[1])
                             <img src="{{ $src[1] }}" alt="Signature" height="50">
                             @endif
                             <div class="text-bold">{{ $a2->approver_name ?? '—' }}</div>
                             <div>{{ $a2->approver_position ?? '' }}</div>
                         </div>
                    </td>
                    <td>
                        <div class="">
                            7.B. RECOMMENDATION
                         </div>
                         <div class="icheck-primary text-sm pt-2">
                            <input type="checkbox"  value="" id="check1">
                            <label for="check1"> For Approval</label>
                        </div>
                        <div class="icheck-primary text-sm">
                            <input type="checkbox"  value="" id="check1">
                            <label for="check1"> For Disapproval</label>
                        </div>
                        <div>
                           _________________________________________________________
                        </div>
                        <div>
                            _________________________________________________________
                        </div>
                     
                        <div class="text-center pt-4">
                            @if($src[2])
                            <img src="{{ $src[2] }}" alt="Signature" height="50">
                            @endif
                            <div class="text-bold">{{ $a2->approver_name ?? '—' }}</div>
                            <div>{{ $a2->approver_position ?? '' }}</div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td  colspan="5">
                        <div class="row"> 
                            <div class="col-sm-6">
                                7.C. APPROVED FOR:
                            </div>
                            <div class="col-sm-5 pl-4">
                                7.D. DISAPPROVED DUE TO:
                            </div>
                        </div>
                        <div class="row"> 
                            <div class="col-sm-1">
                              _________
                            </div>
                            <div class="col-sm-5">
                             days with Pay
                            </div>
                            <div class="col-sm-3 pl-4">
                               _________________________________________________   
                            </div>
                         
                        </div>
                        <div class="row"> 
                            <div class="col-sm-1">
                              _________
                            </div>
                            <div class="col-sm-5">
                             days without Pay
                            </div>
                            <div class="col-sm-3 pl-4">
                               _________________________________________________
                            </div>
                        
                        </div>
                        <div class="row"> 
                            <div class="col-sm-1">
                              _________
                            </div>
                            <div class="col-sm-5">
                            Others (Specify)
                            </div>
                            <div class="col-sm-3 pl-4">
                               _________________________________________________  
                            </div>
                    
                        </div>
                        <div class="text-center pt-4">
                            @if($src[3])
                            <img src="{{ $src[3] }}" alt="Signature" height="50">
                            @endif
                            <div class="text-bold">{{ $a3->approver_name ?? '—' }}</div>
                            <div>{{ $a3->approver_position ?? '' }}</div>
                        </div>
                    </td>
                </tr>
           
          </tbody>
        </table>
       
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
<!-- Page specific script -->
{{-- <script>
    window.addEventListener('load', () => window.print());
    window.onafterprint = () => location.replace("{{ route('userleave.index') }}");

</script> --}}
@if (empty($preview))
<script>
    window.addEventListener('load', function() {
        window.print();
    });
    window.onafterprint = function() {
        location.replace(@json(route('userleave.index'))); // /leave-management
    };

</script>
@endif


</body>
</html>
