<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('applications', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('owner_id');
      $table->unsignedBigInteger('server_id');
      $table->string('name');
      $table->string('version')->nullable();
      $table->string('status');
      $table->string('type')->nullable();
      $table->text('comments')->nullable();
      $table->text('processes')->nullable();
      $table->integer('assigned_memory')->nullable();
      $table->string('installation_route')->nullable();
      $table->date('latest_security_patch')->nullable();
      $table->string('user_service')->nullable();
      $table->text('cron_jobs')->nullable();
      $table->timestamps();
      $table->softDeletes();

      $table->foreign('owner_id')->references('id')->on('owners')->onDelete('cascade');
      $table->foreign('server_id')->references('id')->on('servers')->onDelete('cascade');
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('applications');
  }
};
