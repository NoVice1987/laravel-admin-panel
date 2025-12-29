<?php

namespace StatisticLv\AdminPanel\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use StatisticLv\AdminPanel\Models\AdminUser;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create-user 
                            {--name= : The name of the admin user}
                            {--email= : The email of the admin user}
                            {--password= : The password for the admin user}
                            {--role=admin : The role for the admin user (admin or super_admin)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new admin user';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $name = $this->option('name') ?: $this->ask('Name');
        $email = $this->option('email') ?: $this->ask('Email');
        $password = $this->option('password') ?: $this->secret('Password');
        $role = $this->option('role');

        // Validate role
        if (!in_array($role, ['admin', 'super_admin'])) {
            $this->error('Role must be either "admin" or "super_admin"');
            return 1;
        }

        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('Please provide a valid email address.');
            return 1;
        }

        // Check if user already exists
        if (AdminUser::withTrashed()->where('email', $email)->exists()) {
            $this->error('An admin user with this email already exists!');
            return 1;
        }

        // Validate password strength
        $passwordValidation = $this->validatePasswordStrength($password);
        if ($passwordValidation !== true) {
            $this->error($passwordValidation);
            return 1;
        }

        // Create the admin user
        try {
            $admin = AdminUser::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'role' => $role,
                'is_active' => true,
            ]);

            $this->info('Admin user created successfully!');
            $this->line('');
            $this->line('Login credentials:');
            $this->line('Email: ' . $admin->email);
            $this->line('Password: (the password you entered)');
            $this->line('');
            $this->line('You can now login at: ' . url(config('admin-panel.route_prefix', 'admin') . '/login'));

            Log::info('Admin user created via command', [
                'user_id' => $admin->id,
                'email' => $admin->email,
                'role' => $admin->role,
            ]);

            return 0;
        } catch (\Exception $e) {
            $this->error('Failed to create admin user: ' . $e->getMessage());
            Log::error('Failed to create admin user via command', [
                'error' => $e->getMessage(),
                'email' => $email,
            ]);
            return 1;
        }
    }

    /**
     * Validate password strength.
     *
     * @param string $password
     * @return bool|string
     */
    private function validatePasswordStrength(string $password): bool|string
    {
        // Check minimum length
        if (strlen($password) < 8) {
            return 'Password must be at least 8 characters long.';
        }

        // Check for uppercase letter
        if (!preg_match('/[A-Z]/', $password)) {
            return 'Password must contain at least one uppercase letter.';
        }

        // Check for lowercase letter
        if (!preg_match('/[a-z]/', $password)) {
            return 'Password must contain at least one lowercase letter.';
        }

        // Check for number
        if (!preg_match('/[0-9]/', $password)) {
            return 'Password must contain at least one number.';
        }

        // Check for special character (optional but recommended)
        if (!preg_match('/[^A-Za-z0-9]/', $password)) {
            $this->warn('Warning: It is recommended to include at least one special character in your password.');
        }

        return true;
    }
}
