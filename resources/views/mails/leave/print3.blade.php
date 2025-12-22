<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ config('app.name', 'Information System') }}</title>
    <link rel="stylesheet" href="{{ asset('css/printLeave.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">

</head>

<body>
    <div id="print-area" class="page">


        <div class="hdr">
            <div>CSC Form No. 6<br />Revised 2020</div>
            <div></div>
        </div>

        <div class="title">Application for Leave</div>
        <i>System Generated Leave id: {{$Leave->id }}</i>
        <!-- 1–5 -->
        <table class="no-border" style="width: 100%;">
            <tr>
                <th colspan="2" rowspan="2" style="width: auto;">1. OFFICE/DEPARTMENT
                    <br><br>
                    <u>{{$Employee->office->office}}</u>
                </th>
                <th rowspan="2" colspan="4" style="width: auto;">2. NAME<br>
                    <div class="rowName">
                        <div>
                            <i>(Last)</i><br>
                            <u>{{ $Employee->lastname }}</u>
                        </div>
                        <div>
                            <i>(First)</i><br>
                            <u>{{ $Employee->firstname }}</u>
                        </div>
                        <div>
                            <i>(Middle)</i><br>
                            <u>{{ $Employee->middlename }}</u>
                        </div>
                    </div>
                </th>
            </tr>
            <tr>
            </tr>
            <tr>
                <th colspan="2">
                    <div style="display: flex; flex-direction: column;">
                        3. DATE OF FILING
                        <br>
                        <u>{{ $Leave->created_at }}</u>
                    </div>
                </th>
                <th colspan="2">4. POSITION <br>
                    <u>{{ $Employee->position }}</u>
                </th>
                <th colspan="2">5. SALARY <br>
                    <u></u>
                </th>
            </tr>
        </table>

        <!-- 6. DETAILS OF APPLICATION -->
        <table>
            <tr>
                <th colspan="2" class="center">6. DETAILS OF APPLICATION</th>
            </tr>
            <tr>
                <td style="width:50%">
                    <strong>6.A TYPE OF LEAVE TO BE AVAILED OF</strong>
                    <div class="checks" style="margin-top:2mm">
                        @foreach($Leave_types as $LeaveType)
                        @if($LeaveType->id == $Leave->leaveid)
                        <label for="check1">
                            <input type="checkbox" checked value="" id="check1">
                            {{ $LeaveType->leave_type}}
                        </label>
                        @else
                        <label for="check1">
                            <input type="checkbox" value="" id="check1">
                            {{ $LeaveType->leave_type}}
                        </label>
                        @endif
                        @endforeach
                        <!-- <label><input type="checkbox" /> Vacation Leave <span class="muted">(Sec. 51, Rule XVI, Omnibus
                                Rules Implementing E.O. No. 292)</span></label>
                        <label><input type="checkbox" /> Mandatory/Forced Leave <span class="muted">(Sec. 25, Rule XVI,
                                Omnibus Rules Implementing E.O. No. 292)</span></label>
                        <label><input type="checkbox" /> Sick Leave <span class="muted">(Sec. 43, Rule XVI, Omnibus
                                Rules Implementing E.O. No. 292)</span></label>
                        <label><input type="checkbox" /> Maternity Leave <span class="muted">(RA No. 11210/IRR issued by
                                CSC, DOLE and SSS)</span></label>
                        <label><input type="checkbox" /> Paternity Leave <span class="muted">(RA No. 8187/CSC MC No. 71,
                                s. 1998, as amended)</span></label>
                        <label><input type="checkbox" /> Special Privilege Leave <span class="muted">(Sec. 21, Rule XVI,
                                Omnibus Rules Implementing E.O. No. 292)</span></label>
                        <label><input type="checkbox" /> Solo Parent Leave <span class="muted">(RA No. 8972/CSC MC No.
                                8, s. 2004)</span></label>
                        <label><input type="checkbox" /> Study Leave <span class="muted">(Sec. 68, Rule XVI, Omnibus
                                Rules Implementing E.O. No. 292)</span></label>
                        <label><input type="checkbox" /> 10-Day VAWC Leave <span class="muted">(RA No. 9262 / CSC MC No.
                                15, s. 2005)</span></label>
                        <label><input type="checkbox" /> Rehabilitation Privilege <span class="muted">(Sec. 55, Rule
                                XVI, Omnibus Rules Implementing E.O. No. 292)</span></label>
                        <label><input type="checkbox" /> Special Leave Benefits for Women <span class="muted">(RA No.
                                9710 / CSC MC No. 25, s. 2010)</span></label>
                        <label><input type="checkbox" /> Special Emergency (Calamity) Leave <span class="muted">(CSC MC
                                No. 2, s. 2012, as amended)</span></label>
                        <label><input type="checkbox" /> Adoption Leave <span class="muted">(RA No. 8552)</span></label>
                        <label><input type="checkbox" /> Others: <span class="line"
                                style="min-width:55mm"></span></label> -->
                    </div>
                </td>
                <td style="width:50%">
                    <strong>6.B DETAILS OF LEAVE</strong>
                    <div class="checks" style="margin-top:2mm">
                        <label class="muted"><em>In case of Vacation/Special Privilege Leave:</em></label>
                        <label><input type="checkbox" /> Within the Philippines &nbsp;
                            <input type="text" class="form-control" value="" oninput="this.value = this.value.toUpperCase()">
                        </label>
                        <label><input type="checkbox" /> Abroad (Specify) &nbsp;
                            <input type="text" class="form-control" value="" oninput="this.value = this.value.toUpperCase()">
                        </label>

                        <label class="muted" style="margin-top:2mm"><em>In case of Sick Leave:</em></label>
                        <label><input type="checkbox" /> In Hospital (Specify illness) &nbsp;
                            <input type="text" class="form-control" value="" oninput="this.value = this.value.toUpperCase()">
                        </label>
                        <label><input type="checkbox" /> Out Patient (Specify illness) &nbsp;
                            <input type="text" class="form-control" value="" oninput="this.value = this.value.toUpperCase()">
                        </label>

                        <label class="muted" style="margin-top:2mm"><em>In case of Study Leave:</em></label>
                        <label><input type="checkbox" /> Completion of Master’s Degree</label>
                        <label><input type="checkbox" /> BAR/Board Examination Review</label>

                        <label class="muted" style="margin-top:2mm"><em>Other Purpose:</em></label>
                        <label><input type="checkbox" /> Monetization of Leave Credits</label>
                        <label><input type="checkbox" /> Terminal Leave</label>
                    </div>
                </td>
            </tr>

            <tr>
                <td>
                    <strong>6.C NUMBER OF WORKING DAYS APPLIED FOR:</strong>
                    <u>{{$Count . ' ' . '- DAY/S'}}</u>
                    <div class="row" style="margin-top:2mm">
                        <strong>INCLUSIVE DATES:</strong>
                        <div><u>{{$Leave->daterange }}</u></div>
                    </div>
                </td>
                <td>
                    <strong>6.D COMMUTATION</strong>
                    <div class="checks" style="margin-top:2mm">
                        <label><input type="checkbox" /> Not Requested</label>
                        <label><input type="checkbox" /> Requested</label>
                    </div>
                    <div class="right">
                        <img src="{{asset('images/dummySign.png')}}" style="width: 50px;" alt="">
                        <!-- <span class="line" style="min-width:60mm"></span><br /> -->
                        <div class="muted" style="margin-top:1mm"><b>{{$Employee->firstname . ' ' . $Employee->middlename . ' ' . $Employee->lastname }}</b><br />Signature of Applicant
                        </div>
                        <!-- <span class="muted">Signature of Applicant</span> -->
                    </div>
                </td>
            </tr>
        </table>

        <!-- 7. ACTION ON APPLICATION -->
        <table>
            <tr>
                <th colspan="2" class="center">7. DETAILS OF ACTION ON APPLICATION</th>
            </tr>
            <tr>
                <td style="width:50%">
                    <strong>7.A CERTIFICATION OF LEAVE CREDITS</strong>
                    <div class="row" style="margin-top:2mm">
                        <div class="cell w-30">As of <u>{{ now() }}</u></div>
                    </div>
                    <table class="credits" style="margin-top:3mm">
                        <tr>
                            <th></th>
                            <th class="muted" style="width: 100px;">Vacation Leave</th>
                            <th class="muted" style="width: 100px;">Sick Leave</th>
                        </tr>
                        <tr>
                            <td class="nobr muted">Total Earned</td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="nobr muted">Less this application</td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="nobr muted">Balance</td>
                            <td></td>
                            <td></td>
                        </tr>
                    </table>

                    <div class="center" style="margin-top:8mm">
                        <img src="{{asset('images/dummySign.png')}}" style="width: 50px;" alt="">
                        <!-- <div class="signline" style="width:70%"></div> -->
                        <div class="muted" style="margin-top:1mm"><b>{{$SetLeaveSignatory->employee1->firstname .' '. $SetLeaveSignatory->employee1->middlename .' '.
                        $SetLeaveSignatory->employee1->lastname}}</b><br />
                            {{$SetLeaveSignatory->employee1->position}}
                        </div>
                    </div>
                </td>

                <td style="width:50%;">
                    <div class="td-recommendation">
                        <div class="recommendation">
                            <strong>7.B RECOMMENDATION</strong>
                            <div class="checks" style="margin-top:2mm">
                                <label><input type="checkbox" {{ ($Leave->recommendation === 'for_approval' || !$Leave->recommendation) ? 'checked' : '' }} /> For Approval</label>
                                <label><input type="checkbox" {{ ($Leave->recommendation === 'for_disapproval') ? 'checked' : '' }} /> For disapproval due to</label>
                            </div>
                            <div style="height:17mm;border:1px solid #000;border-style:dashed;margin:2mm 0 6mm 0"></div>
                        </div>

                        <div class="center sig">
                            <img src="{{asset('images/dummySign.png')}}" style="width: 50px;" alt="Signature">
                            <!-- <div class="signline" style="width:70%"></div> -->
                            <div class="muted" style="margin-top:1mm"><b>{{$SetLeaveSignatory->employee2->firstname .' '. $SetLeaveSignatory->employee2->middlename .' '.
                            $SetLeaveSignatory->employee2->lastname}}</b><br />
                                {{$SetLeaveSignatory->employee2->position}}
                            </div>
                        </div>
                    </div>
                </td>
            </tr>

            <tr>
                <td>
                    <strong>7.C APPROVED FOR:</strong>
                    <div style="margin-top:3mm">
                        <div class="row">
                            <div>_____</div>
                            <div class="cell muted">day(s) with pay</div>
                        </div>
                        <div class="row">
                            <div>_____</div>
                            <div class="cell muted">day(s) without pay</div>
                        </div>
                        <div class="row">
                            <div>_____</div>
                            <div class="cell muted">others (specify)</div>
                        </div>
                    </div>
                </td>
                <td>
                    <strong>7.D DISAPPROVED DUE TO:</strong>
                    <div style="height:15mm;border:1px solid #000;border-style:dashed;margin:2mm 0 6mm 0"></div>
                </td>
            </tr>
            <tr style="border-top-style: none;">
                <td colspan="2">
                    <div class="center">
                        <img src="{{asset('images/dummySign.png')}}" style="width: 50px;" alt="">
                        <!-- <div class="signline" style="width:60%"></div> -->
                        <div class="muted"><b>{{$SetLeaveSignatory->employee3->firstname .' '. $SetLeaveSignatory->employee3->middlename .' '.
                        $SetLeaveSignatory->employee3->lastname}}</b><br />
                            {{$SetLeaveSignatory->employee3->position}}
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>
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
