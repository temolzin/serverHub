<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gcp_machines', function (Blueprint $table) {
            $table->string('state')->default('poweredOn')->after('machine_internal_name');
        });
    }

    public function down(): void
    {
        Schema::table('gcp_machines', function (Blueprint $table) {
            $table->dropColumn('state');
        });
    }
};
