<?php

namespace App\Services;

use App\Models\Document;
use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser as PdfParser;
use PhpOffice\PhpWord\IOFactory as WordFactory;

class DocumentParserService
{
    private PdfParser $pdfParser;

    public function __construct()
    {
        $this->pdfParser = new PdfParser();
    }

    /**
     * Parse a document and extract text
     */
    public function parse(Document $document): array
    {
        $filePath = Storage::disk('local')->path($document->file_path);

        if (!file_exists($filePath)) {
            throw new \Exception('Document file not found');
        }

        return match ($document->type) {
            'pdf' => $this->parsePdf($filePath),
            'docx', 'doc' => $this->parseWord($filePath, $document->type),
            'image' => $this->parseImage($filePath),
            default => throw new \Exception('Unsupported document type'),
        };
    }

    /**
     * Parse PDF document
     */
    private function parsePdf(string $filePath): array
    {
        try {
            $pdf = $this->pdfParser->parseFile($filePath);
            $text = $pdf->getText();
            $pageCount = count($pdf->getPages());

            // Clean up the text
            $text = $this->cleanText($text);

            return [
                'text' => $text,
                'page_count' => max(1, $pageCount),
            ];
        } catch (\Exception $e) {
            throw new \Exception('Failed to parse PDF: ' . $e->getMessage());
        }
    }

    /**
     * Parse Word document (DOCX/DOC)
     */
    private function parseWord(string $filePath, string $type): array
    {
        try {
            $reader = $type === 'docx' ? 'Word2007' : 'MsDoc';
            $phpWord = WordFactory::load($filePath, $reader);
            
            $text = '';
            $pageCount = 0;
            
            foreach ($phpWord->getSections() as $section) {
                $pageCount++;
                
                foreach ($section->getElements() as $element) {
                    $text .= $this->extractTextFromElement($element) . "\n";
                }
            }

            $text = $this->cleanText($text);

            return [
                'text' => $text,
                'page_count' => max(1, $pageCount),
            ];
        } catch (\Exception $e) {
            throw new \Exception('Failed to parse Word document: ' . $e->getMessage());
        }
    }

    /**
     * Parse image document (OCR would be implemented here)
     */
    private function parseImage(string $filePath): array
    {
        // For now, return a placeholder
        // In production, you would integrate OCR (e.g., Google Vision, AWS Textract)
        
        // Check if tesseract is available
        $tesseractPath = trim(shell_exec('which tesseract') ?? '');
        
        if (!empty($tesseractPath)) {
            try {
                $outputFile = sys_get_temp_dir() . '/' . uniqid('ocr_');
                $command = escapeshellcmd("tesseract {$filePath} {$outputFile}");
                exec($command);
                
                $text = file_get_contents($outputFile . '.txt');
                @unlink($outputFile . '.txt');
                
                return [
                    'text' => $this->cleanText($text),
                    'page_count' => 1,
                ];
            } catch (\Exception $e) {
                // Fall through to placeholder
            }
        }

        return [
            'text' => '[Image document - OCR processing required. Please upgrade to Pro+ for OCR support.]',
            'page_count' => 1,
        ];
    }

    /**
     * Extract text from Word element
     */
    private function extractTextFromElement($element): string
    {
        $text = '';
        
        if (method_exists($element, 'getText')) {
            $text .= $element->getText() . ' ';
        }
        
        if (method_exists($element, 'getElements')) {
            foreach ($element->getElements() as $childElement) {
                $text .= $this->extractTextFromElement($childElement);
            }
        }
        
        return $text;
    }

    /**
     * Clean and normalize extracted text
     */
    private function cleanText(string $text): string
    {
        // Remove excessive whitespace
        $text = preg_replace('/\s+/', ' ', $text);
        
        // Remove special characters that might cause issues
        $text = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $text);
        
        // Trim and normalize line breaks
        $text = trim($text);
        
        // Limit text length to prevent token overflow
        $maxLength = 50000;
        if (strlen($text) > $maxLength) {
            $text = substr($text, 0, $maxLength) . '... [Content truncated]';
        }

        return $text;
    }

    /**
     * Estimate page count from text length
     */
    public function estimatePageCount(string $text): int
    {
        // Approximately 3000 characters per page
        $charsPerPage = 3000;
        return max(1, (int) ceil(strlen($text) / $charsPerPage));
    }
}

