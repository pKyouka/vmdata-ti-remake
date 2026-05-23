<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class VMSpecificationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->word() . ' Spec',
            'description' => $this->faker->sentence(),
            'ram' => $this->faker->randomElement([4, 8, 16, 32]),
            'storage' => $this->faker->randomElement([128, 256, 512, 1024]),
        ];
    }
}
