<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Tests\Models;

use Inisiatif\Package\User\Models\Employee;
use Inisiatif\Package\User\Tests\UserModel;
use Inisiatif\Package\User\Tests\UserTestCase;
use Inisiatif\Package\User\Models\AbstractUser;

final class EmployeeTest extends UserTestCase
{
    public function test_user_relation_must_be_return_correct_class(): void
    {
        $employee = new Employee();

        $this->assertSame(UserModel::class, \get_class($employee->user()->getModel()));

        $userClass = new class() extends AbstractUser
        {
        };

        $this->app->bind(UserModel::class, get_class($userClass));

        $this->assertSame(\get_class($userClass), \get_class($employee->user()->getModel()));
    }
}
