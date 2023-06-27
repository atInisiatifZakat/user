<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Tests\Models;

use Ramsey\Uuid\Uuid;
use Inisiatif\Package\Common\Models\Branch;
use Inisiatif\Package\User\Models\Employee;
use Inisiatif\Package\User\Tests\UserModel;
use Inisiatif\Package\User\Models\Volunteer;
use Inisiatif\Package\User\Tests\UserTestCase;
use Inisiatif\Package\User\Models\AbstractUser;

final class VolunteerTest extends UserTestCase
{
    public function test_user_relation_must_be_return_correct_class(): void
    {
        $employee = new Volunteer();

        $this->assertSame(UserModel::class, \get_class($employee->user()->getModel()));

        $userClass = new class() extends AbstractUser
        {
        };

        $this->app->bind(UserModel::class, \get_class($userClass));

        $this->assertSame(\get_class($userClass), \get_class($employee->user()->getModel()));
    }

    public function test_must_be_return_branch(): void
    {
        $branch = Branch::query()->forceCreate([
            'type' => 'KP',
            'name' => 'Branch Name',
            'is_active' => true,
            'is_head_office' => true,
        ]);

        $employee = Employee::query()->forceCreate([
            'nip' => Uuid::uuid6()->toString(),
            'branch_id' => $branch->getAttribute('id'),
            'name' => 'Employee Name',
            'email' => 'foo@employee.com',
        ]);

        /** @var Volunteer $volunteer */
        $volunteer = Volunteer::query()->forceCreate([
            'employee_id' => $employee->getAttribute('id'),
            'name' => 'Volunteer Name',
            'email' => 'foo@volunteer.com',
        ]);

        $this->assertSame(Branch::class, \get_class($volunteer->branch()->getModel()));
        $this->assertSame($branch->getAttribute('id'), $volunteer->loadMissing('branch')->getAttribute('branch')->getAttribute('id'));
    }
}
