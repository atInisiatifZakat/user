<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Tests\Http\Controllers;

use Laravel\Sanctum\NewAccessToken;
use Inisiatif\Package\User\Tests\TestCase;
use Inisiatif\Package\User\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;

final class AuthTokenControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_new_token(): void
    {
        config()->set('user.hashing_password_before_attempt', false);

        $user = UserFactory::new()->createOne();

        $response = $this->postJson('/auth/token', [
            'email' => $user->getAttribute('email'),
            'password' => 'password',
        ])->assertStatus(200);

        $accessToken = $response->json('data.access_token');

        $this->assertNotNull($accessToken);

        $this->assertTrue(\str_contains($accessToken, '1|'));
    }

    public function test_cannot_create_new_token(): void
    {
        config()->set('user.hashing_password_before_attempt', false);

        $user = UserFactory::new()->createOne();

        $response = $this->postJson('/auth/token', [
            'email' => $user->getAttribute('email'),
            'password' => 'password12',
        ])->assertStatus(422);

        $response->assertJsonValidationErrorFor('email');
    }

    public function test_can_destroy_token_when_logout(): void
    {
        config()->set('user.hashing_password_before_attempt', false);

        /** @var mixed $user */
        $user = UserFactory::new()->createOne();

        /** @var NewAccessToken $newToken */
        $newToken = $user->createToken('testing');

        $this->withToken(
            $newToken->plainTextToken
        )->deleteJson('/auth/token')->assertStatus(204);

        $this->assertDatabaseMissing('personal_access_tokens', [
            'token' => $newToken->accessToken,
        ]);
    }

    public function test_can_create_new_token_when_hashing_config_true(): void
    {
        config()->set('user.hashing_password_before_attempt', true);

        $user = UserFactory::new([
            'password' => \bcrypt(
                \md5('password')
            ),
        ])->createOne();

        $response = $this->postJson('/auth/token', [
            'email' => $user->getAttribute('email'),
            'password' => 'password',
        ])->assertStatus(200);

        $accessToken = $response->json('data.access_token');

        $this->assertNotNull($accessToken);

        $this->assertTrue(\str_contains($accessToken, '1|'));
    }

    public function test_cannot_create_new_token_when_hashing_config_true(): void
    {
        config()->set('user.hashing_password_before_attempt', true);

        $user = UserFactory::new([
            'password' => \bcrypt('password'),
        ])->createOne();

        $response = $this->postJson('/auth/token', [
            'email' => $user->getAttribute('email'),
            'password' => 'password',
        ])->assertStatus(422);
    }
}
