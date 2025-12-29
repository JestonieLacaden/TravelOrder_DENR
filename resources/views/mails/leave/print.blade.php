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

        /* Use Arial as the document font for screen and print */
        html,
        body {
            font-family: Arial, Helvetica, sans-serif;
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

        /* Table borders (fallback if Bootstrap not applied) */
        /* Outer table border thicker (4px) and inner cell borders thinner (2px).
           Using separate border-collapse and border-spacing:0 keeps borders flush. */
        table.table-bordered,
        .table-bordered {
            border: 2px solid #000 !important;
            border-collapse: separate !important;
            border-spacing: 0 !important;
            width: 100% !important;
            box-sizing: border-box;
        }

        table.table-bordered th,
        table.table-bordered td,
        .table-bordered th,
        .table-bordered td {
            border: 1px solid #000 !important;
            /* padding: .35rem !important; */
            vertical-align: top;
        }

        /* Full-width underline block used for 6.B fields. Text wraps; underline spans full column. */
        .underline-block {
            display: block;
            width: 100%;
            min-height: 1.2em;
            padding-bottom: 0.25rem;
            box-sizing: border-box;
            border-bottom: 1px solid #000;
            /* reliable in print */
        }

        .underline-text {
            display: block;
            white-space: normal;
            background: transparent;
        }

        /* Fixed underline blocks for stable printed line lengths */
        .fixed-underline {
            display: block;
            width: 100%;
            min-height: 1.2em;
            padding-bottom: 0.25rem;
            box-sizing: border-box;
            border-bottom: 1px solid #000;
        }

        .fixed-underline--short {
            display: inline-block;
            min-width: 80px;
            max-width: 140px;
            padding: 0 6px 0.15rem 6px;
            box-sizing: border-box;
            text-align: center;
            border-bottom: 1px solid #000;
            vertical-align: bottom;
        }

        @media print {
            .underline-block {
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
            }
        }

        /* Print: force checkboxes to be black-and-white (remove colored accents) */
        @media print {

            /* Native checkbox color in supporting browsers */
            input[type="checkbox"] {
                accent-color: #000 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            /* iCheck/AdminLTE style overrides (covers custom pseudo-elements) */
            .icheck-primary input[type="checkbox"]+label::before,
            .icheck-primary input[type="checkbox"]+label::after,
            .icheck-primary input[type="checkbox"]:checked+label::before,
            .icheck-primary input[type="checkbox"]:checked+label::after {
                background-color: #000 !important;
                border-color: #000 !important;
                color: #000 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            /* Ensure checkmark (if drawn) is visible in black */
            .icheck-primary input[type="checkbox"]:checked+label::after {
                color: #fff !important;
            }

            /* Fallback: grayscale any colored icons inside labels */
            .icheck-primary label,
            input[type="checkbox"]+label {
                filter: grayscale(100%) !important;
            }
        }

        .txtSmall {
            font-size: 0.75rem;
            line-height: 12px;
        }

        .text-xs {
            font-size: 0.75rem !important;
        }

        .text-s {
            font-size: 0.75rem !important;
        }

        /* Reduce boldness of plain labels inside the invoice to match print layout */
        .invoice label:not(.form-check-label):not(.custom-file-label) {
            font-weight: 400 !important;
        }

        @media print {
            .invoice label:not(.form-check-label):not(.custom-file-label) {
                font-weight: 400 !important;
            }
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

            {{-- <i>System Generated Leave id: {{$Leave->id }}</i> --}}

            <table class="table table-bordered border-5 border-black">
                <tbody>
                    <tr>
                        <td colspan="5">
                            <div class="txtSmall">
                                CSC Form No. 6
                            </div>
                            <div class="txtSmall">
                                Revised 2020
                            </div>
                            <div class="text-center">
                                <h4 class="text-bold">APPLICATION FOR LEAVE</h4>
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
                                    <div class="underline-block">
                                        @if(!empty($Leave->created_at))
                                        <span class="underline-text">{{ $Leave->created_at }}</span>
                                        @else
                                        <span class="underline-text">&nbsp;</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-fill px-2 text-center ">
                                    4. POSITION :
                                </div>
                                <div class="col-sm-4 mr-3 text-bold">
                                    <div class="underline-block">
                                        @if(!empty($Employee->position))
                                        <span class="underline-text">{{ $Employee->position }}</span>
                                        @else
                                        <span class="underline-text">&nbsp;</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-fill">
                                    5. SALARY :
                                </div>
                                <div class="col-sm-2 px-2 text-bold text-left">
                                    <div class="underline-block">
                                        @if(is_numeric($Employee->salary))
                                        <span class="underline-text">₱{{ number_format($Employee->salary, 2) }}</span>
                                        @else
                                        <span class="underline-text">&nbsp;</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5" class="p-0 m-0">
                            <div class=" text-center text-sm">
                                <h6 style="margin: 0;">6. DETAILS OF APPLICATION</h6>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div>
                                6.A. TYPE OF LEAVE TO BE AVAILED OF
                            </div>
                            <div style="display: flex; flex-direction:column; justify-content: space-between; height:420px;">
                                @foreach($Leave_types as $LeaveType)
                                @if($LeaveType->id == $Leave->leaveid)
                                <div class="icheck-primary text-sm" style="display: flex; align-items:center;">
                                    <input type="checkbox" checked value="" id="check1" style="margin-right: 4px;">
                                    <label for="check1" class="text-xs" style="margin-bottom: 0;"> {{ $LeaveType->leave_type}}</label>
                                </div>
                                @else
                                <div class="icheck-primary text-sm" style="display: flex; align-items:center;">
                                    <input type="checkbox" value="" id="check1" style="margin-right: 4px;">
                                    <label for="check1" class="text-xs" style="margin-bottom: 0;"> {{ $LeaveType->leave_type}}</label>

                                </div>
                                @endif
                                @endforeach
                            </div>
                        </td>
                        <td>
                            <div class="">
                                6.B. DETAILS OF LEAVE
                            </div>
                            <div class="">
                                <i class="text-sm">In case of Vacation/Special Privilege Leave:</i>
                            </div>
                            <div class="form-group row check-primary text-sm align-items-center">
                                <input type="checkbox" value="" id="check1" {{ !empty($Leave->location_within_ph) ? 'checked' : '' }}>
                                <label class="col-sm-4 mb-0 text-s" for="datereceived">Within the Philippines </label>
                                <div class=" col-sm-7">
                                    <div class="underline-block">
                                        @if(!empty($Leave->location_within_ph))
                                        <span class="underline-text">{{ $Leave->location_within_ph }}</span>
                                        @else
                                        <span class="underline-text">&nbsp;</span>
                                        @endif
                                    </div>
                                </div>

                            </div>
                            <div class="form-group row check-primary text-sm align-items-center">
                                <input type="checkbox" value="" id="check1" {{ !empty($Leave->location_abroad) ? 'checked' : '' }}>
                                <label class="col-sm-4 mb-0 text-s" for="datereceived">Abroad (Specify) </label>
                                <div class=" col-sm-7">
                                    <div class="underline-block">
                                        @if(!empty($Leave->location_abroad))
                                        <span class="underline-text">{{ $Leave->location_abroad }}</span>
                                        @else
                                        <span class="underline-text">&nbsp;</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="">
                                <i class="text-sm">In case of Sick Leave:</i>
                            </div>
                            <div class="form-group row check-primary text-sm align-items-center">
                                <input type="checkbox" value="" id="check1" {{ !empty($Leave->hospital_specify) ? 'checked' : '' }}>
                                <label class="col-sm-5 mb-0 text-s" for="datereceived"> In Hospital (Specify Illness) </label>
                                <div class=" col-sm-6">
                                    <div class="underline-block">
                                        @if(!empty($Leave->hospital_specify))
                                        <span class="underline-text">{{ $Leave->hospital_specify }}</span>
                                        @else
                                        <span class="underline-text">&nbsp;</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row check-primary text-sm align-items-center">
                                <input type="checkbox" value="" id="check1" {{ !empty($Leave->outpatient_specify) ? 'checked' : '' }}>
                                <label class="col-sm-5 mb-0 text-s" for="datereceived"> Out Patient (Specify Illness) </label>
                                <div class=" col-sm-6">
                                    <div class="underline-block">
                                        @if(!empty($Leave->outpatient_specify))
                                        <span class="underline-text">{{ $Leave->outpatient_specify }}</span>
                                        @else
                                        <span class="underline-text">&nbsp;</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="pt-2">
                                <i class="text-sm">In case of Study Leave:</i>
                            </div>
                            <div class="icheck-primary text-sm d-flex align-items-center mb-1">
                                <input type="checkbox" class="mr-1" value="" id="check1" {{ ($Leave->study_masters_degree ?? false) ? 'checked' : '' }}>
                                <label for="check1" class="mb-0 text-s"> Completion of Master's Degree</label>
                            </div>
                            <div class="icheck-primary text-sm d-flex align-items-center mb-1">
                                <input type="checkbox" class="mr-1" value="" id="check2" {{ ($Leave->study_bar_board ?? false) ? 'checked' : '' }}>
                                <label for="check2" class="mb-0 text-s"> BAR/Board Examination Review</label>
                            </div>
                            <div class="pt-2">
                                <i class="text-sm">Other Purpose:</i>
                            </div>
                            <div class="icheck-primary text-sm d-flex align-items-center mb-1">
                                <input type="checkbox" class="mr-1" value="" id="check3" {{ ($Leave->other_monetization ?? false) ? 'checked' : '' }}>
                                <label for="check3" class="mb-0 text-s"> Monetization of Leave Credits</label>
                            </div>
                            <div class="icheck-primary text-sm d-flex align-items-center mb-1">
                                <input type="checkbox" class="mr-1" value="" id="check4" {{ ($Leave->other_terminal_leave ?? false) ? 'checked' : '' }}>
                                <label for="check4" class="mb-0 text-s"> Terminal Leave</label>
                            </div>

                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="">
                                6.C. NUMBER OF WORKING DAYS APPLIED FOR
                            </div>
                            <div class="">
                                <h6><strong><u>{{$Count . ' ' . '- DAY/S'}} </u></strong></h6>
                            </div>

                            <div class="pl-1">
                                INCLUSIVE DATES
                            </div>
                            <div class="">
                                <h6><strong><u>{{$Leave->daterange }}</u></strong></h6>
                            </div>
                        </td>
                        <td>
                            <div class="">
                                6.D. COMMUTATION
                            </div>
                            <div class="icheck-primary text-sm d-flex align-items-center mb-1">
                                <input type="checkbox" class="mr-1" value="" id="check1" {{ (is_string($Leave->commutation ?? null) && $Leave->commutation === 'not_requested') ? 'checked' : '' }}>
                                <label for="check1" class="mb-0 text-s"> Not Requested</label>
                            </div>
                            <div class="icheck-primary text-sm d-flex align-items-center mb-1">
                                <input type="checkbox" class="mr-1" value="" id="check1" {{ (is_string($Leave->commutation ?? null) && $Leave->commutation === 'requested') ? 'checked' : '' }}>
                                <label for="check1" class="mb-0 text-s"> Requested</label>
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
                <h6 style="margin: 0;">7. DETAILS OF ACTION ON APPLICATION</h6>

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
                <table class="table table-bordered1 border-3 border-black ">

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
                <u>
                    <div class="text-bold">{{ ($names[1] ?? '') !== '' ? $names[1] : '—' }}</div>
                </u>
                <div>{{ ($positions[1] ?? '') !== '' ? $positions[1] : '—' }}</div>
            </div>
        </td>
        <td>
            <div class="">
                7.B. RECOMMENDATION
            </div>
            <div class="icheck-primary text-sm pt-2 d-flex align-items-center mb-1">
                <input type="checkbox" class="mr-1" value="" id="check1" {{ ($Leave->recommendation === 'for_approval') ? 'checked' : '' }}>
                <label for="check1" class="mb-0"> For Approval</label>
            </div>
            <div class="icheck-primary text-sm d-flex align-items-center mb-1">
                <input type="checkbox" class="mr-1" value="" id="check1" {{ ($Leave->recommendation === 'for_disapproval') ? 'checked' : '' }}>
                <label for="check1" class="mb-0"> For Disapproval Due to</label>
            </div>
            <div>
                <div class="fixed-underline">
                    @if(!empty($Leave->recommendation_notes))
                    <span class="underline-text">{{ $Leave->recommendation_notes }}</span>
                    @else
                    <span class="underline-text">&nbsp;</span>
                    @endif
                </div>
            </div>

            <div class="text-center pt-4">
                @if (!empty($src[2]))
                <img src="{{ $src[2] }}" class="sig" onerror="this.style.display='none'">
                @endif
                <u>
                    <div class="text-bold">{{ ($names[2] ?? '') !== '' ? $names[2] : '—' }}</div>
                </u>

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
                    <div class="row align-items-center">
                        <div class="col-sm-2">
                            <div class="fixed-underline--short">
                                @if(!empty($Leave->days_with_pay))
                                <strong>{{ $Leave->days_with_pay }}</strong>
                                @else
                                &nbsp;
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-10">
                            days with Pay
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <div class="fixed-underline--short">
                                @if(!empty($Leave->days_without_pay))
                                <strong>{{ $Leave->days_without_pay }}</strong>
                                @else
                                &nbsp;
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-10">
                            days without Pay
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <div class="fixed-underline--short">
                                @if(!empty($Leave->approved_others))
                                <strong>{{ $Leave->approved_others }}</strong>
                                @else
                                &nbsp;
                                @endif
                            </div>
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
                    <div class="fixed-underline">
                        @if(!empty($Leave->disapproved_reason))
                        <span class="underline-text">{{ $Leave->disapproved_reason }}</span>
                        @else
                        <span class="underline-text">&nbsp;</span>
                        @endif
                    </div>
                </div>
            </div>
            </div>

            <div class="approver3-sign">
                @if (!empty($src[3]))
                <img src="{{ $src[3] }}" class="sig" onerror="this.style.display='none'">
                @endif
                <u>
                    <div class="text-bold">{{ ($names[3] ?? '') !== '' ? $names[3] : '—' }}</div>
                </u>
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
