<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Memorandum PDF Preview - {{ $memorandum->memorandum_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .header {
            background-color: #2c3e50;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .header h1 {
            font-size: 18px;
            font-weight: 600;
        }

        .header-actions {
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-print {
            background-color: #f39c12;
            color: white;
        }

        .btn-print:hover {
            background-color: #e67e22;
        }

        .btn-download {
            background-color: #27ae60;
            color: white;
        }

        .btn-download:hover {
            background-color: #229954;
        }

        .btn-close {
            background-color: #95a5a6;
            color: white;
        }

        .btn-close:hover {
            background-color: #7f8c8d;
        }

        .pdf-container {
            flex: 1;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }

        .pdf-frame {
            width: 100%;
            height: 100%;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        @media print {
            .header {
                display: none;
            }

            .pdf-container {
                padding: 0;
            }

            .pdf-frame {
                border: none;
                box-shadow: none;
            }
        }

        .loading {
            text-align: center;
            padding: 50px;
            font-size: 18px;
            color: #7f8c8d;
        }

        .icon {
            font-size: 18px;
        }

    </style>
</head>
<body>
    <div class="header">
        <h1>📄 Memorandum PDF Preview - {{ $memorandum->memorandum_number }}</h1>
        <div class="header-actions">
            <button onclick="printPDF()" class="btn btn-print">
                <span class="icon">🖨️</span> PRINT
            </button>
            <a href="{{ route('memorandums.export-pdf', $memorandum->id) }}?download=1" class="btn btn-download" download>
                <span class="icon">⬇️</span> DOWNLOAD
            </a>
            <button onclick="window.close()" class="btn btn-close">
                <span class="icon">✖️</span> CLOSE
            </button>
        </div>
    </div>

    <div class="pdf-container">
        <iframe id="pdfFrame" src="{{ route('memorandums.export-pdf', $memorandum->id) }}" class="pdf-frame" title="PDF Preview"></iframe>
    </div>

    <script>
        function printPDF() {
            // Try to print the iframe content
            const iframe = document.getElementById('pdfFrame');

            try {
                // Method 1: Print iframe content directly
                iframe.contentWindow.focus();
                iframe.contentWindow.print();
            } catch (e) {
                // Method 2: If iframe print fails, print the whole page
                window.print();
            }
        }

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl+P or Cmd+P for print
            if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                e.preventDefault();
                printPDF();
            }
            // Escape to close
            if (e.key === 'Escape') {
                window.close();
            }
        });

        // Handle iframe load
        document.getElementById('pdfFrame').addEventListener('load', function() {
            console.log('PDF loaded successfully');
        });

        // Show loading indicator if needed
        window.addEventListener('load', function() {
            console.log('Page loaded');
        });

    </script>
</body>
</html>
