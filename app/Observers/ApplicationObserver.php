<?php

namespace App\Observers;

use App\Models\Application;
use App\Models\AuditLog;

class ApplicationObserver
{
    public function updating(Application $application)
    {
        if (AuditLog::$suppressed) return;
        AuditLog::create([
            'alter_by' => auth()->id() ?? 1,
            'module' => 'application',
            'action' => 'update',
            'record_id' => $application->id,
            'before_data' => json_encode($application->getOriginal()),
            'current_data' => json_encode($application->getDirty()),
        ]);
    }

    public function deleting(Application $application)
    {
        if (AuditLog::$suppressed) return;
        AuditLog::create([
            'alter_by' => auth()->id() ?? 1,
            'module' => 'application',
            'action' => 'delete',
            'record_id' => $application->id,
            'before_data' => json_encode($application->toArray()),
            'current_data' => null,
        ]);
    }
}
