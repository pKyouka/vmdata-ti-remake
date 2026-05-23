<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ExpireRentals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rentals:expire {--dry-run : Do not persist changes, just show counts}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mark VMRental and Rental records as expired when end time/date has passed';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $dry = $this->option('dry-run');
        $now = Carbon::now();
        $today = $now->startOfDay();

        // Expire rentals where end_date < today() and status not already expired/cancelled
        // Since all rental types are consolidated into `rentals` table
        $where = "`end_date` < ? AND `status` NOT IN ('expired', 'cancelled')";
        $count = DB::table('rentals')->whereRaw($where, [$today->toDateString()])->count();

        $this->info("Rentals to expire: {$count}");

        if (!$dry && $count > 0) {
            // Use transaction to prevent race condition
            DB::transaction(function () use ($now, $today) {
                DB::table('rentals')
                    ->whereRaw("`end_date` < ? AND `status` NOT IN ('expired', 'cancelled')", [$today->toDateString()])
                    ->update(['status' => 'expired', 'updated_at' => $now->toDateTimeString()]);
            });

            $this->info("Expired {$count} rentals.");
        }

        $this->info('Done.');
        return 0;
    }
}

