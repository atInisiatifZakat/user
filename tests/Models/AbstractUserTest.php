<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Tests\Models;

use Inisiatif\Package\User\Tests\UserTestCase;
use Inisiatif\Package\User\Models\AbstractUser;
use Inisiatif\Package\Contract\User\Model\UserInterface;
use Inisiatif\Package\Contract\User\Model\LoginableAwareInterface;
use Inisiatif\Package\Contract\Common\Concern\ToggleAwareInterface;

final class AbstractUserTest extends UserTestCase
{
    public function testCanCreateUserObject(): void
    {
        $user = new class() extends AbstractUser {
        };

        $user->setId('6f16bc0a-84f0-4ac6-b4ec-9430dd5d48de');
        $user->setUsername('foobar');
        $user->setEmail('foo@bar.com');
        $user->setName('Foo Bar');

        $this->assertInstanceOf(AbstractUser::class, $user);
        $this->assertInstanceOf(UserInterface::class, $user);
        $this->assertInstanceOf(LoginableAwareInterface::class, $user);
        $this->assertInstanceOf(ToggleAwareInterface::class, $user);

        $this->assertSame($user->getId(), '6f16bc0a-84f0-4ac6-b4ec-9430dd5d48de');
        $this->assertSame($user->getUsername(), 'foobar');
        $this->assertSame($user->getEmail(), 'foo@bar.com');
        $this->assertSame($user->getName(), 'Foo Bar');
        $this->assertTrue($user->isActive());
    }
}
