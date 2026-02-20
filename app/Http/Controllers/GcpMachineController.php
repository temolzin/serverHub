<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GcpMachine;
use App\Models\Owner;

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
    $gcpMachines = GcpMachine::with('owner');

    if ($request->filled('search')) {
      $search = $request->search;

      $gcpMachines->where(function ($q) use ($search) {
        $q->where('project_name', 'like', "%{$search}%")
          ->orWhere('machine_name', 'like', "%{$search}%")
          ->orWhere('machine_internal_name', 'like', "%{$search}%")
          ->orWhere('internal_ip', 'like', "%{$search}%");
      });
    }

    $gcpMachines = $gcpMachines
      ->orderBy('id', 'desc')
      ->paginate(10)
      ->withQueryString();
    $owners = Owner::orderBy('name')->get();

    if ($request->ajax()) {
      return response()->json([
        'table' => view('gcp-machines.search', [
          'gcpMachines' => $gcpMachines,
          'owners' => $owners
        ])->render(),
        'pagination' => view('gcp-machines.pagination', compact('gcpMachines'))->render(),
      ]);
    }
    return view('gcp-machines.index', compact('gcpMachines', 'owners'));
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'owner_id' => 'required|exists:owners,id',
      'project_name' => 'required|string|max:255',
      'environment' => 'required|string|max:255',
      'machine_name' => 'required|string|max:255',
      'machine_internal_name' => 'required|string|max:255',
      'operations_system' => 'required|string|max:255',
      'internal_ip' => 'required|unique:gcp_machines,internal_ip',
      'ram_memory' => 'required|integer',
      'swap_memory' => 'required|integer',
      'latest_security_patch' => 'nullable|date',
      'alias_ip' => 'nullable|string|max:255',
      'alias2_ip' => 'nullable|string|max:255',
      'alias3_ip' => 'nullable|string|max:255',
      'other_ips' => 'nullable|string',
      'kernel_version' => 'nullable|string|max:255',
    ]);

    GcpMachine::create($validated);

    return redirect()
      ->route('gcp-machines.index')
      ->with('success', 'Máquina creada con éxito');
  }

  public function update(Request $request, GcpMachine $gcp_machine)
  {
    $validated = $request->validate([
      'owner_id' => 'required|exists:owners,id',
      'project_name' => 'required|string|max:255',
      'environment' => 'required|string|max:255',
      'machine_name' => 'required|string|max:255',
      'machine_internal_name' => 'required|string|max:255',
      'operations_system' => 'required|string|max:255',
      'internal_ip' => 'required|unique:gcp_machines,internal_ip,' . $gcp_machine->id,
      'ram_memory' => 'required|integer',
      'swap_memory' => 'required|integer',
      'latest_security_patch' => 'nullable|date',
      'alias_ip' => 'nullable|string|max:255',
      'alias2_ip' => 'nullable|string|max:255',
      'alias3_ip' => 'nullable|string|max:255',
      'other_ips' => 'nullable|string',
      'kernel_version' => 'nullable|string|max:255',
    ]);

    $gcp_machine->update($validated);

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
}
