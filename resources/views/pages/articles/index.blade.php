@extends('layouts.app')

@section('title', 'Articles & Guides - DoCMind AI | AI Document Summarization Tips')
@section('description', 'Read expert guides on AI document summarization, PDF tips for students, OCR workflows, and how to read long reports faster. Tips from the DoCMind AI team.')
@section('keywords', 'AI document summarization articles, PDF summarizer guide, OCR tips, study with AI, document productivity, DoCMind AI blog')

@section('structured_data')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "CollectionPage",
    "name": "Articles & Guides - DoCMind AI",
    "description": "AI document summarization tips and guides",
    "url": "{{ url('/articles') }}",
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
                "name": "Articles"
            }
        ]
    }
}
</script>
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "ItemList",
    "name": "DoCMind AI Articles",
    "itemListElement": [
        @foreach ($articles as $index => $article)
        {
            "@type": "ListItem",
            "position": {{ $index + 1 }},
            "url": "{{ url('/articles/' . $article['slug']) }}",
            "name": {!! json_encode($article['title']) !!}
        }@if (!$loop->last),@endif
        @endforeach
    ]
}
</script>
@endsection

@section('content')
<section class="legal-hero">
    <div class="container">
        <h1>Articles &amp; Guides</h1>
        <p>Tips on AI document summarization, productivity, and getting more from your PDFs and images.</p>
    </div>
</section>

<section class="articles-content" aria-label="Article list">
    <div class="container">
        <div class="articles-grid">
            @foreach ($articles as $article)
            <article class="article-card">
                <div class="article-card-meta">
                    <time datetime="{{ $article['published_at'] }}">{{ \Carbon\Carbon::parse($article['published_at'])->format('M j, Y') }}</time>
                    <span aria-hidden="true">&middot;</span>
                    <span>{{ $article['reading_time'] }} min read</span>
                </div>
                <h2>
                    <a href="{{ route('articles.show', $article['slug']) }}">{{ $article['title'] }}</a>
                </h2>
                <p>{{ $article['excerpt'] }}</p>
                <a href="{{ route('articles.show', $article['slug']) }}" class="article-read-more">
                    Read article
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </a>
            </article>
            @endforeach
        </div>
    </div>
</section>

<section class="cta cta-compact" aria-labelledby="articles-cta-heading">
    <div class="container">
        <h2 id="articles-cta-heading">Try AI Summarization Free</h2>
        <p>Put these tips into practice — download DoCMind AI and summarize your first document in seconds.</p>
        <a href="https://apps.apple.com/app/id6757693350" class="btn btn-primary" target="_blank" rel="noopener">Download on App Store</a>
    </div>
</section>
@endsection
