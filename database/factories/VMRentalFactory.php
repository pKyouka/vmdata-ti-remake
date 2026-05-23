<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\VM;
use Carbon\Carbon;

class VMRentalFactory extends Factory
{
    public function definition(): array
    {
        $startTime = Carbon::now()->addHours(rand(1, 72));
        $endTime = $startTime->copy()->addHours(rand(1, 24));

        return [
            'user_id' => User::where('role', 'user')->inRandomOrder()->first()->id ?? User::factory(),
            'vm_id' => VM::inRandomOrder()->first()->id ?? VM::factory(),
            'start_time' => $startTime,
            'end_time' => $endTime,
            'status' => $this->faker->randomElement(['pending', 'active', 'completed', 'cancelled', 'expired']),
            'price_per_hour' => $this->faker->numberBetween(10000, 100000),
            'total_cost' => $this->faker->numberBetween(50000, 2000000),
        ];
    }
}
