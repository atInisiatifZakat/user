<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Events;

use Inisiatif\Package\Contract\User\Model\UserInterface;
use Inisiatif\Package\Contract\User\Model\TokenAwareInterface;
use Inisiatif\Package\Contract\Common\Event\ModelEventInterface;

final class TokenWasGenerated implements ModelEventInterface, TokenAwareInterface
{
    private string $token;

    private UserInterface $user;

    public function __construct(UserInterface $user, string $token)
    {
        $this->token = $token;
        $this->user = $user;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function name(): string
    {
        return 'user.login';
    }

    public function getModel(): UserInterface
    {
        return $this->user;
    }
}
