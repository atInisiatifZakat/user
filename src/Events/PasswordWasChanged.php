<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Events;

use Inisiatif\Package\User\Abstracts\UserEvent;

final class PasswordWasChanged extends UserEvent
{
    public function name(): string
    {
        return 'user.password.changed';
    }
}
