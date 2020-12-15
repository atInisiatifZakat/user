<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Events;

use Inisiatif\Package\User\Abstracts\UserEvent;

final class UserWasEnabled extends UserEvent
{
    public function name(): string
    {
        return 'user.enabled';
    }
}
