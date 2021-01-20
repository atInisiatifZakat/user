<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Providers;

use phpseclib\System\SSH\Agent;
use Illuminate\Support\ServiceProvider;
use Inisiatif\Package\User\Models\AuthToken;
use Inisiatif\Package\User\Models\AuthTokenBlacklist;
use Inisiatif\Package\User\Repositories\UserRepository;
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
        $this->loadMigrationsFrom(__DIR__ . '/../../migrations');

        $this->mergeConfigFrom(__DIR__ . '/../../config/user.php', 'user');
    }

    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(AuthTokenInterface::class, AuthToken::class);
        $this->app->bind(AuthTokenRepositoryInterface::class, AuthTokenRepository::class);
        $this->app->bind(AuthTokenBlacklistInterface::class, AuthTokenBlacklist::class);
        $this->app->bind(AuthTokenBlacklistRepositoryInterface::class, AuthTokenBlacklistRepository::class);
    }
}
