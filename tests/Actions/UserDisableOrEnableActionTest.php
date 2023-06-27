<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Tests\Actions;

use Mockery as m;
use Illuminate\Support\Facades\Event;
use Illuminate\Contracts\Events\Dispatcher;
use Inisiatif\Package\User\Tests\UserTestCase;
use Inisiatif\Package\User\Models\AbstractUser;
use Inisiatif\Package\User\Events\UserWasEnabled;
use Inisiatif\Package\User\Events\UserWasDisabled;
use Inisiatif\Package\User\Actions\UserDisableOrEnableAction;
use Inisiatif\Package\Contract\User\Repository\UserRepositoryInterface;

final class UserDisableOrEnableActionTest extends UserTestCase
{
    public function test_can_disable_user(): void
    {
        $user = new class() extends AbstractUser
        {
        };

        $dispatcher = m::mock(Dispatcher::class);
        $dispatcher->shouldReceive('dispatch')
            ->with(UserWasDisabled::class, $user)
            ->once();

        $repository = m::mock(UserRepositoryInterface::class);
        $repository->shouldReceive('save')
            ->with($user)
            ->once()
            ->andReturn(true);

        $action = new UserDisableOrEnableAction($dispatcher, $repository);
        $disabledUser = $action->handle($user, false);

        $this->assertNotNull($disabledUser->getDeactivatedAt());
        $this->assertFalse($disabledUser->isActive());
    }

    public function test_can_actual_disable_user(): void
    {
        $user = $this->createUser();

        $event = Event::fake();

        $dispatcher = $this->app->make(Dispatcher::class);
        $repository = $this->app->make(UserRepositoryInterface::class);

        $action = new UserDisableOrEnableAction($dispatcher, $repository);
        $action->handle($user, false);

        $newUser = $repository->findUsingId($user->getId());
        $this->assertFalse($newUser->isActive());
        $this->assertNotNull($newUser->getDeactivatedAt());
        $event->assertDispatched(UserWasDisabled::class);
    }

    public function test_can_enable_user(): void
    {
        $user = new class() extends AbstractUser
        {
        };

        $dispatcher = m::mock(Dispatcher::class);
        $dispatcher->shouldReceive('dispatch')
            ->with(UserWasEnabled::class, $user)
            ->once();

        $repository = m::mock(UserRepositoryInterface::class);
        $repository->shouldReceive('save')
            ->with($user)
            ->once()
            ->andReturn(true);

        $action = new UserDisableOrEnableAction($dispatcher, $repository);
        $disabledUser = $action->handle($user, true);

        $this->assertTrue($disabledUser->isActive());
        $this->assertNull($disabledUser->getDeactivatedAt());
    }

    public function test_can_actual_enable_user(): void
    {
        $user = $this->createUser();

        $event = Event::fake();

        $dispatcher = $this->app->make(Dispatcher::class);
        $repository = $this->app->make(UserRepositoryInterface::class);

        $action = new UserDisableOrEnableAction($dispatcher, $repository);
        $action->handle($user, true);

        $newUser = $repository->findUsingId($user->getId());
        $this->assertTrue($newUser->isActive());
        $this->assertNull($newUser->getDeactivatedAt());
        $event->assertDispatched(UserWasEnabled::class);
    }
}
