# Frontend Integration Guide

This guide shows you how to integrate the Laravel Admin Panel content into your frontend application.

## Table of Contents

- [News Integration](#news-integration)
- [Menu Integration](#menu-integration)
- [Helper Functions](#helper-functions)
- [Example Pages](#example-pages)
- [Styling Tips](#styling-tips)

## News Integration

### Display Latest News on Homepage

```php
<!-- resources/views/home.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-3xl font-bold mb-8">Latest News</h1>
    
    @php
        $latestNews = admin_latest_news(6);
    @endphp
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($latestNews as $article)
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                @if($article->featured_image)
                    <img src="{{ $article->featured_image }}" 
                         alt="{{ $article->title }}"
                         class="w-full h-48 object-cover">
                @endif
                
                <div class="p-6">
                    <h2 class="text-xl font-semibold mb-2">
                        <a href="{{ route('news.show', $article->slug) }}" 
                           class="text-gray-900 hover:text-blue-600">
                            {{ $article->title }}
                        </a>
                    </h2>
                    
                    <p class="text-gray-600 text-sm mb-4">
                        {{ admin_truncate($article->excerpt ?: $article->content, 120) }}
                    </p>
                    
                    <div class="flex items-center text-sm text-gray-500">
                        <span>{{ $article->published_at->format('M d, Y') }}</span>
                        <span class="mx-2">•</span>
                        <span>{{ $article->author->name }}</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
    <div class="mt-8 text-center">
        <a href="{{ route('news.index') }}" 
           class="inline-block px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            View All News
        </a>
    </div>
</div>
@endsection
```

### News Listing Page with Pagination

```php
<!-- resources/views/news/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-4xl font-bold mb-8">All News</h1>
    
    @php
        $news = admin_news(null, 12); // 12 per page
    @endphp
    
    <div class="space-y-8">
        @foreach($news as $article)
            <article class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="md:flex">
                    @if($article->featured_image)
                        <div class="md:w-1/3">
                            <img src="{{ $article->featured_image }}" 
                                 alt="{{ $article->title }}"
                                 class="w-full h-64 object-cover">
                        </div>
                    @endif
                    
                    <div class="p-6 {{ $article->featured_image ? 'md:w-2/3' : '' }}">
                        <h2 class="text-2xl font-bold mb-3">
                            <a href="{{ route('news.show', $article->slug) }}" 
                               class="text-gray-900 hover:text-blue-600">
                                {{ $article->title }}
                            </a>
                        </h2>
                        
                        @if($article->excerpt)
                            <p class="text-gray-700 mb-4">{{ $article->excerpt }}</p>
                        @else
                            <p class="text-gray-700 mb-4">
                                {{ admin_excerpt($article->content, 300) }}
                            </p>
                        @endif
                        
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-500">
                                <span>{{ $article->published_at->format('F j, Y') }}</span>
                                <span class="mx-2">•</span>
                                <span>By {{ $article->author->name }}</span>
                                <span class="mx-2">•</span>
                                <span>{{ $article->views_count }} views</span>
                            </div>
                            
                            <a href="{{ route('news.show', $article->slug) }}" 
                               class="text-blue-600 hover:text-blue-800 font-medium">
                                Read More →
                            </a>
                        </div>
                    </div>
                </div>
            </article>
        @endforeach
    </div>
    
    <div class="mt-8">
        {{ $news->links() }}
    </div>
</div>
@endsection
```

### Single News Page

```php
<!-- resources/views/news/show.blade.php -->
@extends('layouts.app')

@section('title', $article->title)

@section('content')
<article class="container mx-auto px-4 py-8 max-w-4xl">
    <header class="mb-8">
        <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
            {{ $article->title }}
        </h1>
        
        <div class="flex items-center text-gray-600 text-sm">
            <img src="https://ui-avatars.com/api/?name={{ urlencode($article->author->name) }}" 
                 alt="{{ $article->author->name }}"
                 class="w-10 h-10 rounded-full mr-3">
            <div>
                <div class="font-medium">{{ $article->author->name }}</div>
                <div>
                    {{ $article->published_at->format('F j, Y') }}
                    <span class="mx-2">•</span>
                    {{ $article->views_count }} views
                </div>
            </div>
        </div>
    </header>
    
    @if($article->featured_image)
        <div class="mb-8">
            <img src="{{ $article->featured_image }}" 
                 alt="{{ $article->title }}"
                 class="w-full h-auto rounded-lg shadow-lg">
        </div>
    @endif
    
    @if($article->excerpt)
        <div class="text-xl text-gray-700 mb-8 font-medium leading-relaxed">
            {{ $article->excerpt }}
        </div>
    @endif
    
    <div class="prose prose-lg max-w-none">
        {!! $article->content !!}
    </div>
    
    <footer class="mt-12 pt-8 border-t border-gray-200">
        <div class="flex items-center justify-between">
            <a href="{{ route('news.index') }}" 
               class="text-blue-600 hover:text-blue-800 font-medium">
                ← Back to News
            </a>
            
            <div class="text-sm text-gray-500">
                Last updated: {{ $article->updated_at->diffForHumans() }}
            </div>
        </div>
    </footer>
</article>

<!-- Related News -->
@php
    $relatedNews = admin_latest_news(3);
@endphp

@if($relatedNews->count() > 0)
    <section class="bg-gray-50 py-12 mt-12">
        <div class="container mx-auto px-4 max-w-4xl">
            <h2 class="text-2xl font-bold mb-6">Related News</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($relatedNews as $related)
                    @if($related->id !== $article->id)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden">
                            @if($related->featured_image)
                                <img src="{{ $related->featured_image }}" 
                                     alt="{{ $related->title }}"
                                     class="w-full h-32 object-cover">
                            @endif
                            
                            <div class="p-4">
                                <h3 class="font-semibold mb-2">
                                    <a href="{{ route('news.show', $related->slug) }}" 
                                       class="text-gray-900 hover:text-blue-600">
                                        {{ admin_truncate($related->title, 60) }}
                                    </a>
                                </h3>
                                <div class="text-xs text-gray-500">
                                    {{ $related->published_at->format('M d, Y') }}
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </section>
@endif
@endsection
```

### Controller for News Pages

```php
<?php
// app/Http/Controllers/NewsController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use StatisticLv\AdminPanel\Models\News;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::published()
            ->with('author')
            ->orderBy('published_at', 'desc')
            ->paginate(12);
            
        return view('news.index', compact('news'));
    }
    
    public function show($slug)
    {
        $article = News::where('slug', $slug)
            ->published()
            ->with('author')
            ->firstOrFail();
            
        // Increment view count
        $article->incrementViews();
        
        return view('news.show', compact('article'));
    }
}
```

### Routes for News

```php
<?php
// routes/web.php

use App\Http\Controllers\NewsController;

Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{slug}', [NewsController::class, 'show'])->name('news.show');
```

## Menu Integration

### Basic Navigation Menu

```php
<!-- resources/views/layouts/partials/navigation.blade.php -->
@php
    $mainMenu = admin_menu('main-menu');
@endphp

@if($mainMenu)
    <nav class="bg-white shadow">
        <div class="container mx-auto px-4">
            <ul class="flex items-center space-x-6 py-4">
                @foreach($mainMenu->items as $item)
                    <li class="relative group">
                        <a href="{{ $item->url }}" 
                           target="{{ $item->target }}"
                           class="text-gray-700 hover:text-blue-600 font-medium {{ $item->css_class }}">
                            {{ $item->title }}
                        </a>
                        
                        @if($item->children->count() > 0)
                            <ul class="absolute left-0 mt-2 w-48 bg-white shadow-lg rounded-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                                @foreach($item->children as $child)
                                    <li>
                                        <a href="{{ $child->url }}" 
                                           target="{{ $child->target }}"
                                           class="block px-4 py-2 text-gray-700 hover:bg-gray-100 {{ $child->css_class }}">
                                            {{ $child->title }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </nav>
@endif
```

### Footer Menu

```php
<!-- resources/views/layouts/partials/footer.blade.php -->
<footer class="bg-gray-800 text-white py-8">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Footer Menu -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                @php
                    $footerMenu = admin_menu('footer', 'location');
                @endphp
                
                @if($footerMenu)
                    <ul class="space-y-2">
                        @foreach($footerMenu->items as $item)
                            <li>
                                <a href="{{ $item->url }}" 
                                   target="{{ $item->target }}"
                                   class="text-gray-300 hover:text-white">
                                    {{ $item->title }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
            
            <!-- Latest News -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Latest News</h3>
                @php
                    $footerNews = admin_latest_news(3);
                @endphp
                
                <ul class="space-y-2">
                    @foreach($footerNews as $news)
                        <li>
                            <a href="{{ route('news.show', $news->slug) }}" 
                               class="text-gray-300 hover:text-white text-sm">
                                {{ admin_truncate($news->title, 50) }}
                            </a>
                            <div class="text-xs text-gray-400">
                                {{ $news->published_at->format('M d, Y') }}
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
            
            <!-- Contact Info -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Contact</h3>
                <p class="text-gray-300">
                    Email: info@example.com<br>
                    Phone: (123) 456-7890
                </p>
            </div>
        </div>
        
        <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400 text-sm">
            <p>&copy; {{ date('Y') }} Your Company. All rights reserved.</p>
        </div>
    </div>
</footer>
```

### Mobile-Friendly Menu

```php
<!-- resources/views/layouts/partials/mobile-menu.blade.php -->
<div x-data="{ open: false }" class="lg:hidden">
    <!-- Hamburger Button -->
    <button @click="open = !open" 
            class="p-2 rounded-md text-gray-700 hover:text-gray-900 hover:bg-gray-100">
        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M4 6h16M4 12h16M4 18h16" />
            <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
    
    <!-- Mobile Menu Panel -->
    <div x-show="open" 
         @click.away="open = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform -translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform -translate-y-2"
         class="absolute top-16 left-0 right-0 bg-white shadow-lg z-50"
         style="display: none;">
        
        @php
            $mobileMenu = admin_menu('main-menu');
        @endphp
        
        @if($mobileMenu)
            <ul class="py-2">
                @foreach($mobileMenu->items as $item)
                    <li>
                        <a href="{{ $item->url }}" 
                           target="{{ $item->target }}"
                           class="block px-4 py-3 text-gray-700 hover:bg-gray-100">
                            {{ $item->title }}
                        </a>
                        
                        @if($item->children->count() > 0)
                            <ul class="bg-gray-50">
                                @foreach($item->children as $child)
                                    <li>
                                        <a href="{{ $child->url }}" 
                                           target="{{ $child->target }}"
                                           class="block px-8 py-2 text-sm text-gray-600 hover:bg-gray-100">
                                            {{ $child->title }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
```

## Helper Functions

All available helper functions:

```php
// Get menu
$menu = admin_menu('main-menu', 'slug');
$menu = admin_menu('footer', 'location');

// Render menu as HTML
$html = admin_render_menu('main-menu', 'slug', [
    'ul_class' => 'navbar-nav',
    'li_class' => 'nav-item',
    'a_class' => 'nav-link',
    'active_class' => 'active',
    'has_children_class' => 'dropdown'
]);

// Get news
$allNews = admin_news();
$limitedNews = admin_news(5);
$paginatedNews = admin_news(null, 10);

// Get specific news
$article = admin_news_by_slug('my-article-slug');

// Get latest/popular
$latest = admin_latest_news(5);
$popular = admin_popular_news(5);

// Format date
$formatted = admin_format_date($date);
$custom = admin_format_date($date, 'd/m/Y H:i');

// Text manipulation
$short = admin_truncate($longText, 100, '...');
$excerpt = admin_excerpt($htmlContent, 200);
```

## Styling Tips

### Add Tailwind Typography for News Content

Install the typography plugin:

```bash
npm install -D @tailwindcss/typography
```

Configure in `tailwind.config.js`:

```javascript
module.exports = {
    plugins: [
        require('@tailwindcss/typography'),
    ],
}
```

Use in templates:

```php
<div class="prose prose-lg max-w-none">
    {!! $article->content !!}
</div>
```

### Custom CSS for Menus

```css
/* resources/css/menu.css */
.dropdown-menu {
    @apply absolute left-0 mt-2 w-48 bg-white shadow-lg rounded-lg;
    @apply opacity-0 invisible transition-all duration-200;
}

.dropdown:hover .dropdown-menu {
    @apply opacity-100 visible;
}

.menu-item {
    @apply text-gray-700 hover:text-blue-600 font-medium;
}

.menu-item.active {
    @apply text-blue-600 border-b-2 border-blue-600;
}
```

## SEO Best Practices

### Meta Tags for News

```php
<!-- In news/show.blade.php -->
@section('meta')
    <meta name="description" content="{{ $article->excerpt ?: admin_excerpt($article->content, 160) }}">
    <meta name="keywords" content="{{ $article->title }}">
    <meta name="author" content="{{ $article->author->name }}">
    
    <!-- Open Graph -->
    <meta property="og:title" content="{{ $article->title }}">
    <meta property="og:description" content="{{ $article->excerpt ?: admin_excerpt($article->content, 160) }}">
    <meta property="og:image" content="{{ $article->featured_image }}">
    <meta property="og:url" content="{{ route('news.show', $article->slug) }}">
    <meta property="og:type" content="article">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $article->title }}">
    <meta name="twitter:description" content="{{ $article->excerpt ?: admin_excerpt($article->content, 160) }}">
    <meta name="twitter:image" content="{{ $article->featured_image }}">
@endsection
```

### Structured Data

```php
<!-- In news/show.blade.php -->
@push('scripts')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "NewsArticle",
    "headline": "{{ $article->title }}",
    "image": "{{ $article->featured_image }}",
    "datePublished": "{{ $article->published_at->toIso8601String() }}",
    "dateModified": "{{ $article->updated_at->toIso8601String() }}",
    "author": {
        "@type": "Person",
        "name": "{{ $article->author->name }}"
    },
    "publisher": {
        "@type": "Organization",
        "name": "Your Company",
        "logo": {
            "@type": "ImageObject",
            "url": "{{ asset('images/logo.png') }}"
        }
    },
    "description": "{{ $article->excerpt ?: admin_excerpt($article->content, 160) }}"
}
</script>
@endpush
```

---

This guide covers the most common integration scenarios. For more advanced usage, refer to the main README.md file.
