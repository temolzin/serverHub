<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $this->backfillManualOverrideFields('server', 'servers');
        $this->backfillManualOverrideFields('gcp_machine', 'gcp_machines');
    }

    public function down(): void
    {

    }

    private function backfillManualOverrideFields(string $module, string $table): void
    {
        $recordFields = [];

        DB::table('audit_logs')
            ->select(['record_id', 'current_data'])
            ->where('module', $module)
            ->where('action', 'update')
            ->whereNotNull('current_data')
            ->orderBy('id')
            ->chunk(500, function ($logs) use (&$recordFields) {
                foreach ($logs as $log) {
                    $fields = collect(array_keys($this->decodeJsonArray($log->current_data)))
                        ->reject(fn($field) => in_array($field, [
                            'created_at',
                            'updated_at',
                            'deleted_at',
                            'manual_override_fields',
                        ], true))
                        ->values()
                        ->all();

                    if (empty($fields)) {
                        continue;
                    }

                    $recordFields[$log->record_id] = collect($recordFields[$log->record_id] ?? [])
                        ->merge($fields)
                        ->unique()
                        ->values()
                        ->all();
                }
            });

        if (empty($recordFields)) {
            return;
        }

        DB::table($table)
            ->select(['id', 'manual_override_fields'])
            ->whereIn('id', array_keys($recordFields))
            ->orderBy('id')
            ->chunk(500, function ($records) use ($table, $recordFields) {
                foreach ($records as $record) {
                    $existingFields = $this->decodeJsonArray($record->manual_override_fields);
                    $mergedFields = collect($existingFields)
                        ->merge($recordFields[$record->id] ?? [])
                        ->unique()
                        ->values()
                        ->all();

                    DB::table($table)
                        ->where('id', $record->id)
                        ->update([
                            'manual_override_fields' => json_encode($mergedFields),
                        ]);
                }
            });
    }

    private function decodeJsonArray(mixed $value): array
    {
        if (empty($value)) {
            return [];
        }

        if (is_array($value)) {
            return $value;
        }

        $decoded = json_decode($value, true);

        if (is_string($decoded)) {
            $decoded = json_decode($decoded, true);
        }

        return is_array($decoded) ? $decoded : [];
    }
};
