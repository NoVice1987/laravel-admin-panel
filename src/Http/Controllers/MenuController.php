<?php

namespace StatisticLv\AdminPanel\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use StatisticLv\AdminPanel\Models\Menu;
use StatisticLv\AdminPanel\Models\MenuItem;

class MenuController extends Controller
{
    /**
     * Display a listing of menus.
     *
     * @return \Illuminate\View\View
     */
    public function index(): \Illuminate\View\View
    {
        $menus = Menu::withCount('allItems')
            ->orderBy('created_at', 'desc')
            ->paginate(config('admin-panel.per_page', 15));

        return view('admin-panel::menus.index', compact('menus'));
    }

    /**
     * Show the form for creating a new menu.
     *
     * @return \Illuminate\View\View
     */
    public function create(): \Illuminate\View\View
    {
        return view('admin-panel::menus.create');
    }

    /**
     * Store a newly created menu in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'slug' => 'nullable|unique:menus,slug',
            'location' => 'required|in:main,footer,sidebar',
            'is_active' => 'boolean',
        ]);

        $menu = Menu::create($validated);
        
        // Clear menu cache
        Cache::forget("menu.slug.{$menu->slug}");
        Cache::forget("menu.location.{$menu->location}");
        
        Log::info('Menu created', [
            'menu_id' => $menu->id,
            'name' => $menu->name,
            'user_id' => auth()->guard('admin')->id(),
        ]);

        return redirect()
            ->route('admin.menus.edit', $menu)
            ->with('success', 'Menu created successfully.');
    }

    /**
     * Show the form for editing the specified menu.
     *
     * @param \StatisticLv\AdminPanel\Models\Menu $menu
     * @return \Illuminate\View\View
     */
    public function edit(Menu $menu): \Illuminate\View\View
    {
        $menu->load('items.children');
        
        return view('admin-panel::menus.edit', compact('menu'));
    }

