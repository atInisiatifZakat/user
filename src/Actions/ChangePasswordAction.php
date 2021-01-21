<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Actions;

use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Contracts\Events\Dispatcher;
use Inisiatif\Package\User\Abstracts\UserAction;
use Inisiatif\Package\User\Events\PasswordWasChanged;
use Inisiatif\Package\Contract\User\Model\UserInterface;
use Inisiatif\Package\Contract\User\Repository\UserRepositoryInterface;

final class ChangePasswordAction extends UserAction
{
    private Hasher $hasher;

    public function __construct(Dispatcher $event, UserRepositoryInterface $repository, Hasher $hasher)
    {
        $this->hasher = $hasher;

        parent::__construct($event, $repository);
    }

    public function handle(UserInterface $user, string $plainPassword): UserInterface
    {
        $user->setPassword(
            $this->hasher->make(
                md5($plainPassword)
            )
        );

        $this->repository->save($user);

        $this->event->dispatch(PasswordWasChanged::class, $user);

        return $user;
    }
}
