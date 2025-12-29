<?php

namespace StatisticLv\AdminPanel\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use StatisticLv\AdminPanel\Models\Page;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::with('author')
            ->orderBy('order')
            ->orderBy('created_at', 'desc')
            ->paginate(config('admin-panel.per_page', 15));

        return view('admin-panel::pages.index', compact('pages'));
    }

    public function create()
    {
        $templates = $this->getAvailableTemplates();
        return view('admin-panel::pages.create', compact('templates'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'slug' => 'nullable|unique:pages,slug',
            'content' => 'required',
            'excerpt' => 'nullable',
            'template' => 'required|string',
            'meta_title' => 'nullable|max:255',
            'meta_description' => 'nullable',
            'meta_keywords' => 'nullable',
            'is_published' => 'boolean',
            'order' => 'nullable|integer',
        ]);

        $validated['author_id'] = auth()->guard('admin')->id();
        $validated['order'] = $validated['order'] ?? 0;

        $page = Page::create($validated);

        return redirect()
            ->route('admin.pages.edit', $page)
            ->with('success', 'Page created successfully.');
    }

    public function edit(Page $page)
    {
        $templates = $this->getAvailableTemplates();
        return view('admin-panel::pages.edit', compact('page', 'templates'));
    }

    public function update(Request $request, Page $page)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'slug' => 'nullable|unique:pages,slug,' . $page->id,
            'content' => 'required',
            'excerpt' => 'nullable',
            'template' => 'required|string',
            'meta_title' => 'nullable|max:255',
            'meta_description' => 'nullable',
            'meta_keywords' => 'nullable',
            'is_published' => 'boolean',
            'order' => 'nullable|integer',
        ]);

        $page->update($validated);

        return redirect()
            ->route('admin.pages.edit', $page)
            ->with('success', 'Page updated successfully.');
    }

    public function destroy(Page $page)
    {
        $page->delete();

        return redirect()
            ->route('admin.pages.index')
            ->with('success', 'Page deleted successfully.');
    }

    /**
     * Get available page templates
     */
    protected function getAvailableTemplates()
    {
        return [
            'default' => 'Default Template',
            'full-width' => 'Full Width',
            'sidebar' => 'With Sidebar',
            'landing' => 'Landing Page',
        ];
    }
}
