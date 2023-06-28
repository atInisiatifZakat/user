<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Resources\Json\JsonResource;
use Inisiatif\Package\User\Actions\NewUserToken;
use Inisiatif\Package\User\Actions\DeleteCurrentToken;

final class TokenAuthController
{
    public function store(Request $request, NewUserToken $token): JsonResource
    {
        $request->validate([
            'email' => ['required', 'email'], 'password' => ['required'],
        ]);

        $newToken = $token->fromRequest($request);

        if (\is_null($newToken)) {
            throw ValidationException::withMessages([
                'email' => [
                    'These credentials do not match our records.',
                ],
            ]);
        }

        return JsonResource::make([
            'access_token' => $newToken->plainTextToken,
        ]);
    }

    public function destroy(Request $request, DeleteCurrentToken $token): JsonResponse
    {
        $token->fromRequest($request);

        return new JsonResponse(null, 204);
    }
}
