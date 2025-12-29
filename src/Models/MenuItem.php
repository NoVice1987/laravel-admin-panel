<?php

namespace StatisticLv\AdminPanel\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Route;

class MenuItem extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'menu_id',
        'parent_id',
        'title',
        'url',
        'route',
        'target',
        'css_class',
        'order',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the menu that owns the menu item.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    /**
     * Get the parent menu item.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    /**
     * Get the child menu items.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->orderBy('order');
    }

    /**
     * Get the URL for this menu item.
     * This accessor is only used when displaying the menu on the frontend.
     * In the admin panel, we access the raw attributes directly.
     *
     * @param string|null $value
     * @return string
     */
    public function getUrlAttribute($value): string
    {
        // If we're accessing this from the admin panel, return the raw value
        // to avoid route resolution errors
        if (request()->is('admin/*')) {
            return $value ?? '';
        }

        // For frontend display, try to resolve the route
        if ($this->attributes['route']) {
            try {
                if (Route::has($this->attributes['route'])) {
                    return route($this->attributes['route']);
                }
            } catch (\Exception $e) {
                // If route resolution fails, fall back to URL
            }
        }
        
        return $value ?? '';
    }

    /**
     * Get the raw URL value without accessor logic.
     *
     * @return string
     */
    public function getRawUrl(): string
    {
        return $this->attributes['url'] ?? '';
    }

    /**
     * Get the raw route value without accessor logic.
     *
     * @return string
     */
    public function getRawRoute(): string
    {
        return $this->attributes['route'] ?? '';
    }

    /**
     * Get the display URL for this menu item (safe for admin panel).
     *
     * @return string
     */
    public function getDisplayUrl(): string
    {
        if ($this->attributes['route']) {
            try {
                if (Route::has($this->attributes['route'])) {
                    return route($this->attributes['route']);
                }
                return 'Route: ' . $this->attributes['route'];
            } catch (\Exception $e) {
                return 'Route: ' . $this->attributes['route'];
            }
        }
        
        return $this->attributes['url'] ?? '#';
    }
}
