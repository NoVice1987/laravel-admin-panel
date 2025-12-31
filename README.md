# Laravel Admin Panel

A comprehensive admin panel package for Laravel with authentication, content management, and frontend integration. This package provides a complete CMS solution with news management, pages, menus, settings, and a built-in frontend.

## Version

**Current Version:** 1.0.0

## Features

- 🔐 **Secure Authentication** - Separate admin authentication with role-based access control (admin, super_admin)
- 📰 **News Management** - Create, edit, publish, and archive news articles with featured images
- 📄 **Page Management** - Manage static pages with custom templates and SEO metadata
- 🗂️ **Menu Management** - Create and manage nested menu structures for different locations
- ⚙️ **Settings Management** - Dynamic settings system with grouping and type casting
- 🎨 **Frontend Integration** - Built-in frontend routes and controllers for immediate use
- 🔄 **Soft Deletes** - All main models support soft deletes for data recovery
- 🔗 **Slug Generation** - Automatic URL-friendly slug generation
- 💾 **Caching** - Built-in cache management for improved performance
- 📝 **Activity Logging** - Automatic logging of admin actions
- ✅ **Testing** - PHPUnit tests included for authentication features

## Requirements

- PHP 8.1 or higher
- Laravel 10.0, 11.0, or 12.0

## Installation

### Step 1: Install via Composer

```bash
composer require statisticlv/laravel-admin-panel
```

### Step 2: Install the Package

Run the installation command to publish all necessary files:

```bash
php artisan admin-panel:install
```

The installation command will:
- Publish configuration file to `config/admin-panel.php`
- Publish database migrations
- Publish controllers to `app/Http/Controllers/`
- Publish web routes to `routes/web.php`
- Publish views to `resources/views/`
- Publish assets to `public/vendor/admin-panel/`
- Run database migrations
- Seed settings table
- Optionally install demo data

### Step 3: Install with Demo Data (Optional)

To install with sample data for testing:

```bash
php artisan admin-panel:install --demo
```

Demo credentials:
- **Email:** admin@example.com
- **Password:** password

## Configuration

After installation, you can customize the package by editing `config/admin-panel.php`:

```php
return [
    // Admin panel route prefix
    'route_prefix' => 'admin',

    // Middleware applied to admin routes
    'middleware' => ['web'],

    // Enable default frontend routes
    'enable_frontend_routes' => true,

    // Authentication guard
    'guard' => 'admin',

    // Admin panel title
    'title' => 'Admin Panel',

    // Items per page in list views
    'per_page' => 15,

    // Default date format
    'date_format' => 'Y-m-d H:i:s',
];
```

## Available Commands

### Create Admin User

Create a new admin user via command line:

```bash
php artisan admin:create-user
```

Options:
- `--name` - The name of the admin user
- `--email` - The email of the admin user
- `--password` - The password for the admin user
- `--role` - The role (admin or super_admin, default: admin)

Example:
```bash
php artisan admin:create-user --name="John Doe" --email="john@example.com" --password="SecurePass123" --role="super_admin"
```

## Database Tables

The package creates the following tables:

- `admin_users` - Admin user accounts with roles and status
- `news` - News articles with status, author, and view counts
- `pages` - Static pages with templates and SEO metadata
- `menus` - Menu definitions with locations
- `menu_items` - Individual menu items with support for nesting
- `settings` - Dynamic key-value settings with grouping

## Models

### AdminUser

Admin user model with role-based access control.

```php
use StatisticLv\AdminPanel\Models\AdminUser;

// Check if user is super admin
$user->isSuperAdmin(); // bool

// Check if user is admin (regular or super)
$user->isAdmin(); // bool

// Get news articles by author
$user->news;
```

### News

News article model with publishing workflow.

```php
use StatisticLv\AdminPanel\Models\News;

// Get published news
$publishedNews = News::published()->get();

// Get draft news
$draftNews = News::draft()->get();

// Check if published
$news->isPublished(); // bool

// Increment view count
$news->incrementViews();
```

### Page

Page model with SEO support.

```php
use StatisticLv\AdminPanel\Models\Page;

// Get published pages
$pages = Page::published()->get();

// Get meta title (falls back to title)
$page->meta_title;

// Get meta description (falls back to excerpt)
$page->meta_description;
```

### Menu & MenuItem

Menu management with nested structure.

```php
use StatisticLv\AdminPanel\Models\Menu;

$menu = Menu::where('slug', 'main')->first();

// Get root items only
$items = $menu->items;

// Get all items including nested
$allItems = $menu->allItems;
```

### Setting

Dynamic settings management.

```php
use StatisticLv\AdminPanel\Models\Setting;

// Get a setting value
$value = Setting::getValue('site_name', 'Default Name');

// Set a setting value
Setting::setValue('site_name', 'My Site');

// Get all settings grouped
$groupedSettings = Setting::getAllGrouped();

// Get all settings as array
$settingsArray = Setting::getAllAsArray();
```

## Helper Functions

The package provides global helper functions for easy access to data:

### Menu Helpers

