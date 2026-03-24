<?php

namespace App\Http\Controllers;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\GenericExport;
use App\Models\Owner;
use App\Models\Server;
use App\Models\Database;
use App\Models\Instance;
use App\Models\Storage;
use App\Models\TypeApplication;
use App\Models\GcpMachine;
use App\Models\Application;
use App\Models\User;

class ExportController extends Controller
{
    public function export($module)
    {
        $map = [
            'owners' => Owner::class,
            'servers' => Server::class,
            'databases' => Database::class,
            'instances' => Instance::class,
            'storages' => Storage::class,
            'type-applications' => TypeApplication::class,
            'gcp-machines' => GcpMachine::class,
            'applications' => Application::class,
            'users' => User::class,
            'appliances' => Server::class,
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
