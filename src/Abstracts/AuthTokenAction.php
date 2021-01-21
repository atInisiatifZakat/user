<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Abstracts;

use Illuminate\Contracts\Events\Dispatcher;
use Inisiatif\Package\Contract\User\Repository\AuthTokenRepositoryInterface;

abstract class AuthTokenAction
{
    protected Dispatcher $event;

    protected AuthTokenRepositoryInterface $repository;

    public function __construct(Dispatcher $event, AuthTokenRepositoryInterface $repository)
    {
        $this->event = $event;
        $this->repository = $repository;
    }
}
