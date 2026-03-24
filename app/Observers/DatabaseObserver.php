<?php

namespace App\Observers;

use App\Models\Database;
use App\Models\AuditLog;

class DatabaseObserver
{
    public function updating(Database $database)
    {
        AuditLog::create([
            'alter_by' => auth()->id() ?? 1,
            'module' => 'database',
            'action' => 'update',
            'record_id' => $database->id,
            'before_data' => json_encode($database->getOriginal()),
            'current_data' => json_encode($database->getDirty()),
        ]);
    }

    public function deleting(Database $database)
    {
        AuditLog::create([
            'alter_by' => auth()->id() ?? 1,
            'module' => 'database',
            'action' => 'delete',
            'record_id' => $database->id,
            'before_data' => json_encode($database->toArray()),
            'current_data' => null,
        ]);
    }
}
