<?php

declare(strict_types=1);

namespace Inisiatif\Package\User;

use Illuminate\Database\Eloquent\Model;

final class ModelRegistrar
{
    /**
     * @return class-string<Model>
     */
    public static function getUserModelClass(): string
    {
        /** @var class-string<Model> */
        return \config('user.models.user');
    }

    public static function getUserModel(): mixed
    {
        return app(
            self::getUserModelClass()
        );
    }

    /**
     * @return class-string<Model>
     */
    public static function getEmployeeModelClass(): string
    {
        /** @var class-string<Model> */
        return \config('user.models.employee');
    }

    public static function getEmployeeModel(): mixed
    {
        return app(
            self::getEmployeeModelClass()
        );
    }

    /**
     * @return class-string<Model>
     */
    public static function getVolunteerModelClass(): string
    {
        /** @var class-string<Model> */
        return \config('user.models.volunteer');
    }

    public static function getVolunteerModel(): mixed
    {
        return app(
            self::getVolunteerModelClass()
        );
    }

    /**
     * @return class-string<Model>
     */
    public static function getBranchModelClass(): string
    {
        /** @var class-string<Model> */
        return \config('user.models.branch');
    }

    public static function getBranchModel(): mixed
    {
        return app(
            self::getBranchModelClass()
        );
    }
}
