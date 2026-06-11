<?php

namespace App\Http\Controllers;

use App\Support\ArticleRepository;
use Illuminate\Http\Response;

class LlmsTxtController extends Controller
{
    public function __invoke(ArticleRepository $articles): Response
    {
        $content = view('llms', [
            'baseUrl' => rtrim(config('app.url'), '/'),
            'articles' => $articles->all(),
        ])->render();

        return response($content, 200, [
            'Content-Type' => 'text/plain; charset=UTF-8',
        ]);
    }
}
