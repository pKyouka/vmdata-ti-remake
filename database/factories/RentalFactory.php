<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\VM;
use Carbon\Carbon;

class RentalFactory extends Factory
{
    public function definition(): array
    {
        $startDate = Carbon::now()->addDays(rand(1, 30));
        $endDate = $startDate->copy()->addDays(rand(5, 30));

        return [
            'user_id' => User::where('role', 'user')->inRandomOrder()->first()->id ?? User::factory(),
            'vm_id' => VM::inRandomOrder()->first()->id ?? VM::factory(),
            'admin_id' => User::where('role', 'admin')->inRandomOrder()->first()->id ?? User::factory(['role' => 'admin']),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => $this->faker->randomElement(['pending', 'active', 'expired', 'cancelled']),
            'vm_username' => $this->faker->userName(),
            'vm_password' => $this->faker->password(),
            'vm_ip_address' => $this->faker->ipv4(),
            'total_cost' => $this->faker->numberBetween(100000, 5000000),
            'rental_type' => $this->faker->randomElement(['hourly', 'daily', 'monthly']),
            'purpose' => $this->faker->sentence(),
        ];
    }
}
