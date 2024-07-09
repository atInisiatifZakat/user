<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Inisiatif\Package\User\Actions\ConfirmPassword;
use Inisiatif\Package\User\Actions\UpdateIdentificationNumber;
use Inisiatif\Package\User\Events\AuthenticationAttemptsExceeded;

final class IdentificationNumberController
{
    private $maxAttempts;
    private $decayMinutes;

    public function __construct()
    {
        $this->maxAttempts = \config('user.pin.max_attempts', 3);
        $this->decayMinutes = \config('user.pin.max_decay_minutes', 30);
    }

    /**
     * @throws ValidationException
     */
    public function update(Request $request, ConfirmPassword $confirmPassword, UpdateIdentificationNumber $pin): JsonResponse
    {
        $user = $request->user();
        $key = 'change-pin-attempts:' . $user->id;

        try {
            if (RateLimiter::tooManyAttempts($key, $this->maxAttempts)) {
                $seconds = RateLimiter::availableIn($key);
                $minutes = ceil($seconds / 60);
                throw ValidationException::withMessages([
                    'attempt' => [
                        "Too many attempts. Please try again in $minutes minutes.",
                    ],
                ]);
            }

            $request->validate([
                'password' => ['required'],
                'pin' => ['required', 'numeric', 'confirmed'],
            ]);

            $confirm = $confirmPassword->handle($user, $request->string('password')->toString());

            if ($confirm === false) {
                RateLimiter::hit($key, $this->decayMinutes * 60);

                if (RateLimiter::tooManyAttempts($key, $this->maxAttempts)) {
                    event(new AuthenticationAttemptsExceeded($user));
                }

                throw ValidationException::withMessages([
                    'password' => [
                        'Password yang anda masukkan salah.',
                    ],
                ]);
            }

            RateLimiter::clear($key);

            $pin->handle($user, $request->string('pin')->toString());

            return new JsonResponse([
                'message' => 'Pin Berhasil Diubah',
                'type' => 'change_pin_success'
            ], 204);
        } catch (ValidationException $e) {
            $errors = $e->errors();

            if (isset($errors['pin'])) {
                return response()->json([
                    'message' => 'PIN dan konfirmasi PIN tidak sama.',
                    'type' => 'pin_confirmation_error'
                ], 422);
            }

            if (isset($errors['attempt'])) {
                $seconds = RateLimiter::availableIn($key);
                $minutes = ceil($seconds / 60);

                return response()->json([
                    'message' => $errors['attempt'],
                    'type' => 'rate_limit'
                ], 429);
            }

            if (isset($errors['password'])) {
                return response()->json([
                    'message' => $errors['password'],
                    'type' => 'password_error'
                ], 422);
            }
        }
    }
}
