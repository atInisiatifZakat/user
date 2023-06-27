<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Tests\Repositories;

use Inisiatif\Package\User\Tests\UserModel;
use Inisiatif\Package\User\Tests\UserTestCase;
use Inisiatif\Package\User\Repositories\UserRepository;
use Inisiatif\Package\Contract\User\Model\UserInterface;

final class UserRepositoryTest extends UserTestCase
{
    public function test_can_find_using_email_or_username(): void
    {
        $this->createUser();

        $user = new UserModel();
        $repository = new UserRepository($user);

        $user1 = $repository->findUsingEmailOrUsername('foo@bar.com');
        $this->assertInstanceOf(UserInterface::class, $user1);
        $this->assertSame('f6ad7c84-70c8-4f5e-8b96-efb0ceae1d31', $user1->getId());
        $this->assertSame('foobar', $user1->getUsername());
        $this->assertSame('foo@bar.com', $user1->getEmail());

        $user2 = $repository->findUsingEmailOrUsername('foobar');
        $this->assertInstanceOf(UserInterface::class, $user2);
        $this->assertSame('f6ad7c84-70c8-4f5e-8b96-efb0ceae1d31', $user2->getId());
        $this->assertSame('foobar', $user2->getUsername());
        $this->assertSame('foo@bar.com', $user2->getEmail());
    }
}
