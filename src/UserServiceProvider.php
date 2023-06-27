<?php

declare(strict_types=1);

namespace Inisiatif\Package\User;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class UserServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('user')
            ->hasConfigFile()
            ->hasMigrations([
                'create_employees_and_volunteers_table',
                'create_users_table',
                'user_create_personal_access_tokens_table',
            ]);
    }

    public function packageRegistered(): void
    {
        $this->package->runsMigrations(
            $this->app->runningUnitTests()
        );
    }
}
