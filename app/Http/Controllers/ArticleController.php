<?php

namespace App\Http\Controllers;

use App\Support\ArticleRepository;
use Illuminate\Http\Response;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function index(ArticleRepository $articles): View
    {
        return view('pages.articles.index', [
            'articles' => $articles->all(),
        ]);
    }

    public function show(string $slug, ArticleRepository $articles): View|Response
    {
        $article = $articles->findBySlug($slug);

        if ($article === null) {
            abort(404);
        }

        return view('pages.articles.show', [
            'article' => $article,
        ]);
    }
}
