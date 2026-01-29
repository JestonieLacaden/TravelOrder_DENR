<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Memorandum - {{ $memorandum->memorandum_number }}</title>
    <style>
        /* --- DOCUMENT SETUP --- */
        @page {
            size: 8.5in 11in;
            margin: 0.5in 0.5in 0.5in 0.5in;
        }

        body {
            font-family: 'Times New Roman', serif;
            font-size: 12pt;
            color: #000;
            margin: 0;
            padding: 0;
        }

        /* --- HEADER TABLE --- */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }

        .header-table td {
            vertical-align: middle;
            padding: 0;
        }

        .header-logo-left {
            width: 90px;
            text-align: left;
        }

        .header-logo-left img {
            width: 90px;
            height: auto;
        }

        .header-text-center {
            text-align: center;
            line-height: 1.1;
            white-space: nowrap;
        }

        .header-text-center p {
            margin: 0;
            padding: 0;
        }

        .header-logo-right {
            width: 120px;
            text-align: right;
        }

        .header-logo-right img {
            width: 120px;
            height: auto;
        }

        .republic {
            font-weight: normal;
        }

        .dept {
            font-weight: bold;
        }

        /* --- HEADER SEPARATOR --- */
        .header-separator {
            border-bottom: 3px solid #993366;
            margin-top: 30px;
            margin-bottom: 20px;
            margin-left: -0.8in;
            margin-right: -0.8in;
        }

        /* --- DATE TABLE (RIGHT ALIGNED) --- */
        .date-wrapper {
            width: 100%;
            margin-top: 15px;
            margin-bottom: 25px;
        }

        .date-table {
            width: 280px;
            float: right;
            border-collapse: collapse;
        }

        .date-table td {
            text-align: center;
            padding: 0;
        }

        .date-text {
            font-weight: normal;
            padding-bottom: 2px;
        }

        .date-text.with-underline {
            border-bottom: 1px solid #000;
        }

        /* --- TITLE --- */
        .memo-title {
            font-weight: bold;
            font-size: 14pt;
            margin-bottom: 25px;
            margin-left: 0.5in;
            text-align: left;
            clear: both;
        }

        /* --- MEMO FIELDS TABLE --- */
        .memo-table {
            width: auto;
            border-collapse: collapse;
            margin-bottom: 25px;
            margin-left: 0.5in;
        }

        .memo-table td {
            vertical-align: top;
            padding-bottom: 12px;
            font-size: 12pt;
            font-weight: normal;
        }

        .label-col {
            width: 100px;
        }

        .colon-col {
            width: 30px;
            text-align: center;
        }

        .value-col {
            padding-left: 30px;
        }

        /* --- BODY --- */
        .body-content {
            text-align: justify;
            line-height: 1.5;
            margin-bottom: 30px;
        }

        .body-content p {
            text-indent: 0.5in;
            margin-bottom: 15px;
            margin-top: 0;
        }

        /* --- SIGNATURE BLOCK (FLOATED RIGHT) --- */
        .signature-block {
            float: right;
            text-align: center;
            width: 280px;
            margin-top: 20px;
        }

        .signature-img {
            display: block;
            margin: 0 auto -25px auto;
            height: 70px;
            width: auto;
        }

        .signatory-name {
            font-weight: bold;
            padding-top: 5px;
        }

        .signatory-position {
            font-weight: normal;
            font-size: 11pt;
            margin-top: 2px;
        }

        /* --- FOOTER --- */
        .footer-wrapper {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            padding-left: 0.8in;
            padding-right: 0.8in;
            padding-bottom: 10px;
        }

        .footer-memo-number {
            font-size: 10pt;
            font-style: italic;
            color: #555;
            margin-bottom: 5px;
        }

        .footer-table {
            width: 100%;
            border-collapse: collapse;
            padding-top: 10px;
        }

        .footer-table td {
            vertical-align: middle;
            color: #555;
            font-size: 10pt;
            line-height: 1.2;
            padding: 0;
        }

        .footer-left {
            width: 15%;
            text-align: left;
        }

        .footer-left img {
            height: 50px;
            width: auto;
        }

        .footer-center {
            width: 70%;
            text-align: center;
            white-space: nowrap;
        }

        .footer-right {
            width: 15%;
            text-align: right;
        }

        .footer-right img {
            height: 50px;
            width: auto;
        }

        /* --- CONTENT PADDING FOR FOOTER --- */
        .content-wrapper {
            padding-bottom: 100px;
        }

        /* Clear floats */
        .clearfix {
            clear: both;
        }

    </style>
