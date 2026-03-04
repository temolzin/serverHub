<?php

namespace App\Http\Controllers;

use App\Models\Owner;
use App\Models\Server;
use App\Models\TypeApplication;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Models\Database;

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

        $payload = $off
            ? $this->buildOffPayload($validated, $server)
            : $validated;

        $payload['state'] = $this->normalizeState(
            $payload['state'] ?? ($off ? 'poweredOff' : 'poweredOn')
        );

        $server ? $server->update($payload) : Server::create($payload);

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

        return back()->with(
            'success',
            $state === 'poweredOn'
                ? 'Servidor encendido correctamente'
                : 'Servidor apagado correctamente'
        );
    }

    private function renderIndex(Request $request, bool $off = false)
    {
        $servers = Server::with(['owner', 'typeApplication', 'database']);
        $filterMethod = $off ? 'applyPoweredOffFilter' : 'applyPoweredOnFilter';
        $this->{$filterMethod}($servers);
        $servers->when(
            $request->filled('search'),
            fn($query) => $this->applySearch(
                $query,
                trim($request->search),
                $off
            )
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
            : view("$view.index", [
                'servers' => $servers,
                'owners' => $owners,
                'typeApplications' => $typeApplications,
                'databases' => $databases,
            ]);
    }

    private function applySearch(Builder $query, string $search, bool $off): void
    {
        $uuid = str_replace('-', '', strtolower($search));
        $columns = $off
            ? [
                'vm_according_to_the_vmware',
                'dns_name',
                'datacenter',
                'os_version_internal',
                'os_according_to_the_vmware',
                'comments',
                'primary_ip_address',
            ]
            : [
                'hostname_internal',
                'primary_ip_address',
                'environment',
                'vm_according_to_the_vmware',
                'dns_name',
            ];

        $query->where(function ($q) use ($search, $uuid, $columns, $off) {

            $q->where('uuid', 'like', "%$search%")
                ->orWhereRaw(
                    "REPLACE(LOWER(COALESCE(uuid, '')), '-', '') LIKE ?",
                    ["%$uuid%"]
                );

            foreach ($columns as $column) {
                $q->orWhere($column, 'like', "%$search%");
            }

            if (!$off) {
                $q->orWhereHas(
                    'typeApplication',
                    fn($sub) => $sub->where('name_application', 'like', "%$search%")
                );
            }
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
            'vm_according_to_the_vmware' => 'required|string|max:255',
            'state' => 'required|in:poweredOn,poweredOff',
            'primary_ip_address' => 'nullable|string|max:255',
            'environment' => 'required|string|max:255',
            'datacenter' => 'required|string|max:255',
            'os_according_to_the_vmware' => 'required|string|max:255',
            'os_version_internal' => 'required|string|max:255',
            'hostname_internal' => 'required|string|max:255',
            'ram_memory' => 'required|integer|min:0',
            'swap_memory' => 'required|integer|min:0',
            'dns_name' => 'nullable|string|max:255',
            'ip_user' => 'nullable|string|max:255',
            'ip_monitoring' => 'nullable|string|max:255',
            'other_ips' => 'nullable|string',
            'latest_security_patch' => 'nullable|date',
            'comments' => 'nullable|string',
        ]);
    }

    private function validateOffServer(Request $request, ?Server $server = null): array
    {
        $request->merge([
            'state' => $this->normalizeState($request->state ?? 'poweredOff', 'poweredOff')
        ]);

        return $request->validate([
            'owner_id' => 'required|exists:owners,id',
            'type_application_id' => 'required|exists:type_applications,id',
            'database_id' => 'nullable|exists:databases,id',
            'vm_according_to_the_vmware' => 'required|string|max:255',
            'state' => 'required|in:poweredOn,poweredOff',
            'primary_ip_address' => 'nullable|string|max:255',
            'environment' => 'required|string|max:255',
            'datacenter' => 'required|string|max:255',
            'os_according_to_the_vmware' => 'required|string|max:255',
            'os_version_internal' => 'required|string|max:255',
            'hostname_internal' => 'required|string|max:255',
            'ram_memory' => 'required|integer|min:0',
            'swap_memory' => 'required|integer|min:0',
            'dns_name' => 'nullable|string|max:255',
            'ip_user' => 'nullable|string|max:255',
            'ip_monitoring' => 'nullable|string|max:255',
            'other_ips' => 'nullable|string',
            'latest_security_patch' => 'nullable|date',
            'comments' => 'nullable|string',
        ]);
    }

    private function buildOffPayload(array $validated, ?Server $server = null): array
    {
        $payload = $validated;
        $payload['state'] = $this->normalizeState($payload['state'] ?? 'poweredOff', 'poweredOff');

        return $payload;
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
        $expression = "LOWER(TRIM(COALESCE(state, '')))";
        $placeholders = implode(',', array_fill(0, count(Server::POWERED_OFF_VALUES), '?'));
        $query->whereRaw("{$expression} IN ({$placeholders})", Server::POWERED_OFF_VALUES);
    }

    private function applyPoweredOnFilter(Builder $query): void
    {
        $expression = "LOWER(TRIM(COALESCE(state, '')))";
        $placeholders = implode(',', array_fill(0, count(Server::POWERED_OFF_VALUES), '?'));
        $query->where(function (Builder $stateQuery) use ($expression, $placeholders) {
            $stateQuery
                ->whereNull('state')
                ->orWhereRaw("{$expression} = ''")
                ->orWhereRaw("{$expression} NOT IN ({$placeholders})", Server::POWERED_OFF_VALUES);
        });
    }
}
