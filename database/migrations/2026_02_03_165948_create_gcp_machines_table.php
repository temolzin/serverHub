<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('gcp_machines', function (Blueprint $table) {
      $table->id();
      $table->string('project_name');
      $table->string('environment');
      $table->string('machine_name');
      $table->string('machine_internal_name');
      $table->string('operations_system');
      $table->date('latest_security_patch')->nullable();
      $table->string('internal_ip');
      $table->string('alias_ip')->nullable();
      $table->string('alias2_ip')->nullable();
      $table->string('alias3_ip')->nullable();
      $table->text('other_ips')->nullable();
      $table->string('kernel_version')->nullable();
      $table->integer('ram_memory');
      $table->integer('swap_memory');
      $table->foreignId('owner_id')
        ->constrained('owners')
        ->cascadeOnDelete();
      $table->timestamps();
      $table->softDeletes();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('gcp_machines');
  }
};
