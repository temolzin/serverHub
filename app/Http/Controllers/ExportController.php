<?php

namespace App\Http\Controllers;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\GenericExport;

class ExportController extends Controller
{
    public function export($module)
    {
        $map = [
            'owners' => \App\Models\Owner::class,
            'servers' => \App\Models\Server::class,
            'databases' => \App\Models\Database::class,
            'instances' => \App\Models\Instance::class,
            'storages' => \App\Models\Storage::class,
            'type-applications' => \App\Models\TypeApplication::class,
            'gcp-machines' => \App\Models\GcpMachine::class,
            'applications' => \App\Models\Application::class,
            'users' => \App\Models\User::class,
        ];

        if (!array_key_exists($module, $map)) {
            abort(404);
        }

        $modelClass = $map[$module];

        return Excel::download(
            new GenericExport($modelClass),
            $module . '.xlsx'
        );
    }
}
