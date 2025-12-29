# Quick Start Guide

Get up and running with Laravel Admin Panel in 5 minutes!

## Prerequisites

- PHP 8.1+
- Laravel 10 or 11
- Composer
- MySQL/PostgreSQL/SQLite

## Installation (5 Steps)

### Step 1: Install Package (1 minute)

For local development, add to `composer.json`:

```json
{
    "repositories": [
        {
            "type": "path",
            "url": "../admin-panel"
        }
    ],
    "require": {
        "statisticlv/laravel-admin-panel": "@dev"
    }
}
```

Then run:
```bash
composer update
```

### Step 2: Publish & Migrate (1 minute)

```bash
php artisan vendor:publish --provider="StatisticLv\AdminPanel\AdminPanelServiceProvider"
php artisan migrate
```

### Step 3: Create Admin User (30 seconds)

```bash
php artisan admin:create-user
```

Follow the prompts or use flags:
```bash
php artisan admin:create-user --name="Admin" --email="admin@test.com" --password="password123" --role="super_admin"
```

### Step 4: Access Admin Panel (30 seconds)

Visit: `http://your-app.test/admin`

Login with your credentials.

### Step 5: Create Your First News Article (2 minutes)

1. Click **News** → **Create News**
2. Fill in:
   - Title: "Welcome to Our Site"
   - Content: "This is our first article!"
   - Status: Published
3. Click **Create News**

**Done!** 🎉

---

## Display News on Frontend

### Create Routes

Add to `routes/web.php`:

```php
use StatisticLv\AdminPanel\Models\News;

Route::get('/', function() {
    $news = admin_latest_news(5);
    return view('welcome', compact('news'));
});

Route::get('/news/{slug}', function($slug) {
    $article = admin_news_by_slug($slug);
    if (!$article) abort(404);
    $article->incrementViews();
    return view('news.show', compact('article'));
})->name('news.show');
```

### Create View

Create `resources/views/news/show.blade.php`:

```php
<!DOCTYPE html>
<html>
<head>
    <title>{{ $article->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <article class="bg-white rounded-lg shadow-lg p-8">
            <h1 class="text-4xl font-bold mb-4">{{ $article->title }}</h1>
            
            <div class="text-gray-600 text-sm mb-6">
                By {{ $article->author->name }} • 
                {{ $article->published_at->format('F j, Y') }}
            </div>
            
            @if($article->featured_image)
                <img src="{{ $article->featured_image }}" 
                     alt="{{ $article->title }}"
                     class="w-full h-64 object-cover rounded-lg mb-6">
            @endif
            
            <div class="prose max-w-none">
                {!! $article->content !!}
            </div>
        </article>
    </div>
</body>
</html>
```

### Update Welcome Page

Edit `resources/views/welcome.blade.php`:

```php
<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-4xl font-bold mb-8">Latest News</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($news as $article)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    @if($article->featured_image)
                        <img src="{{ $article->featured_image }}" 
                             class="w-full h-48 object-cover">
                    @endif
                    
                    <div class="p-6">
                        <h2 class="text-xl font-bold mb-2">
                            <a href="/news/{{ $article->slug }}" 
                               class="text-gray-900 hover:text-blue-600">
                                {{ $article->title }}
                            </a>
                        </h2>
                        
                        <p class="text-gray-600 mb-4">
                            {{ admin_truncate($article->excerpt ?: $article->content, 100) }}
                        </p>
                        
                        <div class="text-sm text-gray-500">
                            {{ $article->published_at->format('M d, Y') }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</body>
</html>
```

---

## Add Navigation Menu

### Create Menu in Admin

1. Go to **Menus** → **Create Menu**
2. Name: "Main Menu"
3. Slug: "main-menu"
4. Location: Main Navigation
5. Click **Create**
6. Click **Edit** and add items:
   - Home (URL: `/`)
   - News (URL: `/news`)
   - About (URL: `/about`)
   - Contact (URL: `/contact`)

### Display Menu

Create `resources/views/layouts/navigation.blade.php`:

```php
@php
    $menu = admin_menu('main-menu');
@endphp

@if($menu)
    <nav class="bg-white shadow">
        <div class="container mx-auto px-4">
            <ul class="flex space-x-6 py-4">
                @foreach($menu->items as $item)
                    <li>
                        <a href="{{ $item->url }}" 
                           class="text-gray-700 hover:text-blue-600 font-medium">
                            {{ $item->title }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </nav>
@endif
```

Include in your layout:

```php
@include('layouts.navigation')
```

---

## Common Tasks

### Get Latest News
```php
$news = admin_latest_news(5);
```

### Get Popular News
```php
$popular = admin_popular_news(5);
```

### Get News with Pagination
```php
$news = admin_news(null, 10); // 10 per page
{{ $news->links() }}
```

### Get Specific Article
```php
$article = admin_news_by_slug('my-article');
```

### Format Date
```php
{{ admin_format_date($article->published_at, 'F j, Y') }}
```

### Truncate Text
```php
{{ admin_truncate($article->content, 150) }}
```

### Get Menu by Location
```php
$footerMenu = admin_menu('footer', 'location');
```

---

## Configuration

Edit `config/admin-panel.php`:

```php
return [
    'route_prefix' => 'admin',      // Change admin URL
    'per_page' => 15,               // Items per page
    'title' => 'My Admin Panel',    // Panel title
];
```

---

## Next Steps

### Customize Views
```bash
php artisan vendor:publish --tag=admin-panel-views
```
Views will be in `resources/views/vendor/admin-panel/`

### Add Custom CSS/JS
```bash
php artisan vendor:publish --tag=admin-panel-assets
```
Assets will be in `public/vendor/admin-panel/`

### Create More Admin Users
```bash
php artisan admin:create-user --role="admin"
```

### Add More Menus
Create menus for different locations:
- Main navigation (header)
- Footer links
- Sidebar navigation

---

## Testing

### Manual Test Checklist

- [ ] Login to admin panel
- [ ] Create a news article
- [ ] Edit the article
- [ ] Delete an article
- [ ] Create a menu
- [ ] Add menu items
- [ ] View dashboard statistics
- [ ] Logout

### Automated Tests
```bash
cd vendor/statisticlv/laravel-admin-panel
composer install
./vendor/bin/phpunit
```

---

## Troubleshooting

### Can't login?
Check `config/auth.php` has the admin guard:
```php
'guards' => [
    'admin' => [
        'driver' => 'session',
        'provider' => 'admin_users',
    ],
],
```

### Routes not working?
```bash
php artisan route:clear
php artisan config:clear
php artisan cache:clear
```

### Views not found?
```bash
php artisan view:clear
```

### Menu/News not showing?
Make sure they're marked as active and published.

---

## Production Checklist

Before deploying:

- [ ] Change admin credentials
- [ ] Enable HTTPS
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Configure proper database
- [ ] Set up backups
- [ ] Configure caching (Redis)
- [ ] Optimize autoloader: `composer install --optimize-autoloader --no-dev`
- [ ] Cache config: `php artisan config:cache`
- [ ] Cache routes: `php artisan route:cache`
- [ ] Cache views: `php artisan view:cache`

---

## Support

- 📖 Read full docs: `README.md`
- 🎨 Frontend guide: `FRONTEND_GUIDE.md`
- 🔧 Installation: `INSTALLATION.md`
- 🐛 Report issues: GitHub Issues

---

**You're all set!** 🚀

Start creating content in the admin panel and displaying it on your frontend.

For detailed examples and advanced usage, check out `FRONTEND_GUIDE.md`.
