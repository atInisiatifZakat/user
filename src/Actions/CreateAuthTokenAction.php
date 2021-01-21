<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Actions;

use Inisiatif\Package\User\Abstracts\AuthTokenAction;
use Inisiatif\Package\Contract\User\Model\AuthTokenInterface;

final class CreateAuthTokenAction extends AuthTokenAction
{
    public function handle(AuthTokenInterface $token): AuthTokenInterface
    {
        $this->repository->save($token);

        return $token;
    }
}
