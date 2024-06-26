<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Factories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Model>
 */
final class UserFactory extends Factory
{
    public function definition(): array
    {
        $hashed = \config('user.hashing_password_before_attempt', true);

        return [
            'name' => $this->faker->name(),
            'username' => $this->faker->safeEmail(),
            'email' => $this->faker->safeEmail(),
            'password' => \bcrypt($hashed ? \md5('password') : 'password'),
            'deactivated_at' => null,
            'loginable_id' => $this->faker->uuid(),
            'loginable_type' => $this->faker->randomElement(['EMPLOYEE', 'VOLUNTEER']),
        ];
    }

    public function employee(): self
    {
        return $this->state(function () {
            return [
                'loginable_id' => EmployeeFactory::new()->create()->getKey(),
                'loginable_type' => 'EMPLOYEE',
            ];
        });
    }
}
