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
        Schema::create('v_m_specifications', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('ram')->comment('RAM in GB');
            $table->integer('storage')->comment('Storage in GB');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('v_m_specifications');
    }
};
