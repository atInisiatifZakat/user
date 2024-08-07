<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Tests\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Inisiatif\Package\User\Models\User;
use Inisiatif\Package\User\Tests\TestCase;
use Inisiatif\Package\User\Factories\UserFactory;

final class IdentificationNumberControllerTest extends TestCase
{
    public function test_can_update_pin(): void
    {
        /** @var User $user */
        $user = UserFactory::new()->createOne([
            'pin' => Hash::make('123456'),
        ]);

        $token = $user->createToken('testing');

        $this->withToken($token->plainTextToken)->putJson('/personal-identification-number', [
            'pin' => '654321',
            'pin_confirmation' => '654321',
            'password' => 'password',
        ])->assertStatus(204);
    }

    public function test_password_incorect_to_update_pin(): void
    {
        $user = UserFactory::new()->createOne([
            'pin' => Hash::make('123456'),
        ]);

        $token = $user->createToken('testing');

        $response = $this->withToken($token->plainTextToken)->putJson('/personal-identification-number', [
            'pin' => '654321',
            'pin_confirmation' => '654321',
            'password' => 'passrod',
        ])->json();

        $this->assertEquals('password_error', $response['type']);
    }

    public function test_max_attempt_incorect_passwotd_to_update_pin(): void
    {
        /** @var User $user */
        $user = UserFactory::new()->createOne([
            'pin' => Hash::make('123456'),
        ]);

        $token = $user->createToken('testing');

        $this->withToken($token->plainTextToken)->putJson('/personal-identification-number', [
            'pin' => '654321',
            'pin_confirmation' => '654321',
            'password' => 'passrod',
        ])->assertStatus(422);

        $this->withToken($token->plainTextToken)->putJson('/personal-identification-number', [
            'pin' => '654321',
            'pin_confirmation' => '654321',
            'password' => 'passrod',
        ])->assertStatus(422);

        $this->withToken($token->plainTextToken)->putJson('/personal-identification-number', [
            'pin' => '654321',
            'pin_confirmation' => '654321',
            'password' => 'passrod',
        ])->assertStatus(422);

        $response = $this->withToken($token->plainTextToken)->putJson('/personal-identification-number', [
            'pin' => '654321',
            'pin_confirmation' => '654321',
            'password' => 'passrod',
        ])->json();

        $this->assertEquals('rate_limit', $response['type']);
    }

    public function test_pin_and_pin_confirmation_not_equal(): void
    {
        /** @var User $user */
        $user = UserFactory::new()->createOne([
            'pin' => Hash::make('123456'),
        ]);

        $token = $user->createToken('testing');

        $response = $this->withToken($token->plainTextToken)->putJson('/personal-identification-number', [
            'pin' => '654321',
            'pin_confirmation' => '543216',
            'password' => 'password',
        ])->json();

        $this->assertEquals('pin_error', $response['type']);
    }

    public function test_pin_less_than_six(): void
    {
        /** @var User $user */
        $user = UserFactory::new()->createOne([
            'pin' => Hash::make('123456'),
        ]);

        $token = $user->createToken('testing');

        $response = $this->withToken($token->plainTextToken)->putJson('/personal-identification-number', [
            'pin' => '1234',
            'pin_confirmation' => '1234',
            'password' => 'password',
        ])->json();

        $this->assertEquals('pin_error', $response['type']);
    }
}
