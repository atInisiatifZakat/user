<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Tests\Actions;

use Mockery as m;
use RuntimeException;
use Illuminate\Support\Facades\Event;
use Illuminate\Contracts\Events\Dispatcher;
use Inisiatif\Package\User\Tests\UserTestCase;
use Inisiatif\Package\User\Events\UserProfileWasUpdated;
use Inisiatif\Package\User\Actions\UserProfileUpdateAction;
use Inisiatif\Package\Contract\User\Repository\UserRepositoryInterface;

final class UserProfileUpdateActionTest extends UserTestCase
{
    public function testCanUpdateProfileUser(): void
    {
        $user = $this->createUser();

        $dispatcher = m::mock(Dispatcher::class);
        $dispatcher->shouldReceive('dispatch')
            ->with(UserProfileWasUpdated::class, $user)
            ->once();

        $repository = m::mock(UserRepositoryInterface::class);
        $repository->shouldReceive('findUsingId')
            ->with($user->getId())
            ->once()
            ->andReturn($user);
        $repository->shouldReceive('save')
            ->with($user)
            ->once()
            ->andReturn(true);

        $action = new UserProfileUpdateAction($dispatcher, $repository);
        $updatedUser = $action->handle($user->getId(), [
            'name' => 'foo bar',
            'email' => 'bar@foo.com',
            'username' => 'foobar',
        ]);

        $this->assertSame($updatedUser->getName(), 'foo bar');
        $this->assertSame($updatedUser->getEmail(), 'bar@foo.com');
        $this->assertSame($updatedUser->getUsername(), 'foobar');
    }

    public function testCannotUpdateProfileUserWithInvalidId(): void
    {
        $event = Event::fake();

        $id = '22b99de2-539a-41a3-b64a-ad6ec80475dc';

        $this->expectException(RuntimeException::class);
        $this->expectDeprecationMessage(sprintf('User with id `%s` not found.', $id));

        $dispatcher = $this->app->make(Dispatcher::class);
        $repository = $this->app->make(UserRepositoryInterface::class);

        $action = new UserProfileUpdateAction($dispatcher, $repository);
        $action->handle($id, [
            'name' => 'foo bar',
            'email' => 'bar@foo.com',
            'username' => 'foobar',
        ]);
        $event->assertNotDispatched(UserProfileWasUpdated::class);
    }

    public function testCanActualUpdateProfileUser(): void
    {
        $user = $this->createUser();

        $event = Event::fake();

        $dispatcher = $this->app->make(Dispatcher::class);
        $repository = $this->app->make(UserRepositoryInterface::class);

        $action = new UserProfileUpdateAction($dispatcher, $repository);
        $action->handle($user->getId(), [
            'name' => 'foo bar',
            'email' => 'bar@foo.com',
            'username' => 'foobar',
        ]);

        $updatedUser = $repository->findUsingId($user->getId());
        $this->assertSame($updatedUser->getName(), 'foo bar');
        $this->assertSame($updatedUser->getEmail(), 'bar@foo.com');
        $this->assertSame($updatedUser->getUsername(), 'foobar');

        $event->assertDispatched(UserProfileWasUpdated::class);

        $this->assertDatabaseHas('users', [
            'name' => 'foo bar',
            'email' => 'bar@foo.com',
            'username' => 'foobar',
        ]);
    }
}
