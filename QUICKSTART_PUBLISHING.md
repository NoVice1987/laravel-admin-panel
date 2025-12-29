# Quick Start: Publishing Your Package

This is a condensed guide to get your Laravel Admin Panel package published and accessible to everyone.

## Prerequisites

- GitHub account
- Packagist account
- Git installed

## Step 1: Update Package Details (5 minutes)

### Update composer.json

Replace placeholders with your actual details:

```json
{
    "name": "statisticlv/laravel-admin-panel",
    "description": "A comprehensive admin panel for Laravel",
    "authors": [
        {
            "name": "Your Name",
            "email": "your.email@example.com"
        }
    ],
    "homepage": "https://github.com/statisticlv/laravel-admin-panel",
    "support": {
        "issues": "https://github.com/statisticlv/laravel-admin-panel/issues",
        "source": "https://github.com/statisticlv/laravel-admin-panel"
    }
}
```

**Important:** Replace `statisticlv` with your GitHub username or organization name.

## Step 2: Create GitHub Repository (3 minutes)

```bash
# Initialize git if needed
git init

# Add all files
git add .

# Commit
git commit -m "Initial commit"

# Add remote (replace with your URL)
git remote add origin https://github.com/statisticlv/laravel-admin-panel.git

# Push to GitHub
git branch -M main
git push -u origin main
```

## Step 3: Create Release (2 minutes)

1. Go to your GitHub repository
2. Click "Releases" → "Create a new release"
3. Tag: `v1.4.0`
4. Title: `Version 1.4.0`
5. Click "Publish release"

## Step 4: Submit to Packagist (2 minutes)

1. Go to [Packagist.org](https://packagist.org/) and log in
2. Click "Submit"
3. Enter: `https://github.com/statisticlv/laravel-admin-panel`
4. Click "Check" then "Submit"

## Step 5: Enable Auto-Updates (2 minutes)

1. Go to your Packagist package page
2. Click the gear icon (or "Not auto-updated")
3. Authorize GitHub access
4. Select your repository and click "Update"

## Step 6: Test Installation (5 minutes)

```bash
# Create test project
composer create-project laravel/laravel test-panel
cd test-panel

# Install your package
composer require statisticlv/laravel-admin-panel

# Publish config
php artisan vendor:publish --tag=admin-panel-config

# Run migrations
php artisan migrate

# Create admin user
php artisan admin:create-user
```

## Step 7: Verify and Share

1. Check your package on Packagist: `https://packagist.org/packages/statisticlv/laravel-admin-panel`
2. Share the installation command:
   ```bash
   composer require statisticlv/laravel-admin-panel
   ```

## Total Time: ~20 minutes

## What's Next?

- Update README.md with your actual package name
- Add badges to README (already included in template)
- Monitor issues and pull requests
- Plan next features

## Troubleshooting

**Packagist not updating?**
- Check GitHub webhook is configured
- Click "Update" button on Packagist package page

**Installation fails?**
- Verify composer.json is valid
- Check namespace matches vendor name
- Ensure all files are committed to GitHub

**Need help?**
- Check [PUBLISHING.md](PUBLISHING.md) for detailed guide
- Review [CONTRIBUTING.md](CONTRIBUTING.md) for contribution guidelines

## Success!

Your package is now accessible to everyone via Composer! 🎉
