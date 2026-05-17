<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('servers', function (Blueprint $table) {
            $table->unsignedBigInteger('owner_id')->nullable()->change();
            $table->unsignedBigInteger('type_application_id')->nullable()->change();
            $table->string('datacenter')->nullable()->change();
            $table->string('hostname_internal')->nullable()->change();
            $table->string('os_version_internal')->nullable()->change();
            $table->string('os_according_to_the_vmware')->nullable()->change();
            $table->string('state')->nullable()->change();
        });

        Schema::table('gcp_machines', function (Blueprint $table) {
            $table->unsignedBigInteger('owner_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('servers', function (Blueprint $table) {
            $table->unsignedBigInteger('owner_id')->nullable(false)->change();
            $table->unsignedBigInteger('type_application_id')->nullable(false)->change();
            $table->string('datacenter')->nullable(false)->change();
            $table->string('hostname_internal')->nullable(false)->change();
            $table->string('os_version_internal')->nullable(false)->change();
            $table->string('os_according_to_the_vmware')->nullable(false)->change();
            $table->boolean('state')->default(true)->change();
        });

        Schema::table('gcp_machines', function (Blueprint $table) {
            $table->unsignedBigInteger('owner_id')->nullable(false)->change();
        });
    }
};
