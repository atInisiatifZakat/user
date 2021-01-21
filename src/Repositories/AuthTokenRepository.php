<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Repositories;

use Webmozart\Assert\Assert;
use Inisiatif\Package\User\Models\AuthToken;
use Inisiatif\Package\Common\Abstracts\AbstractRepository;
use Inisiatif\Package\Contract\User\Model\AuthTokenInterface;
use Inisiatif\Package\Contract\User\Repository\AuthTokenRepositoryInterface;

final class AuthTokenRepository extends AbstractRepository implements AuthTokenRepositoryInterface
{
    protected $model = AuthToken::class;

    public function findOneUsingTokenKey($value)
    {
        $model = $this->findOneUsingColumn('key', $value);

        Assert::nullOrIsInstanceOf($model, AuthTokenInterface::class);

        return $model;
    }
}
