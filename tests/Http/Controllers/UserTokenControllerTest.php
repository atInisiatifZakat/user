<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Tests\Http\Controllers;

use Inisiatif\Package\User\Models\User;
use Inisiatif\Package\User\Tests\TestCase;
use Inisiatif\Package\User\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;

final class UserTokenControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_show_list_user_token(): void
    {
        $this->withoutExceptionHandling();

        /** @var User $user */
        $user = UserFactory::new()->create();

        $user->createToken('testing');
        $token = $user->createToken('testing2');

        $response = $this->withToken(
            $token->plainTextToken
        )->getJson('/user-token')->assertSuccessful();

        $this->assertCount(2, $response->json('data'));
    }

    public function test_can_delete_user_token(): void
    {
        $this->withoutExceptionHandling();

        /** @var User $user */
        $user = UserFactory::new()->create();

        $token1 = $user->createToken('testing');
        $token = $user->createToken('testing2');

        $this->withToken(
            $token->plainTextToken
        )->deleteJson('/user-token/1')->assertSuccessful();

        $this->assertDatabaseMissing('personal_access_tokens', [
            'token' => $token1->accessToken,
        ]);
    }
}
