# Laravel Admin Panel Installation Guide

## Step-by-Step Installation

### 1. Install the Package

If installing from a local directory:

```bash
composer require statisticlv/laravel-admin-panel
```

Or add to your `composer.json`:

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

### 2. Publish Package Assets

Publish configuration, migrations, and views:

```bash
php artisan vendor:publish --provider="StatisticLv\AdminPanel\AdminPanelServiceProvider"
```

Or publish individually:

```bash
# Publish config only
php artisan vendor:publish --tag=admin-panel-config

# Publish migrations only
php artisan vendor:publish --tag=admin-panel-migrations

# Publish views only (for customization)
php artisan vendor:publish --tag=admin-panel-views
```

### 3. Configure Authentication Guard

Add the admin guard to your `config/auth.php`:

```php
'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',
    ],
    
    'admin' => [
        'driver' => 'session',
        'provider' => 'admin_users',
    ],
],

'providers' => [
    'users' => [
        'driver' => 'eloquent',
        'model' => App\Models\User::class,
    ],
    
    'admin_users' => [
        'driver' => 'eloquent',
        'model' => StatisticLv\AdminPanel\Models\AdminUser::class,
    ],
],
```

### 4. Run Migrations

```bash
php artisan migrate
```

This will create the following tables:
- `admin_users` - Admin user accounts
- `news` - News/posts management
- `menus` - Menu definitions
- `menu_items` - Menu item entries

### 5. Create Your First Admin User

```bash
php artisan admin:create-user
```

Follow the prompts to enter:
- Name
- Email
- Password
- Role (admin or super_admin)

Or use options:

```bash
php artisan admin:create-user --name="Admin User" --email="admin@example.com" --password="secure123" --role="super_admin"
```

### 6. Access the Admin Panel

Visit: `http://your-domain.com/admin`

Login with the credentials you just created.

## Configuration

Edit `config/admin-panel.php` to customize:

```php
return [
    // Change the URL prefix
    'route_prefix' => 'admin',
    
    // Add middleware
    'middleware' => ['web'],
    
    // Authentication guard
    'guard' => 'admin',
    
    // Panel title
    'title' => 'Admin Panel',
    
    // Items per page
    'per_page' => 15,
    
    // Date format
    'date_format' => 'Y-m-d H:i:s',
];
```

## Usage

### Creating News/Posts

1. Navigate to **News** in the admin panel
2. Click **Create News**
3. Fill in the form:
   - Title (required)
   - Slug (auto-generated if left empty)
   - Excerpt
   - Content (required)
   - Featured Image URL
   - Status (draft/published/archived)
   - Publish Date
4. Click **Create News**

### Managing Menus

1. Navigate to **Menus** in the admin panel
2. Click **Create Menu**
3. Set menu name, slug, and location
4. After creating, click **Edit** to add menu items
5. Add items with:
   - Title
   - URL or Route name
   - Parent item (for submenus)
   - Order
   - CSS classes

### Displaying Menus in Your Frontend

In your Blade templates:

```php
@php
    $menu = \StatisticLv\AdminPanel\Models\Menu::where('slug', 'main-menu')
        ->where('is_active', true)
        ->with('items.children')
        ->first();
@endphp

@if($menu)
    <nav>
        <ul>
            @foreach($menu->items as $item)
                <li>
                    <a href="{{ $item->url }}" target="{{ $item->target }}">
                        {{ $item->title }}
                    </a>
                    
                    @if($item->children->count() > 0)
                        <ul>
                            @foreach($item->children as $child)
                                <li>
                                    <a href="{{ $child->url }}" target="{{ $child->target }}">
                                        {{ $child->title }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
        </ul>
    </nav>
@endif
```

### Displaying News on Frontend

```php
// Get published news
$news = \StatisticLv\AdminPanel\Models\News::published()
    ->orderBy('published_at', 'desc')
    ->paginate(10);

// Single news page
$article = \StatisticLv\AdminPanel\Models\News::where('slug', $slug)
    ->published()
    ->firstOrFail();
```

## Customization

### Customizing Views

Publish the views to customize them:

```bash
php artisan vendor:publish --tag=admin-panel-views
```

Views will be copied to `resources/views/vendor/admin-panel/`

### Adding Custom Middleware

In `config/admin-panel.php`:

```php
'middleware' => ['web', 'your-custom-middleware'],
```

### Extending Models

You can extend the package models in your application:

```php
namespace App\Models;

use StatisticLv\AdminPanel\Models\News as BaseNews;

class News extends BaseNews
{
    // Add your custom methods
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}
```

## Troubleshooting

### "Class not found" errors

Run:
```bash
composer dump-autoload
php artisan config:clear
php artisan cache:clear
```

### Routes not working

Clear route cache:
```bash
php artisan route:clear
```

### Authentication issues

Make sure the admin guard is properly configured in `config/auth.php`

## Security Considerations

1. **Change default credentials** immediately after installation
2. **Use strong passwords** for admin accounts
3. **Enable HTTPS** in production
4. **Regular updates** - keep Laravel and this package updated
5. **Backup database** regularly

## Support

For issues, questions, or contributions, please visit the GitHub repository or contact support.
