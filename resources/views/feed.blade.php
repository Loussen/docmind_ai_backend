{!! '<'.'?xml version="1.0" encoding="UTF-8"?>' !!}
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
    <channel>
        <title>{{ $siteName }} Articles</title>
        <link>{{ $siteUrl }}/articles</link>
        <description>AI document summarization tips, guides, and productivity insights from {{ $siteName }}.</description>
        <language>en-us</language>
        <atom:link href="{{ $siteUrl }}/feed.xml" rel="self" type="application/rss+xml"/>
        @foreach ($articles as $article)
        <item>
            <title>{{ $article['title'] }}</title>
            <link>{{ $siteUrl }}/articles/{{ $article['slug'] }}</link>
            <guid isPermaLink="true">{{ $siteUrl }}/articles/{{ $article['slug'] }}</guid>
            <pubDate>{{ \Carbon\Carbon::parse($article['published_at'])->toRfc2822String() }}</pubDate>
            <description><![CDATA[{{ $article['excerpt'] }}]]></description>
        </item>
        @endforeach
    </channel>
</rss>
