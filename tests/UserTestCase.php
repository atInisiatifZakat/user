<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Tests;

use Orchestra\Testbench\TestCase;
use Inisiatif\Package\User\Models\AbstractUser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Inisiatif\Package\User\Providers\UserServiceProvider;

abstract class UserTestCase extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__ . '/stub/migrations');

        $this->app->bind(AbstractUser::class, UserModel::class);
    }

    protected function getPackageProviders($app): array
    {
        return [
            UserServiceProvider::class,
        ];
    }

    protected function createUser(): UserModel
    {
        $user = new UserModel();
        $user->setId('f6ad7c84-70c8-4f5e-8b96-efb0ceae1d31');
        $user->setUsername('foobar');
        $user->setName('Foo Bar');
        $user->setEmail('foo@bar.com');
        $user->setPassword(bcrypt('secret'));
        $user->save();

        return $user;
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testbench');

        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }
}
