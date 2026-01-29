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
            margin: 0.5in 0.8in 0.5in 0.8in;
        }

        body {
            font-family: 'Times New Roman', serif;
            font-size: 12pt;
            color: #000;
            margin: 0;
            padding: 20px;
            background-color: #f0f0f0;
        }

        /* --- PAGE CONTAINER --- */
        .page-container {
            width: 8.5in;
            min-height: 11in;
            margin: 0 auto 20px auto;
            background: white;
            padding: 0.5in 0.8in 0.8in 0.8in;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            box-sizing: border-box;
            position: relative;
        }

        .content-wrapper {
            min-height: calc(11in - 1.3in - 1.2in);
            padding-bottom: 120px;
        }

        /* --- HEADER --- */
        .header-section {
            width: 95%;
            max-width: 650px;
            border-collapse: collapse;
            margin: 0 auto;
            display: table;
        }

        .header-section td {
            vertical-align: middle;
            padding: 0;
        }

        .header-logo-left {
            width: 100px;
            text-align: left;
        }

        .logo {
            width: 90px;
            height: auto;
            display: block;
            margin: 0;
            margin-right: 10px;
            maring-left: -10px;
        }

        .header-text-center {
            text-align: center;
            width: auto;
            padding: 0 10px;
        }

        .header-logo-right {
            width: 120px;
            text-align: center;
        }

        .logo2 {
            width: 110px;
            height: auto;
            display: block;
            margin: 0 auto;
        }

        .header-separator {
            border-bottom: 3px solid #993366;
            margin-top: 5px;
            margin-bottom: 15px;
            margin-left: -0.8in;
            margin-right: -0.8in;
            width: calc(100% + 1.6in);
        }

        .header-text {
            text-align: center;
            line-height: 1.1;
            white-space: nowrap;
            max-width: 100%;
        }

        .header-text p {
            margin: 0;
        }

        .republic {
            font-weight: normal;
            text-transform: uppercase;
        }

        .dept {
            font-weight: bold;
            text-transform: uppercase;

        }

        /* --- DATE (FIXED ALIGNMENT) --- */
        .date-section {
            display: flex;
            justify-content: flex-end;
            /* Align box to the right */
            margin-top: 15px;
            margin-bottom: 25px;
        }

        /* Invisible box na kasing laki ng Signature Block */
        .date-container {
            width: 280px;
            /* Match signature-block width */
            text-align: center;
            /* Gitna ang text sa loob ng box na ito */
        }

        /* Ang mismong text at linya */
        .date-text {
            display: inline-block;
            /* Sumusunod sa haba ng text */
            font-weight: normal;
            padding-bottom: 2px;
            /* Tinanggal ang fixed width para di humaba ang line */
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
            text-align: justify;
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

        .signature-block {
            float: right;
            text-align: center;
            width: 280px;
            /* Fixed Width: Dapat match sa date-container */
            margin-top: 20px;
            margin-bottom: 50px;
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

        /* --- FOOTER (ABSOLUTE POSITIONING AT BOTTOM) --- */
        .footer-wrapper {
            position: absolute;
            bottom: 0.5in;
            left: 0.8in;
            right: 0.8in;
            width: calc(100% - 1.6in);
        }

        .footer-memo-number {
            font-size: 10pt;
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
            padding-top: 10px;
        }

        .footer-address td {
            vertical-align: middle;
            padding: 0;
        }

        .footer-left {
            width: 15%;
            text-align: left;
        }

        .footer-center {
            text-align: center;
            width: 70%;
            font-size: 10pt;
        }

        .footer-right {
            width: 15%;
            text-align: right;
        }

        .footer-img {
            height: 50px;
            width: auto;
        }

        .print-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            z-index: 1000;
            text-decoration: none;
        }

        .print-btn:hover {
            background: #0056b3;
        }

        .export-pdf-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            z-index: 1000;
            background: #dc3545;
            text-decoration: none;
        }

        .export-pdf-btn:hover {
            background: #c82333;
            color: white;
            text-decoration: none;
        }

        .screen-only {
            display: block;
        }

        /* --- PRINT SETTINGS --- */
        @media print {
            body {
                background: white;
                padding: 0;
            }

            .page-container {
                margin: 0;
                box-shadow: none;
                page-break-after: always;
            }

            .screen-only,
            .print-btn,
            .export-pdf-btn {
                display: none !important;
            }

            .footer-wrapper {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                padding: 0 0.8in;
            }
        }

        .headerDivCenter {
            display: flex;
            justify-content: center;
            align-items: center;
        }

    </style>
</head>
<body>

    <div class="screen-only">
        <button onclick="exportPdfBlob()" class="export-pdf-btn">📄 Export to PDF</button>
    </div>

    <div class="page-container">
        <div class="content-wrapper">

            <script>
                function exportPdfBlob() {
                    fetch('{{ route('memorandums.export-pdf', $memorandum->id) }}').then(response => response.json()).then(data => {
                            const binaryString = atob(data.pdf);
                            const bytes = new Uint8Array(binaryString.length);
                            for (let i = 0; i < binaryString.length; i++) {
                                bytes[i] = binaryString.charCodeAt(i);
                            }
                            const blob = new Blob([bytes], {
                                type: 'application/pdf'
                            });
                            const url = window.URL.createObjectURL(blob);
                            window.open(url, '_blank');
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Failed to generate PDF');
                        });
                }

            </script>

            <!-- FIRST PAGE HEADER (shown only on page 1) -->
            <div class="headerDivCenter">
                <table class="header-section">
                    <tr>
                        <td class="header-logo-left">
                            <img src="{{ asset('images/DENRLOGOSeal.svg') }}" alt="DENR Logo" class="logo">
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
                            <img src="{{ asset('images/bagongPilipinasLogo.png') }}" alt="Bagong Pilipinas" class="logo2">
                        </td>
                    </tr>
                </table>
            </div>
            <div class="header-separator"></div>

            <div class="date-section">
                <div class="date-container">
                    <span class="date-text {{ !$memorandum->memorandum_date ? 'with-underline' : '' }}">
                        @if($memorandum->memorandum_date)
                        {{ $memorandum->memorandum_date->format('F d, Y') }}
                        @else
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        @endif
                    </span>
                </div>
            </div>

            <div class="memo-title">MEMORANDUM</div>

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
                <img src="{{ asset('storage/' . $signaturePath) }}" class="signature-img" alt="Signature">
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
            <div class="footer-memo-number">
                @if($memorandum->transaction_number)
                Tracking Number: {{ $memorandum->transaction_number }} | {{ $memorandum->memorandum_number }}
                @else
                {{ $memorandum->memorandum_number }}
                @endif
            </div>
            <table class="footer-address">
                <tr>
                    <td class="footer-left">
                        <img src="{{ asset('images/ISO.jpg') }}" class="footer-img" alt="Footer Left">
                    </td>
                    <td class="footer-center">
                        Barangay Payompon, Mamburao, Occidental Mindoro<br>
                        penroocc.mindoro@denr.gov.ph<br>
                        Landline No. (043) 458-11-03
                    </td>
                    <td class="footer-right">
                        <img src="{{ asset('images/SOCOTEC.jpg') }}" class="footer-img" alt="Footer Right">
                    </td>
                </tr>
            </table>
        </div>
    </div>

</body>
</html>
