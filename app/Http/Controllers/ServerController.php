<?php

namespace App\Http\Controllers;

use App\Models\Owner;
use App\Models\Server;
use App\Models\TypeApplication;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Models\Database;
use Illuminate\Validation\Rule;

class ServerController extends Controller
{
    public function index(Request $request)
    {
        return $this->renderIndex($request, false);
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

        if (!$server) {
            $payload['created_by'] = auth()->id();
            Server::create($payload);
        } else {
            $server->update($payload);
        }

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

    public function powerOn(Server $server)
    {
        return $this->changeState($server, 'poweredOn');
    }

    public function powerOff(Server $server)
    {
        return $this->changeState($server, 'poweredOff');
    }

    private function changeState(Server $server, string $state)
    {
        $server->update(['state' => $state]);

        if ($server->database) {
            $server->database->update([
                'status' => $state === 'poweredOn' ? 'active' : 'inactive'
            ]);
        }

        return back()->with(
            'success',
            $state === 'poweredOn'
                ? 'Servidor encendido correctamente'
                : 'Servidor apagado correctamente'
        );
    }

    private function renderIndex(Request $request, bool $off = false)
    {
        $servers = Server::with(['owner', 'typeApplication', 'database', 'creator']);
        $filterMethod = $off ? 'applyPoweredOffFilter' : 'applyPoweredOnFilter';
        $this->{$filterMethod}($servers);
        $servers->when(
            $request->filled('search'),
            fn($query) => $this->applySearch($query, trim($request->search), $off)
        );

        $servers = $servers->latest()->paginate(10)->withQueryString();
        $view = $off ? 'serversOff' : 'servers';
        $owners = Owner::orderBy('name')->get();
        $typeApplications = TypeApplication::orderBy('name_application')->get();
        $databases = Database::orderBy('name')->get();

        return $request->ajax()
            ? response()->json([
                'table' => view("$view.search", compact('servers', 'owners', 'typeApplications', 'databases'))->render(),
                'pagination' => view("$view.pagination", compact('servers'))->render(),
            ])
            : view("$view.index", compact('servers', 'owners', 'typeApplications', 'databases'));
    }

    private function applySearch(Builder $query, string $search, bool $off): void
    {
        $query->where(function ($q) use ($search) {

            $q->where('hostname_internal', 'like', "%$search%")
              ->orWhere('primary_ip_address', 'like', "%$search%")
              ->orWhere('environment', 'like', "%$search%")
              ->orWhere('vm_according_to_the_vmware', 'like', "%$search%")
              ->orWhere('dns_name', 'like', "%$search%");
        });
    }

    private function validateActiveServer(Request $request, ?Server $server = null): array
    {
        $request->merge([
            'state' => $this->normalizeState($request->state)
        ]);

        return $request->validate([
            'owner_id' => 'required|exists:owners,id',
            'type_application_id' => 'required|exists:type_applications,id',
            'database_id' => 'nullable|exists:databases,id',
            'vm_according_to_the_vmware' => 'required|string|max:50',
            'state' => 'required|in:poweredOn,poweredOff',
            'primary_ip_address' => 'nullable|ip',
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
            'ip_user' => 'nullable|ip',
            'ip_monitoring' => 'nullable|ip',
            'other_ips' => 'nullable|string|max:255',
            'latest_security_patch' => 'nullable|date',
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

        if ($value === '') {
            return $default;
        }

        return in_array($value, Server::POWERED_OFF_VALUES, true)
            ? 'poweredOff'
            : 'poweredOn';
    }

    private function applyPoweredOffFilter(Builder $query): void
    {
        $query->where('state', 'poweredOff');
    }

    private function applyPoweredOnFilter(Builder $query): void
    {
        $query->where('state', '!=', 'poweredOff');
    }
}
