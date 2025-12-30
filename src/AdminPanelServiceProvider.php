<?php

namespace StatisticLv\AdminPanel;

use Illuminate\Support\ServiceProvider;
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
        
        // Publish web routes (merged admin and frontend routes)
        $this->publishes([
            __DIR__.'/../routes/web.php' => base_path('routes/web.php'),
        ], 'admin-panel-routes');
        
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
     * Register package middleware
     */
    protected function registerMiddleware()
    {
        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('admin.auth', \StatisticLv\AdminPanel\Http\Middleware\AdminAuth::class);
    }
}
