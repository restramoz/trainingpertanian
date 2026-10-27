<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Services\RegionalService;
use Illuminate\View\View;

class PublicArticleController extends Controller
{
    /**
     * Display public index of published articles.
     */
    public function index(): View
    {
        $articles = Article::published()
            ->latest('published_at')
            ->paginate(12);

        return view('articles.index', compact('articles'));
    }

    /**
     * Display single published article.
     */
    public function show(string $slug, RegionalService $regionalService): View
    {
        $article = Article::published()
            ->where('slug', $slug)
            ->firstOrFail();

        $targetCity = null;
        if ($article->target_city_slug) {
            $targetCity = $regionalService->findCity($article->target_city_slug);
        }

        $relatedArticles = Article::published()
            ->where('id', '!=', $article->id)
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('articles.show', compact('article', 'targetCity', 'relatedArticles'));
    }
}
