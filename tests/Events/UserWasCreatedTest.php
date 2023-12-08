<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Tests\Events;

use Inisiatif\Package\User\Tests\UserModel;
use Inisiatif\Package\User\Tests\UserTestCase;
use Inisiatif\Package\User\Abstracts\UserEvent;
use Inisiatif\Package\User\Events\UserWasCreated;
use Inisiatif\Package\Contract\User\Model\UserInterface;

final class UserWasCreatedTest extends UserTestCase
{
    public function test_can_create_event_object(): void
    {
        $user = new UserModel();

        $event = new UserWasCreated($user);

        $this->assertInstanceOf(UserEvent::class, $event);
        $this->assertInstanceOf(UserInterface::class, $event->getModel());
        $this->assertSame('user.created', $event->name());
    }
}