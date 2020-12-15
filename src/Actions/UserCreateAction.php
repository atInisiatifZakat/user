<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Actions;

use Inisiatif\Package\User\Abstracts\UserAction;
use Inisiatif\Package\User\Events\UserWasCreated;
use Inisiatif\Package\Contract\User\Model\UserInterface;

final class UserCreateAction extends UserAction
{
    public function handle(UserInterface $user): UserInterface
    {
        $this->repository->save($user);

        $this->event->dispatch(UserWasCreated::class, $user);

        return $user;
    }
}
