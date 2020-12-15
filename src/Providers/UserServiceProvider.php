<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Providers;

use Illuminate\Support\ServiceProvider;
use Inisiatif\Package\User\Repositories\UserRepository;
use Inisiatif\Package\Contract\User\Repository\UserRepositoryInterface;

final class UserServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $migrations = [
            __DIR__ . '/../../migrations/2020_09_14_160119_create_users_table.php.stub' => $this->app->databasePath() . '/migrations/2020_09_14_160119_create_users_table.php',
        ];

        $this->publishes($migrations, 'inisiatif-user-migration');
    }

    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }
}
