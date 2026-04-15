<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('servers', function (Blueprint $table) {
            $table->json('manual_override_fields')->nullable()->after('comments');
        });

        Schema::table('gcp_machines', function (Blueprint $table) {
            $table->json('manual_override_fields')->nullable()->after('created_by');
        });
    }

    public function down(): void
    {
        Schema::table('servers', function (Blueprint $table) {
            $table->dropColumn('manual_override_fields');
        });

        Schema::table('gcp_machines', function (Blueprint $table) {
            $table->dropColumn('manual_override_fields');
        });
    }
};
