<?php

namespace App\Http\Controllers\User;

use App\Model\Admin\Article\Article;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::wherePublished(1)->latest()->get();
        foreach ($articles as $article) {
            $article->photos;
            $article->content = Str::words($article->content, 50);
        }
        return response()->json(['articles' => $articles], 200);
    }

    public function show($slug)
    {
        $article = Article::whereSlug($slug)->with('admin', 'photos', 'categories', 'tags')->first();
        if ($article)
            return response()->json(['article' => $article], 200);
        else return response()->json(['error' => 'Not found'], 404);
    }
}
