# Laravel Admin Panel

[![Latest Version](https://img.shields.io/packagist/v/statisticlv/laravel-admin-panel)](https://packagist.org/packages/statisticlv/laravel-admin-panel)
[![Total Downloads](https://img.shields.io/packagist/dt/statisticlv/laravel-admin-panel)](https://packagist.org/packages/statisticlv/laravel-admin-panel)
[![License](https://img.shields.io/packagist/l/statisticlv/laravel-admin-panel)](https://packagist.org/packages/statisticlv/laravel-admin-panel)
[![PHP Version](https://img.shields.io/packagist/php-v/statisticlv/laravel-admin-panel)](https://packagist.org/packages/statisticlv/laravel-admin-panel)
[![Laravel](https://img.shields.io/badge/laravel-10.x%20%7C%2011.x%20%7C%2012.x-ff2d20.svg)](https://laravel.com)

A comprehensive admin panel for Laravel with authentication, news management, and menu management functionality.

## Features

- **Authentication System**: Secure admin authentication with rate limiting and password strength validation
- **News Management**: Create, edit, and manage news articles with draft/published/archived status
- **Menu Management**: Dynamic menu system with hierarchical structure and multiple locations
- **Role-Based Access**: Admin and Super Admin roles with appropriate permissions
- **Soft Deletes**: All models support soft deletes for data recovery
- **Caching**: Built-in caching for improved performance
- **Logging**: Comprehensive logging for security and audit trails
- **Type Safety**: Full type hints and PHPDoc blocks throughout

## Installation

### Requirements

- PHP 8.1 or higher
- Laravel 10.x, 11.x, or 12.x

### Steps

1. Install the package via Composer:
```bash
composer require statisticlv/laravel-admin-panel
```

2. Publish the configuration file:
```bash
php artisan vendor:publish --tag=admin-panel-config
```

3. Run the migrations:
```bash
php artisan migrate
```

4. Create an admin user:
```bash
php artisan admin:create-user
```

## Configuration

The configuration file is located at `config/admin-panel.php`. You can customize:

- Route prefix (default: `admin`)
- Middleware stack
- Admin guard
- Panel title
- Pagination settings
- Date format

## Security Features

### Rate Limiting
Login attempts are rate-limited to 5 attempts per minute per IP address to prevent brute force attacks.

### Password Strength
Passwords must meet the following requirements:
- Minimum 8 characters
- At least one uppercase letter
- At least one lowercase letter
- At least one number
- Special characters recommended

### Authorization
- Only Super Admins can delete menus
- Only Super Admins or the original author can delete news articles
- Inactive users cannot access the admin panel

## Models

### AdminUser
Admin user model with authentication support.

**Properties:**
- `name` - User's full name
- `email` - User's email (unique)
- `password` - Hashed password
- `role` - User role: `admin` or `super_admin`
- `is_active` - Whether the account is active

**Methods:**
- `isSuperAdmin()` - Check if user is a super admin
- `isAdmin()` - Check if user is an admin (regular or super)

### News
News article model with publishing workflow.

**Properties:**
- `title` - Article title
- `slug` - URL-friendly slug (auto-generated)
- `excerpt` - Short summary
- `content` - Full article content
- `featured_image` - URL to featured image
- `status` - Article status: `draft`, `published`, or `archived`
- `published_at` - Publication date
- `author_id` - Foreign key to admin_users
- `views_count` - View counter

**Scopes:**
- `published()` - Filter published articles
- `draft()` - Filter draft articles

**Methods:**
- `isPublished()` - Check if article is published
- `incrementViews()` - Increment the view counter

### Menu
Menu model for organizing navigation items.

**Properties:**
- `name` - Menu display name
- `slug` - URL-friendly slug (auto-generated)
- `location` - Menu location: `main`, `footer`, or `sidebar`
- `is_active` - Whether the menu is active

**Relationships:**
- `items()` - Root menu items (no parent)
- `allItems()` - All menu items (including nested)

### MenuItem
Individual menu item with hierarchical support.

**Properties:**
- `menu_id` - Foreign key to menus
- `parent_id` - Parent menu item (for nesting)
- `title` - Item display text
- `url` - Direct URL
- `route` - Laravel route name
- `target` - Link target: `_self` or `_blank`
- `css_class` - Custom CSS classes
- `order` - Display order
- `is_active` - Whether the item is active

**Relationships:**
- `menu()` - Parent menu
- `parent()` - Parent menu item
- `children()` - Child menu items

## Helper Functions

The package provides several helper functions:

### Menu Functions
```php
// Get a menu by slug
$menu = admin_menu('main-menu');

// Get a menu by location
$menu = admin_menu('main', 'location');

// Render a menu as HTML
echo admin_render_menu('main-menu');

// Render with custom options
echo admin_render_menu('main-menu', 'slug', [
    'ul_class' => 'custom-menu',
    'li_class' => 'custom-item',
]);
```

### News Functions
```php
// Get published news
$news = admin_news(limit: 10);

// Get paginated news
$news = admin_news(paginate: 15);

// Get a single article by slug
$article = admin_news_by_slug('my-article');

// Get latest news
$latest = admin_latest_news(5);

// Get popular news (by views)
$popular = admin_popular_news(5);
```

### Utility Functions
```php
// Format a date
echo admin_format_date($date, 'Y-m-d');

// Truncate text
echo admin_truncate($text, 100);

// Get excerpt from HTML
echo admin_excerpt($htmlContent, 200);
```

## Routes

All admin routes are prefixed with the configured route prefix (default: `admin`).

### Authentication
- `GET /admin/login` - Login form
- `POST /admin/login` - Login submit
- `POST /admin/logout` - Logout

### Dashboard
- `GET /admin` - Dashboard
- `GET /admin/dashboard` - Dashboard

### News Management
- `GET /admin/news` - List news
- `GET /admin/news/create` - Create form
- `POST /admin/news` - Store news
- `GET /admin/news/{news}` - Show news
- `GET /admin/news/{news}/edit` - Edit form
- `PUT /admin/news/{news}` - Update news
- `DELETE /admin/news/{news}` - Delete news

### Menu Management
- `GET /admin/menus` - List menus
- `GET /admin/menus/create` - Create form
- `POST /admin/menus` - Store menu
- `GET /admin/menus/{menu}` - Show menu
- `GET /admin/menus/{menu}/edit` - Edit form
- `PUT /admin/menus/{menu}` - Update menu
- `DELETE /admin/menus/{menu}` - Delete menu
- `POST /admin/menus/{menu}/items` - Add menu item
- `PUT /admin/menus/{menu}/items/{item}` - Update menu item
- `DELETE /admin/menus/{menu}/items/{item}` - Delete menu item

## Caching

The package uses Laravel's cache system to improve performance:

- Dashboard stats: Cached for 5 minutes
- Menus: Cached for 1 hour
- News articles: Cached for 5-10 minutes depending on query type

Cache is automatically cleared when data is modified.

## Testing

Run the test suite:

```bash
php artisan test
```

The package includes comprehensive tests for:
- Authentication
- Authorization
- Rate limiting
- CRUD operations

## Logging

All important actions are logged for security and audit purposes:

- Login attempts (success and failure)
- Unauthorized access attempts
- CRUD operations (create, update, delete)
- Inactive user access attempts

Logs are stored in Laravel's default log channels.

## Security Best Practices

1. **Always use HTTPS** in production
2. **Keep dependencies updated**
3. **Use strong passwords** (enforced by the system)
4. **Review logs regularly** for suspicious activity
5. **Implement 2FA** for additional security (not included by default)
6. **Use environment variables** for sensitive configuration

## Troubleshooting

### Login Issues
- Check that the user is active (`is_active = true`)
- Verify the password meets strength requirements
- Check rate limiting logs if login is blocked

### Cache Issues
Clear the cache if changes don't appear:
```bash
php artisan cache:clear
```

### Migration Issues
If you have existing tables, use the soft deletes migration:
```bash
php artisan migrate --path=database/migrations/2024_01_01_000004_add_soft_deletes_to_existing_tables.php
```

## License

MIT License - see LICENSE file for details.

## Support

For issues and feature requests, please use the project's issue tracker.
