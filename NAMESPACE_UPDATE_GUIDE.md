# Namespace Update Guide

This guide will help you update all namespace references in the codebase from the placeholder `statisticlv\AdminPanel` to your actual vendor namespace.

## Why Update Namespaces?

The placeholder namespace `statisticlv\AdminPanel` must be replaced with your actual vendor name before publishing. This ensures:
- Unique package identification
- Proper Composer autoloading
- No conflicts with other packages

## Step 1: Choose Your Vendor Name

Your vendor name should be:
- **Unique**: Not used by other packages on Packagist
- **Descriptive**: Related to you or your organization
- **Consistent**: Use the same name across all projects

**Examples:**
- Your GitHub username: `johndoe/laravel-admin-panel`
- Your organization: `acmecorp/laravel-admin-panel`
- Your brand: `mybrand/laravel-admin-panel`

**Check availability:**
1. Go to [Packagist.org](https://packagist.org/)
2. Search for your desired vendor name
3. Ensure no existing packages use it

## Step 2: Update composer.json

### Change Package Name

```json
{
    "name": "statisticlv/laravel-admin-panel"
}
```

Replace `statisticlv` with your actual vendor name.

### Change Namespace in Autoloader

```json
{
    "autoload": {
        "psr-4": {
            "statisticlv\\AdminPanel\\": "src/"
        }
    },
    "autoload-dev": {
        "psr-4": {
            "statisticlv\\AdminPanel\\Tests\\": "tests/"
        }
    }
}
```

Replace `statisticlv` with your actual vendor name (PascalCase).

### Change Service Provider

```json
{
    "extra": {
        "laravel": {
            "providers": [
                "statisticlv\\AdminPanel\\AdminPanelServiceProvider"
            ]
        }
    }
}
```

Replace `statisticlv` with your actual vendor name.

## Step 3: Update All PHP Files

### Files to Update

Update the namespace declaration at the top of every PHP file in:

1. **src/** directory (all subdirectories)
2. **tests/** directory (all subdirectories)
3. **config/** directory (if any PHP files)

### Example Transformation

**Before:**
```php
<?php

namespace statisticlv\AdminPanel\Http\Controllers;

use statisticlv\AdminPanel\Models\News;
```

**After (for vendor `johndoe`):**
```php
<?php

namespace JohnDoe\AdminPanel\Http\Controllers;

use JohnDoe\AdminPanel\Models\News;
```

### Manual Update Method

For each PHP file:
1. Open the file
2. Find the `namespace` declaration at the top
3. Replace `statisticlv` with your vendor name (PascalCase)
4. Find any `use` statements referencing the package
5. Replace `statisticlv` in those statements

### Automated Update Method (Recommended)

Use this script to update all files automatically:

```bash
#!/bin/bash

# Replace YOUR_VENDOR with your actual vendor name (PascalCase)
# Example: JohnDoe, AcmeCorp, MyBrand

VENDOR_NAME="YOUR_VENDOR"

# Update all PHP files in src/
find src/ -type f -name "*.php" -exec sed -i "s/statisticlv\\\/${VENDOR_NAME}\\\/g" {} \;

# Update all PHP files in tests/
find tests/ -type f -name "*.php" -exec sed -i "s/statisticlv\\\/${VENDOR_NAME}\\\/g" {} \;

# Update composer.json
sed -i "s/statisticlv\\\\AdminPanel/${VENDOR_NAME}\\\\AdminPanel/g" composer.json

echo "Namespace updated to ${VENDOR_NAME}\\AdminPanel"
```

**For Windows (PowerShell):**

```powershell
# Replace YOUR_VENDOR with your actual vendor name (PascalCase)
$vendorName = "YOUR_VENDOR"

# Update all PHP files
Get-ChildItem -Path "src" -Filter "*.php" -Recurse | ForEach-Object {
    (Get-Content $_.FullName) -replace 'statisticlv\\', "$vendorName\" | Set-Content $_.FullName
}

Get-ChildItem -Path "tests" -Filter "*.php" -Recurse | ForEach-Object {
    (Get-Content $_.FullName) -replace 'statisticlv\\', "$vendorName\" | Set-Content $_.FullName
}

# Update composer.json
(Get-Content "composer.json") -replace 'statisticlv\\', "$vendorName\" | Set-Content "composer.json"

Write-Host "Namespace updated to ${vendorName}\AdminPanel"
```

## Step 4: Update Service Provider Class Name

If you want to rename the service provider class:

**File:** `src/AdminPanelServiceProvider.php`

**Before:**
```php
<?php

namespace statisticlv\AdminPanel;

class AdminPanelServiceProvider extends ServiceProvider
{
```

**After (optional):**
```php
<?php

namespace JohnDoe\AdminPanel;

class AdminPanelServiceProvider extends ServiceProvider
{
```

If you change the class name, update composer.json accordingly:

```json
{
    "extra": {
        "laravel": {
            "providers": [
                "JohnDoe\\AdminPanel\\AdminPanelServiceProvider"
            ]
        }
    }
}
```

## Step 5: Update Configuration Files

### config/admin-panel.php

Update any namespace references in configuration:

```php
<?php

return [
    // Update model references if needed
    'models' => [
        'user' => JohnDoe\AdminPanel\Models\AdminUser::class,
        'news' => JohnDoe\AdminPanel\Models\News::class,
        'menu' => JohnDoe\AdminPanel\Models\Menu::class,
    ],
];
```

## Step 6: Regenerate Autoloader

After updating namespaces, regenerate the Composer autoloader:

```bash
composer dump-autoload
```

## Step 7: Test the Changes

### Verify Namespace Changes

Run this command to verify all namespaces are updated:

```bash
grep -r "statisticlv" src/ tests/
```

If nothing is found, all namespaces have been updated successfully.

### Run Tests

```bash
composer test
```

All tests should pass if namespaces are updated correctly.

### Test Installation

Create a test Laravel project and install your package:

```bash
composer create-project laravel/laravel test-namespace
cd test-namespace

# Add your package to composer.json (local path for testing)
composer require statisticlv/laravel-admin-panel

# Check if service provider is registered
php artisan package:discover

# Try to use a class
php artisan tinker
>>> use statisticlv\AdminPanel\Models\News;
>>> News::all();
```

## Step 8: Update Documentation

### README.md

Update installation instructions:

```markdown
## Installation

```bash
composer require statisticlv/laravel-admin-panel
```

### PUBLISHING.md

Update all references to the package name:

```markdown
- Replace `statisticlv` with your actual vendor name
- Update namespace from `statisticlv\AdminPanel` to `YourActualVendor\AdminPanel`
```

### Other Documentation Files

Update namespace references in:
- CONTRIBUTING.md
- CHANGELOG.md
- Any other markdown files

## Common Namespace Patterns

| Vendor Name (composer.json) | Namespace (PHP) | Example |
|-----------------------------|-----------------|---------|
| `johndoe/laravel-admin-panel` | `JohnDoe\AdminPanel` | `namespace JohnDoe\AdminPanel\Http\Controllers;` |
| `acme-corp/laravel-admin-panel` | `AcmeCorp\AdminPanel` | `namespace AcmeCorp\AdminPanel\Models;` |
| `my-brand/laravel-admin-panel` | `MyBrand\AdminPanel` | `namespace MyBrand\AdminPanel\Console\Commands;` |

**Rules:**
- Composer package name: lowercase with hyphens
- PHP namespace: PascalCase (each word capitalized)
- Both should use the same base name

## Troubleshooting

### Class Not Found Error

**Error:** `Class 'statisticlv\AdminPanel\Models\News' not found`

**Solution:**
- Verify namespace is updated in the file
- Run `composer dump-autoload`
- Clear cache: `php artisan cache:clear`

### Autoloading Issues

**Error:** Classes not loading correctly

**Solution:**
```bash
composer clear-cache
composer dump-autoload -o
```

### Mixed Namespaces

**Error:** Some files still have old namespace

**Solution:**
```bash
# Find all files with old namespace
grep -r "statisticlv" src/ tests/

# Update them manually or use the automated script
```

### Service Provider Not Registered

**Error:** Service provider not found

**Solution:**
1. Check composer.json extra.laravel.providers section
2. Verify namespace in AdminPanelServiceProvider.php
3. Run `php artisan package:discover`

## Verification Checklist

Before publishing, ensure:

- [ ] composer.json has correct package name
- [ ] All PHP files in src/ have updated namespace
- [ ] All PHP files in tests/ have updated namespace
- [ ] Service provider namespace is correct
- [ ] composer.json autoload section is correct
- [ ] composer.json autoload-dev section is correct
- [ ] composer.json extra.laravel.providers is correct
- [ ] No references to `statisticlv` remain in code
- [ ] `composer dump-autoload` has been run
- [ ] All tests pass
- [ ] Documentation is updated

## Example: Complete Update for "acme-corp"

### composer.json

```json
{
    "name": "acme-corp/laravel-admin-panel",
    "autoload": {
        "psr-4": {
            "AcmeCorp\\AdminPanel\\": "src/"
        }
    },
    "autoload-dev": {
        "psr-4": {
            "AcmeCorp\\AdminPanel\\Tests\\": "tests/"
        }
    },
    "extra": {
        "laravel": {
            "providers": [
                "AcmeCorp\\AdminPanel\\AdminPanelServiceProvider"
            ]
        }
    }
}
```

### PHP File Example

```php
<?php

namespace AcmeCorp\AdminPanel\Http\Controllers;

use AcmeCorp\AdminPanel\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::latest()->paginate(15);
        return view('admin-panel::news.index', compact('news'));
    }
}
```

## Next Steps

After updating namespaces:

1. Run `composer dump-autoload`
2. Run all tests: `composer test`
3. Create a commit: `git add . && git commit -m "Update namespace to AcmeCorp\AdminPanel"`
4. Push to GitHub: `git push origin main`
5. Create a new release
6. Submit to Packagist

## Need Help?

If you encounter issues:

1. Check the [PUBLISHING.md](PUBLISHING.md) guide
2. Review [TROUBLESHOOTING.md](TROUBLESHOOTING.md)
3. Open an issue on GitHub

---

**Remember:** Choose a unique vendor name and update all namespaces consistently throughout the codebase before publishing!
