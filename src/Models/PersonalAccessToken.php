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

    public function getAgent(): ?array
    {
        $name = $this->getAttribute('name');

        if (! str_contains($name, '|')) {
            return null;
        }

        [$device, $platform, $browser, $ip] = \explode('|', $name, 4);

        return \compact('device', 'platform', 'browser', 'ip');
    }
}
