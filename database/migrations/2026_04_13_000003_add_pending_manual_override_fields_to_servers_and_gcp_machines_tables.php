<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('servers', function (Blueprint $table) {
            $table->json('pending_manual_override_fields')
                ->nullable()
                ->after('manual_override_fields');
        });

        Schema::table('gcp_machines', function (Blueprint $table) {
            $table->json('pending_manual_override_fields')
                ->nullable()
                ->after('manual_override_fields');
        });
    }

    public function down(): void
    {
        Schema::table('servers', function (Blueprint $table) {
            $table->dropColumn('pending_manual_override_fields');
        });

        Schema::table('gcp_machines', function (Blueprint $table) {
            $table->dropColumn('pending_manual_override_fields');
        });
    }
};
