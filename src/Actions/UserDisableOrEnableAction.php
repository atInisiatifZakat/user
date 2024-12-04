<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Actions;

use DateTime;
use Inisiatif\Package\User\Abstracts\UserAction;
use Inisiatif\Package\User\Events\UserWasEnabled;
use Inisiatif\Package\User\Events\UserWasDisabled;
use Inisiatif\Package\Contract\User\Model\UserInterface;

final class UserDisableOrEnableAction extends UserAction
{
    public function handle(UserInterface $user, bool $isActive): UserInterface
    {
        $value = $isActive ? null : new DateTime;

        $user->setDeactivatedAt($value);

        $this->repository->save($user);

        $this->event->dispatch($isActive ? UserWasEnabled::class : UserWasDisabled::class, $user);

        return $user;
    }
}
