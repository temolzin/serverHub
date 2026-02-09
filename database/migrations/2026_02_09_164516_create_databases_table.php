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
          Schema::create('databases', function (Blueprint $table) {
            $table->id();

            $table->foreignId('server_id')
                ->constrained('servers')
                ->cascadeOnDelete();

            $table->string('name');
            $table->string('type');
            $table->string('status');
            $table->text('comments')->nullable();
            $table->integer('port');
            $table->string('version');
            $table->date('last_update')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('databases');
    }
};
