<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Tests\Actions;

use Mockery as m;
use Illuminate\Support\Facades\Event;
use Illuminate\Contracts\Events\Dispatcher;
use Inisiatif\Package\User\Tests\UserModel;
use Inisiatif\Package\User\Tests\UserTestCase;
use Inisiatif\Package\User\Models\AbstractUser;
use Inisiatif\Package\User\Builders\UserBuilder;
use Inisiatif\Package\User\Events\UserWasCreated;
use Inisiatif\Package\User\Actions\UserCreateAction;
use Inisiatif\Package\Contract\User\Repository\UserRepositoryInterface;

final class UserCreateActionTest extends UserTestCase
{
    public function testCanCreateUser(): void
    {
        $user = UserBuilder::makeFromArray(new class() extends AbstractUser {
        }, [
            'name' => 'foo bar',
            'email' => 'bar@foo.com',
            'username' => 'foobar',
            'password' => 'secret',
        ]);

        $dispatcher = m::mock(Dispatcher::class);
        $dispatcher->shouldReceive('dispatch')
            ->with(UserWasCreated::class, $user)
            ->once();

        $repository = m::mock(UserRepositoryInterface::class);
        $repository->shouldReceive('save')
            ->with($user)
            ->once()
            ->andReturn(true);

        $action = new UserCreateAction($dispatcher, $repository);
        $user = $action->handle($user);

        $this->assertNotNull($user->getId());
        $this->assertSame($user->getName(), 'foo bar');
        $this->assertSame($user->getEmail(), 'bar@foo.com');
        $this->assertSame($user->getUsername(), 'foobar');
        $this->assertTrue(password_verify(md5('secret'), $user->getPassword()));
    }

    public function testCanActualCreateUser(): void
    {
        $model = UserModel::fromArray([
            'name' => 'foo bar',
            'email' => 'bar@foo.com',
            'username' => 'foobar',
            'password' => 'secret',
        ]);

        $event = Event::fake();

        $dispatcher = $this->app->make(Dispatcher::class);
        $repository = $this->app->make(UserRepositoryInterface::class);

        $action = new UserCreateAction($dispatcher, $repository);
        $user = $action->handle($model);

        $newUser = $repository->findUsingId($user->getId());
        $this->assertSame($newUser->getId(), $user->getId());
        $this->assertSame($newUser->getName(), 'foo bar');
        $this->assertSame($newUser->getEmail(), 'bar@foo.com');
        $this->assertSame($newUser->getUsername(), 'foobar');
        $this->assertTrue(password_verify(md5('secret'), $newUser->getPassword()));

        $event->assertDispatched(UserWasCreated::class);

        $this->assertDatabaseHas('users', [
            'name' => 'foo bar',
            'email' => 'bar@foo.com',
            'username' => 'foobar',
        ]);
    }
}
