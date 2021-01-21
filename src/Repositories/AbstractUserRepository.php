<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Repositories;

use Inisiatif\Package\User\Models\AbstractUser;
use Inisiatif\Package\Contract\User\Model\UserInterface;
use Inisiatif\Package\Common\Abstracts\AbstractRepository;
use Inisiatif\Package\Contract\User\Repository\UserRepositoryInterface;

abstract class AbstractUserRepository extends AbstractRepository implements UserRepositoryInterface
{
    public function __construct(AbstractUser $user)
    {
        $this->model = get_class($user);
    }

    public function findUsingEmailOrUsername(string $value): ?UserInterface
    {
        $this->disableCache();

        $user = $this->findOneUsingColumn('email', $value);

        if ($user === null) {
            return $this->findOneUsingColumn('username', $value);
        }

        return $user;
    }
}
