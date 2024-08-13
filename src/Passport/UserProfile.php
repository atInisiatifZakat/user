<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Passport;

final class UserProfile
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $email,
        public readonly string $loginableId,
        public readonly string $loginableType
    ) {}
}
