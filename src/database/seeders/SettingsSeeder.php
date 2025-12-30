<?php

namespace StatisticLv\AdminPanel\Database\Seeders;

use Illuminate\Database\Seeder;
use StatisticLv\AdminPanel\Models\Setting;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultSettings = [
            // General Settings
            [
                'key' => 'site_name',
                'value' => 'Admin Panel',
                'type' => 'text',
                'group' => 'general',
                'title' => 'Site Name',
                'description' => 'The name of your website',
            ],
            [
                'key' => 'site_description',
                'value' => 'A Laravel-based admin panel',
                'type' => 'textarea',
                'group' => 'general',
                'title' => 'Site Description',
                'description' => 'A brief description of your website',
            ],
            [
                'key' => 'site_email',
                'value' => 'admin@example.com',
                'type' => 'text',
                'group' => 'general',
                'title' => 'Site Email',
                'description' => 'Contact email for the website',
            ],
            [
                'key' => 'maintenance_mode',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'general',
                'title' => 'Maintenance Mode',
                'description' => 'Enable maintenance mode to disable public access',
            ],
            
            // SEO Settings
            [
                'key' => 'meta_keywords',
                'value' => 'laravel, admin, panel, cms',
                'type' => 'text',
                'group' => 'seo',
                'title' => 'Meta Keywords',
                'description' => 'Default meta keywords for SEO',
            ],
            [
                'key' => 'meta_description',
                'value' => 'A Laravel-based admin panel for content management',
                'type' => 'textarea',
                'group' => 'seo',
                'title' => 'Meta Description',
                'description' => 'Default meta description for SEO',
            ],
            [
                'key' => 'google_analytics',
                'value' => '',
                'type' => 'text',
                'group' => 'seo',
                'title' => 'Google Analytics ID',
                'description' => 'Your Google Analytics tracking ID (e.g., GA-XXXXXXXXX)',
            ],
            
            // Social Media Settings
            [
                'key' => 'facebook_url',
                'value' => '',
                'type' => 'text',
                'group' => 'social',
                'title' => 'Facebook URL',
                'description' => 'Your Facebook page URL',
            ],
            [
                'key' => 'twitter_url',
                'value' => '',
                'type' => 'text',
                'group' => 'social',
                'title' => 'Twitter URL',
                'description' => 'Your Twitter profile URL',
            ],
            [
                'key' => 'instagram_url',
                'value' => '',
                'type' => 'text',
                'group' => 'social',
                'title' => 'Instagram URL',
                'description' => 'Your Instagram profile URL',
            ],
            [
                'key' => 'linkedin_url',
                'value' => '',
                'type' => 'text',
                'group' => 'social',
                'title' => 'LinkedIn URL',
                'description' => 'Your LinkedIn profile URL',
            ],
            
            // Theme Settings
            [
                'key' => 'theme_color',
                'value' => '#3B82F6',
                'type' => 'text',
                'group' => 'theme',
                'title' => 'Theme Color',
                'description' => 'Primary theme color (hex code)',
            ],
            [
                'key' => 'dark_mode',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'theme',
                'title' => 'Dark Mode',
                'description' => 'Enable dark mode for the admin panel',
            ],
            [
                'key' => 'items_per_page',
                'value' => '15',
                'type' => 'number',
                'group' => 'theme',
                'title' => 'Items Per Page',
                'description' => 'Number of items to display per page in lists',
            ],
        ];

        foreach ($defaultSettings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}