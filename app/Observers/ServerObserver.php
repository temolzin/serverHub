<?php

namespace App\Observers;

use App\Models\Server;
use App\Models\AuditLog;

class ServerObserver
{
    public function updating(Server $server)
    {
        AuditLog::create([
            'alter_by' => auth()->id() ?? 1,
            'module' => 'server',
            'action' => 'update',
            'record_id' => $server->id,
            'before_data' => json_encode($server->getOriginal()),
            'current_data' => json_encode($server->getDirty()),
        ]);
    }

    public function deleting(Server $server)
    {
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
