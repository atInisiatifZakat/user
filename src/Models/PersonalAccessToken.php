<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Models;

use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;

final class PersonalAccessToken extends SanctumPersonalAccessToken
{
    public function getTable(): string
    {
        /** @var string */
        return \config('user.table_names.personal_access_tokens', parent::getTable());
    }
}
