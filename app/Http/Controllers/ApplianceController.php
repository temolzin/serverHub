<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Owner;
use App\Models\Server;
use App\Models\TypeApplication;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Models\Database;

class ApplianceController extends Controller
{
    private const APPLIANCE_SHEETS = ['tulapliance', 'qroapliance'];

    public static function getApplianceTypeApplicationId(): int
    {
        $type = TypeApplication::firstOrCreate(
            ['name_application' => 'Apliance'],
            ['type_application' => 'Apliance']
        );
        return $type->id;
    }

    public static function isApplianceSheet(?string $sheetName): bool
    {
        return in_array(strtolower(trim((string) $sheetName)), self::APPLIANCE_SHEETS, true);
    }

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
        $payload = $request->all();

        $payload['state'] = $this->normalizeState(
            $payload['state'] ?? ($off ? 'poweredOff' : 'poweredOn')
        );

        $payload['type_application_id'] = self::getApplianceTypeApplicationId();

        $server = $server
            ? tap($server)->update($payload)
            : Server::create($payload + ['created_by' => auth()->id()]);

        $this->syncApplications(
            $server,
            $request->input('application_ids', [])
        );

        return redirect()
            ->route($off ? 'appliances-off.index' : 'appliances.index', ['page' => $request->page])
            ->with('success', 'Apliance guardado correctamente');
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
            ->route($off ? 'appliances-off.index' : 'appliances.index', ['page' => $request->page])
            ->with('success', 'Apliance eliminado correctamente');
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

        optional($server->database)->update([
            'status' => $state === 'poweredOn' ? 'active' : 'inactive'
        ]);

        return back()->with(
            'success',
            $state === 'poweredOn'
                ? 'Apliance encendido correctamente'
                : 'Apliance apagado correctamente'
        );
    }

    private function renderIndex(Request $request, bool $off = false)
    {
        $applianceTypeId = self::getApplianceTypeApplicationId();

        $servers = Server::with(['owner', 'typeApplication', 'database', 'creator', 'applications'])
            ->where('type_application_id', $applianceTypeId);

        ($off ? fn($q) => $this->applyPoweredOffFilter($q)
            : fn($q) => $this->applyPoweredOnFilter($q))($servers);

        $servers->when(
            $request->filled('search'),
            fn($query) => $this->applySearch($query, trim($request->search))
        );

        $servers = $servers->latest()->paginate(10)->withQueryString();
        $this->hydrateServerPresentationData($servers);

        $view = $off ? 'appliancesOff' : 'appliances';

        $owners          = Owner::orderBy('name')->get();
        $typeApplications = TypeApplication::orderBy('name_application')->get();
        $databases       = Database::orderBy('name')->get();
        $applications    = Application::orderBy('name')->get();

        return $request->ajax()
            ? response()->json([
                'table'      => view("$view.search", compact('servers', 'owners', 'typeApplications', 'databases', 'applications'))->render(),
                'pagination' => view("$view.pagination", compact('servers'))->render(),
            ])
            : view("$view.index", compact(
                'servers',
                'owners',
                'typeApplications',
                'databases',
                'applications'
            ));
    }

    private function hydrateServerPresentationData($servers): void
    {
        $servers->getCollection()->transform(function (Server $server) {
            $ownerFullName = trim(
                (optional($server->owner)->name ?? '') . ' ' . (optional($server->owner)->last_name ?? '')
            );

            $isPoweredOff = $server->isPoweredOff();

            $server->setAttribute('display_state_label', $server->stateLabel());
            $server->setAttribute('is_powered_off', $isPoweredOff);
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

    private function applySearch(Builder $query, string $search): void
    {
        $query->where(function ($q) use ($search) {
            $q->where('hostname_internal', 'like', "%$search%")
                ->orWhere('primary_ip_address', 'like', "%$search%")
                ->orWhere('environment', 'like', "%$search%")
                ->orWhere('vm_according_to_the_vmware', 'like', "%$search%")
                ->orWhere('dns_name', 'like', "%$search%");
        });
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
        $detachQuery->update(['server_id' => null]);

        if (empty($applicationIds)) {
            return;
        }

        Application::whereIn('id', $applicationIds)->update(['server_id' => $server->id]);
    }
}
