<?php

namespace StatisticLv\AdminPanel\Http\Controllers\Frontend;

use Illuminate\Routing\Controller;
use StatisticLv\AdminPanel\Models\Page;

class PageController extends Controller
{
    public function show($slug)
    {
        $page = Page::where('slug', $slug)
            ->published()
            ->with('author')
            ->firstOrFail();

        $template = 'admin-panel::frontend.pages.' . $page->template;
        
        // Fallback to default template if specific one doesn't exist
        if (!view()->exists($template)) {
            $template = 'admin-panel::frontend.pages.default';
        }

        return view($template, compact('page'));
    }
}
