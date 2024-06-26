<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Actions;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Inisiatif\Package\User\Models\User;
use Inisiatif\Package\User\ModelRegistrar;

final class ConfirmIdentificationNumber
{
    /**
     * @param  User  $user
     */
    public function handle(mixed $user, string $pin): bool
    {
        $modelClass = ModelRegistrar::getUserModel()::class;

        if (! $user instanceof $modelClass) {
            throw new \RuntimeException(
                'Parameter $user must be instanceof '.ModelRegistrar::getUserModelClass()
            );
        }

        $confirmed = Hash::check($pin, $user->getAttribute('pin'));

        if ($confirmed) {
            $this->updatePin($user, $pin);
        }

        return $confirmed;
    }

    /**
     * @param  Model  $user
     */
    private function updatePin(mixed $user, string $pin): void
    {
        $modelClass = ModelRegistrar::getUserModel()::class;

        if (! $user instanceof $modelClass) {
            throw new \RuntimeException(
                'Parameter $user must be instanceof '.ModelRegistrar::getUserModelClass()
            );
        }

        $user->forceFill(
            Hash::needsRehash($user->getAttribute('pin')) ? [
                'pin' => Hash::make($pin),
                'pin_last_used_at' => now(),
            ] : [
                'pin_last_used_at' => now(),
            ]
        )->save();
    }
}
