<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Server;
use App\Models\Database;
use App\Models\Application;
use App\Models\Owner;
use App\Models\Instance;
use App\Models\Storage;
use App\Models\GcpMachine;
use App\Models\User;
use App\Models\TypeApplication;

class Analytics extends Controller
{
    public function index()
    {
        $servers = Server::count();
        $serversOn = Server::where('state', 'poweredOn')->count();
        $serversOff = Server::where('state', 'poweredOff')->count();
        $databases = Database::count();
        $applications = Application::count();
        $owners = Owner::count();
        $instances = Instance::count();
        $storages = Storage::count();
        $machines = GcpMachine::count();
        $users = User::count();
        $machinesOn = GcpMachine::where('state', 'poweredOn')->count();
        $machinesOff = GcpMachine::where('state', 'poweredOff')->count();
        $typeApps = TypeApplication::withCount('servers')
            ->orderBy('servers_count', 'desc')
            ->get();
        $appNames = $typeApps->pluck('type_application');
        $appCounts = $typeApps->pluck('servers_count');
        $dbTypes = Database::selectRaw('type, COUNT(*) as total')
            ->groupBy('type')
            ->orderBy('total', 'desc')
            ->get();
        $dbNames = $dbTypes->pluck('type');
        $dbCounts = $dbTypes->pluck('total');

        return view('content.dashboard.dashboards-analytics', compact(
            'servers',
            'serversOn',
            'serversOff',
            'databases',
            'applications',
            'owners',
            'instances',
            'storages',
            'machines',
            'users',
            'machinesOn',
            'machinesOff',
            'appNames',
            'appCounts',
            'dbNames',
            'dbCounts'
        ));
    }
}
