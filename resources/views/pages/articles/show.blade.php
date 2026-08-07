@extends('layouts.app')

@php
    $coverUrl = !empty($article['image'])
        ? asset($article['image'])
        : asset('assets/images/og-image.png');
@endphp

@section('title', $article['title'] . ' | DoCMind AI')
@section('description', $article['meta_description'])
@section('keywords', $article['keywords'])
@section('og_type', 'article')
@section('og_image', $coverUrl)

@push('head')
<meta property="article:published_time" content="{{ $article['published_at'] }}T00:00:00+00:00">
<meta property="article:modified_time" content="{{ ($article['updated_at'] ?? $article['published_at']) }}T00:00:00+00:00">
<meta property="article:author" content="DoCMind AI">
<meta property="article:section" content="{{ $article['category'] ?? 'Document Summarization' }}">
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
    "image": {!! json_encode([$coverUrl]) !!},
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
            @if(!empty($article['image']))
            <figure class="article-cover">
                <img
                    src="{{ $coverUrl }}"
                    alt="{{ $article['title'] }}"
                    width="1200"
                    height="675"
                    loading="eager"
                    decoding="async"
                    itemprop="image"
                >
            </figure>
            @endif

            {!! $article['body'] !!}

            <div class="article-cta-box">
                <h2>Try it on an actual file</h2>
                <p>If you use an iPhone and most documents hit your Mail or Files app first, DoCMind AI is the mobile path — PDF, Word, or a photo of a page. Free to start; no account required.</p>
                <a href="https://apps.apple.com/app/id6757693350" class="btn btn-primary" style="color: #fff;" target="_blank" rel="noopener">Open DoCMind AI on the App Store</a>
            </div>
        </div>
    </section>
</article>
@endsection
