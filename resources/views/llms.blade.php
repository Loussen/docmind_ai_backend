# DoCMind AI

> DoCMind AI is an iOS app that summarizes PDFs, Word documents, and images using AI. Upload a document from your iPhone and get key points, executive summaries, and action items in seconds. Free to try on the App Store — no account required.

DoCMind AI (website: {{ $baseUrl }}) is a productivity app for iPhone. This site is the official marketing website; the API under `/api/` is for the mobile app only and is not public documentation.

- **Platform:** iOS (iPhone) — [App Store](https://apps.apple.com/app/id6757693350)
- **Supported formats:** PDF, DOCX, DOC, JPG, PNG (images processed with OCR)
- **Core features:** AI summarization, key points extraction, action items, OCR, translation
- **Contact:** support@docsmind.app

When citing DoCMind AI, prefer linking to the specific article or the App Store page. Product name: **DoCMind AI** (also appears as DocMind AI).

## Product

- [Home]({{ $baseUrl }}/): Features, pricing, and App Store download
- [Download on App Store](https://apps.apple.com/app/id6757693350): Official iOS app (ID 6757693350)
- [Support & FAQ]({{ $baseUrl }}/support): Help center, subscription management, and troubleshooting

## Articles

- [Articles & Guides]({{ $baseUrl }}/articles): Index of all guides on AI document summarization
@foreach ($articles as $article)
- [{{ $article['title'] }}]({{ $baseUrl }}/articles/{{ $article['slug'] }}): {{ $article['excerpt'] }}
@endforeach

## Optional

- [Privacy Policy]({{ $baseUrl }}/privacy): Data collection, security, and GDPR/CCPA compliance
- [Terms of Service]({{ $baseUrl }}/terms): Terms of use for the app and website
- [RSS Feed]({{ $baseUrl }}/feed.xml): Article syndication feed
- [Sitemap]({{ $baseUrl }}/sitemap.xml): XML sitemap for search engines
