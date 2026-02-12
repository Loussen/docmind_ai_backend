@extends('layouts.app')

@section('title', 'Privacy Policy - DocMind AI | How We Protect Your Data')
@section('description', 'DocMind AI Privacy Policy. Learn how we collect, use, and protect your personal information and documents. GDPR & CCPA compliant. Your data is encrypted and secure.')
@section('keywords', 'DocMind AI privacy policy, data protection, document security, AI app privacy, GDPR, CCPA, data encryption')

@section('structured_data')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebPage",
    "name": "Privacy Policy - DocMind AI",
    "description": "Privacy Policy for DocMind AI document summarization app",
    "url": "{{ url('/privacy') }}",
    "isPartOf": {
        "@type": "WebSite",
        "name": "DocMind AI",
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
                "name": "Privacy Policy"
            }
        ]
    }
}
</script>
@endsection

@section('content')
<section class="legal-hero">
    <div class="container">
        <h1>Privacy Policy</h1>
        <p>Last updated: {{ date('F d, Y') }}</p>
    </div>
</section>

<section class="legal-content">
    <div class="container">
        <p>Welcome to DocMind AI ("we," "our," or "us"). This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you use our mobile application and related services (collectively, the "Service").</p>
        
        <h2>1. Information We Collect</h2>
        
        <h3>1.1 Personal Information</h3>
        <p>We may collect personal information that you voluntarily provide when using our Service, including:</p>
        <ul>
            <li><strong>Account Information:</strong> Email address, name, and password when you create an account</li>
            <li><strong>Apple Sign-In Data:</strong> If you use Sign in with Apple, we receive your Apple ID, email address (if shared), and name</li>
            <li><strong>Payment Information:</strong> When you subscribe to our premium services, payment processing is handled by Apple through the App Store</li>
        </ul>
        
        <h3>1.2 Document Data</h3>
        <p>When you upload documents for processing:</p>
        <ul>
            <li>Document files (PDF, DOCX, images) are temporarily stored for processing</li>
            <li>Extracted text and generated summaries are stored in your account</li>
            <li>We do not read or access your document content for any purpose other than providing the summarization service</li>
        </ul>
        
        <h3>1.3 Automatically Collected Information</h3>
        <p>When you use our Service, we automatically collect:</p>
        <ul>
            <li>Device information (device type, operating system)</li>
            <li>App usage data (features used, session duration)</li>
            <li>Error logs and diagnostic data</li>
        </ul>
        
        <h2>2. How We Use Your Information</h2>
        <p>We use the collected information for:</p>
        <ul>
            <li>Providing and maintaining the Service</li>
            <li>Processing your document uploads and generating summaries</li>
            <li>Managing your account and subscription</li>
            <li>Sending service-related notifications</li>
            <li>Improving and optimizing our Service</li>
            <li>Responding to your inquiries and support requests</li>
            <li>Preventing fraud and ensuring security</li>
        </ul>
        
        <h2>3. AI Processing and Data</h2>
        
        <h3>3.1 OpenAI Integration</h3>
        <p>We use OpenAI's API to process and summarize your documents. When you upload a document:</p>
        <ul>
            <li>The extracted text is sent to OpenAI for analysis</li>
            <li>OpenAI processes the text to generate summaries</li>
            <li>OpenAI's use of data is governed by their <a href="https://openai.com/privacy" target="_blank">Privacy Policy</a></li>
            <li>We do not use your documents to train AI models</li>
        </ul>
        
        <h2>4. Data Retention</h2>
        <p>We retain your information as follows:</p>
        <ul>
            <li><strong>Account Data:</strong> Until you delete your account</li>
            <li><strong>Documents:</strong> Processed documents may be deleted after 30 days; summaries are retained in your account</li>
            <li><strong>Usage Data:</strong> Aggregated and anonymized data may be retained indefinitely</li>
        </ul>
        
        <h2>5. Data Sharing and Disclosure</h2>
        <p>We do not sell your personal information. We may share your information with:</p>
        <ul>
            <li><strong>Service Providers:</strong> Third-party services that help us operate (e.g., cloud hosting, AI processing)</li>
            <li><strong>Legal Requirements:</strong> When required by law or to protect our rights</li>
            <li><strong>Business Transfers:</strong> In connection with a merger, acquisition, or sale of assets</li>
        </ul>
        
        <h2>6. Data Security</h2>
        <p>We implement appropriate security measures including:</p>
        <ul>
            <li>Encryption of data in transit and at rest</li>
            <li>Secure authentication mechanisms</li>
            <li>Regular security assessments</li>
            <li>Access controls and monitoring</li>
        </ul>
        <p>However, no method of transmission over the Internet is 100% secure, and we cannot guarantee absolute security.</p>
        
        <h2>7. Your Rights and Choices</h2>
        <p>You have the right to:</p>
        <ul>
            <li><strong>Access:</strong> Request a copy of your personal data</li>
            <li><strong>Correction:</strong> Update or correct inaccurate information</li>
            <li><strong>Deletion:</strong> Request deletion of your account and data</li>
            <li><strong>Data Portability:</strong> Receive your data in a portable format</li>
            <li><strong>Opt-out:</strong> Unsubscribe from marketing communications</li>
        </ul>
        <p>To exercise these rights, contact us at <a href="mailto:privacy@docsmind.app">privacy@docsmind.app</a>.</p>
        
        <h2>8. Children's Privacy</h2>
        <p>Our Service is not intended for children under 13 years of age. We do not knowingly collect personal information from children under 13. If you become aware that a child has provided us with personal information, please contact us.</p>
        
        <h2>9. International Data Transfers</h2>
        <p>Your information may be transferred to and processed in countries other than your country of residence. These countries may have different data protection laws. We ensure appropriate safeguards are in place for international transfers.</p>
        
        <h2>10. Third-Party Links</h2>
        <p>Our Service may contain links to third-party websites or services. We are not responsible for the privacy practices of these third parties. We encourage you to review their privacy policies.</p>
        
        <h2>11. Changes to This Privacy Policy</h2>
        <p>We may update this Privacy Policy from time to time. We will notify you of any changes by posting the new Privacy Policy on this page and updating the "Last updated" date. Your continued use of the Service after changes constitutes acceptance of the updated policy.</p>
        
        <h2>12. Contact Us</h2>
        <p>If you have questions about this Privacy Policy or our privacy practices, please contact us:</p>
        <ul>
            <li>Email: <a href="mailto:privacy@docsmind.app">privacy@docsmind.app</a></li>
            <li>Support: <a href="{{ route('support') }}">{{ route('support') }}</a></li>
        </ul>
        
        <h2>13. California Privacy Rights (CCPA)</h2>
        <p>If you are a California resident, you have additional rights under the California Consumer Privacy Act (CCPA):</p>
        <ul>
            <li>Right to know what personal information is collected</li>
            <li>Right to know if personal information is sold or disclosed</li>
            <li>Right to opt-out of the sale of personal information</li>
            <li>Right to non-discrimination for exercising privacy rights</li>
        </ul>
        <p>We do not sell personal information to third parties.</p>
        
        <h2>14. European Privacy Rights (GDPR)</h2>
        <p>If you are in the European Economic Area (EEA), you have rights under the General Data Protection Regulation (GDPR):</p>
        <ul>
            <li>Right to access your personal data</li>
            <li>Right to rectification of inaccurate data</li>
            <li>Right to erasure ("right to be forgotten")</li>
            <li>Right to restrict processing</li>
            <li>Right to data portability</li>
            <li>Right to object to processing</li>
            <li>Right to withdraw consent</li>
        </ul>
        <p>Our legal basis for processing personal data includes consent, contractual necessity, and legitimate interests.</p>
    </div>
</section>
@endsection
