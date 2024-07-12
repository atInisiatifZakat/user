<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Actions;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Inisiatif\Package\User\Models\User;
use Inisiatif\Package\User\ModelRegistrar;
use Inisiatif\Package\User\Events\AuthenticationAttemptsExceeded;

final class ConfirmIdentificationNumber
{
    /**
     * @param  User  $user
     */
    public function handle(mixed $user, string $pin): bool
    {
        $maxAttempts = \config('pin.max_attempts', 3);
        $decayMinutes = \config('pin.max_decay_minutes', 30);
        $key = 'pin-attempts:' . $user->id;

        $modelClass = ModelRegistrar::getUserModel()::class;

        if (!$user instanceof $modelClass) {
            throw new \RuntimeException(
                'Parameter $user must be instanceof ' . ModelRegistrar::getUserModelClass()
            );
        }

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);
            $minutes = ceil($seconds / 60);

            throw ValidationException::withMessages([
                'pin' => "Terlalu banyak percobaan memasukan pin. Silakan coba lagi dalam $minutes menit.",
            ]);
        }

        $confirmed = Hash::check($pin, $user->getAttribute('pin'));

        if (!$confirmed) {
            RateLimiter::hit($key, 60 * $decayMinutes);

            if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
                event(new AuthenticationAttemptsExceeded($user, 'usage-pin'));
            }

            throw ValidationException::withMessages([
                'pin' => 'PIN yang diberikan salah.',
            ]);
        }

        RateLimiter::clear($key);

        return $confirmed;
    }
}
