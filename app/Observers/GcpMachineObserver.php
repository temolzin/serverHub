<?php

namespace App\Observers;

use App\Models\GcpMachine;
use App\Models\AuditLog;

class GcpMachineObserver
{
    public function updating(GcpMachine $machine)
    {
        AuditLog::create([
            'alter_by' => auth()->id() ?? 1,
            'module' => 'gcp_machine',
            'action' => 'update',
            'record_id' => $machine->id,
            'before_data' => json_encode($machine->getOriginal()),
            'current_data' => json_encode($machine->getDirty()),
        ]);
    }

    public function deleting(GcpMachine $machine)
    {
        AuditLog::create([
            'alter_by' => auth()->id() ?? 1,
            'module' => 'gcp_machine',
            'action' => 'delete',
            'record_id' => $machine->id,
            'before_data' => json_encode($machine->toArray()),
            'current_data' => null,
        ]);
    }

    public function created(GcpMachine $machine)
    {
        AuditLog::create([
            'alter_by' => auth()->id() ?? 1,
            'module' => 'gcp_machine',
            'action' => 'create',
            'record_id' => $machine->id,
            'before_data' => null,
            'current_data' => json_encode($machine->toArray()),
        ]);
    }
}
