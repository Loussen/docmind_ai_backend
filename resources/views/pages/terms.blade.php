@extends('layouts.app')

@section('title', 'Terms of Service - DoCMind AI | Usage Terms & Conditions')
@section('description', 'Terms of Service for DoCMind AI. Read our terms and conditions for using the AI-powered document summarization app. Subscription, refund, and usage policies.')
@section('keywords', 'DoCMind AI terms of service, app terms and conditions, subscription terms, AI app usage policy, document summarizer terms')

@section('structured_data')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebPage",
    "name": "Terms of Service - DoCMind AI",
    "description": "Terms of Service for DoCMind AI document summarization app",
    "url": "{{ url('/terms') }}",
    "isPartOf": {
        "@type": "WebSite",
        "name": "DoCMind AI",
        "url": "{{ url('/') }}"
    },
    "breadcrumb": {
        "@type": "BreadcrumbList",
        "itemListElement": [
            {
                "@type": "ListItem",
                "position": 1,
                "name": "Home",
                "item": "{{ url('/') }}"
            },
            {
                "@type": "ListItem",
                "position": 2,
                "name": "Terms of Service"
            }
        ]
    }
}
</script>
@endsection

@section('content')
<section class="legal-hero">
    <div class="container">
        <h1>Terms of Service</h1>
        <p>Last updated: {{ date('F d, Y') }}</p>
    </div>
</section>

