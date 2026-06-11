<?php

namespace App\Http\Controllers;

use App\Support\ArticleRepository;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(ArticleRepository $articles): Response
    {
        $baseUrl = rtrim(config('app.url'), '/');

        $staticPages = [
            ['loc' => $baseUrl . '/', 'lastmod' => '2026-06-01', 'changefreq' => 'weekly', 'priority' => '1.0'],
            ['loc' => $baseUrl . '/articles', 'lastmod' => '2026-06-10', 'changefreq' => 'weekly', 'priority' => '0.9'],
            ['loc' => $baseUrl . '/support', 'lastmod' => '2026-02-09', 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['loc' => $baseUrl . '/privacy', 'lastmod' => '2026-02-09', 'changefreq' => 'yearly', 'priority' => '0.5'],
            ['loc' => $baseUrl . '/terms', 'lastmod' => '2026-02-09', 'changefreq' => 'yearly', 'priority' => '0.5'],
        ];

        $articlePages = $articles->all()->map(fn (array $article) => [
            'loc' => $baseUrl . '/articles/' . $article['slug'],
            'lastmod' => $article['updated_at'] ?? $article['published_at'],
            'changefreq' => 'monthly',
            'priority' => '0.8',
        ])->all();

        $urls = array_merge($staticPages, $articlePages);

        $xml = view('sitemap', ['urls' => $urls])->render();

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
        ]);
    }
}
