<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('databases', function (Blueprint $table) {
            $table->dropForeign(['server_id']);
            $table->dropColumn('server_id');
            $table->dropColumn('version');
            $table->foreignId('instance_id')
                  ->after('id')
                  ->constrained()
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('databases', function (Blueprint $table) {
            $table->dropForeign(['instance_id']);
            $table->dropColumn('instance_id');
            $table->foreignId('server_id')
                  ->constrained()
                  ->onDelete('cascade');

            $table->string('version')->nullable();
        });
    }
};
