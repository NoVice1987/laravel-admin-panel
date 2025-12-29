<?php

use StatisticLv\AdminPanel\Helpers\AdminPanelHelpers;

if (!function_exists('admin_menu')) {
    /**
     * Get an admin panel menu
     *
     * @param string $identifier
     * @param string $type
     * @return \StatisticLv\AdminPanel\Models\Menu|null
     */
    function admin_menu($identifier, $type = 'slug')
    {
        return AdminPanelHelpers::getMenu($identifier, $type);
    }
}

if (!function_exists('admin_render_menu')) {
    /**
     * Render a menu as HTML
     *
     * @param string $identifier
     * @param string $type
     * @param array $options
     * @return string
     */
    function admin_render_menu($identifier, $type = 'slug', $options = [])
    {
        return AdminPanelHelpers::renderMenu($identifier, $type, $options);
    }
}

if (!function_exists('admin_news')) {
    /**
     * Get published news articles
     *
     * @param int|null $limit
     * @param int|null $paginate
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    function admin_news($limit = null, $paginate = null)
    {
        return AdminPanelHelpers::getPublishedNews($limit, $paginate);
    }
}

if (!function_exists('admin_news_by_slug')) {
    /**
     * Get a news article by slug
     *
     * @param string $slug
     * @return \StatisticLv\AdminPanel\Models\News|null
     */
    function admin_news_by_slug($slug)
    {
        return AdminPanelHelpers::getNewsBySlug($slug);
    }
}

if (!function_exists('admin_latest_news')) {
    /**
     * Get latest news articles
     *
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    function admin_latest_news($limit = 5)
    {
        return AdminPanelHelpers::getLatestNews($limit);
    }
}

if (!function_exists('admin_popular_news')) {
    /**
     * Get popular news articles
     *
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    function admin_popular_news($limit = 5)
    {
        return AdminPanelHelpers::getPopularNews($limit);
    }
}

if (!function_exists('admin_page_by_slug')) {
    /**
     * Get a page by slug
     *
     * @param string $slug
     * @return \StatisticLv\AdminPanel\Models\Page|null
     */
    function admin_page_by_slug($slug)
    {
        return \StatisticLv\AdminPanel\Models\Page::where('slug', $slug)
            ->published()
            ->with('author')
            ->first();
    }
}

if (!function_exists('admin_pages')) {
    /**
     * Get all published pages
     *
     * @param int|null $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    function admin_pages($limit = null)
    {
        $query = \StatisticLv\AdminPanel\Models\Page::published()
            ->orderBy('order')
            ->orderBy('created_at', 'desc');

        if ($limit) {
            return $query->take($limit)->get();
        }

        return $query->get();
    }
}

if (!function_exists('admin_format_date')) {
    /**
     * Format a date according to admin panel settings
     *
     * @param \Carbon\Carbon $date
     * @param string|null $format
     * @return string
     */
    function admin_format_date($date, $format = null)
    {
        return AdminPanelHelpers::formatDate($date, $format);
    }
}

if (!function_exists('admin_truncate')) {
    /**
     * Truncate text to a specified length
     *
     * @param string $text
     * @param int $length
     * @param string $suffix
     * @return string
     */
    function admin_truncate($text, $length = 100, $suffix = '...')
    {
        return AdminPanelHelpers::truncate($text, $length, $suffix);
    }
}

if (!function_exists('admin_excerpt')) {
    /**
     * Get excerpt from HTML content
     *
     * @param string $html
     * @param int $length
     * @return string
     */
    function admin_excerpt($html, $length = 200)
    {
        return AdminPanelHelpers::getExcerpt($html, $length);
    }
}
