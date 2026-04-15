<?php

namespace App\Observers;

use App\Models\GcpMachine;
use App\Models\AuditLog;

class GcpMachineObserver
{
    public function updating(GcpMachine $machine)
    {
        if (AuditLog::$suppressed) {
            return;
        }

        $dirty = collect($machine->getDirty())
            ->except(['manual_override_fields', 'pending_manual_override_fields', 'updated_at'])
            ->all();

        $machine->mergeManualOverrideFields(array_keys($dirty));
        $machine->mergePendingManualOverrideFields(array_keys($dirty));

        AuditLog::create([
            'alter_by' => auth()->id() ?? 1,
            'module' => 'gcp_machine',
            'action' => 'update',
            'record_id' => $machine->id,
            'before_data' => json_encode($machine->getOriginal()),
            'current_data' => json_encode($dirty),
        ]);
    }

    public function deleting(GcpMachine $machine)
    {
        if (AuditLog::$suppressed) return;
        AuditLog::create([
            'alter_by' => auth()->id() ?? 1,
            'module' => 'gcp_machine',
            'action' => 'delete',
            'record_id' => $machine->id,
            'before_data' => json_encode($machine->toArray()),
            'current_data' => null,
        ]);
    }
}
