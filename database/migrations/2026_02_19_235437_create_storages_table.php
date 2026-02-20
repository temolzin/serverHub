<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
      Schema::create('storages', function (Blueprint $table) {
        $table->id();
        $table->string('hostname');
        $table->string('data_ip')->nullable();
        $table->string('platform')->nullable();
        $table->string('os_name')->nullable();
        $table->string('os_internal')->nullable();
        $table->string('operations_system')->nullable();
        $table->string('internal_ip')->nullable();
        $table->string('environment')->nullable();
        $table->string('datacenter')->nullable();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('storages');
    }
};
