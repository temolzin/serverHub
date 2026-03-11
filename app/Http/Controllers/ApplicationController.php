<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\GcpMachine;
use App\Models\Owner;
use App\Models\Server;

class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        $query = Application::with(['owner', 'server', 'gcpMachine', 'creator']);

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhereHas('server', function ($sub) use ($search) {
                    $sub->where('hostname_internal', 'like', "%{$search}%");
                })
                ->orWhereHas('owner', function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('gcpMachine', function ($sub) use ($search) {
                    $sub->where('machine_name', 'like', "%{$search}%")
                    ->orWhere('machine_internal_name', 'like', "%{$search}%");
                });
            });
        }

        $applications = $query
        ->orderBy('id', 'desc')
        ->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'table' => view('applications.search', compact('applications'))->render(),
                'pagination' => view('applications.pagination', compact('applications'))->render(),
            ]);
        }

        $owners = Owner::orderBy('name')->get();
        $servers = Server::orderBy('hostname_internal')->get();
        $gcpMachines = GcpMachine::orderBy('machine_name')->get();
        return view('applications.index', compact('applications', 'owners', 'servers', 'gcpMachines'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'owner_id' => 'required|exists:owners,id',
            'server_id' => 'required|exists:servers,id',
            'gcp_machine_id' => 'nullable|exists:gcp_machines,id',
            'name' => 'required|string|max:255',
            'version' => 'nullable|string|max:255',
            'status' => 'required|in:production,staging,development,inactive',
            'type' => 'nullable|string|max:255',
            'comments' => 'nullable|string',
            'processes' => 'nullable|string',
            'assigned_memory' => 'nullable|integer',
            'installation_route' => 'nullable|string|max:255',
            'latest_security_patch' => 'nullable|date',
            'user_service' => 'nullable|string|max:255',
            'cron_jobs' => 'nullable|string',
        ]);

        $validated['created_by'] = auth()->id();
        Application::create($validated);

        return redirect()
        ->route('applications.index')
        ->with('success', 'Aplicación creada con éxito');
    }

    public function update(Request $request, Application $application)
    {
        $validated = $request->validate([
            'owner_id' => 'required|exists:owners,id',
            'server_id' => 'required|exists:servers,id',
            'gcp_machine_id' => 'nullable|exists:gcp_machines,id',
            'name' => 'required|string|max:255',
            'version' => 'nullable|string|max:255',
            'status' => 'required|in:production,staging,development,inactive',
            'assigned_memory' => 'nullable|integer',
            'type' => 'nullable|string|max:255',
            'comments' => 'nullable|string',
            'processes' => 'nullable|string',
            'installation_route' => 'nullable|string|max:255',
            'latest_security_patch' => 'nullable|date',
            'user_service' => 'nullable|string|max:255',
            'cron_jobs' => 'nullable|string',
        ]);
        $application->update($validated);
        return redirect()
        ->route('applications.index')
        ->with('success', 'Aplicación actualizada con éxito');
    }

    public function destroy(Application $application)
    {
        $application->delete();

        return redirect()
        ->route('applications.index')
        ->with('success', 'Aplicación eliminada con éxito');
    }
}
