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
        Schema::table('vm_rentals', function (Blueprint $table) {
            $table->boolean('reset_requested')->default(false)->after('status');
            $table->timestamp('reset_requested_at')->nullable()->after('reset_requested');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vm_rentals', function (Blueprint $table) {
            $table->dropColumn(['reset_requested', 'reset_requested_at']);
        });
    }
};
