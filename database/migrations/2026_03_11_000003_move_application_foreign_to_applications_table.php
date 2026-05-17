<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('applications', 'gcp_machine_id')) {
            Schema::table('applications', function (Blueprint $table) {
                $table->unsignedBigInteger('gcp_machine_id')->nullable()->after('server_id');
                $table->foreign('gcp_machine_id')
                    ->references('id')
                    ->on('gcp_machines')
                    ->nullOnDelete();
            });
        }

        if (!Schema::hasColumn('gcp_machines', 'application_id')) {
            return;
        }

        $relations = DB::table('gcp_machines')
            ->whereNotNull('application_id')
            ->orderBy('id')
            ->get(['id', 'application_id']);

        foreach ($relations as $relation) {
            DB::table('applications')
                ->where('id', $relation->application_id)
                ->whereNull('gcp_machine_id')
                ->update(['gcp_machine_id' => $relation->id]);
        }

        Schema::table('gcp_machines', function (Blueprint $table) {
            $table->dropForeign(['application_id']);
            $table->dropColumn('application_id');
        });
    }

    public function down(): void
    {
        if (!Schema::hasColumn('gcp_machines', 'application_id')) {
            Schema::table('gcp_machines', function (Blueprint $table) {
                $table->unsignedBigInteger('application_id')->nullable()->after('owner_id');
                $table->foreign('application_id')
                    ->references('id')
                    ->on('applications')
                    ->nullOnDelete();
            });
        }

        if (!Schema::hasColumn('applications', 'gcp_machine_id')) {
            return;
        }

        $relations = DB::table('applications')
            ->whereNotNull('gcp_machine_id')
            ->orderBy('id')
            ->get(['id', 'gcp_machine_id']);

        foreach ($relations as $relation) {
            DB::table('gcp_machines')
                ->where('id', $relation->gcp_machine_id)
                ->whereNull('application_id')
                ->update(['application_id' => $relation->id]);
        }

        Schema::table('applications', function (Blueprint $table) {
            $table->dropForeign(['gcp_machine_id']);
            $table->dropColumn('gcp_machine_id');
        });
    }
};
