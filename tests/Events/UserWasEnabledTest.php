<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Tests\Events;

use Inisiatif\Package\User\Tests\UserModel;
use Inisiatif\Package\User\Tests\UserTestCase;
use Inisiatif\Package\User\Abstracts\UserEvent;
use Inisiatif\Package\User\Events\UserWasEnabled;
use Inisiatif\Package\Contract\User\Model\UserInterface;

final class UserWasEnabledTest extends UserTestCase
{
    public function test_can_create_event_object(): void
    {
        $user = new UserModel();

        $event = new UserWasEnabled($user);

        $this->assertInstanceOf(UserEvent::class, $event);
        $this->assertInstanceOf(UserInterface::class, $event->getModel());
        $this->assertSame('user.enabled', $event->name());
    }
}