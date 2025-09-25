<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Information System') }}</title>

    {{--
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback"> --}}
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">

    <style>
        @page {
            size: A4;
            margin: 18mm;
        }

        html,
        body {
            height: 100%;
        }

        body {
            margin: 0;
            font-family: "Times New Roman", serif;
            line-height: 1.35;
            color: #000;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .doc {
            max-width: 210mm;
            margin: 0 auto;
            padding: 0;
        }

        /* Header */
        .header {
            display: grid;
            grid-template-columns: 90px 1fr 90px;
            align-items: center;
            border-bottom: 2px solid #000;
            padding-bottom: 8px;
        }

        .header img {
        display: block;
        margin: auto;
        }

        .header .logoLeft{
            max-width: 85px;
            max-height: 85px;
        }

        .header .logoRight{
            max-width: 110px;
        }

        .hdr-text {
            text-align: center;
        }

        .hdr-text p {
            margin: 0px;
            line-height: 1;
            font-size: 18px;
        }

        .hdr-text .office {
            font-weight: 600;
        }

        .hdr-text h3,
        .hdr-text h2 {
            margin: 0;
            line-height: 1.2;
            font-weight: 100;
            font-size: 18px;
        }


        .hdr-text h2 {
            margin-top: 6px;
            font-weight: 600
        }

        .travel-no {
            text-align: right;
            font-size: 14px;
            margin-top: 6px;
        }

        /* Info grid */
        .section {
            padding: 12px 0;
        }

        .section .title{
            text-align: center
        }

        .grid {
            display: grid;
            grid-template-columns: 26% 34% 18% 22%;
            column-gap: 10px;
            row-gap: 10px;
        }

        .label {
            font-weight: bold;
            font-size: 18px
        }

        .field {
            border-bottom: 1px solid #000;
            min-height: 22px;
            padding: 2px 4px;
            text-align: left;
            align-self: anchor-center
        }

        .field-block {
            border: 1px solid #000;
            padding: 8px;
            min-height: 48px;
        }

        .mono {
            font-family: "Courier New", monospace;
        }

        /* Certification & Authorization */
        .title {
            font-weight: bold;
            text-transform: uppercase;
            margin: 8px 0 4px;
        }

        .justify {
            text-align: justify;
        }

        /* Signatures */
        .sign-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            column-gap: 18px;
            margin-top: 24px;
        }

        .sign-box {
            text-align: center;
            padding-top: 36px;
            margin-left: 110px
        }

        .line {
            border-bottom: 1.4px solid #000;
            margin: 0 auto 4px;
            width: 90%;
        }

        .sign-name {
            font-weight: bold;
        }

        .sign-role {
            font-size: 14px;
        }

        /* signature */
        .sig-block{
        display:flex;
        align-items:center;
        justify-content: center
        gap:2px
        }
        .sig-img{
        max-height:60px
        }
        .sig-meta{
        font-size:12px;
        line-height:1.15;
        color:#333;
        text-align: left;
        }
        @media print {
        .sig-meta{color:#000}
        }


        /* Footer */
        .footer {
            margin-top: 22px;
            font-size: 13px;
            text-align: center;
        }

        @media screen {
            .doc {
                padding: 18mm;
                border: 1px solid #ddd;
                background: #fff;
            }

            body {
                background: #f4f4f4;
            }
        }

    </style>


</head>

<body>
    <div class="doc">

        <!-- Header -->
        <div class="header">
            <!-- DENR Logo -->
            <img class="logoLeft" src="{{ asset('images/logo.png') }}" alt="DENR Logo">


            <div class="hdr-text">
                <p>Republic of the Philippines</p>
                <p>Department of Environment and Natural Resources</p>
                <h3>MIMAROPA Region</h3>
                @if(!empty($officeHeader))
                <h3 class="office">{{ $officeHeader }}</h3>
                @endif
                <h3>Mamburao, Occidental Mindoro</h3>
                <h2>TRAVEL ORDER</h2>
            </div>

            <!-- Bagong Pilipinas Logo -->
            <img src="{{ asset('images/bagongPilipinasLogo.png') }}" class="logoRight" alt="Bagong Pilipinas Logo">
        </div>

        <div class="travel-no"><span class="label">No.:</span> <span class="mono">{{ $TravelOrdernumber->travelorderid }}</span></div>

        <!-- Info grid -->
        <div class="section grid">
            <div class="label">Name:</div>
            <div class="field">{{$Employee->firstname .' '. $Employee->middlename .' '. $Employee->lastname }}</div>
            <div class="label">Date:</div>
            <div class="field">{{ $docDate }}</div>

            <div class="label">Position:</div>
            <div class="field">{{$Employee->position }}</div>
            <div class="label">Salary (PhP):</div>
            <div class="field">&nbsp;</div>

            <div class="label">Div/Sec/Unit:</div>
            <div class="field">{{$Employee->unit->unit}}</div>
            <div class="label">Official Station:</div>
            <div class="field">{{$Employee->office->office}}</div>

            <div class="label">Departure Date:</div>
            <div class="field">{{$date1}}</div>
            <div class="label">Arrival:</div>
            <div class="field">{{$date2}}</div>

            <div class="label">Destination:</div>
            <div class="field" style="grid-column: span 3;">
                {{$TravelOrder->destinationoffice}}
            </div>

            <div class="label">Purpose of Travel:</div>
            <div class="field" style="grid-column: span 3;">
                <!-- To conduct preparatory activities for the upcoming “Support for the Organizing and Strengthening of the
                Provincial CBFM-People’s Organizations (CBFM-PO) Federation in the Province of Occidental Mindoro and
                the documentation of success story of Palbong CBFM.” -->
                {{$TravelOrder->purpose}}
            </div>

            <div class="label">Per Diems/Expenses allowed:</div>
            <div class="field">{{$TravelOrder->perdime}}</div>
            <div></div>
            <div></div>
            <div class="label">Appropriations to which travel should be charged:</div>
            <div class="field" style="grid-column: span 1;">{{$TravelOrder->appropriation }}</div>
            <div></div>
            <div></div>

            <div class="label" style="grid-column: span 1;">Remarks or Special Instructions:</div>
            <div class="field" style="grid-column: span 2;">{{$TravelOrder->remarks }}</div>
        </div>

        <!-- Certification -->
        <div class="section">
            <div class="title">Certification</div>
            <div class="justify">
                <i>
                This is to certify that the travel is necessary and is connected with the functions of the
                official/employee of the Div/Sec/Unit.
                </i>
            </div>

            @php
            use Illuminate\Support\Facades\Storage;
            $tz = config('app.timezone', 'Asia/Manila');

            $makeSigUrl = function ($emp) {
            if (!$emp || empty($emp->signature_path)) return null;
            $p = ltrim(str_replace('\\','/',$emp->signature_path), '/');
            if (Storage::disk('public')->exists($p)) return Storage::url($p);
            if (file_exists(public_path($p))) return asset($p);
            return null;
            };

            $sigUrl1 = $makeSigUrl($approver1Emp);
            $sigUrl2 = $makeSigUrl($approver2Emp);

            $ap1 = $TravelOrder->approve1_at ? \Carbon\Carbon::parse($TravelOrder->approve1_at)->timezone($tz) : null;
            $ap2 = $TravelOrder->approve2_at ? \Carbon\Carbon::parse($TravelOrder->approve2_at)->timezone($tz) : null;

            // gawing “+08'00'” ang offset para kahawig ng sample (-04'00')
            $off1 = $ap1 ? str_replace(':', "'", $ap1->format('P')) : null;
            $off2 = $ap2 ? str_replace(':', "'", $ap2->format('P')) : null;
            @endphp



            <div class="sign-row">
                <div class="sign-box">
                    <div class="sig-block">
                        <img src="{{ $sigUrl1 ?? asset('images/dummySign.png') }}" class="sig-img" draggable="false" oncontextmenu="return false;" onmousedown="return false;" onselectstart="return false;">
                        <div class="sig-meta">
                            <div><strong>Digitally signed</strong> by</div>
                            <div>{{ $approver1Name }}</div>
                            @if($ap1)
                            <div>Date: {{ $ap1->format('Y.m.d') }}</div>
                            <div>{{ $ap1->format('H:i:s') }} {{ $off1 }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="sign-name"><u>{{ $approver1Name }}</u></div>
                    <div class="sign-role">{{ $approver1Pos }}</div>
                </div>
                <div class="sign-box">
                    <div class="sig-block">
                        <img src="{{ $sigUrl2 ?? asset('images/dummySign.png') }}" class="sig-img" draggable="false" oncontextmenu="return false;" onmousedown="return false;" onselectstart="return false;">
                        <div class="sig-meta">
                            <div><strong>Digitally signed</strong> by</div>
                            <div>{{ $approver2Name }}</div>
                            @if($ap2)
                            <div>Date: {{ $ap2->format('Y.m.d') }}</div>
                            <div>{{ $ap2->format('H:i:s') }} {{ $off2 }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="sign-name"><u>{{ $approver2Name }}</u></div>
                    <div class="sign-role">{{ $approver2Pos }}</div>
                </div>
            </div>
        </div>

        <!-- Authorization -->
        <div class="section">
            <div class="title">Authorization</div>
            <div class="justify">
                I hereby authorize the Accountant to deduct the corresponding amount of the unliquidated
                cash advance from my succeeding salary for my failure to liquidate this travel within the
                prescribed thirty-day period upon return to my permanent official station pursuant to item
                5.1.3 Circular 97-002 dated February 10, 1997 and sec. 16 EO No. 248 dated May 29, 1995.
            </div>

            <div class="sign-row" style="grid-template-columns: 1fr;">
                <div class="sign-box" style="max-width: 320px; margin: 0 auto;">
                    <div class="sign-name"><u>{{$Employee->firstname .' '. $Employee->middlename .' '.
                    $Employee->lastname }}</u></div>

                    <div class="sign-role">Official/Employee</div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
             So. Pag-asa, Brgy. Payompon, Mamburao, Occidental Mindoro {{--· Email: tsdocomin04@gmail.com --}}

        </div>

    </div>


    <!-- Page specific script -->
    <script>
        // window.addEventListener("load", window.print());

        // (function () {
        // function openPrint() { try { window.print(); } catch (e) {} }
        // function goBack() { history.back(); }


        // if ('onafterprint' in window) {
        // window.addEventListener('afterprint', goBack);
        // } else {

        // const mql = window.matchMedia('print');
        // mql.addListener(function (mq) {
        // if (!mq.matches) goBack();
        // });
        // }

        // window.addEventListener('load', openPrint);
        // })();

        (function() {
            const params = new URLSearchParams(location.search);
            const embedded = params.has('embed');

            // auto-open print dialog
            window.addEventListener('load', () => window.print());

            // kung HINDI embedded (i.e., direct visit), bumalik sa list pagkatapos
            if (!embedded) {
                window.addEventListener('afterprint', () => history.back());
            }
        })();

    </script>
</body>

</html>
