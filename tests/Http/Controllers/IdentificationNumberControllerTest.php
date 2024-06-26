<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Tests\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Inisiatif\Package\User\Tests\TestCase;
use Inisiatif\Package\User\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;

final class IdentificationNumberControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_update_pin(): void
    {
        $user = UserFactory::new()->createOne([
            'pin' => Hash::make('123456'),
        ]);

        $this->putJson('/personal-identification-number', [
            'email' => $user->getAttribute('email'),
            'password' => 'password',
        ])->assertStatus(204);
    }
}
