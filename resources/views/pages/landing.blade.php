@extends('layouts.app')

@section('title', 'DocMind AI - AI-Powered Document Summarization')
@section('description', 'Transform your documents into intelligent summaries with AI-powered analysis. Upload PDFs, DOCX files, and images for instant insights.')

@section('content')
<!-- Hero Section -->
<section class="hero" id="home">
    <div class="container">
        <div class="hero-content">
            <div class="hero-badge">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                </svg>
                AI-Powered Document Analysis
            </div>
            
            <h1>Summarize Documents<br>in Seconds</h1>
            
            <p>Upload any document and let AI extract the key insights, create summaries, and help you understand complex content faster than ever.</p>
            
            <div class="hero-buttons">
                <a href="https://apps.apple.com/app/id6757693350" class="app-store-btn" id="download" target="_blank" rel="noopener">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/>
                    </svg>
                    <span>
                        <span class="small">Download on the</span>
                        <span class="large">App Store</span>
                    </span>
                </a>
                
                <a href="#features" class="btn btn-outline">Learn More</a>
            </div>
        </div>
        
        <div class="hero-visual">
            <div class="phone-mockup">
                <div class="phone-screen">
                    <!-- Status Bar -->
                    <div class="status-bar">
                        <span>9:41</span>
                        <div class="status-icons">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 3C7.46 3 3.34 4.78.29 7.67c-.18.18-.29.43-.29.71 0 .28.11.53.29.71l11 11c.18.18.43.29.71.29s.53-.11.71-.29l11-11c.18-.18.29-.43.29-.71 0-.28-.11-.53-.29-.71C20.66 4.78 16.54 3 12 3z"/></svg>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M15.67 4H14V2h-4v2H8.33C7.6 4 7 4.6 7 5.33v15.33C7 21.4 7.6 22 8.33 22h7.33c.74 0 1.34-.6 1.34-1.33V5.33C17 4.6 16.4 4 15.67 4z"/></svg>
                        </div>
                    </div>
                    
                    <!-- App Header -->
                    <div class="app-header">
                        <div class="header-back">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                        </div>
                        <div class="header-title">Summary</div>
                        <div class="header-share">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/><polyline points="16 6 12 2 8 6"/><line x1="12" y1="2" x2="12" y2="15"/></svg>
                        </div>
                    </div>
                    
                    <!-- Document Info -->
                    <div class="doc-info-bar">
                        <div class="doc-icon-small">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M14 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6zm-1 7V3.5L18.5 9H13z"/></svg>
                        </div>
                        <span class="doc-title">Annual Report 2024.pdf</span>
                    </div>
                    
                    <!-- Summary Content -->
                    <div class="mock-content">
                        <!-- Overview Card -->
                        <div class="summary-card">
                            <div class="card-header purple">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><path d="M16 13H8"/><path d="M16 17H8"/></svg>
                                <span>Overview</span>
                            </div>
                            <p class="card-text">This report analyzes the company's financial performance and strategic initiatives for 2024.</p>
                        </div>
                        
                        <!-- Key Points Card -->
                        <div class="summary-card">
                            <div class="card-header green">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4"/><path d="M12 16h.01"/></svg>
                                <span>Key Points</span>
                            </div>
                            <div class="key-points-list">
                                <div class="kp-item"><span class="kp-dot"></span>Revenue grew 24% YoY</div>
                                <div class="kp-item"><span class="kp-dot"></span>Expanded to 12 markets</div>
                                <div class="kp-item"><span class="kp-dot"></span>2.5M active customers</div>
                            </div>
                        </div>
                        
                        <!-- AI Generated Badge -->
                        <div class="ai-generated">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2L9.19 8.63 2 9.24l5.46 4.73L5.82 21 12 17.27 18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2z"/></svg>
                            <span>Generated by AI</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features" id="features">
    <div class="container">
        <div class="section-header">
            <h2>Powerful AI Features</h2>
            <p>Everything you need to extract insights from your documents quickly and efficiently.</p>
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
<section class="how-it-works" id="how-it-works">
    <div class="container">
        <div class="section-header">
            <h2>How It Works</h2>
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
<section class="pricing" id="pricing">
    <div class="container">
        <div class="section-header">
            <h2>Simple Pricing</h2>
            <p>Choose the plan that works best for you. Start free, upgrade anytime.</p>
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
<section class="cta">
    <div class="container">
        <h2>Ready to Get Started?</h2>
        <p>Download DocMind AI now and start transforming your documents into insights.</p>
        <a href="https://apps.apple.com/app/id6757693350" class="app-store-btn" target="_blank" rel="noopener">
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
