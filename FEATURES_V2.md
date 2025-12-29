# Laravel Admin Panel - New Features & Installation Guide

## What's New in Version 2.0

### 🎉 Major Updates

1. **Pages Management System** - Create and manage static pages with SEO support
2. **Automatic Frontend Setup** - Default homepage, news listing, and page routes 
3. **One-Command Installation** - Install with demo data using `php artisan admin-panel:install --demo`
4. **Professional Default Theme** - Beautiful, responsive design out of the box

---

## Quick Installation

### Option 1: Install with Demo Data (Recommended)

```bash
composer require statisticlv/laravel-admin-panel
php artisan admin-panel:install --demo
```

That's it! Your site is ready with:
- ✅ Homepage at `/`
- ✅ News section at `/news`
- ✅ Sample pages (About, Services, Contact)
- ✅ Working navigation menu
- ✅ Admin panel at `/admin`
- ✅ Demo admin user (admin@example.com / password)

### Option 2: Clean Installation

```bash
composer require statisticlv/laravel-admin-panel
php artisan admin-panel:install
```

Follow the prompts to create your admin user.

---

## What Gets Installed

### 1. Frontend Routes (Automatic)

The package automatically creates these routes:

- `/` - Homepage with latest news
- `/news` - News listing page
- `/news/{slug}` - Individual news article
- `/{slug}` - Dynamic page routing

**To disable automatic routes**, set in `config/admin-panel.php`:
```php
'enable_frontend_routes' => false,
```

### 2. Admin Routes

- `/admin` - Admin dashboard
- `/admin/news` - News management
- `/admin/pages` - Pages management  
- `/admin/menus` - Menu management

### 3. Demo Data (with `--demo` flag)

**Admin User:**
- Email: admin@example.com
- Password: password

**Sample Content:**
- 3 News articles
- 3 Pages (About, Services, Contact)
- Main navigation menu
- Footer menu

---

## Pages Management

### Creating Pages

1. Go to Admin Panel → **Pages** → **Create Page**
2. Fill in the details:
   - **Title** - Page title
   - **Slug** - URL slug (auto-generated from title)
   - **Content** - Page content (HTML supported)
   - **Template** - Choose layout template
   - **SEO Settings** - Meta title, description, keywords
   - **Published** - Toggle visibility
   - **Order** - Display order

3. Click **Create Page**

### Available Templates

- **default** - Standard page layout
- **full-width** - Full-width content
- **sidebar** - With sidebar
- **landing** - Landing page style

### Accessing Pages

Pages are accessible at `/{slug}`. For example:
- `http://yoursite.com/about`
- `http://yoursite.com/services`
- `http://yoursite.com/contact`

### Using Pages in Code

```php
// Get a page
$page = admin_page_by_slug('about');

// Get all published pages
$pages = admin_pages();

// Get limited pages
$pages = admin_pages(5);

// Display page
@if($page)
    <h1>{{ $page->title }}</h1>
    {!! $page->content !!}
@endif
```

---

## Frontend Customization

### Homepage

The default homepage displays:
- Hero section with welcome message
- Latest 6 news articles
- Links to News and About pages

**To customize**, publish views:
```bash
php artisan vendor:publish --tag=admin-panel-views
```

Then edit: `resources/views/vendor/admin-panel/frontend/home.blade.php`

### Layout

The frontend layout includes:
- Responsive navigation menu
- Footer with links and latest news
- Mobile-friendly design

**To customize**, edit:
`resources/views/vendor/admin-panel/frontend/layouts/app.blade.php`

### Navigation Menu

The navigation is automatically pulled from the "Main Menu" in admin panel.

To add/edit menu items:
1. Go to Admin → **Menus**
2. Edit "Main Menu"
3. Add/remove/reorder items

### Styling

The frontend uses Tailwind CSS from CDN. To add custom styles:

1. Publish assets:
```bash
php artisan vendor:publish --tag=admin-panel-assets
```

2. Add your CSS to: `public/vendor/admin-panel/css/admin.css`

3. Link in your layout:
```html
<link rel="stylesheet" href="{{ asset('vendor/admin-panel/css/admin.css') }}">
```

---

## Configuration

Edit `config/admin-panel.php`:

```php
return [
    // Admin URL prefix
    'route_prefix' => 'admin',
    
    // Enable/disable frontend routes
    'enable_frontend_routes' => true,
    
    // Admin panel title
    'title' => 'Admin Panel',
    
    // Items per page
    'per_page' => 15,
    
    // Date format
    'date_format' => 'Y-m-d H:i:s',
];
```

---

