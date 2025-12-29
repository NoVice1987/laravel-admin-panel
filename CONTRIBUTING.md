# Contributing to Laravel Admin Panel

Thank you for considering contributing to the Laravel Admin Panel! We welcome contributions from everyone.

## Table of Contents

- [Code of Conduct](#code-of-conduct)
- [How Can I Contribute?](#how-can-i-contribute)
  - [Reporting Bugs](#reporting-bugs)
  - [Suggesting Enhancements](#suggesting-enhancements)
  - [Pull Requests](#pull-requests)
- [Development Setup](#development-setup)
- [Coding Standards](#coding-standards)
- [Commit Messages](#commit-messages)
- [Testing](#testing)
- [Documentation](#documentation)

## Code of Conduct

This project and everyone participating in it is governed by our Code of Conduct. By participating, you are expected to uphold this code.

**Our Pledge:**

- Be respectful and inclusive
- Welcome newcomers and help them learn
- Focus on constructive feedback
- Be considerate of different viewpoints
- Show empathy towards other community members

## How Can I Contribute?

### Reporting Bugs

Before creating bug reports, please check the existing issues as you might find that the problem has already been reported.

When creating a bug report, please include as many details as possible:

**Use a clear and descriptive title:**

- ✅ Good: "Login fails when password contains special characters"
- ❌ Bad: "Login broken"

**Describe the exact steps to reproduce the problem:**

1. Go to '...'
2. Click on '...'
3. Scroll down to '...'
4. See error

**Provide specific examples to demonstrate the steps:**

```php
// Code that causes the issue
```

**Describe the behavior you observed:**

- What happened?
- What should have happened?

**Include relevant information:**

- PHP version
- Laravel version
- Package version
- Operating system
- Browser (if applicable)
- Error messages or stack traces

### Suggesting Enhancements

Enhancement suggestions are tracked as GitHub issues.

**Use a clear and descriptive title:**

- ✅ Good: "Add support for two-factor authentication"
- ❌ Bad: "Add 2FA"

**Provide a detailed description of the proposed enhancement:**

- Why would this enhancement be useful?
- What problem does it solve?
- What alternatives have you considered?

**Provide specific examples to demonstrate the enhancement:**

```php
// Example of how the enhancement would work
```

### Pull Requests

Pull requests are the best way to propose changes to the codebase.

**Before submitting a PR:**

1. Check for open or closed pull requests that relate to your submission
2. Fork the repository
3. Create a new branch for your feature or bugfix
4. Make your changes following our coding standards
5. Write tests for your changes
6. Ensure all tests pass
7. Update documentation if needed
8. Commit your changes with clear messages
9. Push to your fork
10. Submit a pull request

**PR Title:**

- ✅ Good: "Fix: Menu item ordering not saved correctly"
- ❌ Bad: "Fixed menu"

**PR Description:**

Please include:
- Summary of changes
- Motivation for the changes
- Related issues (if any)
- Screenshots for UI changes (if applicable)
- Testing instructions

## Development Setup

### Fork and Clone

1. Fork the repository on GitHub
2. Clone your fork locally:

```bash
git clone https://github.com/YOUR_USERNAME/laravel-admin-panel.git
cd laravel-admin-panel
```

### Install Dependencies

```bash
composer install
```

### Run Tests

```bash
composer test
```

### Code Style

We use PSR-12 coding standards. Check your code:

```bash
composer check-style
```

Auto-fix style issues:

```bash
composer fix-style
```

## Coding Standards

### PHP

- Follow [PSR-12](https://www.php-fig.org/psr/psr-12/) coding standards
- Use type hints for all function parameters and return types
- Add PHPDoc blocks for all classes, methods, and properties
- Use meaningful variable and function names
- Keep functions small and focused on a single responsibility
- Avoid deep nesting (max 3 levels)
- Use early returns to reduce nesting

**Example:**

```php
<?php

namespace statisticlv\AdminPanel\Http\Controllers;

use Illuminate\Http\Request;
use statisticlv\AdminPanel\Models\News;

/**
 * News Controller
 * 
 * Handles all news-related operations including CRUD operations.
 */
class NewsController extends Controller
{
    /**
     * Display a listing of news articles.
     *
     * @param Request $request The HTTP request
     * @return \Illuminate\View\View
     */
    public function index(Request $request): \Illuminate\View\View
    {
        $query = News::query();
        
        // Apply filters if provided
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        $news = $query->latest()->paginate(15);
        
        return view('admin-panel::news.index', compact('news'));
    }
}
```

### Blade Templates

- Use meaningful variable names
- Keep templates organized and readable
- Use component directives when appropriate
- Avoid complex logic in templates
- Use `@php` directive sparingly

**Example:**

```blade
@extends('admin-panel::layouts.app')

@section('content')
<div class="container">
    <h1>{{ __('News Articles') }}</h1>
    
    @if($news->count() > 0)
        <div class="news-list">
            @foreach($news as $article)
                <article class="news-item">
                    <h2>{{ $article->title }}</h2>
                    <p>{{ $article->excerpt }}</p>
                    <a href="{{ route('admin.news.show', $article) }}">
                        {{ __('Read more') }}
                    </a>
                </article>
            @endforeach
        </div>
        
        {{ $news->links() }}
    @else
        <p>{{ __('No news articles found.') }}</p>
    @endif
</div>
@endsection
```

### JavaScript

- Use modern ES6+ syntax
- Keep functions small and focused
- Use meaningful variable names
- Add comments for complex logic
- Avoid global variables

**Example:**

```javascript
/**
 * Initialize menu item drag and drop
 */
function initMenuDragDrop() {
    const menuItems = document.querySelectorAll('.menu-item');
    
    menuItems.forEach(item => {
        item.addEventListener('dragstart', handleDragStart);
        item.addEventListener('dragend', handleDragEnd);
    });
}

/**
 * Handle drag start event
 * @param {DragEvent} event - The drag event
 */
function handleDragStart(event) {
    event.dataTransfer.setData('text/plain', event.target.dataset.id);
    event.target.classList.add('dragging');
}
```

### CSS

- Use BEM naming convention for classes
- Keep selectors specific but not overly complex
- Use CSS variables for theme colors
- Organize by component
- Add comments for complex styles

**Example:**

```css
/* Menu Component */
.menu {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.menu__item {
    padding: 0.75rem;
    background-color: var(--color-white);
    border-radius: 4px;
    transition: background-color 0.2s ease;
}

.menu__item--active {
    background-color: var(--color-primary);
    color: var(--color-white);
}

.menu__item:hover {
    background-color: var(--color-gray-light);
}
```

## Commit Messages

We follow the [Conventional Commits](https://www.conventionalcommits.org/) specification:

**Format:**

```
<type>(<scope>): <subject>

<body>

<footer>
```

**Types:**

- `feat`: A new feature
- `fix`: A bug fix
- `docs`: Documentation only changes
- `style`: Changes that don't affect code meaning (formatting, etc.)
- `refactor`: Code change that neither fixes a bug nor adds a feature
- `perf`: Performance improvement
- `test`: Adding or updating tests
- `chore`: Changes to the build process or auxiliary tools

**Examples:**

```
feat(news): add featured image support

- Add featured_image field to news model
- Update migration to include new column
- Add image upload functionality
- Update admin form to handle image uploads

Closes #123
```

```
fix(auth): resolve login session persistence issue

The session was not persisting correctly after login due to
missing remember_token handling. This fix ensures the token
is properly generated and stored.

Fixes #456
```

## Testing

### Write Tests

All new features must include tests. We use PHPUnit.

**Test Structure:**

```php
<?php

namespace statisticlv\AdminPanel\Tests\Feature;

use statisticlv\AdminPanel\Models\News;
use statisticlv\AdminPanel\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NewsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_news_article()
    {
        $news = News::factory()->create([
            'title' => 'Test Article',
            'status' => 'published',
        ]);

        $this->assertDatabaseHas('news', [
            'title' => 'Test Article',
            'status' => 'published',
        ]);
    }

    /** @test */
    public function it_filters_published_news()
    {
        News::factory()->create(['status' => 'draft']);
        News::factory()->create(['status' => 'published']);

        $published = News::published()->get();

        $this->assertCount(1, $published);
    }
}
```

### Run Tests

Run all tests:

```bash
composer test
```

Run specific test file:

```bash
vendor/bin/phpunit tests/Feature/NewsTest.php
```

Run with coverage:

```bash
vendor/bin/phpunit --coverage-html coverage
```

### Test Coverage

Aim for at least 80% code coverage for new features.

## Documentation

### Update Documentation

When adding or changing features, update the relevant documentation:

- README.md (for user-facing changes)
- Inline code comments (for implementation details)
- PHPDoc blocks (for API documentation)

### Documentation Style

- Use clear, concise language
- Provide code examples
- Explain the "why" not just the "how"
- Keep examples up to date
- Use proper markdown formatting

**Example:**

```markdown
## Creating a News Article

To create a new news article, use the `News` model:

```php
use statisticlv\AdminPanel\Models\News;

$article = News::create([
    'title' => 'My First Article',
    'content' => 'This is the article content.',
    'status' => 'draft',
]);
```

The `status` can be one of:
- `draft`: Article is not visible to the public
- `published`: Article is publicly visible
- `archived`: Article is archived and not editable
```

## Getting Help

If you need help contributing:

- Check existing issues and pull requests
- Read the documentation thoroughly
- Ask questions in GitHub Discussions
- Join our community chat (if available)

## Recognition

Contributors will be recognized in:

- CONTRIBUTORS.md file
- Release notes
- Project documentation

Thank you for contributing to Laravel Admin Panel! 🎉
