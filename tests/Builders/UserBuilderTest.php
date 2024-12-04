<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Tests\Builders;

use Illuminate\Http\Request;
use Inisiatif\Package\User\Tests\UserModel;
use Inisiatif\Package\User\Tests\UserTestCase;
use Inisiatif\Package\User\Builders\UserBuilder;
use Inisiatif\Package\Contract\User\Model\LoginableAwareInterface;

final class UserBuilderTest extends UserTestCase
{
    public function test_can_create_object_user_from_array(): void
    {
        $user = UserBuilder::makeFromArray(new UserModel, [
            'name' => 'foo bar',
            'email' => 'bar@foo.com',
            'username' => 'foobar',
            'password' => 'secret',
        ]);

        $this->assertSame($user->getName(), 'foo bar');
        $this->assertSame($user->getEmail(), 'bar@foo.com');
        $this->assertSame($user->getUsername(), 'foobar');
        $this->assertTrue(password_verify(md5('secret'), $user->getPassword()));
    }

    public function test_can_create_object_user_from_request(): void
    {
        $request = new Request([
            'name' => 'foo bar',
            'email' => 'bar@foo.com',
            'username' => 'foobar',
            'password' => 'secret',
            'loginable_id' => 'f9758f12-975b-4578-81e1-8211b386910b',
            'loginable_type' => LoginableAwareInterface::TYPE_AGENT,
        ]);

        $user = UserBuilder::makeFromRequest(new UserModel, $request);

        $this->assertNotNull($user->getId());
        $this->assertSame($user->getName(), 'foo bar');
        $this->assertSame($user->getEmail(), 'bar@foo.com');
        $this->assertSame($user->getUsername(), 'foobar');
        $this->assertSame($user->getLoginable(), 'f9758f12-975b-4578-81e1-8211b386910b');
        $this->assertSame($user->getLoginableType(), LoginableAwareInterface::TYPE_AGENT);
        $this->assertTrue(password_verify(md5('secret'), $user->getPassword()));
    }
}
