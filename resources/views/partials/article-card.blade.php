@php
    $category = $article['category'] ?? 'Guides';
    $categorySlug = \Illuminate\Support\Str::slug($category);
    $cover = !empty($article['image']) ? asset($article['image']) : null;
@endphp

<article class="article-card">
    <a href="{{ route('articles.show', $article['slug']) }}" class="article-card-link" aria-label="Read: {{ $article['title'] }}">
        <div class="article-card-visual article-card-visual--{{ $categorySlug }}@if($cover) article-card-visual--photo@endif">
            @if($cover)
                <img
                    class="article-card-cover"
                    src="{{ $cover }}"
                    alt=""
                    width="700"
                    height="400"
                    loading="lazy"
                    decoding="async"
                >
                <div class="article-card-cover-shade" aria-hidden="true"></div>
            @else
                <div class="article-card-icon" aria-hidden="true">
                    @include('partials.article-icon', ['category' => $categorySlug])
                </div>
            @endif
            <span class="article-tag">{{ $category }}</span>
        </div>
        <div class="article-card-body">
            <div class="article-card-meta">
                <time datetime="{{ $article['published_at'] }}">{{ \Carbon\Carbon::parse($article['published_at'])->format('M j, Y') }}</time>
                <span aria-hidden="true">&middot;</span>
                <span>{{ $article['reading_time'] }} min read</span>
            </div>
            <h2 class="article-title">{{ $article['title'] }}</h2>
            <p>{{ $article['excerpt'] }}</p>
            <span class="article-read-more">
                Read article
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path d="M5 12h14M12 5l7 7-7 7"/>
                </svg>
            </span>
        </div>
    </a>
</article>
