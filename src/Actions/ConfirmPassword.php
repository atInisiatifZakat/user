<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Actions;

use Illuminate\Database\Eloquent\Model;
use Inisiatif\Package\User\ModelRegistrar;
use Illuminate\Contracts\Auth\StatefulGuard;

final class ConfirmPassword
{
    public function __construct(private readonly StatefulGuard $guard)
    {
    }

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

        return $this->guard->validate([
            'email' => $user->getAttribute('email'),
            'password' => $hashed ? \md5($password) : $password,
        ]);
    }
}
