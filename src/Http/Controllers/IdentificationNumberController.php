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
            $request->validate([
                'password' => ['required'],
                'pin' => ['required', 'numeric', 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            $errors = $e->errors();
            if (isset($errors['pin'])) {
                return response()->json([
                    'message' => 'PIN dan konfirmasi PIN tidak sama.',
                    'error_type' => 'pin_confirmation_error'
                ], 422);
            }
            throw $e;
        }

        if (RateLimiter::tooManyAttempts($key, $this->maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);
            $minutes = ceil($seconds / 60);
            return response()->json([
                'message' => "Terlalu banyak percobaan. Silakan coba lagi dalam $minutes menit.",
                'error_type' => 'rate_limit'
            ], 429);
        }

        $confirm = $confirmPassword->handle($user, $request->string('password')->toString());

        if ($confirm === false) {
            RateLimiter::hit($key, $this->decayMinutes * 60);

            if (RateLimiter::tooManyAttempts($key, $this->maxAttempts)) {
                event(new AuthenticationAttemptsExceeded($user));
            }

            return response()->json([
                'message' => 'Password yang Anda masukkan salah.',
                'error_type' => 'password_error'
            ], 422);
        }

        RateLimiter::clear($key);

        $pin->handle($user, $request->string('pin')->toString());

        return new JsonResponse([
            'message' => 'PIN berhasil dirubah.'
        ], 204);
    }
}
