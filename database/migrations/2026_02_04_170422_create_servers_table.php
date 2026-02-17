<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('servers', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('owner_id');
      $table->unsignedBigInteger('type_application_id');
      $table->string('vm_according_to_the_vmware');
      $table->boolean('state')->default(true);
      $table->string('dns_name')->nullable();
      $table->string('primary_ip_address');
      $table->string('environment');
      $table->string('datacenter');
      $table->string('os_according_to_the_vmware');
      $table->string('os_version_internal');
      $table->string('hostname_internal');
      $table->string('ip_user')->nullable();
      $table->string('ip_monitoring')->nullable();
      $table->text('other_ips')->nullable();
      $table->integer('ram_memory');
      $table->integer('swap_memory');
      $table->date('latest_security_patch')->nullable();
      $table->text('comments')->nullable();
      $table->timestamps();
      $table->softDeletes();

      $table->foreign('owner_id')->references('id')->on('owners')->onDelete('cascade');
      $table->foreign('type_application_id')->references('id')->on('type_applications')->onDelete('cascade');
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('servers');
  }
};
