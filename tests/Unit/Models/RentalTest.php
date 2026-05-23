<?php

namespace Tests\Unit\Models;

use App\Models\Rental;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RentalTest extends TestCase
{
    use RefreshDatabase;

    public function test_is_active_checks_date_range()
    {
        $today = Carbon::now()->startOfDay();

        $rental = Rental::factory()->create([
            'start_date' => $today->copy()->subDay(),
            'end_date' => $today->copy()->addDay(),
            'status' => 'active',
        ]);

        $this->assertTrue($rental->isActive());
    }

    public function test_is_active_returns_false_before_start_date()
    {
        $today = Carbon::now()->startOfDay();

        $rental = Rental::factory()->create([
            'start_date' => $today->copy()->addDay(),
            'end_date' => $today->copy()->addDays(5),
            'status' => 'active',
        ]);

        $this->assertFalse($rental->isActive());
    }

    public function test_is_active_returns_false_after_end_date()
    {
        $today = Carbon::now()->startOfDay();

        $rental = Rental::factory()->create([
            'start_date' => $today->copy()->subDays(5),
            'end_date' => $today->copy()->subDay(),
            'status' => 'active',
        ]);

        $this->assertFalse($rental->isActive());
    }

    public function test_is_expired_checks_end_date()
    {
        $today = Carbon::now()->startOfDay();

        $rental = Rental::factory()->create([
            'end_date' => $today->copy()->subDay(),
        ]);

        $this->assertTrue($rental->isExpired());
    }

    public function test_is_expired_returns_false_if_not_expired()
    {
        $today = Carbon::now()->startOfDay();

        $rental = Rental::factory()->create([
            'end_date' => $today->copy()->addDay(),
        ]);

        $this->assertFalse($rental->isExpired());
    }

    public function test_is_pending_checks_start_date()
    {
        $today = Carbon::now()->startOfDay();

        $rental = Rental::factory()->create([
            'start_date' => $today->copy()->addDay(),
        ]);

        $this->assertTrue($rental->isPending());
    }

    public function test_scope_active_uses_date_range()
    {
        $today = Carbon::now()->startOfDay();

        // Create active rental (within range)
        Rental::factory()->create([
            'start_date' => $today->copy()->subDay(),
            'end_date' => $today->copy()->addDay(),
        ]);

        // Create future rental
        Rental::factory()->create([
            'start_date' => $today->copy()->addDays(5),
            'end_date' => $today->copy()->addDays(10),
        ]);

        // Create expired rental
        Rental::factory()->create([
            'start_date' => $today->copy()->subDays(10),
            'end_date' => $today->copy()->subDay(),
        ]);

        $active = Rental::active()->get();

        $this->assertCount(1, $active);
        $this->assertTrue($active->first()->isActive());
    }

    public function test_scope_expired_uses_end_date()
    {
        $today = Carbon::now()->startOfDay();

        Rental::factory()->create([
            'end_date' => $today->copy()->subDay(),
        ]);

        Rental::factory()->create([
            'end_date' => $today->copy()->addDay(),
        ]);

        $expired = Rental::expired()->get();

        $this->assertCount(1, $expired);
    }

    public function test_scope_pending_uses_start_date()
    {
        $today = Carbon::now()->startOfDay();

        Rental::factory()->create([
            'start_date' => $today->copy()->addDay(),
        ]);

        Rental::factory()->create([
            'start_date' => $today->copy()->subDay(),
        ]);

        $pending = Rental::pending()->get();

        $this->assertCount(1, $pending);
    }

    public function test_date_cast_as_date_not_datetime()
    {
        $rental = Rental::factory()->create([
            'start_date' => '2026-01-15',
            'end_date' => '2026-01-20',
        ]);

        // Ensure dates are cast as 'date', not 'datetime'
        $this->assertInstanceOf(Carbon::class, $rental->start_date);
        $this->assertEquals('2026-01-15', $rental->start_date->format('Y-m-d'));
        // Time should be 00:00:00 for date cast
        $this->assertEquals('00:00:00', $rental->start_date->format('H:i:s'));
    }
}