<section class="legal-content">
    <div class="container">
        <p>Welcome to DoCMind AI. These Terms of Service ("Terms") govern your access to and use of the DoCMind AI mobile application and related services (collectively, the "Service") provided by DoCMind AI ("we," "us," or "our").</p>
        
        <p><strong>By downloading, installing, or using our Service, you agree to be bound by these Terms. If you do not agree to these Terms, do not use the Service.</strong></p>
        
        <h2>1. Acceptance of Terms</h2>
        <p>By accessing or using our Service, you confirm that:</p>
        <ul>
            <li>You are at least 13 years of age</li>
            <li>You have the legal capacity to enter into a binding agreement</li>
            <li>You will comply with these Terms and all applicable laws and regulations</li>
        </ul>
        
        <h2>2. Description of Service</h2>
        <p>DoCMind AI provides an AI-powered document summarization service that allows users to:</p>
        <ul>
            <li>Upload documents (PDF, DOCX, images)</li>
            <li>Extract text using OCR technology</li>
            <li>Generate AI-powered summaries and insights</li>
            <li>Store and access document history</li>
        </ul>
        
        <h2>3. Account Registration</h2>
        
        <h3>3.1 Account Creation</h3>
        <p>To use certain features of the Service, you must create an account. You agree to:</p>
        <ul>
            <li>Provide accurate, current, and complete information</li>
            <li>Maintain and update your account information</li>
            <li>Keep your password secure and confidential</li>
            <li>Notify us immediately of any unauthorized access</li>
        </ul>
        
        <h3>3.2 Account Responsibility</h3>
        <p>You are responsible for all activities that occur under your account. We are not liable for any loss or damage arising from unauthorized use of your account.</p>
        
        <h2>4. Subscription and Payments</h2>
        
        <h3>4.1 Free Tier</h3>
        <p>The Service offers a free tier with limited features:</p>
        <ul>
            <li>3 document uploads per day</li>
            <li>5MB maximum file size</li>
            <li>Basic features</li>
        </ul>
        
        <h3>4.2 Premium Subscriptions</h3>
        <p>Premium subscriptions (Pro, Pro+) provide additional features:</p>
        <ul>
            <li>Unlimited document uploads</li>
            <li>Larger file size limits</li>
            <li>Priority processing</li>
            <li>Advanced features</li>
        </ul>
        
        <h3>4.3 Billing</h3>
        <p>Premium subscriptions are billed through Apple's App Store:</p>
        <ul>
            <li>Payment will be charged to your Apple ID account</li>
            <li>Subscription automatically renews unless canceled at least 24 hours before the end of the current period</li>
            <li>You can manage and cancel subscriptions in your Apple ID account settings</li>
            <li>Prices may vary by location and are subject to change</li>
        </ul>
        
        <h3>4.4 Refunds</h3>
        <p>Refund requests are handled by Apple according to their refund policies. We do not directly process refunds for App Store purchases.</p>
        
        <h2>5. User Content and Conduct</h2>
        
        <h3>5.1 Your Content</h3>
        <p>You retain ownership of documents you upload to the Service. By uploading content, you grant us a limited license to:</p>
        <ul>
            <li>Process and analyze your documents to provide the Service</li>
            <li>Store your documents and summaries in your account</li>
            <li>Improve our Service using anonymized, aggregated data</li>
        </ul>
        
        <h3>5.2 Prohibited Content</h3>
        <p>You agree not to upload content that:</p>
        <ul>
            <li>Infringes intellectual property rights of others</li>
            <li>Contains malware, viruses, or harmful code</li>
            <li>Is illegal, defamatory, or obscene</li>
            <li>Violates any person's privacy or rights</li>
            <li>Is fraudulent or deceptive</li>
        </ul>
        
        <h3>5.3 Prohibited Activities</h3>
        <p>You agree not to:</p>
        <ul>
            <li>Use the Service for any illegal purpose</li>
            <li>Attempt to reverse engineer or hack the Service</li>
            <li>Interfere with or disrupt the Service</li>
            <li>Create multiple accounts to circumvent limits</li>
            <li>Share your account credentials with others</li>
            <li>Use automated systems to access the Service</li>
            <li>Resell or redistribute the Service</li>
        </ul>
        
        <h2>6. Intellectual Property</h2>
        
        <h3>6.1 Our Rights</h3>
        <p>The Service, including its design, features, content, and code, is owned by us and protected by intellectual property laws. You may not:</p>
        <ul>
            <li>Copy, modify, or distribute the Service</li>
            <li>Use our trademarks without permission</li>
            <li>Create derivative works based on the Service</li>
        </ul>
        
        <h3>6.2 Feedback</h3>
        <p>If you provide feedback or suggestions about the Service, you grant us a perpetual, irrevocable license to use such feedback without compensation to you.</p>
        
        <h2>7. AI-Generated Content</h2>
        <p>The Service uses artificial intelligence to generate summaries and analysis:</p>
        <ul>
            <li>AI-generated content is provided "as is" without warranties of accuracy</li>
            <li>You should verify important information independently</li>
            <li>AI summaries are tools to assist understanding, not replacements for reading original documents</li>
            <li>We are not liable for decisions made based on AI-generated content</li>
        </ul>
        
        <h2>8. Privacy</h2>
        <p>Your use of the Service is subject to our <a href="{{ route('privacy') }}">Privacy Policy</a>, which describes how we collect, use, and protect your information.</p>
        
        <h2>9. Disclaimer of Warranties</h2>
        <p>THE SERVICE IS PROVIDED "AS IS" AND "AS AVAILABLE" WITHOUT WARRANTIES OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING:</p>
        <ul>
            <li>MERCHANTABILITY</li>
            <li>FITNESS FOR A PARTICULAR PURPOSE</li>
            <li>NON-INFRINGEMENT</li>
            <li>ACCURACY OR RELIABILITY OF CONTENT</li>
        </ul>
        <p>We do not warrant that the Service will be uninterrupted, error-free, or secure.</p>
        
        <h2>10. Limitation of Liability</h2>
        <p>TO THE MAXIMUM EXTENT PERMITTED BY LAW, WE SHALL NOT BE LIABLE FOR:</p>
        <ul>
            <li>Indirect, incidental, special, or consequential damages</li>
            <li>Loss of profits, data, or business opportunities</li>
            <li>Damages arising from your use or inability to use the Service</li>
            <li>Any third-party conduct or content</li>
        </ul>
        <p>Our total liability shall not exceed the amount you paid for the Service in the past 12 months.</p>
        
        <h2>11. Indemnification</h2>
        <p>You agree to indemnify and hold us harmless from any claims, damages, losses, or expenses arising from:</p>
        <ul>
            <li>Your use of the Service</li>
            <li>Your violation of these Terms</li>
            <li>Your violation of any third-party rights</li>
            <li>Content you upload to the Service</li>
        </ul>
        
        <h2>12. Termination</h2>
        
        <h3>12.1 By You</h3>
        <p>You may stop using the Service and delete your account at any time through the app settings.</p>
        
        <h3>12.2 By Us</h3>
        <p>We may suspend or terminate your access to the Service:</p>
        <ul>
            <li>If you violate these Terms</li>
            <li>If required by law</li>
            <li>For any reason with reasonable notice</li>
        </ul>
        
        <h3>12.3 Effect of Termination</h3>
        <p>Upon termination:</p>
        <ul>
            <li>Your right to use the Service ceases</li>
            <li>We may delete your account data</li>
            <li>Provisions that should survive termination will remain in effect</li>
        </ul>
        
        <h2>13. Modifications to Service and Terms</h2>
        
        <h3>13.1 Service Changes</h3>
        <p>We may modify, suspend, or discontinue any part of the Service at any time. We will provide notice of material changes when practicable.</p>
        
        <h3>13.2 Terms Changes</h3>
        <p>We may update these Terms from time to time. We will notify you of material changes by:</p>
        <ul>
            <li>Posting the updated Terms on our website</li>
            <li>Sending an in-app notification</li>
            <li>Updating the "Last updated" date</li>
        </ul>
        <p>Your continued use of the Service after changes constitutes acceptance of the updated Terms.</p>
        
        <h2>14. Governing Law</h2>
        <p>These Terms are governed by the laws of [Your Jurisdiction], without regard to conflict of law principles. Any disputes shall be resolved in the courts of [Your Jurisdiction].</p>
        
        <h2>15. Dispute Resolution</h2>
        <p>Before filing a legal claim, you agree to try to resolve the dispute informally by contacting us at <a href="mailto:legal@docsmind.app">legal@docsmind.app</a>.</p>
        
        <h2>16. Severability</h2>
        <p>If any provision of these Terms is found unenforceable, the remaining provisions will continue in effect.</p>
        
        <h2>17. Entire Agreement</h2>
        <p>These Terms, together with our Privacy Policy, constitute the entire agreement between you and us regarding the Service.</p>
        
        <h2>18. Contact Us</h2>
        <p>If you have questions about these Terms, please contact us:</p>
        <ul>
            <li>Email: <a href="mailto:legal@docsmind.app">legal@docsmind.app</a></li>
            <li>Support: <a href="{{ route('support') }}">{{ route('support') }}</a></li>
        </ul>
    </div>
</section>
@endsection
