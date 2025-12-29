<?php

namespace StatisticLv\AdminPanel\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use StatisticLv\AdminPanel\Traits\HasSluggable;

class Page extends Model
{
    use HasSluggable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'template',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'is_published',
        'order',
        'author_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_published' => 'boolean',
        'order' => 'integer',
        'deleted_at' => 'datetime',
    ];

    /**
     * The source field for slug generation.
     *
     * @var string
     */
    protected $sourceField = 'title';

    /**
     * Get the author that owns the page.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(AdminUser::class, 'author_id');
    }

    /**
     * Scope a query to only include published pages.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Get the meta title or fall back to title.
     *
     * @return string
     */
    public function getMetaTitleAttribute($value)
    {
        return $value ?: $this->title;
    }

    /**
     * Get the meta description or fall back to excerpt.
     *
     * @return string|null
     */
    public function getMetaDescriptionAttribute($value)
    {
        return $value ?: $this->excerpt;
    }
}
