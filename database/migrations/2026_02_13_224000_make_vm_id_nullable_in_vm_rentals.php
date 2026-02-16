<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Make vm_id nullable
        // We use raw SQL to avoid needing doctrine/dbal dependency for a simple column change
        DB::statement("ALTER TABLE vm_rentals MODIFY vm_id BIGINT UNSIGNED NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to not null (assuming data is clean)
        DB::statement("ALTER TABLE vm_rentals MODIFY vm_id BIGINT UNSIGNED NOT NULL");
    }
};
