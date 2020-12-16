<?php declare(strict_types=1);

namespace Inisiatif\Package\User\Token;

use Illuminate\Support\Carbon;
use Tymon\JWTAuth\Contracts\Providers\Storage as StorageInterface;
use Inisiatif\Package\Contract\User\Model\AuthTokenBlacklistInterface;
use Inisiatif\Package\Contract\User\Repository\AuthTokenBlacklistRepositoryInterface;

final class Storage implements StorageInterface
{
    private AuthTokenBlacklistRepositoryInterface $repository;

    public function __construct(AuthTokenBlacklistRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @noinspection PhpUndefinedMethodInspection
     */
    public function add($key, $value, $minutes)
    {
        if ($this->get($key)) {
            return;
        }

        /** @var AuthTokenBlacklistInterface $model */
        $model = $this->repository->getModel();

        $model->setTokenKey($key);
        $model->setTokenValues($value);

        $expired = Carbon::now()->addMinutes($minutes);
        $model->setExpiredAt($expired);

        $this->repository->save($model);
    }

    /**
     * @noinspection PhpUndefinedMethodInspection
     */
    public function forever($key, $value)
    {
        if ($this->get($key)) {
            return;
        }

        /** @var AuthTokenBlacklistInterface $model */
        $model = $this->repository->getModel();

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

    public function flush(): bool
    {
        return true;
    }
}
