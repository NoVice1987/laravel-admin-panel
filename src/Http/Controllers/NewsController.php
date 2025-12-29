<?php

namespace StatisticLv\AdminPanel\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use StatisticLv\AdminPanel\Models\News;

class NewsController extends Controller
{
    /**
     * Display a listing of news articles.
     *
     * @return \Illuminate\View\View
     */
    public function index(): \Illuminate\View\View
    {
        $news = News::with('author')
            ->orderBy('created_at', 'desc')
            ->paginate(config('admin-panel.per_page', 15));

        return view('admin-panel::news.index', compact('news'));
    }

    /**
     * Show the form for creating a new news article.
     *
     * @return \Illuminate\View\View
     */
    public function create(): \Illuminate\View\View
    {
        return view('admin-panel::news.create');
    }

    /**
     * Store a newly created news article in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'slug' => 'nullable|unique:news,slug',
            'excerpt' => 'nullable|max:500',
            'content' => 'required',
            'featured_image' => 'nullable|url|max:2048',
            'status' => 'required|in:draft,published,archived',
            'published_at' => 'nullable|date',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        $validated['author_id'] = auth()->guard('admin')->id();

        $news = News::create($validated);
        
        // Clear news cache
        Cache::forget('news.published.all');
        Cache::forget('news.latest');
        Cache::forget('news.popular');
        
        Log::info('News article created', [
            'news_id' => $news->id,
            'title' => $news->title,
            'user_id' => auth()->guard('admin')->id(),
        ]);

        return redirect()
            ->route('admin.news.edit', $news)
            ->with('success', 'News created successfully.');
    }

    /**
     * Show the form for editing the specified news article.
     *
     * @param \StatisticLv\AdminPanel\Models\News $news
     * @return \Illuminate\View\View
     */
    public function edit(News $news): \Illuminate\View\View
    {
        return view('admin-panel::news.edit', compact('news'));
    }

    /**
     * Update the specified news article in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \StatisticLv\AdminPanel\Models\News $news
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, News $news): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'slug' => 'nullable|unique:news,slug,' . $news->id,
            'excerpt' => 'nullable|max:500',
            'content' => 'required',
            'featured_image' => 'nullable|url|max:2048',
            'status' => 'required|in:draft,published,archived',
            'published_at' => 'nullable|date',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        $news->update($validated);
        
        // Clear news cache
        Cache::forget('news.published.all');
        Cache::forget('news.latest');
        Cache::forget('news.popular');
        Cache::forget("news.slug.{$news->slug}");
        
        Log::info('News article updated', [
            'news_id' => $news->id,
            'title' => $news->title,
            'user_id' => auth()->guard('admin')->id(),
        ]);

        return redirect()
            ->route('admin.news.edit', $news)
            ->with('success', 'News updated successfully.');
    }

    /**
     * Remove the specified news article from storage.
     *
     * @param \StatisticLv\AdminPanel\Models\News $news
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(News $news): \Illuminate\Http\RedirectResponse
    {
        // Authorization check - only super admins or the author can delete
        $user = auth()->guard('admin')->user();
        if (!$user->isSuperAdmin() && $news->author_id != $user->id) {
            Log::warning('Unauthorized news deletion attempt', [
                'news_id' => $news->id,
                'user_id' => $user->id,
            ]);
            
            abort(403, 'You are not authorized to delete this news article.');
        }
        
        $news->delete();
        
        // Clear news cache
        Cache::forget('news.published.all');
        Cache::forget('news.latest');
        Cache::forget('news.popular');
        Cache::forget("news.slug.{$news->slug}");
        
        Log::info('News article deleted', [
            'news_id' => $news->id,
            'title' => $news->title,
            'user_id' => $user->id,
        ]);

        return redirect()
            ->route('admin.news.index')
            ->with('success', 'News deleted successfully.');
    }
}
