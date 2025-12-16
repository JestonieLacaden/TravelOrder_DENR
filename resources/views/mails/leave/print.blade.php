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
        .sig {
            width: auto;
            max-width: 70px;
        }

        /* Align 7.C and 7.D to top, prevent one column from affecting the other */
        .section-7c-7d {
            display: flex;
            gap: 20px;
            align-items: flex-start;
        }

        .section-7c-7d>div {
            flex: 1;
        }

        .approver3-sign {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding-top: 12px;
        }

    </style>
</head>
@php
use Illuminate\Support\Facades\Storage;

$a1 = optional($Leave->approvals->firstWhere('step', 1));
$a2 = optional($Leave->approvals->firstWhere('step', 2));
$a3 = optional($Leave->approvals->firstWhere('step', 3));

// Snapshot-only sourcing: names, positions, signatures come from leave_approvals
// This preserves historic records when employees change later.

$resolve = function ($p) {
if (!$p) return null;
$p = ltrim(str_replace('\\', '/', $p), '/');
if (preg_match('/^https?:\/:\//i', $p)) return $p;
if (str_starts_with($p, 'storage/')) return asset($p);
return asset('storage/'.$p);
};

$src = [
1 => $resolve($a1->signature_path ?? null),
2 => $resolve($a2->signature_path ?? null),
3 => $resolve($a3->signature_path ?? null),
];

$names = [
1 => trim($a1->approver_name ?? ''),
2 => trim($a2->approver_name ?? ''),
3 => trim($a3->approver_name ?? ''),
];

$positions = [
1 => trim($a1->approver_position ?? ''),
2 => trim($a2->approver_position ?? ''),
3 => trim($a3->approver_position ?? ''),
];

