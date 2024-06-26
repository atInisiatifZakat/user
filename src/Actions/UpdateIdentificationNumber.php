<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Actions;

use Illuminate\Support\Facades\Hash;
use Inisiatif\Package\User\Models\User;
use Inisiatif\Package\User\ModelRegistrar;

final class UpdateIdentificationNumber
{
    /**
     * @param  User  $user
     */
    public function handle(mixed $user, string $pin): void
    {
        $modelClass = ModelRegistrar::getUserModel()::class;

        if (! $user instanceof $modelClass) {
            throw new \RuntimeException(
                'Parameter $user must be instanceof '.ModelRegistrar::getUserModelClass()
            );
        }

        $user->forceFill([
            'pin' => Hash::make($pin),
        ])->save();
    }
}
