@extends('layouts.app')

@section('title', 'DoCMind AI - AI-Powered Document and Image Summarization App for iPhone')
@section('description', 'DoCMind AI summarizes PDFs, Word docs & images in seconds using AI. Download free on the App Store. Try without an account. Smart OCR, key points & action items.')
@section('keywords', 'AI document summarizer, PDF summary app, summarize PDF iPhone, document reader AI, OCR app iOS, AI notes app, DoCMind AI, text extraction app, DOCX summarizer, AI document analysis, App Store')

@section('structured_data')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "SoftwareApplication",
    "name": "DoCMind AI",
    "description": "AI-Powered Document Summarization App. Upload PDFs, Word documents, and images to get instant AI-generated summaries, key points, and action items.",
    "applicationCategory": "ProductivityApplication",
    "operatingSystem": "iOS",
    "offers": [
        {
            "@type": "Offer",
            "price": "0",
            "priceCurrency": "USD",
            "description": "Free plan - 3 documents per day"
        },
        {
            "@type": "Offer",
            "price": "4.99",
            "priceCurrency": "USD",
            "description": "Pro plan - Unlimited documents",
            "priceValidUntil": "{{ date('Y-12-31') }}"
        }
    ],
    "aggregateRating": {
        "@type": "AggregateRating",
        "ratingValue": "4.8",
        "ratingCount": "50",
        "bestRating": "5",
        "worstRating": "1"
    },
    "downloadUrl": "https://apps.apple.com/app/id6757693350",
    "softwareVersion": "1.0",
    "screenshot": "{{ asset('assets/images/og-image.png') }}",
    "author": {
        "@type": "Organization",
        "name": "DoCMind AI",
        "url": "{{ url('/') }}"
    }
}
</script>
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebSite",
    "name": "DoCMind AI",
    "url": "{{ url('/') }}",
    "description": "AI-Powered Document Summarization",
    "potentialAction": {
        "@type": "SearchAction",
        "target": "{{ url('/support') }}?q={search_term_string}",
        "query-input": "required name=search_term_string"
    }
}
</script>
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Organization",
    "name": "DoCMind AI",
    "url": "{{ url('/') }}",
    "logo": "{{ asset('assets/images/app-icon.png') }}",
    "contactPoint": {
        "@type": "ContactPoint",
        "email": "support@docsmind.app",
        "contactType": "customer support"
    },
    "sameAs": []
}
</script>
@endsection

@section('content')
<!-- Hero Section -->
<section class="hero" id="home" itemscope itemtype="https://schema.org/SoftwareApplication">
    <div class="container">
        <div class="hero-content">
            <div class="hero-badge" aria-label="AI-Powered Document Analysis">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                </svg>
                <span itemprop="applicationCategory">AI-Powered Document Analysis</span>
            </div>
            
            <h1>Summarize Documents and Images<br>in Seconds with <span itemprop="name">DoCMind AI</span></h1>
            
            <p itemprop="description">Upload any PDF, Word document, or image and let AI extract key insights, create summaries with key points and action items — understand complex content faster than ever.</p>
            
            <div class="hero-buttons">
                <a href="https://apps.apple.com/app/id6757693350" class="app-store-btn" id="download" target="_blank" rel="noopener" aria-label="Download DoCMind AI on the App Store" itemprop="downloadUrl">
                    <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/>
                    </svg>
                    <span>
                        <span class="small">Download on the</span>
                        <span class="large">App Store</span>
                    </span>
                </a>
                
                <a href="#features" class="btn btn-outline">Learn More</a>
            </div>
            <meta itemprop="operatingSystem" content="iOS">
            <meta itemprop="softwareVersion" content="1.0">
        </div>
        
        <div class="hero-visual" role="img" aria-label="DoCMind AI app screenshot showing document summarization on iPhone">
            <div class="phone-mockup">
                <div class="phone-screen">
                    <div class="phone-header">
                        <h4>Hello, there</h4>
                        <h3>DoCMind AI</h3>
                    </div>
                    <div class="phone-content">
                        <div class="mock-card usage">
                            <h5>Free Usage</h5>
                            <div class="mock-progress">
                                <div class="mock-progress-bar"></div>
                            </div>
                        </div>
                        <div class="mock-card doc">
                            <div class="doc-icon">PDF</div>
                            <div class="doc-info">
                                <h5>Annual Report 2024.pdf</h5>
                                <p>PDF &bull; 2.4 MB &bull; 24 pages</p>
                            </div>
                        </div>
                        <div class="mock-card doc">
                            <div class="doc-icon" style="background: #E3F2FD; color: #1976D2;">DOC</div>
                            <div class="doc-info">
                                <h5>Project Proposal.docx</h5>
                                <p>DOCX &bull; 856 KB &bull; 12 pages</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features" id="features" aria-labelledby="features-heading">
    <div class="container">
        <div class="section-header">
            <h2 id="features-heading">Powerful AI Features for Document Summarization</h2>
            <p>Everything you need to extract insights from PDFs, Word documents, and images quickly and efficiently.</p>
        </div>
        
        <div class="features-grid">
            <div class="feature-card fade-in-up">
                <div class="feature-icon">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                        <line x1="16" y1="13" x2="8" y2="13"/>
                        <line x1="16" y1="17" x2="8" y2="17"/>
                        <polyline points="10 9 9 9 8 9"/>
                    </svg>
                </div>
                <h3>Smart Summarization</h3>
                <p>AI analyzes your documents and creates concise, accurate summaries highlighting key points.</p>
            </div>
            
            <div class="feature-card fade-in-up delay-1">
                <div class="feature-icon">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"/>
                        <path d="M21 21l-4.35-4.35"/>
                        <path d="M11 8v6"/>
                        <path d="M8 11h6"/>
                    </svg>
                </div>
                <h3>OCR Technology</h3>
                <p>Extract text from images and scanned documents with advanced optical character recognition.</p>
            </div>
            
            <div class="feature-card fade-in-up delay-2">
                <div class="feature-icon">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                        <path d="M2 17l10 5 10-5"/>
                        <path d="M2 12l10 5 10-5"/>
                    </svg>
                </div>
                <h3>Multiple Formats</h3>
                <p>Support for PDF, DOCX, DOC, and image files. Upload any document format you need.</p>
            </div>
            
            <div class="feature-card fade-in-up delay-3">
                <div class="feature-icon">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                </div>
                <h3>Secure & Private</h3>
                <p>Your documents are encrypted and processed securely. We never share your data.</p>
            </div>
            
            <div class="feature-card fade-in-up delay-4">
                <div class="feature-icon">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
                    </svg>
                </div>
                <h3>Instant Results</h3>
                <p>Get your document summaries in seconds, not minutes. AI processing at lightning speed.</p>
            </div>
            
            <div class="feature-card fade-in-up delay-4">
                <div class="feature-icon">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 8v4l3 3"/>
                        <circle cx="12" cy="12" r="10"/>
                    </svg>
                </div>
                <h3>History & Archive</h3>
                <p>Access your document history anytime. All summaries are saved for future reference.</p>
            </div>
        </div>
    </div>
