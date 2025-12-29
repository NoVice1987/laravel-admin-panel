<?php

namespace StatisticLv\AdminPanel;

use Illuminate\Support\ServiceProvider;
<<<<<<< HEAD
=======
use Illuminate\Support\Facades\Route;
>>>>>>> b95e348 (Initial commit of Laravel Admin Panel)
use Illuminate\Routing\Router;

class AdminPanelServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/admin-panel.php', 'admin-panel'
        );
    }

    public function boot()
    {
        // Register authentication guard configuration
        $this->configureAuthGuard();
        
        // Load migrations
        $this->loadMigrationsFrom(__DIR__.'/database/migrations', 'admin-panel');
        
        // Load views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'admin-panel');
        
<<<<<<< HEAD
=======
        // Register routes
        $this->registerRoutes();
        
>>>>>>> b95e348 (Initial commit of Laravel Admin Panel)
        // Register middleware
        $this->registerMiddleware();
        
        // Publish configuration
        $this->publishes([
            __DIR__.'/../config/admin-panel.php' => config_path('admin-panel.php'),
        ], 'admin-panel-config');
        
        // Publish migrations
        $this->publishes([
            __DIR__.'/database/migrations' => database_path('migrations'),
        ], 'admin-panel-migrations');
        
        // Publish views (to vendor folder)
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/admin-panel'),
        ], 'admin-panel-views');
        
        // Publish views (to Laravel default views folder)
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views'),
        ], 'admin-panel-views-laravel');
        
        // Publish controllers
        $this->publishes([
            __DIR__.'/Http/Controllers' => app_path('Http/Controllers'),
        ], 'admin-panel-controllers');
        
<<<<<<< HEAD
        // Publish web routes (merged admin and frontend routes)
        $this->publishes([
            __DIR__.'/../routes/web.php' => base_path('routes/web.php'),
        ], 'admin-panel-routes');
        
=======
        // Publish admin routes
        $this->publishes([
            __DIR__.'/../routes/web.php' => base_path('routes/admin.php'),
        ], 'admin-panel-routes');
        
        // Publish frontend routes
        $this->publishes([
            __DIR__.'/../routes/frontend.php' => base_path('routes/frontend.php'),
        ], 'admin-panel-frontend-routes');
        
        // Publish all routes
        $this->publishes([
            __DIR__.'/../routes' => base_path('routes'),
        ], 'admin-panel-all-routes');
        
>>>>>>> b95e348 (Initial commit of Laravel Admin Panel)
        // Publish assets
        $this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/admin-panel'),
        ], 'admin-panel-assets');
        
        // Register commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\Commands\CreateAdminUser::class,
                Console\Commands\InstallCommand::class,
            ]);
        }
    }

    /**
     * Configure the admin authentication guard
     */
    protected function configureAuthGuard()
    {
        // Add admin guard if it doesn't exist
        if (!config('auth.guards.admin')) {
            config([
                'auth.guards.admin' => [
                    'driver' => 'session',
                    'provider' => 'admin_users',
                ]
            ]);
        }
        
        // Add admin users provider if it doesn't exist
        if (!config('auth.providers.admin_users')) {
            config([
                'auth.providers.admin_users' => [
                    'driver' => 'eloquent',
                    'model' => \StatisticLv\AdminPanel\Models\AdminUser::class,
                ]
            ]);
        }
    }

    /**
<<<<<<< HEAD
=======
     * Register package routes
     */
    protected function registerRoutes()
    {
        // Admin routes
        Route::group([
            'prefix' => config('admin-panel.route_prefix', 'admin'),
            'middleware' => config('admin-panel.middleware', ['web']),
            'namespace' => 'StatisticLv\AdminPanel\Http\Controllers',
        ], function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        });

        // Frontend routes (only if enabled in config)
        if (config('admin-panel.enable_frontend_routes', true)) {
            Route::group([
                'middleware' => ['web'],
                'namespace' => 'StatisticLv\AdminPanel\Http\Controllers\Frontend',
            ], function () {
                $this->loadRoutesFrom(__DIR__.'/../routes/frontend.php');
            });
        }
    }

    /**
>>>>>>> b95e348 (Initial commit of Laravel Admin Panel)
     * Register package middleware
     */
    protected function registerMiddleware()
    {
        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('admin.auth', \StatisticLv\AdminPanel\Http\Middleware\AdminAuth::class);
    }
}
