<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Memorandum - {{ $memorandum->memorandum_number }}</title>
    <style>
        /* --- DOCUMENT SETUP --- */
        @page {
            size: A4;
            margin: 15mm 20mm 20mm 20mm;
        }

        body {
            font-family: 'Times New Roman', serif;
            font-size: 12pt;
            color: #000;
            margin: 0;
            padding: 0;
            background-color: white;
            position: relative;
            min-height: 297mm;
            /* A4 height */
        }

        /* --- PAGE CONTAINER --- */
        .page-container {
            width: 100%;
            min-height: 297mm;
            position: relative;
            padding-bottom: 50mm;
            /* Space for footer */
        }

        .content-wrapper {
            width: 100%;
        }

        /* --- HEADER --- */
        .header-section {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }

        .header-section td {
            vertical-align: middle;
            padding: 0;
        }

        .header-logo-left {
            width: 80px;
            text-align: left;
        }

        .logo {
            width: 80px;
            height: auto;
            margin-right: 10px;
            maring-left: -10px;
        }

        .header-text-center {
            text-align: center;
            margin-left: 10px;
        }

        .header-logo-right {
            width: 110px;
            text-align: right;
        }

        .logo2 {
            width: 110px;
            height: auto;
        }

        .header-separator {
            border-bottom: 3px solid #993366;
            margin: 5px -20mm 10px -20mm;
            width: calc(100% + 40mm);
        }

        .header-text {
            text-align: center;
            line-height: 1.1;
            white-space: nowrap;
        }

        .header-text p {
            margin: 0;
            padding: 0;
            white-space: nowrap;
        }

        .republic {
            font-weight: normal;
            font-size: 11pt;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .dept {
            font-weight: bold;
            font-size: 11pt;
            text-transform: uppercase;
            white-space: nowrap;
        }

        /* --- DATE AND TITLE INLINE --- */
        .date-memo-container {
            display: table;
            width: 100%;
            margin-top: 10px;
            margin-bottom: 15px;
        }

        .date-section {
            display: table-cell;
            text-align: right;
            vertical-align: top;
            width: 50%;
        }

        .date-text {
            font-weight: normal;
            padding-bottom: 2px;
        }

        .date-text.with-underline {
            border-bottom: 1px solid #000;
        }

        .memo-title {
            display: table-cell;
            font-weight: bold;
            font-size: 14pt;
            text-align: left;
            vertical-align: top;
            width: 50%;
            padding-left: 0.5in;
        }

        /* --- MEMO FIELDS TABLE --- */
        .memo-table {
            width: auto;
            border-collapse: collapse;
            margin-bottom: 20px;
            margin-left: 0.5in;
        }

        .memo-table td {
            vertical-align: top;
            padding-bottom: 12px;
            font-size: 12pt;
        }

        .label-col {
            width: 100px;
            font-weight: normal;
        }

        .colon-col {
            width: 30px;
            text-align: center;
            font-weight: normal;
        }

        .value-col {
            font-weight: normal;
            padding-left: 30px;
        }

        /* --- BODY --- */
        .body-content {
            text-align: justify;
            line-height: 1.4;
            margin-bottom: 15px;
        }

        .body-content p {
            text-indent: 0.5in;
            margin-bottom: 15px;
            margin-top: 0;
        }

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
            position: relative;
            z-index: 1;
        }

        .signatory-name {
            font-weight: bold;
            position: relative;
            z-index: 2;
            padding-top: 5px;
        }

        /* --- FOOTER (FIXED AT BOTTOM) --- */
        .footer-wrapper {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            width: 100%;
            padding: 10px 0;
            margin: 0;
        }

        .footer-memo-number {
            font-size: 9pt;
            font-style: italic;
            color: #555;
            margin-bottom: 5px;
            text-align: center;
        }

        .footer-address {
            width: 100%;
            border-collapse: collapse;
            color: #555;
            line-height: 1.2;
        }

        .footer-address td {
            vertical-align: middle;
            padding: 5px 0;
        }

        .footer-left {
            width: 60px;
            text-align: left;
        }

        .footer-center {
            text-align: center;
            font-size: 8pt;
            padding: 0 10px;
        }

        .footer-right {
            width: 60px;
            text-align: right;
        }

        .footer-img {
            height: 40px;
            width: auto;
        }

    </style>
</head>
<body>
    <div class="page-container">
        <div class="content-wrapper">
            <table class="header-section">
                <tr>
                    <td class="header-logo-left">
                        <img src="{{ public_path('images/logo.png') }}" alt="DENR Logo" class="logo">
                    </td>
                    <td class="header-text-center">
                        <div class="header-text">
                            <p class="dept">DEPARTMENT OF ENVIRONMENT AND NATURAL RESOURCES</p>
                            <p class="republic">KAGAWARAN NG KAPALIGIRAN AT LIKAS NA YAMAN</p>
                            <p class="republic">MIMAROPA REGION</p>
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
                        </div>
                    </td>
                    <td class="header-logo-right">
                        <img src="{{ public_path('images/bagongPilipinasLogo.png') }}" alt="Bagong Pilipinas" class="logo2">
                    </td>
                </tr>
            </table>

            <div class="header-separator"></div>

            <div class="date-memo-container">
                <div class="memo-title">MEMORANDUM</div>
                <div class="date-section">
                    <span class="date-text {{ !$memorandum->memorandum_date ? 'with-underline' : '' }}">
                        @if($memorandum->memorandum_date)
                        {{ $memorandum->memorandum_date->format('F d, Y') }}
                        @else
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        @endif
                    </span>
                </div>
            </div>

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
                    <td class="label-col">THRU</td>
                    <td class="colon-col">:</td>
                    <td class="value-col">{{ $memorandum->through }}</td>
                </tr>
                @endif
                @if($memorandum->attn)
                <tr>
                    <td class="label-col">ATT'N</td>
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

            <div class="body-content">
                @foreach($memorandum->getParagraphsArray() as $paragraph)
                <p>{!! nl2br(e(trim($paragraph))) !!}</p>
                @endforeach
            </div>

            <div class="signature-block">
                @php
                $signaturePath = $memorandum->signature_path;
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
                <div style="font-weight: normal; font-size: 11pt; margin-top: 2px;">
                    {{ $memorandum->from_position ?? ($memorandum->creator->Employee->position ?? '') }}
                </div>
                @endif
            </div>

            <div style="clear: both;"></div>
        </div>

        <div class="footer-wrapper">
            @if($memorandum->transaction_number)
            <div class="footer-memo-number">
                Tracking Number: {{ $memorandum->transaction_number }} | {{ $memorandum->memorandum_number }}
            </div>
            @else
            <div class="footer-memo-number">
                {{ $memorandum->memorandum_number }}
            </div>
            @endif
            <table class="footer-address">
                <tr>
                    <td class="footer-left">
                        <img src="{{ public_path('images/ISO.jpg') }}" class="footer-img" alt="Footer Left">
                    </td>
                    <td class="footer-center">
                        Barangay Payompon, Mamburao, Occidental Mindoro<br>
                        penroocc.mindoro@denr.gov.ph<br>
                        Landline No. (043) 458-11-03
                    </td>
                    <td class="footer-right">
                        <img src="{{ public_path('images/SOCOTEC.jpg') }}" class="footer-img" alt="Footer Right">
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
