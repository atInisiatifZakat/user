<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Abstracts;

use Illuminate\Contracts\Events\Dispatcher;
use Inisiatif\Package\Contract\User\Repository\UserRepositoryInterface;

abstract class UserAction
{
    protected Dispatcher $event;

    protected UserRepositoryInterface $repository;

    public function __construct(Dispatcher $event, UserRepositoryInterface $repository)
    {
        $this->event = $event;
        $this->repository = $repository;
    }
}
