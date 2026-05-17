<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('databases', function (Blueprint $table) {
            $table->foreignId('instance_id')
                ->after('id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('owner_id')
                ->after('instance_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->dropConstrainedForeignId('server_id');
        });
    }

    public function down(): void
    {
        Schema::table('databases', function (Blueprint $table) {
            $table->foreignId('server_id')
                ->after('id')
                ->nullable()
                ->constrained()
                ->cascadeOnDelete();
            $table->dropConstrainedForeignId('instance_id');
            $table->dropConstrainedForeignId('owner_id');
        });
    }
};
