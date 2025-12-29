# Quick Reference - Laravel Admin Panel v2.0

## Installation

```bash
# With demo data (recommended for testing)
php artisan admin-panel:install --demo

# Clean install
php artisan admin-panel:install
```

## Demo Credentials

- **URL:** http://your-site.com/admin
- **Email:** admin@example.com
- **Password:** password

## Default URLs

| URL | Description |
|-----|-------------|
| `/` | Homepage |
| `/news` | News listing |
| `/news/{slug}` | News article |
| `/about` | About page (demo) |
| `/services` | Services page (demo) |
| `/contact` | Contact page (demo) |
| `/admin` | Admin dashboard |
| `/admin/news` | News management |
| `/admin/pages` | Pages management |
| `/admin/menus` | Menu management |

## Helper Functions

### Pages
```php
admin_page_by_slug('about')      // Get page by slug
admin_pages()                     // Get all pages
admin_pages(5)                    // Get 5 pages
```

### News
```php
admin_latest_news(5)              // Get latest news
admin_popular_news(5)             // Get popular news
admin_news_by_slug('welcome')     // Get news by slug
admin_news(null, 10)              // Paginated news
```

### Menus
```php
admin_menu('main-menu')           // Get menu
admin_render_menu('main-menu')    // Render menu HTML
```

### Utilities
```php
admin_format_date($date, 'F j, Y')    // Format date
admin_truncate($text, 150)             // Truncate text
admin_excerpt($html, 200)              // HTML excerpt
```

## Configuration

```php
// config/admin-panel.php

'route_prefix' => 'admin',              // Admin URL
'enable_frontend_routes' => true,       // Enable/disable routes
'title' => 'Admin Panel',               // Panel title
'per_page' => 15,                       // Items per page
```

## Artisan Commands

```bash
# Install package
php artisan admin-panel:install --demo

# Create admin user
php artisan admin:create-user

# Publish config
php artisan vendor:publish --tag=admin-panel-config

# Publish views
php artisan vendor:publish --tag=admin-panel-views

# Publish assets
php artisan vendor:publish --tag=admin-panel-assets

# Clear caches
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
```

## Common Tasks

### Create a Page

1. Admin → Pages → Create Page
2. Fill in title and content
3. Choose template
4. Click "Published"
5. Save

### Add Menu Item

1. Admin → Menus → Edit "Main Menu"
2. Scroll to "Add Menu Item"
3. Enter title and URL
4. Click "Add Item"

### Create News Article

1. Admin → News → Create News
2. Fill in title, content, excerpt
3. Set status to "Published"
4. Save

### Customize Homepage

```bash
php artisan vendor:publish --tag=admin-panel-views
```
Edit: `resources/views/vendor/admin-panel/frontend/home.blade.php`

### Disable Frontend Routes

```php
// config/admin-panel.php
'enable_frontend_routes' => false,
```

Then create your own routes in `routes/web.php`

## Templates

### Page Templates

- `default` - Standard layout
- `full-width` - Full width content
- `sidebar` - With sidebar
- `landing` - Landing page

### Create Custom Template

1. Create: `resources/views/frontend/pages/my-template.blade.php`
2. In admin, select "my-template" when creating page

## Troubleshooting

### Routes not working
```bash
php artisan route:clear
php artisan config:clear
```

### Views not loading
```bash
php artisan view:clear
```

### Database errors
```bash
php artisan migrate:fresh
php artisan admin-panel:install --demo
```

### Homepage 404
Check: `'enable_frontend_routes' => true` in config

## Production Checklist

- [ ] Change admin password
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Configure database
- [ ] Set up backups
- [ ] Enable HTTPS
- [ ] Cache config: `php artisan config:cache`
- [ ] Cache routes: `php artisan route:cache`
- [ ] Cache views: `php artisan view:cache`
- [ ] Optimize: `php artisan optimize`

## Support

- **Documentation:** FEATURES_V2.md
- **Installation:** INSTALLATION.md
- **API:** README.md
- **Issues:** Report on GitHub

## Version Info

- **Current Version:** 2.0
- **Laravel:** 10.x / 11.x
- **PHP:** 8.1+

---

**Need help?** Check the full documentation in `FEATURES_V2.md`
