<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;
use App\Models\VMSpecification;

class VmFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word . ' VM',
            'category_id' => Category::inRandomOrder()->first()->id ?? Category::factory(),
            'ram' => $this->faker->randomElement([4, 8, 12]),
            'cpu' => $this->faker->randomElement([1, 2, 4]),
            'storage' => $this->faker->randomElement([128, 256, 512]),
            'backup_disk' => $this->faker->randomElement([10, 20, 50]),
            'description' => $this->faker->sentence(),
            'status' => $this->faker->randomElement(['available', 'rented', 'maintenance', 'offline']),
            'specification_id' => VMSpecification::inRandomOrder()->first()->id ?? VMSpecification::factory(),
            'ip_address' => $this->faker->ipv4(),
            'access_username' => $this->faker->userName(),
            'access_password' => $this->faker->password(),
        ];
    }
}

