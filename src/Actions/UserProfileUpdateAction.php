<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Actions;

use RuntimeException;
use Inisiatif\Package\User\Abstracts\UserAction;
use Inisiatif\Package\User\Builders\UserBuilder;
use Inisiatif\Package\Contract\User\Model\UserInterface;
use Inisiatif\Package\User\Events\UserProfileWasUpdated;

final class UserProfileUpdateAction extends UserAction
{
    public function handle(string $id, array $attributes): UserInterface
    {
        $user = $this->repository->findUsingId($id);

        if ($user === null) {
            throw new RuntimeException(sprintf('User with id `%s` not found.', $id));
        }

        $newUser = UserBuilder::makeFromArray($user, $attributes);

        $this->repository->save($newUser);

        $this->event->dispatch(UserProfileWasUpdated::class, $newUser);

        return $newUser;
    }
}
