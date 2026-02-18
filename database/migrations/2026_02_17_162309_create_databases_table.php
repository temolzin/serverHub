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
            $table->foreignId('instance_id')
                  ->constrained()
                  ->onDelete('cascade');
            $table->string('name');
            $table->string('type');
            $table->string('status')->nullable();
            $table->text('comments')->nullable();
            $table->integer('port')->nullable();
            $table->date('last_update')->nullable();
            $table->timestamps();
            $table->softDeletes();
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
