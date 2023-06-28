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
        return [
            'name' => $this->faker->name(),
            'username' => $this->faker->safeEmail(),
            'email' => $this->faker->safeEmail(),
            'password' => \bcrypt('password'),
            'deactivated_at' => null,
            'loginable_id' => $this->faker->uuid(),
            'loginable_type' => $this->faker->randomElement(['EMPLOYEE', 'VOLUNTEER']),
        ];
    }
}
