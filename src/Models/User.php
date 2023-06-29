<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Models;

use Illuminate\Foundation\Auth;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\MorphTo;

final class User extends Auth\User
{
    use HasUuids;
    use Notifiable;
    use HasApiTokens;

    public function getTable(): string
    {
        /** @var string */
        return config('user.table_names.users', parent::getTable());
    }

    public function loginable(): MorphTo
    {
        return $this->morphTo();
    }

    public function getLoginable(): mixed
    {
        $this->loadMissing('loginable');

        return $this->getAttribute('loginable');
    }

    public function getLoginableType(): string
    {
        return $this->getAttribute('loginable_type');
    }
}
