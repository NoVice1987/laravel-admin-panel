<?php

namespace StatisticLv\AdminPanel\Http\Controllers\Frontend;

use Illuminate\Routing\Controller;
use StatisticLv\AdminPanel\Models\News;
use StatisticLv\AdminPanel\Models\Menu;

class HomeController extends Controller
{
    public function index()
    {
        $latestNews = News::published()
            ->with('author')
            ->orderBy('published_at', 'desc')
            ->take(6)
            ->get();

        $mainMenu = Menu::where('location', 'main')
            ->where('is_active', true)
            ->with('items.children')
            ->first();

        return view('admin-panel::frontend.home', compact('latestNews', 'mainMenu'));
    }
}
