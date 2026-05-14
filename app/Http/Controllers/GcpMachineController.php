<?php

namespace App\Http\Controllers;

use App\Models\GcpMachine;
use App\Models\Owner;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\TypeApplication;
use App\Models\Database as DatabaseModel;
use Illuminate\Support\Facades\Auth;

class GcpMachineController extends Controller
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
        return $this->persist($request, false);
    }

    public function offStore(Request $request)
    {
        return $this->persist($request, true);
    }

    public function update(Request $request, GcpMachine $gcp_machine)
    {
        return $this->persist($request, false, $gcp_machine);
    }

    public function offUpdate(Request $request, GcpMachine $gcp_machine)
    {
        return $this->persist($request, true, $gcp_machine);
    }

    public function destroy(Request $request, GcpMachine $gcp_machine)
    {
        return $this->remove($request, $gcp_machine, false);
    }

    public function offDestroy(Request $request, GcpMachine $gcp_machine)
    {
        return $this->remove($request, $gcp_machine, true);
    }

    public function powerOn(GcpMachine $gcp_machine)
    {
        return $this->changeState($gcp_machine, 'poweredOn');
    }

    public function powerOff(Request $request, GcpMachine $gcp_machine)
    {
        $request->validate([
            'motive' => 'required|string|min:5'
        ]);

        $gcp_machine->update([
            'state' => 'poweredOff'
        ]);

        $gcp_machine->powerLogs()->create([
            'action' => 'off',
            'motive' => $request->motive,
            'created_by' => Auth::id(),
        ]);

        $this->syncLinkedDatabaseStatus(
            $gcp_machine->database_id ? (int) $gcp_machine->database_id : null
        );

        return back()->with('success', 'Maquina apagada correctamente');
    }

    public function checkIp(Request $request)
    {
        $ip = $request->query('ip');
        $exclude = $request->query('exclude');

        $query = GcpMachine::where('internal_ip', $ip);

        if ($exclude) {
            $query->where('id', '!=', $exclude);
        }

        return response()->json([
            'exists' => $query->exists()
        ]);
    }

    private function persist(Request $request, bool $off = false, ?GcpMachine $gcpMachine = null)
    {
        $validated = $this->validateMachine($request, $off);
        $payload = $this->normalizeMachinePayload($validated);
        $payload['state'] = $this->normalizeState(
            $payload['state'] ?? ($off ? 'poweredOff' : 'poweredOn'),
            $off ? 'poweredOff' : 'poweredOn'
        );
        $previousDatabaseId = $gcpMachine?->database_id;

        if (!$gcpMachine) {
            $payload['created_by'] = Auth::id();
        }

        $machine = $gcpMachine
            ? tap($gcpMachine)->update($payload)
            : GcpMachine::create($payload);

        $this->syncSelectedApplication(
            $machine,
            isset($payload['application_id']) && $payload['application_id'] !== null
                ? (int) $payload['application_id']
                : null
        );
        $this->syncLinkedDatabaseStatus($machine->database_id);

        if ($previousDatabaseId && (int) $previousDatabaseId !== (int) $machine->database_id) {
            $this->syncLinkedDatabaseStatus((int) $previousDatabaseId);
        }

        return redirect()
            ->route($off ? 'gcp-machines-off.index' : 'gcp-machines.index', ['page' => $request->page])
            ->with('success', 'Maquina guardada con exito');
    }

    private function remove(Request $request, GcpMachine $gcpMachine, bool $off = false)
    {
        $databaseId = $gcpMachine->database_id;
        $gcpMachine->delete();
        $this->syncLinkedDatabaseStatus($databaseId ? (int) $databaseId : null);

        return redirect()
            ->route($off ? 'gcp-machines-off.index' : 'gcp-machines.index', ['page' => $request->page])
            ->with('success', 'Maquina eliminada con exito');
    }

    private function changeState(GcpMachine $gcpMachine, string $state)
    {
        $gcpMachine->update(['state' => $this->normalizeState($state)]);
        $this->syncLinkedDatabaseStatus($gcpMachine->database_id ? (int) $gcpMachine->database_id : null);

        return back()->with(
            'success',
            $state === 'poweredOn'
                ? 'Maquina encendida correctamente'
                : 'Maquina apagada correctamente'
        );
    }

    private function renderIndex(Request $request, bool $off = false)
    {
        $gcpMachines = GcpMachine::with([
            'owner',
            'applications',
            'typeApplication',
            'selectedApplication',
            'database',
            'creator'
        ]);

        if ($request->filled('id')) {
            $gcpMachines->where('id', $request->id);
        }

        if ($request->filled('search')) {
            $gcpMachines->where(function ($q) use ($request) {
                $q->where('machine_name', 'like', "%{$request->search}%")
                    ->orWhere('internal_ip', 'like', "%{$request->search}%");
            });
        }

        $filterMethod = $off ? 'applyPoweredOffFilter' : 'applyPoweredOnFilter';
        $this->{$filterMethod}($gcpMachines);

        $gcpMachines = $gcpMachines
            ->latest('id')
            ->get();

        $this->decorateMachinesForView(collect($gcpMachines));

        $owners = Owner::orderBy('name')->get();
        $typeApplications = TypeApplication::orderBy('name_application')->get();
        $applications = Application::orderBy('name')->get();
        $databases = DatabaseModel::orderBy('name')->get();

        $view = $off ? 'gcp-machines-off' : 'gcp-machines';

        return view("$view.index", compact(
            'gcpMachines',
            'owners',
            'typeApplications',
            'applications',
            'databases'
        ));
    }


    private function validateMachine(Request $request, bool $off = false): array
    {
        $request->merge([
            'state' => $this->normalizeState(
                $request->state,
                $off ? 'poweredOff' : 'poweredOn'
            ),
        ]);

        return $request->validate([
            'owner_id' => 'nullable|exists:owners,id',
            'project_name' => 'required|string|max:255',
            'type_application_id' => 'nullable|exists:type_applications,id',
            'application_id' => 'nullable|exists:applications,id',
            'database_id' => 'nullable|exists:databases,id',
            'state' => 'required|in:poweredOn,poweredOff',
            'environment' => 'required|string|max:255',
            'machine_name' => 'required|string|max:255',
            'machine_internal_name' => 'required|string|max:255',
            'operations_system' => 'required|string|max:255',
            'internal_ip' => 'required|string|max:255',
            'ram_memory' => ['required', 'integer', 'min:256', 'max:1048576'],
            'swap_memory' => ['required', 'integer', 'min:0', 'max:1048576'],
            'latest_security_patch' => 'nullable|date',
            'creation_date' => 'nullable|string|max:255',
            'alias_ip' => 'nullable|string|max:255',
            'alias2_ip' => 'nullable|string|max:255',
            'alias3_ip' => 'nullable|string|max:255',
            'other_ips' => 'nullable|string',
            'kernel_version' => 'nullable|string|max:255',
        ]);
    }

    private function normalizeState(?string $state, string $default = 'poweredOn'): string
    {
        $value = strtolower(trim((string) $state));

        if ($value === '') {
            return $default;
        }

        return in_array($value, GcpMachine::POWERED_OFF_VALUES, true)
            ? 'poweredOff'
            : 'poweredOn';
    }

    private function applyPoweredOffFilter(Builder $query): void
    {
        $expression = "LOWER(TRIM(COALESCE(state, '')))";
        $placeholders = implode(',', array_fill(0, count(GcpMachine::POWERED_OFF_VALUES), '?'));
        $query->whereRaw("{$expression} IN ({$placeholders})", GcpMachine::POWERED_OFF_VALUES);
    }

    private function applyPoweredOnFilter(Builder $query): void
    {
        $expression = "LOWER(TRIM(COALESCE(state, '')))";
        $placeholders = implode(',', array_fill(0, count(GcpMachine::POWERED_OFF_VALUES), '?'));
        $query->where(function (Builder $stateQuery) use ($expression, $placeholders) {
            $stateQuery
                ->whereNull('state')
                ->orWhereRaw("{$expression} = ''")
                ->orWhereRaw("{$expression} NOT IN ({$placeholders})", GcpMachine::POWERED_OFF_VALUES);
        });
    }

    private function normalizeMachinePayload(array $payload): array
    {
        $foreignKeyFields = [
            'type_application_id',
            'application_id',
            'database_id',
        ];

        foreach ($foreignKeyFields as $field) {
            $value = $payload[$field] ?? null;
            $payload[$field] = ($value === null || $value === '')
                ? null
                : (int) $value;
        }

        $fieldsWithNaFallback = [
            'project_name',
            'environment',
            'machine_name',
            'machine_internal_name',
            'operations_system',
            'internal_ip',
            'alias_ip',
            'alias2_ip',
            'alias3_ip',
            'other_ips',
            'kernel_version',
        ];

        foreach ($fieldsWithNaFallback as $field) {
            $value = $payload[$field] ?? null;
            if ($value === null || trim((string) $value) === '') {
                $payload[$field] = 'N/A';
            }
        }

        return $payload;
    }

    private function syncSelectedApplication(GcpMachine $gcpMachine, ?int $applicationId): void
    {
        $detachQuery = Application::where('gcp_machine_id', $gcpMachine->id);

        if ($applicationId) {
            $detachQuery->where('id', '!=', $applicationId);
        }

        $detachQuery->update(['gcp_machine_id' => null]);

        if (!$applicationId) {
            return;
        }

        GcpMachine::where('application_id', $applicationId)
            ->where('id', '!=', $gcpMachine->id)
            ->update(['application_id' => null]);

        Application::whereKey($applicationId)->update(['gcp_machine_id' => $gcpMachine->id]);
    }

    private function syncLinkedDatabaseStatus(?int $databaseId): void
    {
        if (!$databaseId) {
            return;
        }

        $states = GcpMachine::where('database_id', $databaseId)->pluck('state');

        if ($states->isEmpty()) {
            return;
        }

        $hasPoweredOnMachine = $states->contains(
            fn($state) => $this->normalizeState($state) === 'poweredOn'
        );

        $database = DatabaseModel::find($databaseId);

        if ($database) {
            $database->update([
                'status' => $hasPoweredOnMachine ? 'active' : 'inactive',
            ]);
        }
    }

    private function decorateMachinesForView($gcpMachines): void
    {
        $gcpMachines->transform(function (GcpMachine $machine) {
            $ownerFullName = trim((optional($machine->owner)->name ?? '') . ' ' . (optional($machine->owner)->last_name ?? ''));
            $relatedApplicationNames = $machine->applications->pluck('name')->filter()->implode(', ');
            $selectedApplicationName = optional($machine->selectedApplication)->name;
            $applicationType = optional($machine->typeApplication)->name_application
                ?? optional($machine->typeApplication)->type_application;

            $machine->setAttribute('display_owner_full_name', $ownerFullName);
            $machine->setAttribute('display_application_type', $applicationType);
            $machine->setAttribute(
                'display_application_name',
                filled($selectedApplicationName) ? $selectedApplicationName : $relatedApplicationNames
            );
            $machine->setAttribute('display_database_name', optional($machine->database)->name);
            $machine->setAttribute('display_state_label', $machine->stateLabel());
            $machine->setAttribute(
                'display_state_badge_class',
                $machine->isPoweredOff() ? 'bg-label-danger' : 'bg-label-success'
            );
            $machine->setAttribute('is_powered_off', $machine->isPoweredOff());

            return $machine;
        });
    }
}
