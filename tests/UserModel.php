<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Tests;

use Inisiatif\Package\User\Models\AbstractUser;

final class UserModel extends AbstractUser
{
    protected $table = 'users';
}
