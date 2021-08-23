<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Providers;

use Laravel\Sanctum\Sanctum;
use Illuminate\Support\ServiceProvider;
use Inisiatif\Package\User\Models\PersonalAccessToken;
use Inisiatif\Package\User\Repositories\UserRepository;
use Inisiatif\Package\Contract\User\Repository\UserRepositoryInterface;

final class UserServiceProvider extends ServiceProvider
{
    public static $isRunMigration = true;

    public function boot(): void
    {
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);

        if (self::$isRunMigration) {
            $this->loadMigrationsFrom(__DIR__ . '/../../migrations');
        }

        $this->mergeConfigFrom(__DIR__ . '/../../config/user.php', 'user');
    }

    public function register(): void
    {
        Sanctum::ignoreMigrations();

        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }
}