// Use stored edited values if available, otherwise use computed leaveCredits
$displayCredits = [
'vacation' => [
'earned' => $Leave->vacation_earned ?? ($leaveCredits['vacation']['earned'] ?? 0),
'this_app' => $Leave->vacation_this_app ?? ($leaveCredits['vacation']['this_app'] ?? 0),
'balance' => $Leave->vacation_balance ?? ($leaveCredits['vacation']['balance'] ?? 0),
],
'sick' => [
'earned' => $Leave->sick_earned ?? ($leaveCredits['sick']['earned'] ?? 0),
'this_app' => $Leave->sick_this_app ?? ($leaveCredits['sick']['this_app'] ?? 0),
'balance' => $Leave->sick_balance ?? ($leaveCredits['sick']['balance'] ?? 0),
],
];
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
                            <td colspan="5">
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
                                    <div class="col-sm-1">
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
                                    <input type="checkbox" value="" id="check1" {{ !empty($Leave->location_within_ph) ? 'checked' : '' }}>
                                    <label class="col-sm-4" for="datereceived">Within the Philippines </label>
                                    <div class=" col-sm-7">
                                        <input type="text" class="form-control" value="{{ $Leave->location_within_ph ?? '' }}" oninput="this.value = this.value.toUpperCase()">
                                    </div>

                                </div>
                                <div class="form-group row check-primary text-sm">
                                    <input type="checkbox" value="" id="check1" {{ !empty($Leave->location_abroad) ? 'checked' : '' }}>
                                    <label class="col-sm-4" for="datereceived">Abroad (Specify) </label>
                                    <div class=" col-sm-7">
                                        <input type="text" class="form-control" value="{{ $Leave->location_abroad ?? '' }}" oninput="this.value = this.value.toUpperCase()">
                                    </div>
                                </div>

                                <div class="">
                                    <i class="text-sm">In case of Sick Leave:</i>
                                </div>
                                <div class="form-group row check-primary text-sm">
                                    <input type="checkbox" value="" id="check1" {{ !empty($Leave->hospital_specify) ? 'checked' : '' }}>
                                    <label class="col-sm-4" for="datereceived"> In Hospital (Specify Illness) </label>
                                    <div class=" col-sm-7">
                                        <input type="text" class="form-control" value="{{ $Leave->hospital_specify ?? '' }}" oninput="this.value = this.value.toUpperCase()">
                                    </div>
                                </div>
                                <div class="form-group row check-primary text-sm">
                                    <input type="checkbox" value="" id="check1" {{ !empty($Leave->outpatient_specify) ? 'checked' : '' }}>
                                    <label class="col-sm-4" for="datereceived"> Out Patient (Specify Illness) </label>
                                    <div class=" col-sm-7">
                                        <input type="text" class="form-control" value="{{ $Leave->outpatient_specify ?? '' }}" oninput="this.value = this.value.toUpperCase()">
                                    </div>
                                </div>

                                <div class="pt-2">
                                    <i class="text-sm">In case of Study Leave:</i>
                                </div>
                                <div class="icheck-primary text-sm">
                                    <input type="checkbox" value="" id="check1" {{ ($Leave->study_masters_degree ?? false) ? 'checked' : '' }} disabled>
                                    <label for="check1"> Completion of Master's Degree</label>
                                </div>
                                <div class="icheck-primary text-sm">
                                    <input type="checkbox" value="" id="check2" {{ ($Leave->study_bar_board ?? false) ? 'checked' : '' }} disabled>
                                    <label for="check2"> BAR/Board Examination Review</label>
                                </div>
                                <div class="pt-2">
                                    <i class="text-sm">Other Purpose:</i>
                                </div>
                                <div class="icheck-primary text-sm">
                                    <input type="checkbox" value="" id="check3" {{ ($Leave->other_monetization ?? false) ? 'checked' : '' }} disabled>
                                    <label for="check3"> Monetization of Leave Credits</label>
                                </div>
                                <div class="icheck-primary text-sm">
                                    <input type="checkbox" value="" id="check4" {{ ($Leave->other_terminal_leave ?? false) ? 'checked' : '' }} disabled>
                                    <label for="check4"> Terminal Leave</label>
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
                                    6.D. COMMUTATION
                                </div>
                                <div class="icheck-primary text-sm">
                                    <input type="checkbox" value="" id="check1" {{ (is_string($Leave->commutation ?? null) && $Leave->commutation === 'not_requested') ? 'checked' : '' }}>
                                    <label for="check1"> Not Requested</label>
                                </div>
                                <div class="icheck-primary text-sm">
                                    <input type="checkbox" value="" id="check1" {{ (is_string($Leave->commutation ?? null) && $Leave->commutation === 'requested') ? 'checked' : '' }}>
                                    <label for="check1"> Requested</label>
                                </div>
                                <div class="text-center pt-2">
                                    @php
                                    $applicantSig = '';
                                    if (!empty($Employee->signature_path)) {
                                    // Try different path formats
                                    if (filter_var($Employee->signature_path, FILTER_VALIDATE_URL)) {
                                    $applicantSig = $Employee->signature_path;
                                    } elseif (strpos($Employee->signature_path, 'storage/') === 0) {
                                    $applicantSig = asset($Employee->signature_path);
                                    } elseif (strpos($Employee->signature_path, '/storage/') === 0) {
                                    $applicantSig = asset($Employee->signature_path);
                                    } else {
                                    $applicantSig = asset('storage/' . $Employee->signature_path);
                                    }
                                    }
                                    @endphp
                                    @if (!empty($applicantSig))
                                    <img src="{{ $applicantSig }}" class="sig" onerror="this.style.display='none'">
                                    @endif
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
        <td colspan="5" class="p-0 m-0">
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
                        <th class="p-0 m-0">Vacation Leave</th>
                        <th class="p-0 m-0">Sick Leave</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="p-0 m-0">Total Earned</td>
                            <td class="p-0 m-0 text-center">{{ $displayCredits['vacation']['earned'] ?? 0 }}</td>
                            <td class="p-0 m-0 text-center">{{ $displayCredits['sick']['earned'] ?? 0 }}</td>
                        </tr>
                        <tr>
                            <td class="p-0 m-0">Less this Application</td>
                            <td class="p-0 m-0 text-center">{{ $displayCredits['vacation']['this_app'] ?? 0 }}</td>
                            <td class="p-0 m-0 text-center">{{ $displayCredits['sick']['this_app'] ?? 0 }}</td>
                        </tr>
                        <tr>
                            <td class="p-0 m-0">Balance</td>
                            <td class="p-0 m-0 text-center">{{ $displayCredits['vacation']['balance'] ?? 0 }}</td>
                            <td class="p-0 m-0 text-center">{{ $displayCredits['sick']['balance'] ?? 0 }}</td>
                        </tr>
                    </tbody>

                </table>
            </div>
            <div class="text-center pt-2">
                @if (!empty($src[1]))
                <img src="{{ $src[1] }}" class="sig" onerror="this.style.display='none'">
                @endif
                <div class="text-bold">{{ ($names[1] ?? '') !== '' ? $names[1] : '—' }}</div>
                <div>{{ ($positions[1] ?? '') !== '' ? $positions[1] : '—' }}</div>
            </div>
        </td>
        <td>
            <div class="">
                7.B. RECOMMENDATION
            </div>
            <div class="icheck-primary text-sm pt-2">
                <input type="checkbox" value="" id="check1" {{ ($Leave->recommendation ?? '') === 'for_approval' ? 'checked' : '' }}>
                <label for="check1"> For Approval</label>
            </div>
            <div class="icheck-primary text-sm">
                <input type="checkbox" value="" id="check1" {{ ($Leave->recommendation ?? '') === 'for_disapproval' ? 'checked' : '' }}>
                <label for="check1"> For Disapproval</label>
            </div>
            <div>
                <strong><u>{{ $Leave->recommendation_notes ?? '_________________________________________________________' }}</u></strong>
            </div>
            <div>
                @if(empty($Leave->recommendation_notes))
                _________________________________________________________
                @endif
            </div>

            <div class="text-center pt-4">
                @if (!empty($src[2]))
                <img src="{{ $src[2] }}" class="sig" onerror="this.style.display='none'">
                @endif
                <div class="text-bold">{{ ($names[2] ?? '') !== '' ? $names[2] : '—' }}</div>
                <div>{{ ($positions[2] ?? '') !== '' ? $positions[2] : '—' }}</div>
            </div>
        </td>
    </tr>
    <tr>
        <td colspan="5">
            <div class="section-7c-7d">
                <div>
                    <div class="">
                        <strong>7.C. APPROVED FOR:</strong>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <u><strong>{{ $Leave->days_with_pay ?? '_________' }}</strong></u>
                        </div>
                        <div class="col-sm-10">
                            days with Pay
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <u><strong>{{ $Leave->days_without_pay ?? '_________' }}</strong></u>
                        </div>
                        <div class="col-sm-10">
                            days without Pay
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <u><strong>{{ $Leave->approved_others ?? '_________' }}</strong></u>
                        </div>
                        <div class="col-sm-10">
                            Others (Specify)
                        </div>
                    </div>
                    {{-- <div class="text-center pt-4">
                        @if (!empty($src[3]))
                        <img src="{{ $src[3] }}" class="sig" onerror="this.style.display='none'">
                    @endif
                    <div class="text-bold">{{ ($names[3] ?? '') !== '' ? $names[3] : '—' }}</div>
                    <div>{{ ($positions[3] ?? '') !== '' ? $positions[3] : '—' }}</div>
                </div> --}}
            </div>

            <div>
                <div class="">
                    <strong>7.D. DISAPPROVED DUE TO:</strong>
                </div>
                <div class="pt-2">
                    <strong><u>{{ $Leave->disapproved_reason ?? '_________________________________________________' }}</u></strong>
                </div>
                @if(empty($Leave->disapproved_reason))
                <div>
                    _________________________________________________
                </div>
                @endif
            </div>
            </div>

            <div class="approver3-sign">
                @if (!empty($src[3]))
                <img src="{{ $src[3] }}" class="sig" onerror="this.style.display='none'">
                @endif
                <div class="text-bold">{{ ($names[3] ?? '') !== '' ? $names[3] : '—' }}</div>
                <div>{{ ($positions[3] ?? '') !== '' ? $positions[3] : '—' }}</div>
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
