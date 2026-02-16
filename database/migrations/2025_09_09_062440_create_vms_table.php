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
        Schema::create('vms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('category_id');
            $table->integer('storage')->comment('Storage in GB');
            $table->integer('backup_disk')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['available', 'rented', 'maintenance', 'offline'])->default('available');

            // VM Resources
            $table->integer('cpu')->default(1)->comment('Number of CPU cores');
            $table->integer('ram')->default(1)->comment('RAM in GB');

            // VM Network & Access
            $table->string('ip_address')->nullable()->comment('VM IP Address');
            $table->string('access_username')->nullable();
            $table->text('access_password')->nullable()->comment('Encrypted password');

            // Foreign Keys
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreignId('specification_id')->nullable()->constrained('v_m_specifications')->onDelete('set null');
            $table->foreignId('server_id')->nullable()->constrained('servers')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vms');
    }
};
