<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Inisiatif\Package\User\Actions\ConfirmPassword;
use Inisiatif\Package\User\Actions\UpdateIdentificationNumber;

final class IdentificationNumberController
{
    /**
     * @throws ValidationException
     */
    public function update(Request $request, ConfirmPassword $confirmPassword, UpdateIdentificationNumber $pin): JsonResponse
    {
        $request->validate([
            'password' => ['required'],
            'pin' => ['required', 'numeric', 'max:6', 'min:6', 'confirmed'],
        ]);

        $confirm = $confirmPassword->handle(
            $request->user(),
            $request->string('password')->toString()
        );

        if ($confirm === false) {
            throw ValidationException::withMessages([
                'password' => [
                    'Password yang anda masukkan salah.',
                ],
            ]);
        }

        $pin->handle($request->user(), $request->string('pin')->toString());

        return new JsonResponse(null, 204);
    }
}