</section>

<!-- How It Works -->
<section class="how-it-works" id="how-it-works" aria-labelledby="how-it-works-heading">
    <div class="container">
        <div class="section-header">
            <h2 id="how-it-works-heading">How DoCMind AI Works</h2>
            <p>Three simple steps to transform your documents into actionable insights.</p>
        </div>
        
        <div class="steps">
            <div class="step">
                <div class="step-number">1</div>
                <h3>Upload Document</h3>
                <p>Select a PDF, DOCX, or image file from your device. Files up to 10MB supported.</p>
            </div>
            
            <div class="step">
                <div class="step-number">2</div>
                <h3>AI Analysis</h3>
                <p>Our advanced AI reads and understands your document, extracting key information.</p>
            </div>
            
            <div class="step">
                <div class="step-number">3</div>
                <h3>Get Summary</h3>
                <p>Receive a comprehensive summary with key points, insights, and extracted data.</p>
            </div>
        </div>
    </div>
</section>

<!-- Pricing Section -->
<section class="pricing" id="pricing" aria-labelledby="pricing-heading">
    <div class="container">
        <div class="section-header">
            <h2 id="pricing-heading">Simple, Transparent Pricing</h2>
            <p>Start summarizing documents for free. Upgrade to Pro for unlimited access.</p>
        </div>
        
        <div class="pricing-cards">
            <div class="pricing-card">
                <h3>Free</h3>
                <div class="pricing-price">
                    <h4>$0</h4>
                    <span>/month</span>
                </div>
                <ul class="pricing-features">
                    <li>
                        <span class="pricing-check">✓</span>
                        3 documents per day
                    </li>
                    <li>
                        <span class="pricing-check">✓</span>
                        5MB max file size
                    </li>
                    <li>
                        <span class="pricing-check">✓</span>
                        PDF, DOCX support
                    </li>
                    <li>
                        <span class="pricing-check">✓</span>
                        Basic OCR
                    </li>
                </ul>
                <a href="#download" class="btn btn-secondary" style="width: 100%;">Get Started Free</a>
            </div>
            
            <div class="pricing-card featured">
                <div class="pricing-badge">Most Popular</div>
                <h3>Pro</h3>
                <div class="pricing-price">
                    <h4>$4.99</h4>
                    <span>/month</span>
                </div>
                <ul class="pricing-features">
                    <li>
                        <span class="pricing-check">✓</span>
                        Unlimited documents
                    </li>
                    <li>
                        <span class="pricing-check">✓</span>
                        10MB max file size
                    </li>
                    <li>
                        <span class="pricing-check">✓</span>
                        All file formats
                    </li>
                    <li>
                        <span class="pricing-check">✓</span>
                        Advanced OCR
                    </li>
                    <li>
                        <span class="pricing-check">✓</span>
                        Priority processing
                    </li>
                </ul>
                <a href="#download" class="btn btn-primary" style="width: 100%; background: white; color: #667eea;">Upgrade to Pro</a>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta" aria-labelledby="cta-heading">
    <div class="container">
        <h2 id="cta-heading">Ready to Summarize Smarter?</h2>
        <p>Download DoCMind AI free on the App Store and start transforming your documents into actionable insights in seconds.</p>
        <a href="https://apps.apple.com/app/id6757693350" class="app-store-btn" target="_blank" rel="noopener" aria-label="Download DoCMind AI free on the App Store">
            <svg viewBox="0 0 24 24" fill="currentColor">
                <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/>
            </svg>
            <span>
                <span class="small">Download on the</span>
                <span class="large">App Store</span>
            </span>
        </a>
    </div>
</section>
@endsection
