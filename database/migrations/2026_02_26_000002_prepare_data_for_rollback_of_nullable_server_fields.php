<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('servers', function (Blueprint $table) {
            $table->string('primary_ip_address', 255)->nullable()->change();
        });
    }

    public function down(): void
    {
        DB::table('servers')
            ->whereNull('primary_ip_address')
            ->update([
                'primary_ip_address' => ''
            ]);
        DB::table('servers')
            ->whereRaw("LOWER(TRIM(state)) IN ('poweredoff','off','false','0')")
            ->update(['state' => 0]);

        DB::table('servers')
            ->whereRaw("LOWER(TRIM(state)) IN ('poweredon','on','true','1')")
            ->update(['state' => 1]);

        DB::table('servers')
            ->whereNull('state')
            ->update(['state' => 1]);

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
        Schema::table('servers', function (Blueprint $table) {
            $table->string('primary_ip_address', 255)->nullable(false)->change();
        });
    }
};
