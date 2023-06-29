<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Inisiatif\Package\User\Actions\ListUserToken;
use Inisiatif\Package\User\Http\Resources\TokenResource;

final class UserTokenController
{
    public function index(Request $request, ListUserToken $token): JsonResource
    {
        return TokenResource::collection(
            $token->fromRequest($request)
        );
    }

    public function destroy(int $tokenId, Request $request): JsonResponse
    {
        $request->user()?->tokens()->where('id', $tokenId)?->delete();

        return new JsonResponse(null, 204);
    }
}