</head>
<body>

    <div class="content-wrapper">

        <!-- HEADER TABLE -->
        <table class="header-table">
            <tr>
                <td class="header-logo-left">
                    <img src="{{ public_path('images/DENRLOGOSeal.svg') }}" alt="DENR Logo">
                </td>
                <td class="header-text-center">
                    <p class="dept">DEPARTMENT OF ENVIRONMENT AND NATURAL RESOURCES</p>
                    <p class="republic">KAGAWARAN NG KAPALIGIRAN AT LIKAS NA YAMAN</p>
                    <p class="dept">MIMAROPA Region</p>
                    <p class="dept">
                        @php
                        $officeName = 'PROVINCIAL ENVIRONMENT AND NATURAL RESOURCES OFFICE';
                        if ($memorandum->creator && $memorandum->creator->Employee && $memorandum->creator->Employee->office) {
                        $officeNameFromDB = strtoupper(trim($memorandum->creator->Employee->office->name ?? ''));
                        if (str_contains($officeNameFromDB, 'CENRO')) {
                        $officeName = 'COMMUNITY ENVIRONMENT AND NATURAL RESOURCES OFFICE';
                        }
                        }
                        @endphp
                        {{ $officeName }}
                    </p>
                </td>
                <td class="header-logo-right">
                    <img src="{{ public_path('images/bagongPilipinasLogo.png') }}" alt="Bagong Pilipinas">
                </td>
            </tr>
        </table>

        <!-- HEADER SEPARATOR LINE -->
        <div class="header-separator"></div>

        <!-- DATE TABLE (RIGHT ALIGNED) -->
        <div class="date-wrapper">
            <table class="date-table">
                <tr>
                    <td>
                        <span class="date-text {{ !$memorandum->memorandum_date ? 'with-underline' : '' }}">
                            @if($memorandum->memorandum_date)
                            {{ $memorandum->memorandum_date->format('F d, Y') }}
                            @else
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            @endif
                        </span>
                    </td>
                </tr>
            </table>
        </div>

        <!-- MEMORANDUM TITLE -->
        <div class="memo-title">MEMORANDUM</div>

        <!-- MEMO FIELDS TABLE -->
        <table class="memo-table">
            @if(in_array($memorandum->recipient_type, ['TO', 'BOTH']))
            <tr>
                <td class="label-col">TO</td>
                <td class="colon-col">:</td>
                <td class="value-col">{!! nl2br(e($memorandum->recipient_to)) !!}</td>
            </tr>
            @endif

            @if(in_array($memorandum->recipient_type, ['FOR', 'BOTH']))
            <tr>
                <td class="label-col">FOR</td>
                <td class="colon-col">:</td>
                <td class="value-col">{!! nl2br(e($memorandum->recipient_for)) !!}</td>
            </tr>
            @endif

            @if($memorandum->through)
            <tr>
                <td class="label-col">THROUGH</td>
                <td class="colon-col">:</td>
                <td class="value-col">{{ $memorandum->through }}</td>
            </tr>
            @endif

            @if($memorandum->attn)
            <tr>
                <td class="label-col">ATTN</td>
                <td class="colon-col">:</td>
                <td class="value-col">{{ $memorandum->attn }}</td>
            </tr>
            @endif

            <tr>
                <td class="label-col">FROM</td>
                <td class="colon-col">:</td>
                <td class="value-col">{!! nl2br(e($memorandum->from_text ?? '')) !!}</td>
            </tr>

            <tr>
                <td class="label-col">SUBJECT</td>
                <td class="colon-col">:</td>
                <td class="value-col">{{ $memorandum->subject }}</td>
            </tr>
        </table>

        <!-- BODY CONTENT -->
        <div class="body-content">
            @foreach($memorandum->getParagraphsArray() as $paragraph)
            <p>{!! nl2br(e(trim($paragraph))) !!}</p>
            @endforeach
        </div>

        <!-- SIGNATURE BLOCK -->
        <div class="signature-block">
            @php
            $signaturePath = $memorandum->signature_override_path ?? $memorandum->signature_path;
            if ($memorandum->use_esignature && !$signaturePath && $memorandum->creator && $memorandum->creator->Employee) {
            $signaturePath = $memorandum->creator->Employee->signature_path;
            }
            @endphp

            @if($memorandum->use_esignature && $signaturePath)
            <img src="{{ public_path('storage/' . $signaturePath) }}" class="signature-img" alt="Signature">
            @else
            <div style="height: 60px;"></div>
            @endif

            <div class="signatory-name">
                @php
                $creatorName = $memorandum->from_name;
                if (!$creatorName && $memorandum->creator && $memorandum->creator->Employee) {
                $creatorName = \App\Helpers\NameFormatter::formatWithMiddleInitial($memorandum->creator->Employee->fullname);
                } elseif (!$creatorName && $memorandum->creator) {
                $creatorName = strtoupper($memorandum->creator->username);
                }
                @endphp
                {{ strtoupper($creatorName) }}
            </div>

            @if($memorandum->show_position ?? true)
            <div class="signatory-position">
                {{ $memorandum->from_position ?? ($memorandum->creator->Employee->position ?? '') }}
            </div>
            @endif
        </div>

        <div class="clearfix"></div>

    </div>

    <!-- FOOTER (FIXED POSITION) -->
    <div class="footer-wrapper">
        <div class="footer-memo-number">
            @if($memorandum->transaction_number)
            <div style="font-size: 10pt; font-style: italic; margin-bottom: 2px;">
                Transaction Number: {{ $memorandum->transaction_number }}
            </div>
            @endif
            {{ $memorandum->memorandum_number }}
        </div>

        <table class="footer-table">
            <tr>
                <td class="footer-left">
                    <img src="{{ public_path('images/ISO.jpg') }}" alt="ISO">
                </td>
                <td class="footer-center">
                    Barangay Payompon, Mamburao, Occidental Mindoro<br>
                    penroocc.mindoro@denr.gov.ph<br>
                    Landline No. (043) 458-11-03
                </td>
                <td class="footer-right">
                    <img src="{{ public_path('images/SOCOTEC.jpg') }}" alt="SOCOTEC">
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
