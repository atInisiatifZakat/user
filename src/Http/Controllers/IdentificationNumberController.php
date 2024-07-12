<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Inisiatif\Package\User\Actions\ConfirmPassword;
use Inisiatif\Package\User\Actions\UpdateIdentificationNumber;
use Inisiatif\Package\User\Events\AuthenticationAttemptsExceeded;

final class IdentificationNumberController
{
    /**
     * @throws ValidationException
     */
    public function update(Request $request, ConfirmPassword $confirmPassword, UpdateIdentificationNumber $pin): JsonResponse
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'password' => ['required'],
            'pin' => ['required', 'numeric', 'confirmed', 'digits:6'],
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();

            if ($errors->has('password')) {
                return new JsonResponse([
                    'message' => 'Password wajib dimasukan.',
                    'type' => 'password_error',
                ], 422);
            }

            if ($errors->has('pin')) {
                return new JsonResponse([
                    'message' => 'Pin dan konfirmasi pin harus sama dan tidak boleh kurang dari 6 digit.',
                    'type' => 'pin_error',
                ], 422);
            }
        }

        $confirm = $confirmPassword->handle($user, $request->string('password')->toString());


        if ($confirm instanceof JsonResponse) {
            return $confirm;

        }

        $pin->handle($user, $request->string('pin')->toString());

        return new JsonResponse([
            'message' => 'Pin Berhasil Diubah',
            'type' => 'change_pin_success',
        ], 204);
    }
}
