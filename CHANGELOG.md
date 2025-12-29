# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added
- Initial release preparation
- Comprehensive documentation

## [1.4.0] - 2024-01-01

### Added
- Authentication system with rate limiting
- News management with draft/published/archived status
- Menu management with hierarchical structure
- Role-based access control (Admin and Super Admin)
- Soft deletes support for all models
- Built-in caching for improved performance
- Comprehensive logging system
- Type hints and PHPDoc blocks throughout
- Frontend routes for public content display
- Page management system
- Helper functions for common operations

### Changed
- Improved security features
- Enhanced error handling
- Optimized database queries

### Fixed
- Fixed menu item ordering
- Fixed soft delete restoration
- Fixed cache invalidation issues

### Security
- Password strength validation
- Rate limiting on login attempts
- CSRF protection
- SQL injection prevention
- XSS protection

## [1.3.0] - 2023-12-15

### Added
- Menu location support (main, footer, sidebar)
- Menu item CSS classes
- Custom middleware support
- Configuration file for easy customization

### Changed
- Refactored menu rendering logic
- Improved performance with caching

### Fixed
- Fixed menu item URL generation
- Fixed nested menu display

## [1.2.0] - 2023-12-01

### Added
- News featured image support
- View counter for news articles
- News excerpt generation
- Date formatting helper

### Changed
- Improved news search functionality
- Enhanced pagination

### Fixed
- Fixed news slug generation
- Fixed date formatting issues

## [1.1.0] - 2023-11-15

### Added
- Admin user management
- User activation/deactivation
- Password reset functionality
- Session management

### Changed
- Improved authentication flow
- Enhanced security measures

### Fixed
- Fixed login session persistence
- Fixed logout redirect

## [1.0.0] - 2023-11-01

### Added
- Initial release
- Basic authentication
- Dashboard
- News CRUD operations
- Menu CRUD operations
- Basic admin panel UI

[Unreleased]: https://github.com/statisticlv/laravel-admin-panel/compare/v1.4.0...HEAD
[1.4.0]: https://github.com/statisticlv/laravel-admin-panel/compare/v1.3.0...v1.4.0
[1.3.0]: https://github.com/statisticlv/laravel-admin-panel/compare/v1.2.0...v1.3.0
[1.2.0]: https://github.com/statisticlv/laravel-admin-panel/compare/v1.1.0...v1.2.0
[1.1.0]: https://github.com/statisticlv/laravel-admin-panel/compare/v1.0.0...v1.1.0
[1.0.0]: https://github.com/statisticlv/laravel-admin-panel/releases/tag/v1.0.0
