<?php

namespace App\Http\Requests\Document;

use Illuminate\Foundation\Http\FormRequest;

class UploadDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $maxSize = config('docmind.max_file_size_mb', 100) * 1024; // Convert to KB
        $mimeTypes = implode(',', config('docmind.supported_mime_types', [
            'application/pdf',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/msword',
            'image/jpeg',
            'image/png',
        ]));

        return [
            'document' => [
                'required',
                'file',
                "max:{$maxSize}",
                "mimetypes:{$mimeTypes}",
            ],
        ];
    }

    public function messages(): array
    {
        $maxSize = config('docmind.max_file_size_mb', 100);
        
        return [
            'document.required' => 'Please select a document to upload.',
            'document.file' => 'The uploaded file is invalid.',
            'document.max' => "The document must not exceed {$maxSize}MB.",
            'document.mimetypes' => 'Only PDF, DOCX, DOC, JPG, and PNG files are supported.',
        ];
    }
}

