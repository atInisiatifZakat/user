<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Tests;

use Orchestra\Testbench;
use Inisiatif\Package\User\Routes;
use Inisiatif\Package\User\ModelRegistrar;
use Illuminate\Contracts\Config\Repository;
use Laravel\Sanctum\SanctumServiceProvider;
use Inisiatif\Package\User\UserServiceProvider;

abstract class TestCase extends Testbench\TestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            SanctumServiceProvider::class,
            UserServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        \tap($app->make('config'), static function (Repository $config): void {
            $config->set('auth.providers.users.model', ModelRegistrar::getUserModelClass());

            $config->set('database.default', 'testing');

            $config->set('database.connections.testing', [
                'driver' => 'sqlite',
                'database' => ':memory:',
                'prefix' => '',
            ]);

            $config->set('user.migration', true);
        });
    }

    protected function defineRoutes($router): void
    {
        $router->group([], static function (): void {
            Routes::userToken();
            Routes::authToken();
            Routes::userProfile();
        });
    }

    protected function defineDatabaseMigrations(): void
    {
        Testbench\artisan($this, 'migrate', ['--database' => 'testing']);

        $this->beforeApplicationDestroyed(
            fn () => Testbench\artisan($this, 'migrate:rollback', ['--database' => 'testing'])
        );
    }
}
