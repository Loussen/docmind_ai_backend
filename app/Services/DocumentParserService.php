<?php

namespace App\Services;

use App\Models\Document;
use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser as PdfParser;
use PhpOffice\PhpWord\IOFactory as WordFactory;
use OpenAI\Laravel\Facades\OpenAI;

class DocumentParserService
{
    private PdfParser $pdfParser;
    private bool $ocrEnabled;

    public function __construct()
    {
        $this->pdfParser = new PdfParser();
        $this->ocrEnabled = !empty(config('openai.api_key'));
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
     * Parse a file directly (for guest usage without Document model)
     */
    public function parseFile(string $filePath, string $type, ?string $mimeType = null): array
    {
        if (!file_exists($filePath)) {
            throw new \Exception('File not found');
        }

        return match ($type) {
            'pdf' => $this->parsePdf($filePath),
            'docx', 'doc' => $this->parseWord($filePath, $type),
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

            // If PDF has very little text, it might be a scanned document
            // Try OCR if enabled and text is too short
            if (strlen($text) < 100 && $this->ocrEnabled) {
                \Log::info('PDF appears to be scanned, attempting OCR...');
                
                try {
                    // Convert first page of PDF to image for OCR
                    $ocrResult = $this->ocrPdfWithVision($filePath);
                    if (!empty($ocrResult['text']) && strlen($ocrResult['text']) > strlen($text)) {
                        return [
                            'text' => $ocrResult['text'],
                            'page_count' => max(1, $pageCount),
                        ];
                    }
                } catch (\Exception $e) {
                    \Log::warning('PDF OCR failed, using extracted text: ' . $e->getMessage());
                }
            }

            return [
                'text' => $text,
                'page_count' => max(1, $pageCount),
            ];
        } catch (\Exception $e) {
            throw new \Exception('Failed to parse PDF: ' . $e->getMessage());
        }
    }

    /**
     * OCR a PDF using Vision (converts to image first)
     */
    private function ocrPdfWithVision(string $filePath): array
    {
        // Check if Imagick is available for PDF to image conversion
        if (!extension_loaded('imagick')) {
            throw new \Exception('Imagick extension required for PDF OCR');
        }

        try {
            $imagick = new \Imagick();
            $imagick->setResolution(300, 300);
            $imagick->readImage($filePath . '[0]'); // First page only
            $imagick->setImageFormat('png');
            
            $tempFile = sys_get_temp_dir() . '/' . uniqid('pdf_ocr_') . '.png';
            $imagick->writeImage($tempFile);
            $imagick->destroy();
            
            $result = $this->parseImageWithVision($tempFile);
            @unlink($tempFile);
            
            return $result;
        } catch (\Exception $e) {
            throw new \Exception('PDF to image conversion failed: ' . $e->getMessage());
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
     * Parse image document using OpenAI GPT-4 Vision for OCR
     */
    private function parseImage(string $filePath): array
    {
        // Use OpenAI GPT-4 Vision for OCR
        if ($this->ocrEnabled) {
            try {
                return $this->parseImageWithVision($filePath);
            } catch (\Exception $e) {
                \Log::error('Vision OCR failed: ' . $e->getMessage());
                // Fall through to tesseract or placeholder
            }
        }

        // Fallback: Check if tesseract is available
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
     * Parse image using OpenAI GPT-4 Vision
     */
    private function parseImageWithVision(string $filePath): array
    {
        // Read and encode image as base64
        $imageData = file_get_contents($filePath);
        $base64Image = base64_encode($imageData);
        
        // Detect mime type
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($filePath);
        
        // Validate mime type
        $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($mimeType, $allowedMimes)) {
            throw new \Exception('Unsupported image format for OCR');
        }

        $response = OpenAI::chat()->create([
            'model' => 'gpt-4o',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You are an expert OCR system. Extract all text from the provided image. Maintain the original structure, formatting, and layout as much as possible. If the image contains tables, preserve the table structure. If there are multiple columns, process them in reading order. Output only the extracted text, nothing else.',
                ],
                [
                    'role' => 'user',
                    'content' => [
                        [
                            'type' => 'text',
                            'text' => 'Please extract all text from this image. Maintain the original structure and formatting. Output only the extracted text.',
                        ],
                        [
                            'type' => 'image_url',
                            'image_url' => [
                                'url' => "data:{$mimeType};base64,{$base64Image}",
                                'detail' => 'high',
                            ],
                        ],
                    ],
                ],
            ],
            'max_tokens' => 4096,
            'temperature' => 0.1,
        ]);

        $text = $response->choices[0]->message->content ?? '';
        
        if (empty(trim($text))) {
            throw new \Exception('No text could be extracted from the image');
        }

        return [
            'text' => $this->cleanText($text),
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
        
        $maxLength = 15000;
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

