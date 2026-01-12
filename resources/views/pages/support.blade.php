@extends('layouts.app')

@section('title', 'Support - DocMind AI')
@section('description', 'Get help with DocMind AI - Contact support, browse FAQs, and find answers to common questions.')

@section('content')
<section class="legal-hero">
    <div class="container">
        <h1>Support Center</h1>
        <p>We're here to help you get the most out of DocMind AI</p>
    </div>
</section>

<section class="support-content">
    <div class="container">
        <div class="support-grid">
            <div class="support-card">
                <div class="support-card-icon">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                        <polyline points="22,6 12,13 2,6"/>
                    </svg>
                </div>
                <h3>Email Support</h3>
                <p>Get help via email. We typically respond within 24 hours.</p>
                <a href="mailto:support@docsmind.app" class="btn btn-primary mt-2">Send Email</a>
            </div>
            
            <div class="support-card">
                <div class="support-card-icon">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
                        <line x1="12" y1="17" x2="12.01" y2="17"/>
                    </svg>
                </div>
                <h3>FAQs</h3>
                <p>Find answers to commonly asked questions below.</p>
                <a href="#faq" class="btn btn-secondary mt-2">Browse FAQs</a>
            </div>
            
            <div class="support-card">
                <div class="support-card-icon">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
                    </svg>
                </div>
                <h3>Feedback</h3>
                <p>Have a suggestion? We'd love to hear from you.</p>
                <a href="mailto:feedback@docsmind.app" class="btn btn-secondary mt-2">Send Feedback</a>
            </div>
        </div>
        
        <!-- FAQ Section -->
        <section class="faq-section" id="faq">
            <div class="section-header">
                <h2>Frequently Asked Questions</h2>
                <p>Find answers to common questions about DocMind AI</p>
            </div>
            
            <div class="faq-list">
                <div class="faq-item">
                    <div class="faq-question" onclick="this.parentElement.classList.toggle('active')">
                        <span>What file formats does DocMind AI support?</span>
                        <span>+</span>
                    </div>
                    <div class="faq-answer">
                        <p>DocMind AI supports the following file formats:</p>
                        <ul>
                            <li><strong>PDF</strong> - All PDF documents</li>
                            <li><strong>DOCX/DOC</strong> - Microsoft Word documents</li>
                            <li><strong>Images</strong> - JPG, PNG, and other image formats (processed with OCR)</li>
                        </ul>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question" onclick="this.parentElement.classList.toggle('active')">
                        <span>How many documents can I upload with the free plan?</span>
                        <span>+</span>
                    </div>
                    <div class="faq-answer">
                        <p>With the free plan, you can upload up to 3 documents per day. Each document can be up to 5MB in size. This limit resets every 24 hours.</p>
                        <p>For unlimited uploads, consider upgrading to our Pro or Pro+ plans.</p>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question" onclick="this.parentElement.classList.toggle('active')">
                        <span>How accurate are the AI summaries?</span>
                        <span>+</span>
                    </div>
                    <div class="faq-answer">
                        <p>DocMind AI uses advanced AI technology (powered by OpenAI) to generate summaries. While the summaries are generally accurate and helpful, they should be considered as aids to understanding rather than replacements for reading the original document.</p>
                        <p>For critical decisions, we recommend verifying important information against the original document.</p>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question" onclick="this.parentElement.classList.toggle('active')">
                        <span>Is my data secure?</span>
                        <span>+</span>
                    </div>
                    <div class="faq-answer">
                        <p>Yes, we take data security seriously:</p>
                        <ul>
                            <li>All data is encrypted in transit and at rest</li>
                            <li>Documents are processed securely and not shared with third parties</li>
                            <li>We use secure authentication (including Sign in with Apple)</li>
                            <li>You can delete your data at any time</li>
                        </ul>
                        <p>For more details, please read our <a href="{{ route('privacy') }}">Privacy Policy</a>.</p>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question" onclick="this.parentElement.classList.toggle('active')">
                        <span>How do I cancel my subscription?</span>
                        <span>+</span>
                    </div>
                    <div class="faq-answer">
                        <p>To cancel your subscription:</p>
                        <ol>
                            <li>Open the Settings app on your iPhone</li>
                            <li>Tap your name at the top</li>
                            <li>Tap "Subscriptions"</li>
                            <li>Find and tap "DocMind AI"</li>
                            <li>Tap "Cancel Subscription"</li>
                        </ol>
                        <p>Your subscription will remain active until the end of the current billing period.</p>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question" onclick="this.parentElement.classList.toggle('active')">
                        <span>Can I restore my purchases on a new device?</span>
                        <span>+</span>
                    </div>
                    <div class="faq-answer">
                        <p>Yes! To restore your purchases:</p>
                        <ol>
                            <li>Install DocMind AI on your new device</li>
                            <li>Sign in with the same Apple ID used for the original purchase</li>
                            <li>Go to Settings in the app</li>
                            <li>Tap "Restore Purchases"</li>
                        </ol>
                        <p>Your subscription will be restored automatically.</p>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question" onclick="this.parentElement.classList.toggle('active')">
                        <span>What's the difference between Pro and Pro+?</span>
                        <span>+</span>
                    </div>
                    <div class="faq-answer">
                        <p><strong>Pro ($4.99/month or $29.99/year):</strong></p>
                        <ul>
                            <li>Unlimited document uploads</li>
                            <li>10MB max file size</li>
                            <li>Priority processing</li>
                        </ul>
                        <p><strong>Pro+ ($9.99/month or $59.99/year):</strong></p>
                        <ul>
                            <li>Everything in Pro</li>
                            <li>20MB max file size</li>
                            <li>Advanced OCR features</li>
                            <li>Longer document support</li>
                            <li>Priority support</li>
                        </ul>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question" onclick="this.parentElement.classList.toggle('active')">
                        <span>How do I delete my account?</span>
                        <span>+</span>
                    </div>
                    <div class="faq-answer">
                        <p>To delete your account and all associated data:</p>
                        <ol>
                            <li>Open DocMind AI</li>
                            <li>Go to Settings</li>
                            <li>Scroll down and tap "Delete Account"</li>
                            <li>Confirm the deletion</li>
                        </ol>
                        <p>Note: This action is permanent and cannot be undone. All your documents and summaries will be deleted.</p>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question" onclick="this.parentElement.classList.toggle('active')">
                        <span>The app isn't working correctly. What should I do?</span>
                        <span>+</span>
                    </div>
                    <div class="faq-answer">
                        <p>Try these troubleshooting steps:</p>
                        <ol>
                            <li><strong>Restart the app</strong> - Close and reopen DocMind AI</li>
                            <li><strong>Check your internet connection</strong> - The app requires an internet connection to process documents</li>
                            <li><strong>Update the app</strong> - Make sure you have the latest version from the App Store</li>
                            <li><strong>Restart your device</strong> - Sometimes a simple restart helps</li>
                            <li><strong>Contact support</strong> - If the issue persists, email us at <a href="mailto:support@docsmind.app">support@docsmind.app</a></li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Contact Section -->
        <section class="text-center mt-3">
            <h3>Still Need Help?</h3>
            <p class="mb-2">Our support team is ready to assist you.</p>
            <a href="mailto:support@docsmind.app" class="btn btn-primary">Contact Support</a>
        </section>
    </div>
</section>
@endsection

@push('styles')
<style>
    .faq-item {
        max-height: 60px;
        overflow: hidden;
        transition: max-height 0.3s ease;
    }
    
    .faq-item.active {
        max-height: 500px;
    }
    
    .faq-item.active .faq-question span:last-child {
        transform: rotate(45deg);
    }
    
    .faq-question span:last-child {
        font-size: 1.5rem;
        transition: transform 0.3s ease;
    }
</style>
@endpush
