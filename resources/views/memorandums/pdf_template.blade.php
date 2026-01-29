<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Memorandum - {{ $memorandum->memorandum_number }}</title>
    <style>
        @page {
            size: letter;

            margin: {
                    {
                    $layoutConfig['margin_top'] ?? 1
                }
            }

            in {
                    {
                    $layoutConfig['margin_right'] ?? 1
                }
            }

            in {
                    {
                    $layoutConfig['margin_bottom'] ?? 1
                }
            }

            in {
                    {
                    $layoutConfig['margin_left'] ?? 1
                }
            }

            in;
        }

        body {
            font-family: {
                    {
                    $layoutConfig['font_family'] ?? 'Times New Roman'
                }
            }

            ,
            serif;

            font-size: {
                    {
                    $layoutConfig['font_size'] ?? 12
                }
            }

            pt;

            line-height: {
                    {
                    $layoutConfig['line_height'] ?? 1.5
                }
            }

            ;
            color: #000;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header p {
            margin: 2px 0;
        }

        .date {
            text-align: right;
            margin-bottom: 15px;
        }

        .title {
            text-align: center;
            font-weight: bold;
            font-size: 14pt;
            margin: 15px 0;
        }

        .field {
            margin-bottom: 8px;
        }

        .field-label {
            font-weight: bold;
            display: inline-block;
            width: 90px;
        }

        .body-content {
            margin-top: 25px;
            margin-bottom: 25px;
        }

        .body-paragraph {
            text-align: justify;
            text-indent: 36pt;
            margin-bottom: 10pt;
        }

        .signature-section {
            margin-top: 40px;
            text-align: right;
        }

        .signature-space {
            height: 60px;
        }

        .signature-name {
            font-weight: bold;
        }

    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <p>{{ $template->header_line_1 }}</p>
        <p>{{ $template->header_line_2 }}</p>
        <p>{{ $template->header_line_3 }}</p>
        <p>{{ $template->header_line_4 }}</p>
    </div>

    <!-- Date -->
    <div class="date">
        {{ $memorandum->memorandum_date->format('F d, Y') }}
    </div>

    <!-- Title -->
    <div class="title">
        MEMORANDUM
    </div>

    <!-- Fields -->
    <div class="fields">
        @if(in_array($memorandum->recipient_type, ['FOR', 'BOTH']))
        <div class="field">
            <span class="field-label">FOR</span>
            <span>: {{ is_array($memorandum->recipient_for) ? implode(', ', $memorandum->recipient_for) : $memorandum->recipient_for }}</span>
        </div>
        @endif

        @if(in_array($memorandum->recipient_type, ['TO', 'BOTH']))
        <div class="field">
            <span class="field-label">TO</span>
            <span>: {{ is_array($memorandum->recipient_to) ? implode(', ', $memorandum->recipient_to) : $memorandum->recipient_to }}</span>
        </div>
        @endif

        <div class="field">
            <span class="field-label">FROM</span>
            <span>: {{ $memorandum->from_name ?? $memorandum->fromUser->name }}</span>
        </div>

        <div class="field">
            <span class="field-label">SUBJECT</span>
            <span>: {{ strtoupper($memorandum->subject) }}</span>
        </div>
    </div>

    <!-- Body -->
    <div class="body-content">
        @foreach($memorandum->getParagraphsArray() as $paragraph)
        <p class="body-paragraph">{{ trim($paragraph) }}</p>
        @endforeach
    </div>

    <!-- Signature -->
    <div class="signature-section">
        @if($memorandum->use_esignature && $memorandum->signature_path)
        <div>
            <img src="{{ public_path('storage/' . $memorandum->signature_path) }}" style="max-width: 150px; max-height: 60px;">
        </div>
        @else
        <div class="signature-space"></div>
        @endif

        <div class="signature-name">
            {{ strtoupper($memorandum->from_name ?? $memorandum->fromUser->name) }}
        </div>
        <div>
            {{ $memorandum->from_position ?? $memorandum->fromUser->position ?? '' }}
        </div>
    </div>
</body>
</html>
