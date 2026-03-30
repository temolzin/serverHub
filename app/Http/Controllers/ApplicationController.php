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
        $applications = Application::with(['owner', 'server', 'gcpMachine', 'creator'])
            ->orderBy('id', 'desc')
            ->get();
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
            'name' => 'required|string|max:20',
            'version' => 'nullable|string|max:20',
            'status' => 'required|in:production,staging,development,inactive',
            'type' => 'nullable|string|max:20',
            'assigned_memory' => 'nullable|integer|min:1024|max:32768',
            'installation_route' => 'nullable|string|max:100',
            'latest_security_patch' => 'nullable|date',
            'user_service' => 'nullable|string|max:20',
            'comments' => 'nullable|string|max:500',
            'processes' => 'nullable|string|max:500',
            'cron_jobs' => 'nullable|string|max:500',
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
            'name' => 'required|string|max:20',
            'version' => 'nullable|string|max:20',
            'status' => 'required|in:production,staging,development,inactive',
            'type' => 'nullable|string|max:20',
            'assigned_memory' => 'nullable|integer|min:1024|max:32768',
            'installation_route' => 'nullable|string|max:100',
            'latest_security_patch' => 'nullable|date',
            'user_service' => 'nullable|string|max:20',
            'comments' => 'nullable|string|max:500',
            'processes' => 'nullable|string|max:500',
            'cron_jobs' => 'nullable|string|max:500',
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
