<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Providers;

use Laravel\Sanctum\Sanctum;
use Illuminate\Support\ServiceProvider;
use Inisiatif\Package\User\Models\AuthToken;
use Inisiatif\Package\User\Models\AuthTokenBlacklist;
use Inisiatif\Package\User\Models\PersonalAccessToken;
use Inisiatif\Package\User\Repositories\UserRepository;
use Inisiatif\Package\User\Commands\CreateLongTokenCommand;
use Inisiatif\Package\User\Repositories\AuthTokenRepository;
use Inisiatif\Package\Contract\User\Model\AuthTokenInterface;
use Inisiatif\Package\User\Repositories\AuthTokenBlacklistRepository;
use Inisiatif\Package\Contract\User\Model\AuthTokenBlacklistInterface;
use Inisiatif\Package\Contract\User\Repository\UserRepositoryInterface;
use Inisiatif\Package\Contract\User\Repository\AuthTokenRepositoryInterface;
use Inisiatif\Package\Contract\User\Repository\AuthTokenBlacklistRepositoryInterface;

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

        if ($this->app->runningInConsole()) {
            $this->commands([
                CreateLongTokenCommand::class,
            ]);
        }
    }

    public function register(): void
    {
        Sanctum::ignoreMigrations();
        
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(AuthTokenInterface::class, AuthToken::class);
        $this->app->bind(AuthTokenRepositoryInterface::class, AuthTokenRepository::class);
        $this->app->bind(AuthTokenBlacklistInterface::class, AuthTokenBlacklist::class);
        $this->app->bind(AuthTokenBlacklistRepositoryInterface::class, AuthTokenBlacklistRepository::class);
    }
}
