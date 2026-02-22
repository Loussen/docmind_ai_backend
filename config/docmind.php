<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Document Processing Settings
    |--------------------------------------------------------------------------
    */
    
    'max_file_size_mb' => env('MAX_FILE_SIZE_MB', 100),
    
    'max_pages_free' => env('MAX_PAGES_FREE', 5),
    
    'free_docs_total' => env('FREE_DOCS_TOTAL', 2),
    
    'supported_extensions' => ['pdf', 'docx', 'doc', 'jpg', 'jpeg', 'png'],
    
    'supported_mime_types' => [
        'application/pdf',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/msword',
        'image/jpeg',
        'image/png',
    ],
    
    /*
    |--------------------------------------------------------------------------
    | OpenAI Settings
    |--------------------------------------------------------------------------
    */
    
    'openai' => [
        'model' => env('OPENAI_MODEL', 'gpt-4o-mini'),
        'max_tokens' => env('OPENAI_MAX_TOKENS', 2048),
        'temperature' => env('OPENAI_TEMPERATURE', 0.3),
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Subscription Plans
    |--------------------------------------------------------------------------
    */
    
    'plans' => [
        'free' => [
            'name' => 'Free',
            'docs_total' => 2,
            'pages_per_doc' => 5,
            'price_monthly' => 0,
            'price_yearly' => 0,
            'features' => [
                '2 free documents',
                '5 pages per document',
                'Basic AI summaries',
            ],
        ],
        'pro' => [
            'name' => 'Pro',
            'docs_per_day' => -1, // Unlimited
            'pages_per_doc' => -1, // Unlimited
            'price_monthly' => 4.99,
            'price_yearly' => 35.99,
            'apple_product_monthly' => 'com.docmind.pro.monthly',
            'apple_product_yearly' => 'com.docmind.pro.yearly',
            'features' => [
                'Unlimited documents',
                'Unlimited pages',
                'Priority AI processing',
                'Export to PDF',
                'Email support',
            ],
        ],
        'pro_plus' => [
            'name' => 'Pro+',
            'docs_per_day' => -1, // Unlimited
            'pages_per_doc' => -1, // Unlimited
            'price_monthly' => 9.99,
            'price_yearly' => 71.99,
            'apple_product_monthly' => 'com.docmind.proplus.monthly',
            'apple_product_yearly' => 'com.docmind.proplus.yearly',
            'features' => [
                'Everything in Pro',
                'OCR for scanned documents',
                'Long document support (100+ pages)',
                'Advanced AI analysis',
                'Priority support',
            ],
        ],
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Apple In-App Purchase
    |--------------------------------------------------------------------------
    | IMPORTANT: Set APPLE_SANDBOX=false in production .env file
    | This ensures production builds only accept real purchases
    */
    
    'apple' => [
        'shared_secret' => env('APPLE_SHARED_SECRET'),
        'sandbox' => env('APPLE_SANDBOX', false), // Default to production mode
        'verify_url_sandbox' => 'https://sandbox.itunes.apple.com/verifyReceipt',
        'verify_url_production' => 'https://buy.itunes.apple.com/verifyReceipt',
    ],
];

