<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
        $machinesOn = GcpMachine::where('state', 'poweredOn')->count();
        $machinesOff = GcpMachine::where('state', 'poweredOff')->count();
        $users = User::count();
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
        $redhatVersions = GcpMachine::selectRaw('kernel_version, COUNT(*) as total')
            ->whereNotNull('kernel_version')
            ->where('kernel_version', '!=', 'N/A')
            ->groupBy('kernel_version')
            ->orderBy('kernel_version')
            ->get();
        $redhatLabels = $redhatVersions->pluck('kernel_version');
        $redhatCounts = $redhatVersions->pluck('total');
        $osVersions = GcpMachine::selectRaw('operations_system, COUNT(*) as total')
            ->whereNotNull('operations_system')
            ->where('operations_system', '!=', 'N/A')
            ->groupBy('operations_system')
            ->orderBy('operations_system')
            ->get();
        $osLabels = $osVersions->pluck('operations_system');
        $osCounts = $osVersions->pluck('total');
        $start = request('start_date');
        $end = request('end_date');
        $patchedMachines = collect();
        if ($start && $end) {
            $patchedMachines = GcpMachine::whereBetween(
                'latest_security_patch',
                [$start, $end]
            )->orderBy('latest_security_patch', 'desc')->get();
        }
        $serversByOS = Server::select('os_according_to_the_vmware')
            ->selectRaw('count(*) as total')
            ->groupBy('os_according_to_the_vmware')
            ->orderByDesc('total')
            ->get();
        $serverOSLabels = $serversByOS->pluck('os_according_to_the_vmware');
        $serverOSCounts = $serversByOS->pluck('total');

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
            'dbCounts',
            'redhatLabels',
            'redhatCounts',
            'osLabels',
            'osCounts',
            'patchedMachines',
            'serverOSLabels',
            'serverOSCounts'
        ));
    }

    public function filter(Request $request)
    {
        if (!$request->start_date && !$request->end_date) {
            return response()->json([
                'data' => [],
                'message' => 'Debes seleccionar al menos una fecha'
            ]);
        }
        $query = GcpMachine::query();
        if ($request->start_date && $request->end_date) {
            $query->whereBetween('latest_security_patch', [
                $request->start_date,
                $request->end_date
            ]);
        }
            $machines = $query->orderBy('latest_security_patch', 'desc')->get();
        return response()->json($machines);
    }
}