    /**
     * Update the specified menu in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \StatisticLv\AdminPanel\Models\Menu $menu
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Menu $menu): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'slug' => 'nullable|unique:menus,slug,' . $menu->id,
            'location' => 'required|in:main,footer,sidebar',
            'is_active' => 'boolean',
        ]);

        $menu->update($validated);
        
        // Clear menu cache
        Cache::forget("menu.slug.{$menu->slug}");
        Cache::forget("menu.location.{$menu->location}");
        
        Log::info('Menu updated', [
            'menu_id' => $menu->id,
            'name' => $menu->name,
            'user_id' => auth()->guard('admin')->id(),
        ]);

        return redirect()
            ->route('admin.menus.edit', $menu)
            ->with('success', 'Menu updated successfully.');
    }

    /**
     * Remove the specified menu from storage.
     *
     * @param \StatisticLv\AdminPanel\Models\Menu $menu
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Menu $menu): \Illuminate\Http\RedirectResponse
    {
        // Authorization check
        if (!auth()->guard('admin')->user()->isSuperAdmin()) {
            Log::warning('Unauthorized menu deletion attempt', [
                'menu_id' => $menu->id,
                'user_id' => auth()->guard('admin')->id(),
            ]);
            
            abort(403, 'Only super admins can delete menus.');
        }
        
        $menu->delete();
        
        // Clear menu cache
        Cache::forget("menu.slug.{$menu->slug}");
        Cache::forget("menu.location.{$menu->location}");
        
        Log::info('Menu deleted', [
            'menu_id' => $menu->id,
            'name' => $menu->name,
            'user_id' => auth()->guard('admin')->id(),
        ]);

        return redirect()
            ->route('admin.menus.index')
            ->with('success', 'Menu deleted successfully.');
    }

    /**
     * Add a new item to the menu.
     *
     * @param \Illuminate\Http\Request $request
     * @param \StatisticLv\AdminPanel\Models\Menu $menu
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addItem(Request $request, Menu $menu): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'parent_id' => [
                'nullable',
                'exists:menu_items,id',
                function ($attribute, $value, $fail) use ($menu) {
                    // Check if parent belongs to the same menu
                    if ($value && MenuItem::where('id', $value)->where('menu_id', '!=', $menu->id)->exists()) {
                        $fail('Parent item must belong to the same menu.');
                    }
                    
                    // Check for circular reference
                    if ($value) {
                        $parent = MenuItem::find($value);
                        if ($parent && $this->hasCircularReference($parent, $menu->id)) {
                            $fail('Circular reference detected in menu items.');
                        }
                    }
                }
            ],
            'title' => 'required|max:255',
            'url' => 'nullable|string|max:2048',
            'route' => 'nullable|string|max:255',
            'target' => 'required|in:_self,_blank',
            'css_class' => 'nullable|string|max:255',
            'order' => 'nullable|integer|min:0',
        ]);

        $validated['menu_id'] = $menu->id;
        $validated['order'] = $validated['order'] ?? 0;
        $validated['is_active'] = $request->has('is_active');

        MenuItem::create($validated);
        
        // Clear menu cache
        Cache::forget("menu.slug.{$menu->slug}");
        Cache::forget("menu.location.{$menu->location}");
        
        Log::info('Menu item added', [
            'menu_id' => $menu->id,
            'title' => $validated['title'],
            'user_id' => auth()->guard('admin')->id(),
        ]);

        return redirect()
            ->route('admin.menus.edit', $menu)
            ->with('success', 'Menu item added successfully.');
    }

    /**
     * Update the specified menu item.
     *
     * @param \Illuminate\Http\Request $request
     * @param \StatisticLv\AdminPanel\Models\Menu $menu
     * @param \StatisticLv\AdminPanel\Models\MenuItem $item
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateItem(Request $request, Menu $menu, MenuItem $item): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'parent_id' => [
                'nullable',
                'exists:menu_items,id',
                function ($attribute, $value, $fail) use ($menu, $item) {
                    // Prevent setting self as parent
                    if ($value == $item->id) {
                        $fail('An item cannot be its own parent.');
                    }
                    
                    // Check if parent belongs to the same menu
                    if ($value && MenuItem::where('id', $value)->where('menu_id', '!=', $menu->id)->exists()) {
                        $fail('Parent item must belong to the same menu.');
                    }
                    
                    // Check for circular reference
                    if ($value) {
                        $parent = MenuItem::find($value);
                        if ($parent && $this->hasCircularReference($parent, $menu->id, $item->id)) {
                            $fail('Circular reference detected in menu items.');
                        }
                    }
                }
            ],
            'title' => 'required|max:255',
            'url' => 'nullable|string|max:2048',
            'route' => 'nullable|string|max:255',
            'target' => 'required|in:_self,_blank',
            'css_class' => 'nullable|string|max:255',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $item->update($validated);
        
        // Clear menu cache
        Cache::forget("menu.slug.{$menu->slug}");
        Cache::forget("menu.location.{$menu->location}");
        
        Log::info('Menu item updated', [
            'menu_id' => $menu->id,
            'item_id' => $item->id,
            'title' => $item->title,
            'user_id' => auth()->guard('admin')->id(),
        ]);

        return redirect()
            ->route('admin.menus.edit', $menu)
            ->with('success', 'Menu item updated successfully.');
    }

    /**
     * Remove the specified menu item from storage.
     *
     * @param \StatisticLv\AdminPanel\Models\Menu $menu
     * @param \StatisticLv\AdminPanel\Models\MenuItem $item
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteItem(Menu $menu, MenuItem $item): \Illuminate\Http\RedirectResponse
    {
        $item->delete();
        
        // Clear menu cache
        Cache::forget("menu.slug.{$menu->slug}");
        Cache::forget("menu.location.{$menu->location}");
        
        Log::info('Menu item deleted', [
            'menu_id' => $menu->id,
            'item_id' => $item->id,
            'title' => $item->title,
            'user_id' => auth()->guard('admin')->id(),
        ]);

        return redirect()
            ->route('admin.menus.edit', $menu)
            ->with('success', 'Menu item deleted successfully.');
    }

    /**
     * Check for circular reference in menu items.
     *
     * @param \StatisticLv\AdminPanel\Models\MenuItem $parent
     * @param int $menuId
     * @param int|null $excludeItemId
     * @return bool
     */
    private function hasCircularReference(MenuItem $parent, int $menuId, ?int $excludeItemId = null): bool
    {
        $current = $parent;
        $visited = [];
        
        while ($current) {
            if (in_array($current->id, $visited)) {
                return true;
            }
            
            $visited[] = $current->id;
            
            if ($current->menu_id != $menuId) {
                return true;
            }
            
            if ($excludeItemId && $current->id == $excludeItemId) {
                return true;
            }
            
            $current = $current->parent;
        }
        
        return false;
    }
}
