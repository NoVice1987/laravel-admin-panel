<?php

namespace StatisticLv\AdminPanel\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use StatisticLv\AdminPanel\Models\News;
use StatisticLv\AdminPanel\Models\AdminUser;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index(): \Illuminate\View\View
    {
        $stats = Cache::remember('dashboard.stats', 300, function () {
            return [
                'total_news' => News::count(),
                'published_news' => News::published()->count(),
                'draft_news' => News::draft()->count(),
                'total_admins' => AdminUser::count(),
                'recent_news' => News::with('author')
                    ->orderBy('created_at', 'desc')
                    ->take(5)
                    ->get(),
            ];
        });

        return view('admin-panel::dashboard.index', compact('stats'));
    }
}
