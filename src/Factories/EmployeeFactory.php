<?php

namespace Inisiatif\Package\User\Factories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Model>
 */
final class EmployeeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nip' => $this->faker->uuid(),
            'branch_id' => BranchFactory::new()->create()->getKey(),
            'name' => $this->faker->name(),
            'email' => $this->faker->safeEmail(),
            'is_active' => true,
            'intranet_id' => $this->faker->randomDigit(),
        ];
    }
}
