<?php

declare(strict_types=1);

namespace Inisiatif\Package\User;

use Laravel\Sanctum\Sanctum;
use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelPackageTools\Package;
use Inisiatif\Package\User\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Inisiatif\Package\User\Factories\BranchFactory;
use Illuminate\Database\Eloquent\Relations\Relation;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class UserServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('user')
            ->hasConfigFile()
            ->hasMigrations([
                'create_branch_tables',
                'create_employees_and_volunteers_table',
                'create_users_table',
                'user_create_personal_access_tokens_table',
            ]);
    }

    public function registeringPackage(): void
    {
        Sanctum::ignoreMigrations();
    }

    public function bootingPackage(): void
    {
        Relation::morphMap([
            'EMPLOYEE' => ModelRegistrar::getEmployeeModelClass(),
            'VOLUNTEER' => ModelRegistrar::getVolunteerModelClass(),
        ]);

        /** @psalm-suppress InvalidArgument */
        Factory::guessModelNamesUsing(static function (Factory $factory) {
            /** @var class-string<Model>|null */
            return match (\get_class($factory)) {
                UserFactory::class => ModelRegistrar::getUserModelClass(),
                BranchFactory::class => ModelRegistrar::getBranchModelClass(),
                default => null,
            };
        });

        $this->package->runsMigrations(
            \config('user.migration')
        );
    }
}
