<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Actions;

use Illuminate\Http\Request;
use Inisiatif\Package\User\Models\PersonalAccessToken;

final class DeleteCurrentToken
{
    public function handle(string $token): void
    {
        PersonalAccessToken::findToken($token)?->delete();
    }

    public function fromRequest(Request $request): void
    {
        $token = $request->bearerToken();

        if ($token) {
            $this->handle($token);
        }
    }
}
