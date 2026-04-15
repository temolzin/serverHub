<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Exports\PatchedMachinesExport;
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
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

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
            ->get()
            ->groupBy(function ($typeApp) {
                return Str::upper(trim((string) $typeApp->type_application));
            })
            ->map(function ($items, $type) {
                return [
                    'type_application' => $type !== '' ? $type : 'SIN TIPO',
                    'servers_count' => $items->sum('servers_count'),
                ];
            })
            ->sortByDesc('servers_count')
            ->values();
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

        $gcpQuery = GcpMachine::query();

        $serverQuery = Server::query();

        if ($request->start_date && $request->end_date) {
            $gcpQuery->whereBetween('latest_security_patch', [
                $request->start_date,
                $request->end_date
            ]);

            $serverQuery->whereBetween('latest_security_patch', [
                $request->start_date,
                $request->end_date
            ]);
        }

        $gcpMachines = $gcpQuery->get()->map(function ($item) {
            return [
                'machine_name' => $item->machine_name,
                'internal_ip' => $item->internal_ip,
                'operations_system' => $item->operations_system,
                'kernel_version' => $item->kernel_version,
                'latest_security_patch' => $item->latest_security_patch,
                'type' => 'GCP'
            ];
        });

        $servers = $serverQuery->get()->map(function ($item) {
            return [
                'machine_name' => $item->hostname_internal,
                'internal_ip' => $item->primary_ip_address ?? 'N/A',
                'operations_system' => $item->os_according_to_the_vmware,
                'kernel_version' => $item->os_version_internal ?? 'N/A', // aquí no hay kernel real, usamos versión
                'latest_security_patch' => $item->latest_security_patch,
                'type' => 'ON-PREMISE'
            ];
        });

        $machines = $gcpMachines
            ->merge($servers)
            ->sortByDesc('latest_security_patch')
            ->values();

        return response()->json($machines);
    }

    public function exportPatchedMachines(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $machines = GcpMachine::whereBetween('latest_security_patch', [
                $validated['start_date'],
                $validated['end_date'],
            ])
            ->orderBy('latest_security_patch', 'desc')
            ->get([
                'machine_name',
                'internal_ip',
                'operations_system',
                'kernel_version',
                'latest_security_patch',
            ]);

        if ($machines->isEmpty()) {
            return back()->with('error', 'No hay datos para exportar');
        }

        $filename = 'patched-machines-' . $validated['start_date'] . '-to-' . $validated['end_date'] . '.xlsx';

        return Excel::download(new PatchedMachinesExport($machines), $filename);
    }
}
