<?php

namespace Tests\Console;

use App\Console\Commands\ExpireRentals;
use App\Models\Rental;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExpireRentalsTest extends TestCase
{
    use RefreshDatabase;

    public function test_expire_rentals_marks_expired_records()
    {
        $today = Carbon::now()->startOfDay();

        // Create active rental (should not be expired)
        Rental::factory()->create([
            'start_date' => $today->copy()->subDay(),
            'end_date' => $today->copy()->addDay(),
            'status' => 'active',
        ]);

        // Create expired rental (should be marked as expired)
        Rental::factory()->create([
            'end_date' => $today->copy()->subDay(),
            'status' => 'active',
        ]);

        $this->artisan('rentals:expire')
            ->assertExitCode(0);

        $this->assertEquals(1, Rental::where('status', 'expired')->count());
        $this->assertEquals(1, Rental::where('status', 'active')->count());
    }

    public function test_expire_rentals_dry_run_does_not_modify()
    {
        $today = Carbon::now()->startOfDay();

        Rental::factory()->create([
            'end_date' => $today->copy()->subDay(),
            'status' => 'active',
        ]);

        $this->artisan('rentals:expire --dry-run')
            ->assertExitCode(0);

        // Record should still be 'active' (not expired)
        $this->assertEquals(0, Rental::where('status', 'expired')->count());
        $this->assertEquals(1, Rental::where('status', 'active')->count());
    }

    public function test_expire_rentals_uses_transaction()
    {
        $today = Carbon::now()->startOfDay();

        // Create multiple expired rentals to test transaction
        Rental::factory(5)->create([
            'end_date' => $today->copy()->subDay(),
            'status' => 'active',
        ]);

        $this->artisan('rentals:expire')
            ->assertExitCode(0);

        $this->assertEquals(5, Rental::where('status', 'expired')->count());
    }

    public function test_does_not_re_expire_already_expired()
    {
        $today = Carbon::now()->startOfDay();

        Rental::factory()->create([
            'end_date' => $today->copy()->subDay(),
            'status' => 'expired',
        ]);

        $this->artisan('rentals:expire')
            ->assertExitCode(0);

        // Should still be only 1 expired (not double-updated)
        $this->assertEquals(1, Rental::where('status', 'expired')->count());
    }
}
