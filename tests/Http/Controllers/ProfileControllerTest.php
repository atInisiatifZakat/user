<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Tests\Http\Controllers;

use Inisiatif\Package\User\Models\User;
use Inisiatif\Package\User\Tests\TestCase;
use Inisiatif\Package\User\Factories\UserFactory;

final class ProfileControllerTest extends TestCase
{
    public function test_can_show_user_profile(): void
    {
        $this->withoutExceptionHandling();

        /** @var User $user */
        $user = UserFactory::new()->employee()->create();

        $token = $user->createToken('testing');

        $response = $this->withToken(
            $token->plainTextToken
        )->getJson('/user-information')->assertSuccessful();

        $response->assertJsonStructure([
            'data' => [
                'id', 'name', 'email',
                'loginable' => [
                    'id', 'type', 'name',
                    'branch' => [
                        'id', 'name', 'is_head_office',
                    ],
                ],
            ],
        ]);

        $this->assertSame($user->getKey(), $response->json('data.id'));
        $this->assertSame($user->getAttribute('name'), $response->json('data.name'));
        $this->assertSame($user->getAttribute('email'), $response->json('data.email'));
        $this->assertSame($user->getAttribute('loginable_id'), $response->json('data.loginable.id'));
        $this->assertSame($user->getAttribute('loginable_type'), $response->json('data.loginable.type'));
        $this->assertSame($user->getAttribute('loginable')?->getAttribute('name'), $response->json('data.loginable.name'));

        $this->assertSame(
            $user->getAttribute('loginable')?->getAttribute('branch')?->getAttribute('id'),
            $response->json('data.loginable.branch.id')
        );
        $this->assertSame(
            $user->getAttribute('loginable')?->getAttribute('branch')?->getAttribute('name'),
            $response->json('data.loginable.branch.name')
        );
        $this->assertSame(
            $user->getAttribute('loginable')?->getAttribute('branch')?->getAttribute('is_head_office'),
            $response->json('data.loginable.branch.is_head_office')
        );
    }
}
