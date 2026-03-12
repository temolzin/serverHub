<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('applications', 'server_id')) {
            return;
        }

        Schema::table('applications', function (Blueprint $table) {
            $table->dropForeign(['server_id']);

            $table->unsignedBigInteger('server_id')
                ->nullable()
                ->change();

            $table->foreign('server_id')
                ->references('id')
                ->on('servers')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        if (!Schema::hasColumn('applications', 'server_id')) {
            return;
        }

        $defaultServerId = DB::table('servers')->orderBy('id')->value('id');

        DB::table('applications')
            ->whereNull('server_id')
            ->when(
                $defaultServerId === null,
                fn ($query) => $query->delete(),
                fn ($query) => $query->update(['server_id' => $defaultServerId])
            );

        Schema::table('applications', function (Blueprint $table) {
            $table->dropForeign(['server_id']);

            $table->unsignedBigInteger('server_id')
                ->nullable(false)
                ->change();

            $table->foreign('server_id')
                ->references('id')
                ->on('servers')
                ->cascadeOnDelete();
        });
    }
};
