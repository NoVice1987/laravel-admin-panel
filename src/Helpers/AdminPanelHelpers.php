<?php

namespace StatisticLv\AdminPanel\Helpers;

use Illuminate\Support\Facades\Cache;
use StatisticLv\AdminPanel\Interfaces\MenuRendererInterface;
use StatisticLv\AdminPanel\Models\Menu;
use StatisticLv\AdminPanel\Models\News;

class AdminPanelHelpers implements MenuRendererInterface
{
    /**
     * Get an active menu by slug or location.
     *
     * @param string $identifier Slug or location
     * @param string $type 'slug' or 'location'
     * @return Menu|null
     */
    public static function getMenu($identifier, $type = 'slug'): ?Menu
    {
        //dd(Menu::all());

        return Cache::remember("menu.{$type}.{$identifier}", 3600, function () use ($identifier, $type) {
            return Menu::where($type, $identifier)
                ->where('is_active', true)
                ->with('items.children')
                ->first();
        });
    }

    /**
     * Get published news articles.
     *
     * @param int|null $limit
     * @param int|null $paginate
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function getPublishedNews($limit = null, $paginate = null)
    {
        $query = News::published()
            ->with('author')
            ->orderBy('published_at', 'desc');

        if ($paginate) {
            return $query->paginate($paginate);
        }

        if ($limit) {
            return $query->take($limit)->get();
        }

        return Cache::remember('news.published.all', 300, function () use ($query) {
            return $query->get();
        });
    }

    /**
     * Get a single news article by slug.
     *
     * @param string $slug
     * @return News|null
     */
    public static function getNewsBySlug($slug): ?News
    {
        return Cache::remember("news.slug.{$slug}", 3600, function () use ($slug) {
            return News::where('slug', $slug)
                ->published()
                ->with('author')
                ->first();
        });
    }

    /**
     * Get latest news articles.
     *
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getLatestNews($limit = 5)
    {
        return Cache::remember('news.latest', 300, function () use ($limit) {
            return News::published()
                ->orderBy('published_at', 'desc')
                ->take($limit)
                ->get();
        });
    }

    /**
     * Get popular news articles (by views).
     *
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getPopularNews($limit = 5)
    {
        return Cache::remember('news.popular', 300, function () use ($limit) {
            return News::published()
                ->orderBy('views_count', 'desc')
                ->take($limit)
                ->get();
        });
    }

    /**
     * Render menu HTML.
     *
     * @param string $menuIdentifier
     * @param string $type
     * @param array $options
     * @return string
     */
    public static function renderMenu($menuIdentifier, $type = 'slug', $options = []): string
    {
        $menu = self::getMenu($menuIdentifier, $type);

        if (!$menu) {
            return '';
        }

        $defaults = [
            'ul_class' => 'menu',
            'li_class' => 'menu-item',
            'a_class' => 'menu-link',
            'active_class' => 'active',
            'has_children_class' => 'has-children',
        ];

        $options = array_merge($defaults, $options);

        return self::buildMenuHtml($menu->items, $options);
    }

    /**
     * Build menu HTML recursively.
     *
     * @param \Illuminate\Database\Eloquent\Collection $items
     * @param array $options
     * @param int $depth
     * @return string
     */
    protected static function buildMenuHtml($items, $options, $depth = 0): string
    {
        if ($items->isEmpty()) {
            return '';
        }

        $html = '<ul class="' . htmlspecialchars($options['ul_class'], ENT_QUOTES, 'UTF-8') . ($depth > 0 ? ' submenu' : '') . '">';

        foreach ($items as $item) {
            $hasChildren = $item->children->isNotEmpty();
            $isActive = request()->url() === $item->url;

            $liClass = $options['li_class'];
            if ($hasChildren) {
                $liClass .= ' ' . $options['has_children_class'];
            }
            if ($isActive) {
                $liClass .= ' ' . $options['active_class'];
            }

            $html .= '<li class="' . htmlspecialchars($liClass, ENT_QUOTES, 'UTF-8') . '">';
            $html .= '<a href="' . e($item->url) . '" ';
            $html .= 'class="' . htmlspecialchars($options['a_class'], ENT_QUOTES, 'UTF-8') . '" ';
            $html .= 'target="' . htmlspecialchars($item->target, ENT_QUOTES, 'UTF-8') . '"';
            if ($item->css_class) {
                $sanitizedClass = htmlspecialchars($item->css_class, ENT_QUOTES, 'UTF-8');
                $html .= ' class="' . $sanitizedClass . '"';
            }
            $html .= '>' . e($item->title) . '</a>';

            if ($hasChildren) {
                $html .= self::buildMenuHtml($item->children, $options, $depth + 1);
            }

            $html .= '</li>';
        }

        $html .= '</ul>';

        return $html;
    }

    /**
     * Format a date according to admin panel settings.
     *
     * @param \Carbon\Carbon $date
     * @param string|null $format
     * @return string
     */
    public static function formatDate($date, $format = null): string
    {
        if (!$date) {
            return '';
        }

        $format = $format ?: config('admin-panel.date_format', 'Y-m-d H:i:s');

        return $date->format($format);
    }

    /**
     * Truncate text to a specified length.
     *
     * @param string $text
     * @param int $length
     * @param string $suffix
     * @return string
     */
    public static function truncate($text, $length = 100, $suffix = '...'): string
    {
        if (mb_strlen($text) <= $length) {
            return $text;
        }

        return mb_substr($text, 0, $length) . $suffix;
    }

    /**
     * Get excerpt from HTML content.
     *
     * @param string $html
     * @param int $length
     * @return string
     */
    public static function getExcerpt($html, $length = 200): string
    {
        $text = strip_tags($html);
        return self::truncate($text, $length);
    }

    /**
     * Render a menu as HTML (Interface implementation).
     *
     * @param string $identifier
     * @param string $type
     * @param array $options
     * @return string
     */
    public function render(string $identifier, string $type = 'slug', array $options = []): string
    {
        return self::renderMenu($identifier, $type, $options);
    }
}
