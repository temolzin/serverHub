<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::table('servers', function (Blueprint $table) {
      $table->text('creation_date')->nullable()->after('latest_security_patch');
    });

    Schema::table('gcp_machines', function (Blueprint $table) {
      $table->text('creation_date')->nullable()->after('latest_security_patch');
    });
  }

  public function down(): void
  {
    Schema::table('servers', function (Blueprint $table) {
      $table->dropColumn('creation_date');
    });

    Schema::table('gcp_machines', function (Blueprint $table) {
      $table->dropColumn('creation_date');
    });
  }
};