## Using Your Own Routes

If you want to handle routes in your own application:

1. Disable package routes in `config/admin-panel.php`:
```php
'enable_frontend_routes' => false,
```

2. Create your own routes in `routes/web.php`:
```php
use StatisticLv\AdminPanel\Models\News;
use StatisticLv\AdminPanel\Models\Page;

Route::get('/', function() {
    $news = admin_latest_news(6);
    return view('welcome', compact('news'));
});

Route::get('/news', function() {
    $news = News::published()->paginate(12);
    return view('news.index', compact('news'));
});

Route::get('/news/{slug}', function($slug) {
    $article = News::where('slug', $slug)->published()->firstOrFail();
    $article->incrementViews();
    return view('news.show', compact('article'));
});

Route::get('/{slug}', function($slug) {
    $page = Page::where('slug', $slug)->published()->firstOrFail();
    return view('page.show', compact('page'));
});
```

---

## Helper Functions

### Pages
```php
// Get page by slug
$page = admin_page_by_slug('about');

// Get all published pages
$pages = admin_pages();

// Get limited pages
$pages = admin_pages(5);
```

### News
```php
// Get latest news
$news = admin_latest_news(5);

// Get popular news
$news = admin_popular_news(5);

// Get news by slug
$article = admin_news_by_slug('welcome');

// Get all news with pagination
$news = admin_news(null, 10);
```

### Menus
```php
// Get menu
$menu = admin_menu('main-menu');

// Render menu HTML
echo admin_render_menu('main-menu');
```

### Utilities
```php
// Format date
echo admin_format_date($date, 'F j, Y');

// Truncate text
echo admin_truncate($text, 150);

// Get excerpt from HTML
echo admin_excerpt($html, 200);
```

---

## SEO Best Practices

### For Pages

Pages include built-in SEO fields:
- Meta Title
- Meta Description
- Meta Keywords

```blade
<!-- Automatically included in page templates -->
<meta name="description" content="{{ $page->meta_description }}">
<meta name="keywords" content="{{ $page->meta_keywords }}">
```

### For News

Add meta tags in your news template:
```blade
@section('meta')
    <meta name="description" content="{{ $article->excerpt }}">
    <meta property="og:title" content="{{ $article->title }}">
    <meta property="og:image" content="{{ $article->featured_image }}">
@endsection
```

---

## Updating from Version 1.0

If you're upgrading from version 1.0:

1. **Backup your database**

2. **Update composer:**
```bash
composer update statisticlv/laravel-admin-panel
```

3. **Publish new migrations:**
```bash
php artisan vendor:publish --tag=admin-panel-migrations --force
```

4. **Run migrations:**
```bash
php artisan migrate
```

5. **Clear caches:**
```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

6. **(Optional) Install demo data:**
```bash
php artisan db:seed --class="StatisticLv\AdminPanel\Database\Seeders\DemoDataSeeder"
```

---

## Troubleshooting

### Routes Conflict

If you get route conflicts:
1. Disable package routes: `'enable_frontend_routes' => false` in config
2. Define your own routes in `routes/web.php`

### Views Not Loading

```bash
php artisan view:clear
php artisan config:clear
```

### Database Errors

```bash
php artisan migrate:fresh
php artisan admin-panel:install --demo
```

### "Page not found" on homepage

Make sure frontend routes are enabled:
```php
// config/admin-panel.php
'enable_frontend_routes' => true,
```

Then clear cache:
```bash
php artisan route:clear
```

---

## Production Checklist

Before deploying:

- [ ] Change admin password
- [ ] Update `APP_NAME` in `.env`
- [ ] Set `'enable_frontend_routes' => false` if using custom routes
- [ ] Clear and cache everything:
  ```bash
  php artisan config:cache
  php artisan route:cache
  php artisan view:cache
  ```
- [ ] Set up proper database backups
- [ ] Configure caching (Redis recommended)
- [ ] Enable HTTPS
- [ ] Test all pages and forms

---

## What's Next?

Now that your site is installed:

1. **Customize Content**
   - Login to admin panel
   - Edit demo pages or create new ones
   - Add your own news articles
   - Customize menus

2. **Customize Design**
   - Publish views and edit templates
   - Add your logo and branding
   - Customize colors and styles

3. **Add Features**
   - Create custom page templates
   - Add contact forms
   - Integrate with your services
   - Add more menu locations

---

## Support

- 📖 Documentation: See README.md
- 🐛 Issues: Report on GitHub
- 💬 Questions: Create a discussion

---

**Enjoy your new Laravel Admin Panel! 🚀**
