<?php

namespace StatisticLv\AdminPanel\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use StatisticLv\AdminPanel\Traits\HasSluggable;

class News extends Model
{
    use HasSluggable, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'news';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'status',
        'published_at',
        'author_id',
        'views_count',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'published_at' => 'datetime',
        'views_count' => 'integer',
        'deleted_at' => 'datetime',
    ];

    /**
     * The source field for slug generation.
     *
     * @var string
     */
    protected $sourceField = 'title';

    /**
     * Get the author that owns the news article.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(AdminUser::class, 'author_id');
    }

    /**
     * Scope a query to only include published news.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function scopePublished($query)
    {


        return $query->where('status', 'published');
                    // ->where('published_at', '<=', now());
                    
    }

    /**
     * Scope a query to only include draft news.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Check if the news article is published.
     *
     * @return bool
     */
    public function isPublished(): bool
    {
        return $this->status === 'published' 
            && $this->published_at 
            && $this->published_at->lte(now());
    }

    /**
     * Increment the view count for the news article.
     *
     * @return void
     */
    public function incrementViews(): void
    {
        $this->increment('views_count');
    }
}
