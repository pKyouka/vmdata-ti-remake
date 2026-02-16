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
        Schema::create('vm_rentals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('vm_id')->nullable()->constrained('vms')->onDelete('cascade');
            $table->timestamp('start_time');
            $table->timestamp('end_time');
            $table->integer('cpu')->comment('CPU cores allocated');
            $table->integer('ram')->comment('RAM in GB');
            $table->integer('storage')->comment('Storage in GB');
            $table->decimal('total_cost', 10, 2)->default(0);
            $table->enum('status', ['pending', 'active', 'completed', 'cancelled'])->default('pending');
            $table->boolean('reset_requested')->default(false);
            $table->timestamp('reset_requested_at')->nullable();
            $table->text('purpose')->nullable();
            $table->string('operating_system')->nullable();
            $table->json('access_credentials')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vm_rentals');
    }
};
