<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Inisiatif\Package\User\Actions\ConfirmPassword;
use Inisiatif\Package\User\Actions\UpdateIdentificationNumber;
use Inisiatif\Package\User\Utils\PinException;

final class IdentificationNumberController
{
    /**
     * @throws ValidationException
     */
    public function update(Request $request, ConfirmPassword $confirmPassword, UpdateIdentificationNumber $pin): JsonResponse
    {
        try {
            $user = $request->user();

            $validator = Validator::make($request->all(), [
                'password' => ['required'],
                'pin' => ['required', 'numeric', 'confirmed', 'digits:6'],
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();

                if ($errors->has('password')) {
                    throw new PinException('password_error', 'Password wajib dimasukan.');
                }

                if ($errors->has('pin')) {
                    throw new PinException(
                        'pin_error',
                        'Pin dan konfirmasi pin harus sama dan tidak boleh kurang dari 6 digit.',
                    );
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
        } catch (PinException $e) {
            return new JsonResponse([
                'message' => $e->getCustomMessage(),
                'type' => $e->getErrorType(),
            ], $e->getCode());
        }
    }
}
