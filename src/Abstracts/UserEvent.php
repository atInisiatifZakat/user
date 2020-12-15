<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Abstracts;

use Inisiatif\Package\Common\Abstracts\Event;
use Inisiatif\Package\Contract\User\Model\UserInterface;

abstract class UserEvent extends Event
{
    protected UserInterface $user;

    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }

    public function getModel(): UserInterface
    {
        return $this->user;
    }
}
