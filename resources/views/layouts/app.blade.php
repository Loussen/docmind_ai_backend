<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('description', 'DocMind AI - Transform your documents into intelligent summaries with AI-powered analysis. Upload PDFs, DOCX files, and images for instant insights.')">
    <meta name="keywords" content="AI document summary, PDF summarizer, document analysis, OCR, text extraction, AI assistant">
    
    <!-- Open Graph -->
    <meta property="og:title" content="@yield('title', 'DocMind AI - AI-Powered Document Summarization')">
    <meta property="og:description" content="@yield('description', 'Transform your documents into intelligent summaries with AI-powered analysis.')">
    <meta property="og:image" content="{{ asset('assets/images/app-icon.png') }}">
    <meta property="og:type" content="website">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', 'DocMind AI')">
    <meta name="twitter:description" content="@yield('description', 'AI-Powered Document Summarization')">
    
    <title>@yield('title', 'DocMind AI - AI-Powered Document Summarization')</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/images/app-icon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/images/app-icon.png') }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    
    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar" id="navbar">
        <div class="container">
            <a href="{{ route('home') }}" class="logo">
                <img src="{{ asset('assets/images/app-icon.png') }}" alt="DocMind AI">
                <span>DocMind AI</span>
            </a>
            
            <ul class="nav-links">
                <li><a href="{{ route('home') }}#features">Features</a></li>
                <li><a href="{{ route('home') }}#pricing">Pricing</a></li>
                <li><a href="{{ route('support') }}">Support</a></li>
            </ul>
            
            <div class="nav-cta">
                <a href="https://apps.apple.com/app/id6757693350" class="btn btn-primary" target="_blank" rel="noopener">Download App</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-brand">
                    <a href="{{ route('home') }}" class="logo">
                        <img src="{{ asset('assets/images/app-icon.png') }}" alt="DocMind AI">
                        <span>DocMind AI</span>
                    </a>
                    <p>Transform your documents into intelligent summaries with AI-powered analysis.</p>
                </div>
                
                <div class="footer-links">
                    <h4>Product</h4>
                    <ul>
                        <li><a href="{{ route('home') }}#features">Features</a></li>
                        <li><a href="{{ route('home') }}#pricing">Pricing</a></li>
                        <li><a href="{{ route('support') }}">Support</a></li>
                    </ul>
                </div>
                
                <div class="footer-links">
                    <h4>Legal</h4>
                    <ul>
                        <li><a href="{{ route('privacy') }}">Privacy Policy</a></li>
                        <li><a href="{{ route('terms') }}">Terms of Service</a></li>
                    </ul>
                </div>
                
                <div class="footer-links">
                    <h4>Contact</h4>
                    <ul>
                        <li><a href="mailto:support@docsmind.app">support@docsmind.app</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} DocMind AI. All rights reserved.</p>
                <div class="social-links">
                    <a href="#" aria-label="Twitter">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"/>
                        </svg>
                    </a>
                    <a href="#" aria-label="Instagram">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="2" y="2" width="20" height="20" rx="5" ry="5"/>
                            <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/>
                            <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>
