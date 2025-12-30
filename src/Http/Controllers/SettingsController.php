<?php

namespace StatisticLv\AdminPanel\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use StatisticLv\AdminPanel\Models\Setting;

class SettingsController extends Controller
{
    /**
     * Display a listing of the settings grouped by category.
     *
     * @return \Illuminate\View\View
     */
    public function index(): \Illuminate\View\View
    {
        $settings = Setting::getAllGrouped();
        
        return view('admin-panel::settings.index', compact('settings'));
    }

    /**
     * Show the form for editing settings.
     *
     * @return \Illuminate\View\View
     */
    public function edit(): \Illuminate\View\View
    {
        $settings = Setting::getAllGrouped();
        
        return view('admin-panel::settings.edit', compact('settings'));
    }

    /**
     * Update the specified settings in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request): \Illuminate\Http\RedirectResponse
    {
        $settings = Setting::all();
        
        foreach ($settings as $setting) {
            $key = $setting->key;
            
            if ($request->has($key)) {
                $value = $request->input($key);
                
                // Handle different field types
                if ($setting->type === 'boolean') {
                    $value = $value === '1' || $value === 'true' || $value === true ? '1' : '0';
                } elseif ($setting->type === 'number') {
                    $value = is_numeric($value) ? (string) $value : '0';
                } elseif ($setting->type === 'array') {
                    if (is_array($value)) {
                        $value = json_encode($value);
                    }
                }
                
                $setting->value = $value;
                $setting->save();
            }
        }
        
        // Clear settings cache
        Cache::forget('settings.all');
        Cache::forget('settings.grouped');
        
        Log::info('Settings updated', [
            'user_id' => auth()->guard('admin')->id(),
        ]);

        return redirect()
            ->route('admin.settings.edit')
            ->with('success', 'Settings updated successfully.');
    }

    /**
     * Create a new setting.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'key' => 'required|string|max:255|unique:settings,key',
            'title' => 'required|string|max:255',
            'type' => 'required|in:text,textarea,number,boolean,select',
            'group' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'options' => 'nullable|array',
            'value' => 'nullable|string',
        ]);

        if (isset($validated['options']) && is_array($validated['options'])) {
            $validated['options'] = json_encode($validated['options']);
        }

        Setting::create($validated);
        
        // Clear settings cache
        Cache::forget('settings.all');
        Cache::forget('settings.grouped');
        
        Log::info('Setting created', [
            'key' => $validated['key'],
            'user_id' => auth()->guard('admin')->id(),
        ]);

        return redirect()
            ->route('admin.settings.index')
            ->with('success', 'Setting created successfully.');
    }

    /**
     * Remove the specified setting from storage.
     *
     * @param \StatisticLv\AdminPanel\Models\Setting $setting
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Setting $setting): \Illuminate\Http\RedirectResponse
    {
        $setting->delete();
        
        // Clear settings cache
        Cache::forget('settings.all');
        Cache::forget('settings.grouped');
        
        Log::info('Setting deleted', [
            'key' => $setting->key,
            'user_id' => auth()->guard('admin')->id(),
        ]);

        return redirect()
            ->route('admin.settings.index')
            ->with('success', 'Setting deleted successfully.');
    }
}