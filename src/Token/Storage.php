<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Token;

use RuntimeException;
use Illuminate\Support\Carbon;
use Tymon\JWTAuth\Contracts\Providers\Storage as StorageInterface;
use Inisiatif\Package\Contract\User\Model\AuthTokenBlacklistInterface;
use Inisiatif\Package\Contract\Common\Repository\EloquentAwareRepositoryInterface;
use Inisiatif\Package\Contract\User\Repository\AuthTokenBlacklistRepositoryInterface;

final class Storage implements StorageInterface
{
    private AuthTokenBlacklistRepositoryInterface $repository;

    public function __construct(AuthTokenBlacklistRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function add($key, $value, $minutes): void
    {
        if ($this->get($key)) {
            return;
        }

        $model = $this->getModel();

        $model->setTokenKey($key);
        $model->setTokenValues($value);

        $expired = Carbon::now()->addMinutes($minutes);
        $model->setExpiredAt($expired);

        $this->repository->save($model);
    }

    public function forever($key, $value): void
    {
        if ($this->get($key)) {
            return;
        }

        $model = $this->getModel();

        $model->setTokenKey($key);
        $model->setTokenValues($value);

        $this->repository->save($model);
    }

    public function get($key)
    {
        return $this->repository->findOneUsingTokenKey($key);
    }

    public function destroy($key): bool
    {
        return true;
    }

    public function flush(): void
    {
    }

    private function getModel(): AuthTokenBlacklistInterface
    {
        if (! $this->repository instanceof EloquentAwareRepositoryInterface) {
            throw new RuntimeException('Repository must be instanceof ' . EloquentAwareRepositoryInterface::class);
        }

        $model = $this->repository->getModel();

        if (! $model instanceof AuthTokenBlacklistInterface) {
            throw new RuntimeException('Model must be instanceof ' . AuthTokenBlacklistInterface::class);
        }

        return $model;
    }
}
