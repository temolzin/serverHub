<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Owner;
use App\Models\Server;
use App\Models\TypeApplication;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Models\Database;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ServerController extends Controller
{
    public function index(Request $request)
    {
        $applianceType = TypeApplication::where('name_application', 'Apliance')->first();
        $applianceTypeId = $applianceType?->id;

        $servers = Server::with(['owner', 'applications', 'typeApplication', 'database'])
            ->when($applianceTypeId, fn($q) => $q->where('type_application_id', '!=', $applianceTypeId))
            ->when($request->filled('id'), fn($q) => $q->where('id', $request->id))
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where(function ($sub) use ($request) {
                    $sub->where('hostname_internal', 'like', "%{$request->search}%")
                        ->orWhere('primary_ip_address', 'like', "%{$request->search}%");
                });
            })
            ->where('state', '!=', 'poweredOff')
            ->latest()
            ->get();

        $this->hydrateServerPresentationData(collect($servers));

        return view('servers.index', [
            'servers' => $servers,
            'databases' => Database::all(),
            'applications' => Application::all(),
            'owners' => Owner::all(),
            'typeApplications' => TypeApplication::all(),
        ]);
    }

    public function offIndex(Request $request)
    {
        return $this->renderIndex($request, true);
    }

    public function store(Request $request)
    {
        return $this->persist($request);
    }

    public function offStore(Request $request)
    {
        return $this->persist($request, true);
    }

    public function update(Request $request, Server $server)
    {
        return $this->persist($request, false, $server);
    }

    public function offUpdate(Request $request, Server $server)
    {
        return $this->persist($request, true, $server);
    }

    private function persist(Request $request, bool $off = false, ?Server $server = null)
    {
        $validated = $off
            ? $this->validateOffServer($request, $server)
            : $this->validateActiveServer($request, $server);

        $payload = $validated;

        $payload['state'] = $this->normalizeState(
            $payload['state'] ?? ($off ? 'poweredOff' : 'poweredOn')
        );

        $server = $server
            ? tap($server)->update($payload)
            : Server::create($payload + ['created_by' => Auth::id()]);

        $this->syncApplications(
            $server,
            $request->input('application_ids', [])
        );

        return redirect()
            ->route($off ? 'servers-off.index' : 'servers.index', ['page' => $request->page])
            ->with('success', 'Servidor guardado correctamente');
    }

    public function destroy(Request $request, Server $server)
    {
        return $this->remove($request, $server);
    }

    public function offDestroy(Request $request, Server $server)
    {
        return $this->remove($request, $server, true);
    }

    private function remove(Request $request, Server $server, bool $off = false)
    {
        $server->delete();

        return redirect()
            ->route($off ? 'servers-off.index' : 'servers.index', ['page' => $request->page])
            ->with('success', 'Servidor eliminado correctamente');
    }

    public function powerOn(Request $request, Server $server)
    {
        $request->validate([
            'motive' => 'required|string|min:5'
        ]);

        $server->update([
            'state' => 'poweredOn'
        ]);

        $server->powerLogs()->create([
            'action' => 'on',
            'motive' => $request->motive,
            'created_by' => Auth::id(),
        ]);

        optional($server->database)->update([
            'status' => 'active'
        ]);

        return back()->with('success', 'Servidor encendido correctamente');
    }

    public function powerOff(Request $request, Server $server)
    {
        $request->validate([
            'motive' => 'required|string|min:5'
        ]);

        $server->update([
            'state' => 'poweredOff'
        ]);

        $server->powerLogs()->create([
            'action' => 'off',
            'motive' => $request->motive,
            'created_by' => Auth::id(),
        ]);

        optional($server->database)->update([
            'status' => 'inactive'
        ]);

        return back()->with('success', 'Servidor apagado correctamente');
    }

    private function changeState(Server $server, string $state)
    {
        $server->update(['state' => $state]);

        optional($server->database)->update([
            'status' => $state === 'poweredOn' ? 'active' : 'inactive'
        ]);

        return back()->with(
            'success',
            $state === 'poweredOn'
                ? 'Servidor encendido correctamente'
                : 'Servidor apagado correctamente'
        );
    }

    private function renderIndex(Request $request, bool $off = false)
    {
        $applianceType = \App\Models\TypeApplication::where('name_application', 'Apliance')->first();
        $applianceTypeId = $applianceType?->id;

        $servers = Server::with(['owner', 'typeApplication', 'database', 'creator', 'applications'])
            ->when($applianceTypeId, fn($q) => $q->where('type_application_id', '!=', $applianceTypeId));

        ($off ? fn($q) => $this->applyPoweredOffFilter($q)
            : fn($q) => $this->applyPoweredOnFilter($q))($servers);

        $servers = $servers->latest()->get();
        $this->hydrateServerPresentationData($servers);

        $view = $off ? 'serversOff' : 'servers';

        $owners = Owner::orderBy('name')->get();
        $typeApplications = TypeApplication::orderBy('name_application')->get();
        $databases = Database::orderBy('name')->get();
        $applications = Application::orderBy('name')->get();

        return view("$view.index", compact(
            'servers',
            'owners',
            'typeApplications',
            'databases',
            'applications'
        ));
    }

    private function hydrateServerPresentationData($servers): void
    {
        $servers->transform(function (Server $server) {
            $ownerFullName = trim(
                (optional($server->owner)->name ?? '') . ' ' . (optional($server->owner)->last_name ?? '')
            );

            $isPoweredOff = $server->isPoweredOff();

            $server->setAttribute('display_state_label', $server->stateLabel());
            $server->setAttribute(
                'display_state_badge_class',
                $isPoweredOff ? 'bg-label-danger' : 'bg-label-success'
            );
            $server->setAttribute('display_owner_full_name', $ownerFullName);
            $server->setAttribute('display_application_name', optional($server->typeApplication)->name_application);
            $server->setAttribute(
                'display_application_names',
                $server->applications->pluck('name')->filter()->implode(', ')
            );
            $server->setAttribute('display_database_name', optional($server->database)->name);

            return $server;
        });
    }


    private function validateActiveServer(Request $request, ?Server $server = null): array
    {
        $request->merge([
            'state' => $this->normalizeState($request->state)
        ]);

        return $request->validate([
            'owner_id' => 'nullable|exists:owners,id',
            'type_application_id' => 'required|exists:type_applications,id',
            'database_id' => 'nullable|exists:databases,id',
            'vm_according_to_the_vmware' => 'required|string|max:50',
            'state' => 'required|in:poweredOn,poweredOff',
            'primary_ip_address' => 'required|ipv4',
            'environment' => 'required|string|max:20',
            'datacenter' => 'required|string|max:50',
            'os_according_to_the_vmware' => 'required|string|max:50',
            'os_version_internal' => 'required|string|max:50',
            'hostname_internal' => [
                'required',
                'string',
                'max:50',
                Rule::unique('servers')->ignore($server?->id)
            ],
            'ram_memory' => 'required|integer|min:512|max:262144',
            'swap_memory' => 'required|integer|min:0|max:65536',
            'dns_name' => 'nullable|string|max:100',
            'ip_user' => 'nullable|ipv4',
            'ip_monitoring' => 'nullable|ipv4',
            'other_ips' => 'nullable|string|max:255',
            'latest_security_patch' => 'nullable|date',
            'creation_date' => 'nullable|string|max:255',
            'comments' => 'nullable|string|max:500',
        ]);
    }

    private function validateOffServer(Request $request, ?Server $server = null): array
    {
        return $this->validateActiveServer($request, $server);
    }

    private function normalizeState(?string $state, string $default = 'poweredOn'): string
    {
        $value = strtolower(trim((string) $state));

        return $value === ''
            ? $default
            : (in_array($value, Server::POWERED_OFF_VALUES, true)
                ? 'poweredOff'
                : 'poweredOn');
    }

    private function applyPoweredOffFilter(Builder $query): void
    {
        $query->where('state', 'poweredOff');
    }

    private function applyPoweredOnFilter(Builder $query): void
    {
        $query->where('state', '!=', 'poweredOff');
    }

    private function syncApplications(Server $server, array $applicationIds): void
    {
        $applicationIds = array_values(array_unique(array_map('intval', $applicationIds)));

        $detachQuery = Application::where('server_id', $server->id);

        if (!empty($applicationIds)) {
            $detachQuery->whereNotIn('id', $applicationIds);
        }

        $appsToDetach = $detachQuery->get();

        foreach ($appsToDetach as $app) {
            $app->update(['server_id' => null]);
        }

        if (empty($applicationIds)) {
            return;
        }

        $apps = Application::whereIn('id', $applicationIds)->get();

        foreach ($apps as $app) {
            $app->update(['server_id' => $server->id]);
        }
    }
}
