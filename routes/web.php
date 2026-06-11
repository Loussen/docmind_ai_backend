<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\SitemapController;
use App\Support\ArticleRepository;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function (ArticleRepository $articles) {
    return view('pages.landing', [
        'recentArticles' => $articles->recent(3),
    ]);
})->name('home');

Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/articles/{slug}', [ArticleController::class, 'show'])->name('articles.show');

Route::get('/privacy', function () {
    return view('pages.privacy');
})->name('privacy');

Route::get('/terms', function () {
    return view('pages.terms');
})->name('terms');

Route::get('/support', function () {
    return view('pages.support');
})->name('support');

Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');
Route::get('/feed.xml', FeedController::class)->name('feed');
