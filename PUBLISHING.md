# Publishing Guide for Laravel Admin Panel

This guide will help you publish this package to make it accessible to everyone via Composer.

## Prerequisites

Before publishing, ensure you have:

- A [GitHub](https://github.com/) account
- A [Packagist](https://packagist.org/) account
- Git installed on your machine
- A vendor namespace (e.g., `yourusername` or `yourcompany`)

## Step 1: Update Package Metadata

### Update `composer.json`

Replace the placeholder values in `composer.json`:

```json
{
    "name": "statisticlv/laravel-admin-panel",
    "description": "A comprehensive admin panel for Laravel with authentication and content management",
    "type": "library",
    "license": "MIT",
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

**Important:**
- Replace `statisticlv` with your actual vendor name (GitHub username or organization)
- Update author information with your real details
- Add your repository URL

### Update Namespace

Update the namespace throughout the codebase from `statisticlv\AdminPanel` to your actual vendor namespace:

1. Update `composer.json` autoload section:
```json
"autoload": {
    "psr-4": {
        "statisticlv\\AdminPanel\\": "src/"
    }
}
```

2. Update all PHP files in `src/` directory to use the new namespace
3. Update service provider class name and namespace
4. Update all references in configuration files

## Step 2: Prepare Your GitHub Repository

### Create GitHub Repository

1. Go to [GitHub](https://github.com/new) and create a new repository
2. Name it `laravel-admin-panel`
3. Make it public
4. Don't initialize with README (you already have one)

### Push to GitHub

```bash
# Initialize git if not already done
git init

# Add all files
git add .

# Create initial commit
git commit -m "Initial commit of Laravel Admin Panel"

# Add remote repository
git remote add origin https://github.com/statisticlv/laravel-admin-panel.git

# Push to GitHub
git branch -M main
git push -u origin main
```

## Step 3: Create GitHub Release

1. Go to your repository on GitHub
2. Click on "Releases" → "Create a new release"
3. Tag version: `v1.4.0`
4. Release title: `Version 1.4.0`
5. Description: Add release notes from CHANGELOG.md
6. Click "Publish release"

## Step 4: Submit to Packagist

### Register on Packagist

1. Go to [Packagist.org](https://packagist.org/)
2. Sign up for an account
3. Verify your email address

### Submit Your Package

1. Click "Submit" button on Packagist
2. Enter your repository URL: `https://github.com/statisticlv/laravel-admin-panel`
3. Click "Check"
4. Click "Submit"

### Link GitHub to Packagist

To enable automatic updates when you push to GitHub:

1. Go to your Packagist package page
2. Click "Not auto-updated" or the gear icon
3. Log in to GitHub if prompted
4. Grant Packagist access to your repository
5. Select the repository and click "Update"

Now, every time you push a new tag to GitHub, Packagist will automatically update.

## Step 5: Verify Installation

Test that your package can be installed:

```bash
# Create a test Laravel project
composer create-project laravel/laravel test-admin-panel

cd test-admin-panel

# Install your package
composer require statisticlv/laravel-admin-panel

# Publish configuration
php artisan vendor:publish --tag=admin-panel-config

# Run migrations
php artisan migrate

# Create admin user
php artisan admin:create-user
```

## Step 6: Add Badges to README

Add these badges to the top of your `README.md`:

```markdown
[![Latest Version](https://img.shields.io/packagist/v/statisticlv/laravel-admin-panel)](https://packagist.org/packages/statisticlv/laravel-admin-panel)
[![Total Downloads](https://img.shields.io/packagist/dt/statisticlv/laravel-admin-panel)](https://packagist.org/packages/statisticlv/laravel-admin-panel)
[![License](https://img.shields.io/packagist/l/statisticlv/laravel-admin-panel)](https://packagist.org/packages/statisticlv/laravel-admin-panel)
[![PHP Version](https://img.shields.io/packagist/php-v/statisticlv/laravel-admin-panel)](https://packagist.org/packages/statisticlv/laravel-admin-panel)
```

## Step 7: Update Documentation

Update your README.md installation section:

```markdown
## Installation

### Requirements

- PHP 8.1 or higher
- Laravel 10.x, 11.x, or 12.x

### Installation via Composer

```bash
composer require statisticlv/laravel-admin-panel
```

### Manual Installation

If you prefer manual installation or want to contribute:

1. Clone the repository:
```bash
git clone https://github.com/statisticlv/laravel-admin-panel.git
```

2. Add to your project's `composer.json`:
```json
{
    "repositories": [
        {
            "type": "path",
            "url": "./laravel-admin-panel"
        }
    ],
    "require": {
        "statisticlv/laravel-admin-panel": "dev-main"
    }
}
```

3. Run composer update:
```bash
composer update
```

### Configuration Steps

After installation, follow these steps:

1. Publish the configuration file:
```bash
php artisan vendor:publish --tag=admin-panel-config
```

2. Run the migrations:
```bash
php artisan migrate
```

3. Create an admin user:
```bash
php artisan admin:create-user
```

4. Access the admin panel at `/admin`
```

## Step 8: Continuous Integration (Optional but Recommended)

### GitHub Actions

Create `.github/workflows/tests.yml`:

```yaml
name: Tests

on:
  push:
    branches: [ main ]
  pull_request:
    branches: [ main ]

jobs:
  test:
    runs-on: ${{ matrix.os }}
    
    strategy:
      matrix:
        os: [ubuntu-latest]
        php: ['8.1', '8.2', '8.3']
        laravel: ['10.*', '11.*']
        include:
          - laravel: 10.*
            testbench: 8.*
          - laravel: 11.*
            testbench: 9.*

    name: PHP ${{ matrix.php }} - Laravel ${{ matrix.laravel }}

    steps:
      - name: Checkout code
        uses: actions/checkout@v3

      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: ${{ matrix.php }}
          extensions: dom, curl, libxml, mbstring, zip, pcntl, pdo, sqlite, pdo_sqlite, bcmath, soap, intl, gd, exif, iconv
          coverage: none

      - name: Install dependencies
        run: |
          composer require "laravel/framework:${{ matrix.laravel }}" "orchestra/testbench:${{ matrix.testbench }}" --no-interaction --no-update
          composer update --prefer-stable --prefer-dist --no-interaction

      - name: Execute tests
        run: vendor/bin/phpunit
```

## Step 9: Version Management

### Semantic Versioning

Follow semantic versioning (MAJOR.MINOR.PATCH):

- **MAJOR**: Incompatible API changes
- **MINOR**: Backwards-compatible functionality additions
- **PATCH**: Backwards-compatible bug fixes

### Release Process

1. Update version in `composer.json`
2. Update CHANGELOG.md
3. Commit changes
4. Create git tag:
```bash
git tag v1.4.1
git push origin v1.4.1
```
5. Create GitHub release
6. Packagist will automatically update

## Step 10: Community Engagement

### Documentation

- Keep README.md up to date
- Add code examples
- Create video tutorials (optional)

### Issues

- Respond to issues promptly
- Use issue templates
- Label issues appropriately

### Contributing

- Create CONTRIBUTING.md with guidelines
- Use pull request templates
- Review and merge contributions

### Promotion

- Share on Laravel News
- Post on Reddit r/laravel
- Tweet about it
- Add to Laravel package lists

## Alternative: Private Package

If you want to keep the package private but share with specific users:

### Using Private GitHub Repository

1. Create a private GitHub repository
2. Add to user's `composer.json`:
```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/statisticlv/laravel-admin-panel.git"
        }
    ],
    "require": {
        "statisticlv/laravel-admin-panel": "^1.4"
    }
}
```

3. User needs to authenticate with GitHub:
```bash
composer config -g github-oauth.github.com YOUR_GITHUB_TOKEN
```

### Using Satis (Private Package Repository)

Set up your own Composer repository using [Satis](https://github.com/composer/satis).

## Troubleshooting

### Packagist Not Updating

If Packagist doesn't update automatically:

1. Check GitHub webhook is configured
2. Manually update on Packagist package page
3. Ensure tags are pushed to GitHub

### Namespace Issues

If you have namespace conflicts:

1. Ensure your vendor name is unique
2. Check Packagist for existing packages
3. Use your GitHub username as vendor name

### Installation Fails

If users can't install:

1. Verify `composer.json` is valid
2. Check all required dependencies
3. Ensure autoloader is correct
4. Test installation yourself first

## Checklist

Before publishing, ensure:

- [ ] Updated `composer.json` with correct vendor name
- [ ] Updated all namespaces in code
- [ ] Updated README.md with correct installation instructions
- [ ] Created CHANGELOG.md
- [ ] Created CONTRIBUTING.md
- [ ] Added LICENSE file
- [ ] Tested package installation
- [ ] Created GitHub repository
- [ ] Pushed all code to GitHub
- [ ] Created initial release
- [ ] Submitted to Packagist
- [ ] Linked GitHub to Packagist
- [ ] Verified installation works
- [ ] Added badges to README
- [ ] Set up CI/CD (optional)

## Support

After publishing, provide support through:

- GitHub Issues for bug reports
- GitHub Discussions for questions
- Email for enterprise support (if applicable)
- Documentation website (optional)

## Next Steps

Once published:

1. Monitor download statistics
2. Respond to issues and pull requests
3. Plan future features
4. Maintain compatibility with new Laravel versions
5. Keep dependencies updated
6. Write blog posts about your package

Congratulations! Your Laravel Admin Panel is now accessible to everyone!
