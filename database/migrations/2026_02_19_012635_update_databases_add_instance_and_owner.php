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
      Schema::table('databases', function (Blueprint $table) {
        $table->foreignId('instance_id')
              ->after('id')
              ->constrained()
              ->onDelete('cascade');
        $table->foreignId('owner_id')
              ->after('instance_id')
              ->constrained()
              ->onDelete('cascade');
        $table->dropForeign(['server_id']);
        $table->dropColumn('server_id');
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::table('databases', function (Blueprint $table) {
        $table->foreignId('server_id')
              ->constrained()
              ->onDelete('cascade');
        $table->dropForeign(['instance_id']);
        $table->dropForeign(['owner_id']);
        $table->dropColumn(['instance_id', 'owner_id']);
      });
    }
};
