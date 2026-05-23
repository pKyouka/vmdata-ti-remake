<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add columns from vm_rentals to rentals (if not exist)
        if (Schema::hasTable('rentals')) {
            Schema::table('rentals', function (Blueprint $table) {
                // Rental type to differentiate between regular rental and vm rental
                if (!Schema::hasColumn('rentals', 'rental_type')) {
                    $table->string('rental_type')->default('regular')->after('id');
                }
                
                // VM specs (from vm_rentals)
                if (!Schema::hasColumn('rentals', 'cpu')) {
                    $table->integer('cpu')->nullable()->comment('CPU cores allocated');
                }
                if (!Schema::hasColumn('rentals', 'ram')) {
                    $table->integer('ram')->nullable()->comment('RAM in GB');
                }
                if (!Schema::hasColumn('rentals', 'storage')) {
                    $table->integer('storage')->nullable()->comment('Storage in GB');
                }
                
                // Timestamps (from vm_rentals)
                if (!Schema::hasColumn('rentals', 'start_time')) {
                    $table->timestamp('start_time')->nullable()->after('start_date');
                }
                if (!Schema::hasColumn('rentals', 'end_time')) {
                    $table->timestamp('end_time')->nullable()->after('end_date');
                }
                
                // VM purpose & OS (from vm_rentals)
                if (!Schema::hasColumn('rentals', 'purpose')) {
                    $table->text('purpose')->nullable();
                }
                if (!Schema::hasColumn('rentals', 'operating_system')) {
                    $table->string('operating_system')->nullable();
                }
                
                // Access credentials (from vm_rentals)
                if (!Schema::hasColumn('rentals', 'access_credentials')) {
                    $table->json('access_credentials')->nullable();
                }
                
                // Reset request fields (from vm_rentals)
                if (!Schema::hasColumn('rentals', 'reset_requested')) {
                    $table->boolean('reset_requested')->default(false);
                }
                if (!Schema::hasColumn('rentals', 'reset_requested_at')) {
                    $table->timestamp('reset_requested_at')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('rentals')) {
            Schema::table('rentals', function (Blueprint $table) {
                $columns = [
                    'rental_type',
                    'cpu',
                    'ram',
                    'storage',
                    'start_time',
                    'end_time',
                    'purpose',
                    'operating_system',
                    'access_credentials',
                    'reset_requested',
                    'reset_requested_at',
                ];
                
                foreach ($columns as $col) {
                    if (Schema::hasColumn('rentals', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
    }
};
