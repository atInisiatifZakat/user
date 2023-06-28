<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Factories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Model>
 */
final class BranchFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->city(),
            'type' => $this->faker->randomElement(['KC', 'KCP']),
            'is_active' => true,
            'is_head_office' => true,
        ];
    }
}
