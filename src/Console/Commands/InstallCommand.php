<?php

namespace StatisticLv\AdminPanel\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use StatisticLv\AdminPanel\Database\Seeders\DemoDataSeeder;

class InstallCommand extends Command
{
    protected $signature = 'admin-panel:install 
                            {--demo : Install with demo data}
                            {--force : Force the operation}';

    protected $description = 'Install the Admin Panel package with optional demo data';

    public function handle()
    {
        $this->info('Installing Laravel Admin Panel...');
        $this->newLine();

        // Publish configuration
        $this->info('Publishing configuration...');
        $this->call('vendor:publish', [
            '--tag' => 'admin-panel-config',
            '--force' => $this->option('force'),
        ]);

        // Publish migrations
        $this->info('Publishing migrations...');
        $this->call('vendor:publish', [
            '--tag' => 'admin-panel-migrations',
            '--force' => $this->option('force'),
        ]);

        // Publish controllers to Laravel default folder (includes admin and frontend controllers)
        $this->info('Publishing controllers (including Frontend controllers)...');
        $this->call('vendor:publish', [
            '--tag' => 'admin-panel-controllers',
            '--force' => $this->option('force'),
        ]);

        // Publish web routes (merged admin and frontend routes) to Laravel default folder
        $this->info('Publishing web routes (merged admin and frontend routes)...');
        $this->call('vendor:publish', [
            '--tag' => 'admin-panel-routes',
            '--force' => $this->option('force'),
        ]);

        // Publish views to Laravel default views folder
        $this->info('Publishing views to Laravel default folder...');
        $this->call('vendor:publish', [
            '--tag' => 'admin-panel-views-laravel',
            '--force' => $this->option('force'),
        ]);

        // Run migrations
        $this->info('Running migrations...');
        if ($this->option('force') || $this->confirm('Do you want to run migrations now?', true)) {
            $this->call('migrate');
        }

        // Install demo data
        if ($this->option('demo') || $this->confirm('Do you want to install demo data?', true)) {
            $this->info('Installing demo data...');
            $seeder = new DemoDataSeeder();
            $seeder->run();
            $this->info('✓ Demo data installed successfully!');
            $this->newLine();
            
            $this->info('Demo admin credentials:');
            $this->line('  Email: admin@example.com');
            $this->line('  Password: password');
            $this->newLine();
        } else {
            // Create admin user
            $this->info('Creating admin user...');
            $this->call('admin:create-user');
        }

        // Publish assets
        $this->info('Publishing assets...');
        $this->call('vendor:publish', [
            '--tag' => 'admin-panel-assets',
            '--force' => $this->option('force'),
        ]);

        $this->newLine();
        $this->info('✓ Admin Panel installed successfully!');
        $this->newLine();
        
        $this->info('Published Resources:');
        $this->line('  Controllers (Admin & Frontend): app/Http/Controllers/');
        $this->line('    - Admin Controllers: app/Http/Controllers/');
        $this->line('    - Frontend Controllers: app/Http/Controllers/Frontend/');
        $this->line('  Web Routes (merged admin & frontend): routes/web.php');
        $this->line('  Views: resources/views/');
        $this->line('  Config: config/admin-panel.php');
        $this->line('  Assets: public/vendor/admin-panel/');
        $this->newLine();

        $this->info('Quick Links:');
        $this->line('  Admin Panel: ' . url(config('admin-panel.route_prefix', 'admin')));
        $this->line('  Homepage: ' . url('/'));
        $this->line('  News: ' . url('/news'));
        $this->newLine();

        $this->info('Next Steps:');
        $this->line('  1. Visit ' . url('/') . ' to see your new homepage');
        $this->line('  2. Login to admin panel at ' . url(config('admin-panel.route_prefix', 'admin')));
        $this->line('  3. Customize your content, menus, and pages');
        $this->line('  4. Modify published controllers, routes, and views as needed');
        $this->newLine();

        return 0;
    }
}
