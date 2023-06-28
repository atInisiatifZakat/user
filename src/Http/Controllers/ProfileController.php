<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Http\Controllers;

use Illuminate\Http\Request;
use Inisiatif\Package\User\Http\Resources\UserResource;

final class ProfileController
{
    public function show(Request $request): UserResource
    {
        $user = $request->user();

        $user->loadMissing(['loginable', 'loginable.branch']);

        return UserResource::make($user);
    }
}
