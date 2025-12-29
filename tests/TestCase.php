<?php

namespace StatisticLv\AdminPanel\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use StatisticLv\AdminPanel\AdminPanelServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    protected function getPackageProviders($app)
    {
        return [
            AdminPanelServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        // Setup admin panel configuration
        $app['config']->set('admin-panel.route_prefix', 'admin');
        $app['config']->set('admin-panel.middleware', ['web']);
    }
}
