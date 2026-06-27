@extends('layouts.app')

@section('title', 'Privacy Policy - DoCMind AI | How We Protect Your Data')
@section('description', 'DoCMind AI Privacy Policy. Learn how we collect, use, and protect your data. No account required — device-based identification. GDPR & CCPA compliant.')
@section('keywords', 'DoCMind AI privacy policy, data protection, document security, AI app privacy, GDPR, CCPA, data encryption')

@section('structured_data')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebPage",
    "name": "Privacy Policy - DoCMind AI",
    "description": "Privacy Policy for DoCMind AI document summarization app",
    "url": "{{ url('/privacy') }}",
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
        <p>Welcome to DoCMind AI ("we," "our," or "us"). This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you use our mobile application and related services (collectively, the "Service").</p>

        <h2>1. Information We Collect</h2>

        <p>DoCMind AI does <strong>not</strong> require you to create an account. There is no login, registration, email, or password in the app. We identify your app installation with an anonymous <strong>device ID</strong> generated on first launch.</p>

        <h3>1.1 Device Information</h3>
        <p>When you use the app, we automatically collect:</p>
        <ul>
            <li><strong>Device ID:</strong> A unique identifier stored on your device (iOS Identifier for Vendor or a generated UUID) used to associate your documents, settings, and subscription with your installation</li>
            <li><strong>Device type and OS version:</strong> e.g. iPhone model and iOS version, for compatibility and support</li>
        </ul>

        <h3>1.2 Document Data</h3>
        <p>When you upload documents for processing:</p>
        <ul>
            <li>Document files (PDF, DOCX, images) are stored on our servers for processing</li>
            <li>Extracted text and generated summaries are stored and linked to your device ID</li>
            <li>We process document content only to provide the summarization service</li>
            <li>We do not sell or share your document content with advertisers</li>
        </ul>

        <h3>1.3 Subscription Information</h3>
        <p>When you purchase Pro or Pro+:</p>
        <ul>
            <li>Payment is processed entirely by Apple through the App Store — we never receive your payment card details</li>
            <li>We receive subscription status and transaction identifiers from Apple to activate your plan</li>
        </ul>

        <h3>1.4 Analytics and Advertising Measurement</h3>
        <p>We use the TikTok Events SDK to measure app installs and subscription conversions. This may include:</p>
        <ul>
            <li>Device ID and anonymous app interaction events (e.g. app launch, subscription purchase)</li>
            <li>Apple's Advertising Identifier (IDFA) <strong>only if you grant permission</strong> via the App Tracking Transparency prompt</li>
        </ul>
        <p><strong>TikTok does not receive your document content or summaries.</strong> TikTok's use of data is governed by their <a href="https://www.tiktok.com/legal/page/global/privacy-policy/en" target="_blank" rel="noopener">Privacy Policy</a>.</p>

        <h3>1.5 Diagnostics</h3>
        <p>We may collect error logs and diagnostic data to improve app stability and fix bugs.</p>

        <h2>2. How We Use Your Information</h2>
        <p>We use the collected information for:</p>
        <ul>
            <li>Providing document upload, AI summarization, and OCR services</li>
            <li>Managing your subscription and usage limits</li>
            <li>Storing your document history on our servers</li>
            <li>Measuring ad campaign performance (via TikTok, with your consent for IDFA)</li>
            <li>Improving app stability and user experience</li>
            <li>Preventing fraud and ensuring security</li>
        </ul>

        <h2>3. AI Processing and Data</h2>

        <h3>3.1 OpenAI Integration</h3>
        <p>We use OpenAI's API to process and summarize your documents. When you upload a document:</p>
        <ul>
            <li>The extracted text is sent to OpenAI for analysis</li>
            <li>OpenAI processes the text to generate summaries</li>
            <li>OpenAI's use of data is governed by their <a href="https://openai.com/privacy" target="_blank" rel="noopener">Privacy Policy</a></li>
            <li>We do not use your documents to train AI models</li>
        </ul>

        <h2>4. Data Retention</h2>
        <p>We retain your information as follows:</p>
        <ul>
            <li><strong>Device data, documents, and summaries:</strong> Until you delete them via Settings → Delete Account in the app</li>
            <li><strong>Subscription records:</strong> As required for billing and legal compliance</li>
            <li><strong>Aggregated analytics:</strong> May be retained in anonymized form</li>
        </ul>

        <h2>5. Data Sharing and Disclosure</h2>
        <p>We do not sell your personal information. We may share data with:</p>
        <ul>
            <li><strong>OpenAI:</strong> Document text for AI summarization (see Section 3)</li>
            <li><strong>TikTok:</strong> Anonymous device and conversion events for ad measurement — not document content</li>
            <li><strong>Cloud hosting providers:</strong> To store and process your data securely</li>
            <li><strong>Legal requirements:</strong> When required by law or to protect our rights</li>
        </ul>

        <h2>6. Deleting Your Data</h2>
        <p>You can delete all your data at any time:</p>
        <ol>
            <li>Open DoCMind AI</li>
            <li>Go to <strong>Settings</strong></li>
            <li>Tap <strong>Delete Account</strong></li>
            <li>Confirm deletion</li>
        </ol>
        <p>This permanently removes your documents, summaries, and usage data from our servers for your device. This action cannot be undone.</p>

        <h2>7. Data Security</h2>
        <p>We implement appropriate security measures including:</p>
        <ul>
            <li>Encryption of data in transit and at rest</li>
            <li>Secure device identification</li>
            <li>Regular security assessments</li>
            <li>Access controls and monitoring</li>
        </ul>
        <p>However, no method of transmission over the Internet is 100% secure, and we cannot guarantee absolute security.</p>

        <h2>8. Your Rights and Choices</h2>
        <p>You have the right to:</p>
        <ul>
            <li><strong>Access:</strong> Request a copy of your personal data</li>
            <li><strong>Correction:</strong> Update or correct inaccurate information</li>
            <li><strong>Deletion:</strong> Delete all your data via Settings → Delete Account in the app</li>
            <li><strong>Opt-out of tracking:</strong> Deny the App Tracking Transparency prompt on iOS</li>
        </ul>
        <p>To exercise these rights, contact us at <a href="mailto:privacy@docsmind.app">privacy@docsmind.app</a>.</p>

        <h2>9. Children's Privacy</h2>
        <p>Our Service is not intended for children under 13 years of age. We do not knowingly collect personal information from children under 13. If you become aware that a child has provided us with personal information, please contact us.</p>

        <h2>10. International Data Transfers</h2>
        <p>Your information may be transferred to and processed in countries other than your country of residence. These countries may have different data protection laws. We ensure appropriate safeguards are in place for international transfers.</p>

        <h2>11. Third-Party Links</h2>
        <p>Our Service may contain links to third-party websites or services. We are not responsible for the privacy practices of these third parties. We encourage you to review their privacy policies.</p>

        <h2>12. Changes to This Privacy Policy</h2>
        <p>We may update this Privacy Policy from time to time. We will notify you of any changes by posting the new Privacy Policy on this page and updating the "Last updated" date. Your continued use of the Service after changes constitutes acceptance of the updated policy.</p>

        <h2>13. Contact Us</h2>
        <p>If you have questions about this Privacy Policy or our privacy practices, please contact us:</p>
        <ul>
            <li>Email: <a href="mailto:privacy@docsmind.app">privacy@docsmind.app</a></li>
            <li>Support: <a href="{{ route('support') }}">{{ route('support') }}</a></li>
        </ul>

        <h2>14. California Privacy Rights (CCPA)</h2>
        <p>If you are a California resident, you have additional rights under the California Consumer Privacy Act (CCPA):</p>
        <ul>
            <li>Right to know what personal information is collected</li>
            <li>Right to know if personal information is sold or disclosed</li>
            <li>Right to opt-out of the sale of personal information</li>
            <li>Right to non-discrimination for exercising privacy rights</li>
        </ul>
        <p>We do not sell personal information to third parties.</p>

        <h2>15. European Privacy Rights (GDPR)</h2>
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
