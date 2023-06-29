<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Tests;

use Illuminate\Database\Eloquent\Model;
use Inisiatif\Package\User\Models\User;
use Inisiatif\Package\User\Models\Branch;
use Inisiatif\Package\User\ModelRegistrar;
use Inisiatif\Package\User\Models\Employee;
use Inisiatif\Package\User\Models\Volunteer;

final class ModelRegistrarTest extends TestCase
{
    public function test_should_return_valid_value(): void
    {
        $this->assertSame(ModelRegistrar::getUserModelClass(), \config('user.models.user'));
        $this->assertSame(ModelRegistrar::getBranchModelClass(), \config('user.models.branch'));
        $this->assertSame(ModelRegistrar::getEmployeeModelClass(), \config('user.models.employee'));
        $this->assertSame(ModelRegistrar::getVolunteerModelClass(), \config('user.models.volunteer'));

        $this->assertInstanceOf(Model::class, ModelRegistrar::getUserModel());
        $this->assertInstanceOf(User::class, ModelRegistrar::getUserModel());

        $this->assertInstanceOf(Model::class, ModelRegistrar::getBranchModel());
        $this->assertInstanceOf(Branch::class, ModelRegistrar::getBranchModel());

        $this->assertInstanceOf(Model::class, ModelRegistrar::getEmployeeModel());
        $this->assertInstanceOf(Employee::class, ModelRegistrar::getEmployeeModel());

        $this->assertInstanceOf(Model::class, ModelRegistrar::getVolunteerModel());
        $this->assertInstanceOf(Volunteer::class, ModelRegistrar::getVolunteerModel());
    }
}
