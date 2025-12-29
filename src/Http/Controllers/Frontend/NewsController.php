<?php

namespace StatisticLv\AdminPanel\Http\Controllers\Frontend;

use Illuminate\Routing\Controller;
use StatisticLv\AdminPanel\Models\News;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::published()
            ->with('author')
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        return view('admin-panel::frontend.news.index', compact('news'));
    }

    public function show($slug)
    {
        $article = News::where('slug', $slug)
            ->published()
            ->with('author')
            ->firstOrFail();

        $article->incrementViews();

        $relatedNews = News::published()
            ->where('id', '!=', $article->id)
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();

        return view('admin-panel::frontend.news.show', compact('article', 'relatedNews'));
    }
}
