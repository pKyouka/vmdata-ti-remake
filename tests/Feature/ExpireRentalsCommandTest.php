<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Carbon\Carbon;

class ExpireRentalsCommandTest extends TestCase
{
    public function test_command_marks_past_rentals_expired()
    {
        // Use transaction so test is non-destructive against shared DB
        DB::beginTransaction();
        try {
            $now = Carbon::now();

            // Insert a vm_rental that ended in the past
            DB::table('vm_rentals')->insert([
                'user_id' => 1,
                'vm_id' => 1,
                'start_time' => $now->subHours(5)->toDateTimeString(),
                'end_time' => $now->subHours(1)->toDateTimeString(),
                'total_cost' => 0,
                'status' => 'active',
                'created_at' => $now->toDateTimeString(),
                'updated_at' => $now->toDateTimeString(),
            ]);

            // Insert a rental that ended yesterday
            DB::table('rentals')->insert([
                'user_id' => 1,
                'vm_id' => 1,
                'start_date' => $now->subDays(3)->toDateString(),
                'end_date' => $now->subDay()->toDateString(),
                'status' => 'Aktif',
                'created_at' => $now->toDateTimeString(),
                'updated_at' => $now->toDateTimeString(),
            ]);

            // Run the command (not dry-run)
            Artisan::call('rentals:expire');

            $vm = DB::table('vm_rentals')->orderBy('id', 'desc')->first();
            $rental = DB::table('rentals')->orderBy('id', 'desc')->first();

            $this->assertEquals('expired', $vm->status);
            $this->assertEquals('expired', $rental->status);

        } finally {
            DB::rollBack();
        }
    }
}
