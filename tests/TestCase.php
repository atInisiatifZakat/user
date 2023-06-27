<?php

namespace Inisiatif\Package\User\Tests;

use Orchestra\Testbench;
use Inisiatif\Package\User\UserServiceProvider;

abstract class TestCase extends Testbench\TestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            UserServiceProvider::class,
        ];
    }
}
