<?php

namespace StatisticLv\AdminPanel\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use StatisticLv\AdminPanel\Traits\HasSluggable;

class Menu extends Model
{
    use HasSluggable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'location',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    /**
     * The source field for slug generation.
     *
     * @var string
     */
    protected $sourceField = 'name';

    /**
     * Get the items for the menu (only root items).
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(MenuItem::class)->whereNull('parent_id')->orderBy('order');
    }

    /**
     * Get all items for the menu (including nested).
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function allItems()
    {
        return $this->hasMany(MenuItem::class)->orderBy('order');
    }
}
