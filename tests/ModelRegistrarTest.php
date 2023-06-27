<?php

namespace Inisiatif\Package\User\Tests;

use Inisiatif\Package\User\ModelRegistrar;

final class ModelRegistrarTest extends TestCase
{
    public function test_should_return_valid_value(): void
    {
        $this->assertSame(ModelRegistrar::getUserModelClass(), \config('user.models.user'));
        $this->assertSame(ModelRegistrar::getBranchModelClass(), \config('user.models.branch'));
        $this->assertSame(ModelRegistrar::getEmployeeModelClass(), \config('user.models.employee'));
        $this->assertSame(ModelRegistrar::getVolunteerModelClass(), \config('user.models.volunteer'));
    }
}
