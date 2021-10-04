<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Providers;

use Laravel\Sanctum\Sanctum;
use Inisiatif\Package\Common\Common;
use Illuminate\Support\ServiceProvider;
use Inisiatif\Package\User\Models\Employee;
use Inisiatif\Package\User\Models\Volunteer;
use Illuminate\Database\Eloquent\Relations\Relation;
use Inisiatif\Package\User\Models\PersonalAccessToken;
use Inisiatif\Package\User\Repositories\UserRepository;
use Inisiatif\Package\Contract\User\Model\LoginableAwareInterface;
use Inisiatif\Package\Contract\User\Repository\UserRepositoryInterface;

final class UserServiceProvider extends ServiceProvider
{
    public static bool $isRunMigration = true;

    public function boot(): void
    {
        Relation::morphMap([
            LoginableAwareInterface::TYPE_EMPLOYEE => Employee::class,
            LoginableAwareInterface::TYPE_VOLUNTEER => Volunteer::class,
        ]);

        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);

        if (self::$isRunMigration) {
            $this->loadMigrationsFrom(__DIR__ . '/../../migrations');
        }

        $this->mergeConfigFrom(__DIR__ . '/../../config/user.php', 'user');
    }

    public function register(): void
    {
        Sanctum::ignoreMigrations();
        Common::runningMigrations($this->app->runningUnitTests());

        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }
}
