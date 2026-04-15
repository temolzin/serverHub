<?php

namespace App\Observers;

use App\Models\Server;
use App\Models\AuditLog;

class ServerObserver
{
    public function updating(Server $server)
    {
        if (AuditLog::$suppressed) {
            return;
        }

        $dirty = collect($server->getDirty())
            ->except(['manual_override_fields', 'pending_manual_override_fields', 'updated_at'])
            ->all();

        $server->mergeManualOverrideFields(array_keys($dirty));
        $server->mergePendingManualOverrideFields(array_keys($dirty));

        AuditLog::create([
            'alter_by' => auth()->id() ?? 1,
            'module' => 'server',
            'action' => 'update',
            'record_id' => $server->id,
            'before_data' => json_encode($server->getOriginal()),
            'current_data' => json_encode($dirty),
        ]);
    }

    public function deleting(Server $server)
    {
        if (AuditLog::$suppressed) return;
        AuditLog::create([
            'alter_by' => auth()->id() ?? 1,
            'module' => 'server',
            'action' => 'delete',
            'record_id' => $server->id,
            'before_data' => json_encode($server->toArray()),
            'current_data' => null,
        ]);
    }
}
