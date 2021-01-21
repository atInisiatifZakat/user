<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Repositories;

use Webmozart\Assert\Assert;
use Inisiatif\Package\User\Models\AuthTokenBlacklist;
use Inisiatif\Package\Common\Abstracts\AbstractRepository;
use Inisiatif\Package\Contract\User\Model\AuthTokenBlacklistInterface;
use Inisiatif\Package\Contract\User\Repository\AuthTokenBlacklistRepositoryInterface;

final class AuthTokenBlacklistRepository extends AbstractRepository implements AuthTokenBlacklistRepositoryInterface
{
    protected $model = AuthTokenBlacklist::class;

    public function findOneUsingTokenKey($value)
    {
        $model = $this->findOneUsingColumn('key', $value);

        Assert::nullOrIsInstanceOf($model, AuthTokenBlacklistInterface::class);

        return $model;
    }
}
