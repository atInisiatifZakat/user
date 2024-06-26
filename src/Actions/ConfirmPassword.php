<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Actions;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Inisiatif\Package\User\ModelRegistrar;

final class ConfirmPassword
{
    /**
     * @param  Model  $user
     */
    public function handle(mixed $user, string $password): bool
    {
        $modelClass = ModelRegistrar::getUserModel()::class;

        if (! $user instanceof $modelClass) {
            throw new \RuntimeException(
                'Parameter $user must be instanceof '.ModelRegistrar::getUserModelClass()
            );
        }

        $hashed = \config('user.hashing_password_before_attempt', true);

        return Hash::check($hashed ? \md5($password) : $password, $user->getAttribute('password'));
    }
}