```php
// Get a menu by identifier
$menu = admin_menu('main');

// Render a menu as HTML
echo admin_render_menu('main', 'slug', ['class' => 'nav-menu']);
```

### News Helpers

```php
// Get published news
$news = admin_news($limit = 10, $paginate = 15);

// Get news by slug
$article = admin_news_by_slug('my-article');

// Get latest news
$latest = admin_latest_news(5);

// Get popular news
$popular = admin_popular_news(5);
```

### Page Helpers

```php
// Get page by slug
$page = admin_page_by_slug('about-us');

// Get all published pages
$pages = admin_pages($limit = 10);
```

### Utility Helpers

```php
// Format date
$formattedDate = admin_format_date($date, 'Y-m-d');

// Truncate text
$truncated = admin_truncate($text, 100, '...');

// Get excerpt from HTML
$excerpt = admin_excerpt($htmlContent, 200);
```

## Routes

### Admin Routes (Prefix: /admin)

- `GET /admin/login` - Login page
- `POST /admin/login` - Login action
- `POST /admin/logout` - Logout action
- `GET /admin` - Dashboard
- `GET /admin/dashboard` - Dashboard
- `GET|POST|PUT|DELETE /admin/news` - News management (CRUD)
- `GET|POST|PUT|DELETE /admin/pages` - Page management (CRUD)
- `GET|POST|PUT|DELETE /admin/menus` - Menu management (CRUD)
- `POST /admin/menus/{menu}/items` - Add menu item
- `PUT /admin/menus/{menu}/items/{item}` - Update menu item
- `DELETE /admin/menus/{menu}/items/{item}` - Delete menu item
- `GET|POST|PUT|DELETE /admin/settings` - Settings management

### Frontend Routes

- `GET /` - Homepage
- `GET /news` - News listing
- `GET /news/{slug}` - Single news article
- `GET /{slug}` - Page (catch-all route)

## Authentication

The package uses a separate authentication guard for admin users:

```php
// Authenticate admin user
if (Auth::guard('admin')->attempt($credentials)) {
    // Success
}

// Get authenticated admin user
$admin = Auth::guard('admin')->user();

// Check authentication
if (Auth::guard('admin')->check()) {
    // User is authenticated
}
```

### Roles

Two roles are available:
- `admin` - Regular admin with standard permissions
- `super_admin` - Super admin with full permissions

### Middleware

The package includes the `admin.auth` middleware to protect admin routes:

```php
Route::middleware(['admin.auth'])->group(function () {
    // Protected admin routes
});
```

## Views

The package publishes views to `resources/views/`:

- `auth/login.blade.php` - Admin login page
- `dashboard/index.blade.php` - Admin dashboard
- `news/index.blade.php` - News listing
- `news/create.blade.php` - Create news form
- `news/edit.blade.php` - Edit news form
- `pages/index.blade.php` - Pages listing
- `pages/create.blade.php` - Create page form
- `pages/edit.blade.php` - Edit page form
- `menus/index.blade.php` - Menus listing
- `menus/create.blade.php` - Create menu form
- `menus/edit.blade.php` - Edit menu form
- `settings/index.blade.php` - Settings listing
- `settings/edit.blade.php` - Edit settings form
- `frontend/home.blade.php` - Homepage
- `frontend/news/index.blade.php` - News listing (frontend)
- `frontend/news/show.blade.php` - Single news article (frontend)
- `frontend/pages/default.blade.php` - Default page template (frontend)
- `layouts/app.blade.php` - Admin layout
- `frontend/layouts/app.blade.php` - Frontend layout

## Customization

### Customizing Controllers

After installation, controllers are published to `app/Http/Controllers/`. You can modify them to add custom logic:

```php
// app/Http/Controllers/NewsController.php

namespace App\Http\Controllers;

use StatisticLv\AdminPanel\Models\News;

class NewsController extends \StatisticLv\AdminPanel\Http\Controllers\NewsController
{
    // Override methods or add new ones
}
```

### Customizing Views

All views are published to `resources/views/` and can be customized as needed.

### Customizing Routes

The web routes file is published to `routes/web.php`. You can modify routes or add new ones.

## Testing

Run the included tests:

```bash
php artisan test
```

Tests cover:
- Admin authentication
- Login validation
- Rate limiting
- Authorization checks
- Session management

## Security

- Password strength validation (minimum 8 characters, uppercase, lowercase, number)
- Rate limiting on login attempts
- Role-based access control
- CSRF protection
- SQL injection protection via Eloquent ORM

## Performance

- Database indexing on frequently queried columns
- Soft deletes for data recovery
- Caching support for news articles
- Optimized queries with eager loading

## Support

- **Issues:** https://github.com/statisticlv/laravel-admin-panel/issues
- **Source:** https://github.com/statisticlv/laravel-admin-panel
- **Documentation:** https://github.com/statisticlv/laravel-admin-panel/blob/main/README.md

## License

This package is open-source software licensed under the [MIT License](LICENSE).

## Credits

- **Developer:** StatisticLv
- **Email:** contact@statistic.lv
- **Website:** https://statistic.lv

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## Changelog

See [CHANGELOG.md](CHANGELOG.md) for version history and changes.
