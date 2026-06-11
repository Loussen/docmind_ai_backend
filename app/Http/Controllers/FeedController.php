<?php

namespace App\Http\Controllers;

use App\Support\ArticleRepository;
use Illuminate\Http\Response;

class FeedController extends Controller
{
    public function __invoke(ArticleRepository $articles): Response
    {
        $xml = view('feed', [
            'articles' => $articles->all(),
            'siteUrl' => rtrim(config('app.url'), '/'),
            'siteName' => config('app.name'),
        ])->render();

        return response($xml, 200, [
            'Content-Type' => 'application/rss+xml; charset=UTF-8',
        ]);
    }
}
