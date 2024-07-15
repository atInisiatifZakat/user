<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Actions;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\RateLimiter;
use Inisiatif\Package\User\Events\AuthenticationAttemptsExceeded;
use Inisiatif\Package\User\ModelRegistrar;
use Inisiatif\Package\User\Utils\PinException;

final class ConfirmPassword
{
    /**
     * @param  Model  $user
     */
    public function handle(mixed $user, string $password)
    {
        $modelClass = ModelRegistrar::getUserModel()::class;

        if (!$user instanceof $modelClass) {
            throw new \RuntimeException(
                'Parameter $user must be instanceof ' . ModelRegistrar::getUserModelClass()
            );
        }

        $maxAttempts = \config('user.pin.max_attempts', 3);
        $decayMinutes = \config('user.pin.max_decay_minutes', 30);
        $key = 'change-pin-attempts:' . $user->getAuthIdentifier();

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);
            $minutes = ceil($seconds / 60);

            throw new PinException('rate_limit', "Terlalu banyak percobaan untuk memasukan password. Mohon tunggu $minutes menit.");
        }

        $hashed = \config('user.hashing_password_before_attempt', true);

        $confirm = Hash::check($hashed ? \md5($password) : $password, $user->getAttribute('password'));

        if (!$confirm) {
            RateLimiter::hit($key, $decayMinutes * 60);

            if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
                event(new AuthenticationAttemptsExceeded($user, 'change-password'));
            }

            throw new PinException('password_error', "Password yang anda masukan salah");
        }

        RateLimiter::clear($key);
    }
}
