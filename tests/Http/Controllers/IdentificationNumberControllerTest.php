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
}
