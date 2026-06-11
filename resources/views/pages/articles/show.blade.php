@extends('layouts.app')

@section('title', $article['title'] . ' | DoCMind AI')
@section('description', $article['meta_description'])
@section('keywords', $article['keywords'])
@section('og_type', 'article')

@push('head')
<meta property="article:published_time" content="{{ $article['published_at'] }}T00:00:00+00:00">
<meta property="article:modified_time" content="{{ ($article['updated_at'] ?? $article['published_at']) }}T00:00:00+00:00">
<meta property="article:author" content="DoCMind AI">
<meta property="article:section" content="Document Summarization">
@endpush

@section('structured_data')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "BlogPosting",
    "headline": {!! json_encode($article['title']) !!},
    "description": {!! json_encode($article['meta_description']) !!},
    "url": "{{ url('/articles/' . $article['slug']) }}",
    "datePublished": "{{ $article['published_at'] }}",
    "dateModified": "{{ $article['updated_at'] ?? $article['published_at'] }}",
    "author": {
        "@type": "Organization",
        "name": "DoCMind AI",
        "url": "{{ url('/') }}"
    },
    "publisher": {
        "@type": "Organization",
        "name": "DoCMind AI",
        "logo": {
            "@type": "ImageObject",
            "url": "{{ asset('assets/images/app-icon.png') }}"
        }
    },
    "image": "{{ asset('assets/images/og-image.png') }}",
    "mainEntityOfPage": {
        "@type": "WebPage",
        "@id": "{{ url('/articles/' . $article['slug']) }}"
    },
    "wordCount": {{ str_word_count(strip_tags($article['body'])) }},
    "timeRequired": "PT{{ $article['reading_time'] }}M"
}
</script>
<script type="application/ld+json">
{
    "@context": "https://schema.org",
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
            "name": "Articles",
            "item": "{{ url('/articles') }}"
        },
        {
            "@type": "ListItem",
            "position": 3,
            "name": {!! json_encode($article['title']) !!}
        }
    ]
}
</script>
@endsection

@section('content')
<article itemscope itemtype="https://schema.org/BlogPosting">
    <section class="legal-hero article-hero">
        <div class="container">
            <nav class="breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span aria-hidden="true">/</span>
                <a href="{{ route('articles.index') }}">Articles</a>
            </nav>
            @if(!empty($article['category']))
            <span class="article-tag article-tag--hero">{{ $article['category'] }}</span>
            @endif
            <h1 itemprop="headline">{{ $article['title'] }}</h1>
            <div class="article-meta">
                <time datetime="{{ $article['published_at'] }}" itemprop="datePublished">{{ \Carbon\Carbon::parse($article['published_at'])->format('F j, Y') }}</time>
                <span aria-hidden="true">&middot;</span>
                <span>{{ $article['reading_time'] }} min read</span>
            </div>
        </div>
    </section>

    <section class="legal-content article-body" itemprop="articleBody">
        <div class="container">
            {!! $article['body'] !!}

            <div class="article-cta-box">
                <h2>Ready to summarize your documents?</h2>
                <p>Download DoCMind AI free on the App Store — upload PDFs, Word docs, and images for instant AI summaries.</p>
                <a href="https://apps.apple.com/app/id6757693350" class="btn btn-primary" style="color: #fff;" target="_blank" rel="noopener">Get DoCMind AI Free</a>
            </div>
        </div>
    </section>
</article>
@endsection
