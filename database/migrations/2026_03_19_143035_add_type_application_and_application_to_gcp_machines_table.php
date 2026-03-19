<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gcp_machines', function (Blueprint $table) {
            $table->unsignedBigInteger('type_application_id')->nullable()->after('project_name');
            $table->unsignedBigInteger('application_id')->nullable()->after('type_application_id');
            $table->unsignedBigInteger('database_id')->nullable()->after('application_id');

            $table->foreign('type_application_id')
                ->references('id')
                ->on('type_applications')
                ->nullOnDelete();

            $table->foreign('application_id')
                ->references('id')
                ->on('applications')
                ->nullOnDelete();

            $table->foreign('database_id')
                ->references('id')
                ->on('databases')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('gcp_machines', function (Blueprint $table) {
            $table->dropForeign(['type_application_id']);
            $table->dropForeign(['application_id']);
            $table->dropForeign(['database_id']);
            $table->dropColumn(['type_application_id', 'application_id', 'database_id']);
        });
    }
};
