<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GcpMachine;
use App\Models\Owner;
use App\Models\Application;

class GcpMachineController extends Controller
{
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

    public function index(Request $request)
    {
        $gcpMachines = GcpMachine::with(['owner', 'application', 'creator']);

        if ($request->filled('search')) {
            $search = $request->search;
            $uuidSearch = str_replace('-', '', strtolower($search));

            $gcpMachines->where(function ($q) use ($search, $uuidSearch) {
                $q->where('project_name', 'like', "%{$search}%")
                    ->orWhere('uuid', 'like', "%{$search}%")
                    ->orWhereRaw(
                        "REPLACE(LOWER(COALESCE(uuid, '')), '-', '') LIKE ?",
                        ["%{$uuidSearch}%"]
                    )
                    ->orWhere('machine_name', 'like', "%{$search}%")
                    ->orWhere('machine_internal_name', 'like', "%{$search}%")
                    ->orWhere('internal_ip', 'like', "%{$search}%")
                    ->orWhereHas('application', function ($sub) use ($search) {
                        $sub->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $gcpMachines = $gcpMachines
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();
        $owners = Owner::orderBy('name')->get();
        $applications = Application::orderBy('name')->get();

        if ($request->ajax()) {
            return response()->json([
                'table' => view('gcp-machines.search', [
                    'gcpMachines' => $gcpMachines,
                    'owners' => $owners,
                    'applications' => $applications
                ])->render(),
                'pagination' => view('gcp-machines.pagination', compact('gcpMachines'))->render(),
            ]);
        }
        return view('gcp-machines.index', compact('gcpMachines', 'owners', 'applications'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'owner_id' => 'required|exists:owners,id',
            'application_id' => 'nullable|exists:applications,id',
            'project_name' => 'required|string|max:255',
            'environment' => 'required|string|max:255',
            'machine_name' => 'required|string|max:255',
            'machine_internal_name' => 'required|string|max:255',
            'operations_system' => 'required|string|max:255',
            'internal_ip' => 'required|string|max:255',
            'ram_memory' => 'required|integer',
            'swap_memory' => 'required|integer',
            'latest_security_patch' => 'nullable|date',
            'alias_ip' => 'nullable|string|max:255',
            'alias2_ip' => 'nullable|string|max:255',
            'alias3_ip' => 'nullable|string|max:255',
            'other_ips' => 'nullable|string',
            'kernel_version' => 'nullable|string|max:255',
        ]);

        $validated['created_by'] = auth()->id();
        GcpMachine::create($this->normalizeMachinePayload($validated));

        return redirect()
            ->route('gcp-machines.index')
            ->with('success', 'Máquina creada con éxito');
    }

    public function update(Request $request, GcpMachine $gcp_machine)
    {
        $validated = $request->validate([
            'owner_id' => 'required|exists:owners,id',
            'application_id' => 'nullable|exists:applications,id',
            'project_name' => 'required|string|max:255',
            'environment' => 'required|string|max:255',
            'machine_name' => 'required|string|max:255',
            'machine_internal_name' => 'required|string|max:255',
            'operations_system' => 'required|string|max:255',
            'internal_ip' => 'required|string|max:255',
            'ram_memory' => 'required|integer',
            'swap_memory' => 'required|integer',
            'latest_security_patch' => 'nullable|date',
            'alias_ip' => 'nullable|string|max:255',
            'alias2_ip' => 'nullable|string|max:255',
            'alias3_ip' => 'nullable|string|max:255',
            'other_ips' => 'nullable|string',
            'kernel_version' => 'nullable|string|max:255',
        ]);

        $gcp_machine->update($this->normalizeMachinePayload($validated));

        return redirect()
            ->route('gcp-machines.index', ['page' => $request->page])
            ->with('success', 'Máquina actualizada con éxito');
    }

    public function destroy(Request $request, GcpMachine $gcp_machine)
    {
        $gcp_machine->delete();
        return redirect()
            ->route('gcp-machines.index', ['page' => $request->page])
            ->with('success', 'Máquina eliminada con éxito');
    }

    private function normalizeMachinePayload(array $payload): array
    {
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
}
