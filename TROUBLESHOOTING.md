# Troubleshooting Guide

Common issues and their solutions for the Laravel Admin Panel.

## Table of Contents
- [Installation Issues](#installation-issues)
- [Authentication Issues](#authentication-issues)
- [Route Issues](#route-issues)
- [Menu Issues](#menu-issues)
- [View Issues](#view-issues)
- [Database Issues](#database-issues)

---

## Installation Issues

### "Class not found" Error

**Symptom**: Getting class not found errors after installation.

**Solution**:
```bash
composer dump-autoload
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

### Package Not Auto-Discovered

**Symptom**: Service provider not loading automatically.

**Solution**: Manually add to `config/app.php`:
```php
'providers' => [
    // ...
    StatisticLv\AdminPanel\AdminPanelServiceProvider::class,
],
```

### Migrations Not Running

**Symptom**: Tables not created after `php artisan migrate`.

**Solution**:
```bash
# Publish migrations first
php artisan vendor:publish --tag=admin-panel-migrations

# Then run migrations
php artisan migrate
```

---

## Authentication Issues

### Can't Login to Admin Panel

**Symptom**: Login form not working, redirects back.

**Solution 1**: Verify admin guard is configured in `config/auth.php`:
```php
'guards' => [
    'admin' => [
        'driver' => 'session',
        'provider' => 'admin_users',
    ],
],

'providers' => [
    'admin_users' => [
        'driver' => 'eloquent',
        'model' => StatisticLv\AdminPanel\Models\AdminUser::class,
    ],
],
```

**Solution 2**: Clear config cache:
```bash
php artisan config:clear
php artisan cache:clear
```

**Solution 3**: Make sure you created an admin user:
```bash
php artisan admin:create-user
```

### "Unauthenticated" Error on Admin Routes

**Symptom**: Getting redirected to login even after logging in.

**Solution**: Check session configuration in `.env`:
```env
SESSION_DRIVER=file
SESSION_LIFETIME=120
```

Clear sessions:
```bash
php artisan cache:clear
rm -rf storage/framework/sessions/*
```

### Can't Create Admin User

**Symptom**: Command fails with database error.

**Solution**: Make sure migrations have run:
```bash
php artisan migrate

# If migrations already ran, try:
php artisan migrate:fresh
```

---

## Route Issues

### Routes Not Working / 404 Errors

**Symptom**: Visiting `/admin` shows 404.

**Solution 1**: Clear route cache:
```bash
php artisan route:clear
php artisan route:cache
```

**Solution 2**: Check if service provider is loaded:
```bash
php artisan route:list | grep admin
```

You should see admin routes listed.

**Solution 3**: Verify route prefix in `config/admin-panel.php`:
```php
'route_prefix' => 'admin', // Change if needed
```

### Middleware Not Applied

**Symptom**: Can access admin routes without login.

**Solution**: Middleware should be automatically registered. Verify in service provider:
```php
// This is already done in AdminPanelServiceProvider
$router->aliasMiddleware('admin.auth', \StatisticLv\AdminPanel\Http\Middleware\AdminAuth::class);
```

---

## Menu Issues

### "Route [xxx] not defined" Error

**Symptom**: Error when editing menu that contains non-existent route names.

**Cause**: A menu item has a route name that doesn't exist in your Laravel application.

**Solution**: This is now fixed! The MenuItem model checks if the route exists before trying to resolve it. However, if you still encounter this:

1. Edit the menu item and either:
   - Remove the route name, OR
   - Add a valid URL instead, OR
   - Create the route in your application

2. The admin panel now safely displays menu items even if routes don't exist.

### Menu Items Not Displaying

**Symptom**: Created menu items don't show up.

**Solution 1**: Check if menu is active:
```php
// In database or admin panel
is_active = 1
```

**Solution 2**: Check ordering:
```sql
SELECT * FROM menu_items WHERE menu_id = 1 ORDER BY `order`;
```

**Solution 3**: Verify relationship:
```php
$menu = Menu::find(1);
dd($menu->items); // Should show items
```

### Submenu Items Not Working

**Symptom**: Child menu items not displaying.

**Solution**: Check `parent_id` is set correctly:
```sql
SELECT id, title, parent_id FROM menu_items WHERE menu_id = 1;
```

Parent items should have `parent_id = NULL`, children should reference parent's ID.

---

## View Issues

### Views Not Loading / Blank Pages

**Symptom**: Admin pages show blank or views not found.

**Solution 1**: Clear view cache:
```bash
php artisan view:clear
```

**Solution 2**: Publish views if you want to customize:
```bash
php artisan vendor:publish --tag=admin-panel-views
```

**Solution 3**: Check view path is correct:
```php
// Views should be in:
// vendor/statisticlv/laravel-admin-panel/resources/views
// OR (if published)
// resources/views/vendor/admin-panel
```

### Tailwind Styles Not Loading

**Symptom**: Admin panel looks unstyled.

**Solution**: The package uses Tailwind CSS from CDN. Check internet connection and that this line is in layout:
```html
<script src="https://cdn.tailwindcss.com"></script>
```

### Alpine.js Not Working

**Symptom**: Dropdowns and interactive elements not working.

**Solution**: Check Alpine.js is loaded:
```html
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
```

---

## Database Issues

### Foreign Key Constraint Errors

**Symptom**: Can't delete items due to foreign key constraints.

**Solution**: The migrations use `onDelete('cascade')` so related items should be deleted automatically. If you modified migrations:

```php
// Make sure cascading delete is set:
$table->foreignId('menu_id')
      ->constrained('menus')
      ->onDelete('cascade');
```

### Duplicate Entry Errors

**Symptom**: Can't create items with "Duplicate entry" error.

**Solution 1**: For slugs - leave slug field empty to auto-generate unique slug.

**Solution 2**: Check for existing data:
```sql
SELECT * FROM menus WHERE slug = 'your-slug';
SELECT * FROM news WHERE slug = 'your-slug';
```

### Column Not Found Errors

**Symptom**: SQL errors about missing columns.

**Solution**: Run migrations:
```bash
php artisan migrate

# Or reset and re-run:
php artisan migrate:fresh
php artisan migrate
```

---

## News Issues

### Can't Upload Images

**Symptom**: No way to upload featured images.

**Explanation**: The current version uses URL-based images. You need to:

1. Upload images to your server or CDN
2. Enter the full URL in the "Featured Image URL" field

**Alternative**: Integrate with a file upload service or modify the package to include file upload.

### Published News Not Showing

**Symptom**: Published news not appearing on frontend.

**Solution 1**: Check status and publish date:
```php
$news = News::where('status', 'published')
           ->whereNotNull('published_at')
           ->where('published_at', '<=', now())
           ->get();
```

**Solution 2**: Use the `published()` scope:
```php
$news = News::published()->get();
```

### Slug Already Exists

**Symptom**: Can't save news because slug is taken.

**Solution**: Either:
1. Enter a different slug manually, OR
2. Modify the existing news with that slug

---

## Frontend Integration Issues

### Helper Functions Not Found

**Symptom**: `Call to undefined function admin_menu()`

**Solution 1**: Make sure helpers are loaded:
```bash
composer dump-autoload
```

**Solution 2**: Check `composer.json` includes:
```json
"autoload": {
    "files": [
        "src/helpers.php"
    ]
}
```

**Solution 3**: Use the class directly:
```php
use StatisticLv\AdminPanel\Helpers\AdminPanelHelpers;

$menu = AdminPanelHelpers::getMenu('main-menu');
```

### Menu Not Rendering

**Symptom**: Menu returns null or empty.

**Solution**: Check menu exists and is active:
```php
$menu = \StatisticLv\AdminPanel\Models\Menu::where('slug', 'main-menu')
    ->where('is_active', true)
    ->first();
    
if (!$menu) {
    // Menu doesn't exist or is inactive
}
```

---

## Performance Issues

### Slow Admin Panel

**Solution 1**: Enable query caching:
```php
// In your code:
$menu = Cache::remember('menu.main', 3600, function() {
    return Menu::where('slug', 'main')->with('items.children')->first();
});
```

**Solution 2**: Use eager loading:
```php
// Already implemented in controllers, but verify:
News::with('author')->get();
Menu::with('items.children')->get();
```

**Solution 3**: Add database indexes:
```php
// Already in migrations, but verify:
$table->index('status');
$table->index('published_at');
$table->index('slug');
```

---

## Common Error Messages

### "Undefined variable: news"

**Cause**: Variable not passed to view.

**Solution**: Make sure controller passes the variable:
```php
return view('news.index', compact('news'));
```

### "Trying to get property of non-object"

**Cause**: Trying to access property on null object.

**Solution**: Add null checks:
```php
@if($article)
    {{ $article->title }}
@endif
```

Or:
```php
{{ $article->title ?? 'No title' }}
```

### "Class 'StatisticLv\AdminPanel\...' not found"

**Cause**: Namespace issues or autoload not updated.

**Solution**:
```bash
composer dump-autoload
php artisan clear-compiled
php artisan cache:clear
```

---

## Development Tips

### Enable Debug Mode

In `.env`:
```env
APP_DEBUG=true
```

### Check Logs

```bash
tail -f storage/logs/laravel.log
```

### Test Middleware

```bash
php artisan route:list --name=admin
```

Should show all admin routes with `admin.auth` middleware.

### Database Queries

Enable query logging to debug:
```php
\DB::enableQueryLog();
// Your code here
dd(\DB::getQueryLog());
```

---

## Getting Help

If you're still experiencing issues:

1. **Check logs**: `storage/logs/laravel.log`
2. **Enable debug mode**: Set `APP_DEBUG=true` in `.env`
3. **Clear everything**:
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan route:clear
   php artisan view:clear
   composer dump-autoload
   ```
4. **Review documentation**: 
   - README.md
   - INSTALLATION.md
   - FRONTEND_GUIDE.md
   - QUICKSTART.md

5. **Common solutions checklist**:
   - [ ] Migrations run successfully
   - [ ] Admin user created
   - [ ] Config cached cleared
   - [ ] Routes listed correctly
   - [ ] Middleware registered
   - [ ] Views loading
   - [ ] Database connection working
   - [ ] Composer autoload updated

---

## Reporting Issues

When reporting issues, please include:

1. **Error message** (full stack trace)
2. **Laravel version**: `php artisan --version`
3. **PHP version**: `php -v`
4. **Steps to reproduce**
5. **What you've tried**
6. **Relevant code snippets**
7. **Database migrations status**: `php artisan migrate:status`

---

Last Updated: December 23, 2025
