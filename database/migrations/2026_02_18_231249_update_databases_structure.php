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
        if (Schema::hasColumn('databases', 'server_id')) {
          $table->dropForeign(['server_id']);
          $table->dropColumn('server_id');
        }
          if (!Schema::hasColumn('databases', 'instance_id')) {
            $table->foreignId('instance_id')
                ->constrained()
                ->onDelete('cascade');
          }
            if (!Schema::hasColumn('databases', 'owner_id')) {
              $table->foreignId('owner_id')
                ->constrained()
                ->onDelete('cascade');
            }
              if (!Schema::hasColumn('databases', 'version')) {
              $table->string('version')->nullable();
            }
      });
  }
};
