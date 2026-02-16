<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::table('servers', function (Blueprint $table) {
      $table->string('uuid')->nullable()->unique()->after('id');
    });

    Schema::table('gcp_machines', function (Blueprint $table) {
      $table->string('uuid')->nullable()->unique()->after('id');
    });
  }

  public function down(): void
  {
    Schema::table('servers', function (Blueprint $table) {
      $table->dropUnique('servers_uuid_unique');
      $table->dropColumn('uuid');
    });

    Schema::table('gcp_machines', function (Blueprint $table) {
      $table->dropUnique('gcp_machines_uuid_unique');
      $table->dropColumn('uuid');
    });
  }
};
