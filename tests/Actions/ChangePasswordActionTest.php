<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Tests\Actions;

use Mockery as m;
use Illuminate\Support\Facades\Event;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Contracts\Events\Dispatcher;
use Inisiatif\Package\User\Tests\UserTestCase;
use Inisiatif\Package\User\Models\AbstractUser;
use Inisiatif\Package\User\Events\PasswordWasChanged;
use Inisiatif\Package\Contract\User\Model\UserInterface;
use Inisiatif\Package\User\Actions\ChangePasswordAction;
use Inisiatif\Package\Contract\User\Repository\UserRepositoryInterface;

final class ChangePasswordActionTest extends UserTestCase
{
    public function test_can_change_password(): void
    {
        $user = new class extends AbstractUser {};

        $dispatcher = m::mock(Dispatcher::class);
        $dispatcher->shouldReceive('dispatch')
            ->with(PasswordWasChanged::class, $user)
            ->once();

        $repository = m::mock(UserRepositoryInterface::class);
        $repository->shouldReceive('save')
            ->with($user)
            ->once()
            ->andReturn(true);

        $plainText = 'secret';
        $hashText = \md5($plainText);
        $hasherText = bcrypt($hashText);

        $hasher = m::mock(Hasher::class);
        $hasher->shouldReceive('make')
            ->with($hashText)
            ->once()
            ->andReturn($hasherText);

        $action = new ChangePasswordAction($dispatcher, $repository, $hasher);
        $user = $action->handle($user, $plainText);

        $this->assertInstanceOf(UserInterface::class, $user);
        $this->assertSame($user->getPassword(), $hasherText);
    }

    public function test_can_actual_change_user_password(): void
    {
        $user = $this->createUser();

        $event = Event::fake();

        $dispatcher = $this->app->make(Dispatcher::class);
        $repository = $this->app->make(UserRepositoryInterface::class);
        $hasher = $this->app->make(Hasher::class);

        $action = new ChangePasswordAction($dispatcher, $repository, $hasher);
        $action->handle($user, 'new password');

        $newUser = $repository->findUsingId($user->getId());
        $this->assertTrue($hasher->check(\md5('new password'), $newUser->getPassword()));
        $event->assertDispatched(PasswordWasChanged::class);
    }
}
