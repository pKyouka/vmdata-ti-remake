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

        // Expire vm_rentals where end_time < now() and status not already expired/completed/cancelled
        $vmWhere = "`end_time` < ? AND `status` NOT IN ('expired','completed','cancelled')";
        $vmCount = DB::table('vm_rentals')->whereRaw($vmWhere, [$now->toDateTimeString()])->count();

        $this->info("VM rentals to expire: {$vmCount}");

        if (! $dry && $vmCount > 0) {
            DB::table('vm_rentals')
                ->whereRaw($vmWhere, [$now->toDateTimeString()])
                ->update(['status' => 'expired', 'updated_at' => $now->toDateTimeString()]);

            $this->info("Expired {$vmCount} vm_rentals.");
        }

        // Expire rentals table where end_date < today() and status not already expired
        $rWhere = "`end_date` < ? AND `status` NOT IN ('expired')";
        $rCount = DB::table('rentals')->whereRaw($rWhere, [$now->toDateString()])->count();
        $this->info("Rentals to expire: {$rCount}");

        if (! $dry && $rCount > 0) {
            DB::table('rentals')
                ->whereRaw($rWhere, [$now->toDateString()])
                ->update(['status' => 'expired', 'updated_at' => $now->toDateTimeString()]);

            $this->info("Expired {$rCount} rentals.");
        }

        $this->info('Done.');
        return 0;
    }
}
