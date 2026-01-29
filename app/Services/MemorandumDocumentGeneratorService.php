<?php

namespace App\Services;

use App\Models\Memorandum;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class MemorandumDocumentGeneratorService
{
    protected $memorandum;
    protected $template;
    protected $layoutConfig;

    public function __construct(Memorandum $memorandum)
    {
        $this->memorandum = $memorandum;
        $this->template = $memorandum->template;
        $this->layoutConfig = $this->template->layout_config;
    }

    /**
     * Generate DOCX file
     */
    public function generateDOCX()
    {
        $phpWord = new PhpWord();

        // Set default font
        $phpWord->setDefaultFontName($this->layoutConfig['font_family'] ?? 'Times New Roman');
        $phpWord->setDefaultFontSize($this->layoutConfig['font_size'] ?? 12);

        // Create section with margins
        $section = $phpWord->addSection([
            'marginLeft' => $this->layoutConfig['margin_left'] ?? 1440, // 1 inch = 1440 twips
            'marginRight' => $this->layoutConfig['margin_right'] ?? 1440,
            'marginTop' => $this->layoutConfig['margin_top'] ?? 1440,
            'marginBottom' => $this->layoutConfig['margin_bottom'] ?? 1440,
        ]);

        // Add Header Lines (Centered)
        $this->addHeaderLines($section);

        // Add Date (Right-aligned)
        $this->addDate($section);

        // Add MEMORANDUM title (Centered, Bold)
        $this->addMemorandumTitle($section);

        // Add Recipients and Subject
        $this->addRecipientSection($section);

        // Add Body Paragraphs
        $this->addBodyParagraphs($section);

        // Add Signature Section
        $this->addSignatureSection($section);

        // Save file
        $filename = 'memorandum_' . $this->memorandum->id . '_' . time() . '.docx';
        $path = 'memorandums/' . $filename;
        $fullPath = storage_path('app/public/' . $path);

        // Ensure directory exists
        if (!file_exists(dirname($fullPath))) {
            mkdir(dirname($fullPath), 0755, true);
        }

        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($fullPath);

        return $path;
    }

    /**
     * Generate PDF file
     */
    public function generatePDF()
    {
        $data = [
            'memorandum' => $this->memorandum,
            'template' => $this->template,
            'layoutConfig' => $this->layoutConfig,
        ];

        $pdf = Pdf::loadView('memorandums.pdf_template', $data);

        // Apply paper size and orientation
        $pdf->setPaper('letter', 'portrait');

        $filename = 'memorandum_' . $this->memorandum->id . '_' . time() . '.pdf';
        $path = 'memorandums/' . $filename;
        $fullPath = storage_path('app/public/' . $path);

        // Ensure directory exists
        if (!file_exists(dirname($fullPath))) {
            mkdir(dirname($fullPath), 0755, true);
        }

        $pdf->save($fullPath);

        return $path;
    }

    // Helper methods for DOCX generation

    protected function addHeaderLines($section)
    {
        $headerStyle = [
            'alignment' => Jc::CENTER,
            'spaceAfter' => 0,
        ];

        $section->addText($this->template->header_line_1, ['bold' => false, 'size' => 12], $headerStyle);
        $section->addText($this->template->header_line_2, ['bold' => false, 'size' => 12], $headerStyle);
        $section->addText($this->template->header_line_3, ['bold' => false, 'size' => 12], $headerStyle);
        $section->addText($this->template->header_line_4, ['bold' => false, 'size' => 12], $headerStyle);
        $section->addTextBreak(1);
    }

    protected function addDate($section)
    {
        $dateStyle = [
            'alignment' => Jc::END,
            'spaceAfter' => 200,
        ];
        $section->addText($this->memorandum->memorandum_date->format('F d, Y'), [], $dateStyle);
    }

    protected function addMemorandumTitle($section)
    {
        $titleStyle = [
            'alignment' => Jc::CENTER,
            'spaceAfter' => 200,
        ];
        $section->addText('MEMORANDUM', ['bold' => true, 'size' => 14], $titleStyle);
        $section->addTextBreak(1);
    }

    protected function addRecipientSection($section)
    {
        $labelStyle = ['bold' => true];
        $paraStyle = ['spaceAfter' => 100];

        // TO
        if (in_array($this->memorandum->recipient_type, ['TO', 'BOTH']) && $this->memorandum->recipient_to) {
            $lines = explode("\n", $this->memorandum->recipient_to);
            $firstLine = array_shift($lines);

            $toText = $section->addTextRun($paraStyle);
            $toText->addText('TO', $labelStyle);
            $toText->addText("\t\t: " . trim($firstLine));

            foreach ($lines as $line) {
                if (trim($line)) {
                    $section->addText("\t\t\t " . trim($line), [], $paraStyle);
                } else {
                    $section->addTextBreak(1); // Handle empty lines for spacing
                }
            }
        }

        // FOR
        if (in_array($this->memorandum->recipient_type, ['FOR', 'BOTH']) && $this->memorandum->recipient_for) {
            $lines = explode("\n", $this->memorandum->recipient_for);
            $firstLine = array_shift($lines);

            $forText = $section->addTextRun($paraStyle);
            $forText->addText('FOR', $labelStyle);
            $forText->addText("\t\t: " . trim($firstLine));

            foreach ($lines as $line) {
                if (trim($line)) {
                    $section->addText("\t\t\t " . trim($line), [], $paraStyle);
                } else {
                    $section->addTextBreak(1);
                }
            }
        }

        // ATTN
        if ($this->memorandum->attn) {
            $attnText = $section->addTextRun($paraStyle);
            $attnText->addText('ATTN', $labelStyle);
            $attnText->addText("\t\t: " . $this->memorandum->attn);
        }

        // FROM
        $lines = explode("\n", $this->memorandum->from_text ?? '');
        $firstLine = array_shift($lines);

        $fromText = $section->addTextRun($paraStyle);
        $fromText->addText('FROM', $labelStyle);
        $fromText->addText("\t\t: " . strtoupper(trim($firstLine)));

        foreach ($lines as $line) {
            if (trim($line)) {
                $section->addText("\t\t\t  " . strtoupper(trim($line)), [], $paraStyle);
            }
        }

        // SUBJECT
        $subjectText = $section->addTextRun($paraStyle);
        $subjectText->addText('SUBJECT', $labelStyle);
        $subjectText->addText("\t: " . strtoupper($this->memorandum->subject));

        $section->addTextBreak(1);
    }

    protected function addBodyParagraphs($section)
    {
        $paragraphs = $this->memorandum->getParagraphsArray();

        $paraStyle = [
            'alignment' => Jc::BOTH,
            'indentation' => ['firstLine' => 720], // 0.5 inch first-line indent
            'spaceAfter' => 200,
        ];

        foreach ($paragraphs as $paragraph) {
            $section->addText(trim($paragraph), [], $paraStyle);
        }

        $section->addTextBreak(1);
    }

    protected function addSignatureSection($section)
    {
        $rightAlignStyle = ['alignment' => Jc::END];

        if ($this->memorandum->use_esignature && $this->memorandum->signature_path) {
            // Add signature image
            $signaturePath = storage_path('app/public/' . $this->memorandum->signature_path);
            if (file_exists($signaturePath)) {
                $section->addImage($signaturePath, [
                    'width' => 100,
                    'height' => 50,
                    'alignment' => Jc::END,
                ]);
            }
        } else {
            // Leave space for wet signature
            $section->addTextBreak(3);
        }

        // Get name from creator's employee table if available
        $fromName = $this->memorandum->from_name;
        if (!$fromName && $this->memorandum->creator && $this->memorandum->creator->Employee) {
            $fromName = \App\Helpers\NameFormatter::formatWithMiddleInitial($this->memorandum->creator->Employee->fullname);
        } elseif (!$fromName) {
            $fromName = strtoupper($this->memorandum->creator->username ?? 'Unknown');
        }

        // Add name
        $section->addText($fromName, ['bold' => true], $rightAlignStyle);

        // Add position only if show_position is true
        if ($this->memorandum->show_position ?? true) {
            $fromPosition = $this->memorandum->from_position;
            if (!$fromPosition && $this->memorandum->creator && $this->memorandum->creator->Employee) {
                $fromPosition = $this->memorandum->creator->Employee->position ?? '';
            }
            if ($fromPosition) {
                $section->addText($fromPosition, [], $rightAlignStyle);
            }
        }
    }

    protected function formatRecipients($recipients)
    {
        if (is_array($recipients)) {
            return implode(', ', $recipients);
        }
        return $recipients ?? '';
    }
}
