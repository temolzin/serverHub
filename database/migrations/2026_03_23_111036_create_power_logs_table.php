<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('power_logs', function (Blueprint $table) {
            $table->id();
            $table->morphs('powerable');
            $table->enum('action', ['on', 'off']);
            $table->text('motive');
            $table->foreignId('created_by')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('power_logs');
    }
};
