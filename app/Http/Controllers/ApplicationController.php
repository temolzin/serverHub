<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\GcpMachine;
use App\Models\Owner;
use App\Models\Server;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            'assigned_memory' => 'required|integer|min:1024|max:32768',
            'installation_route' => 'required|string|max:100',
            'user_service' => 'required|string|max:20',
            'latest_security_patch' => 'nullable|date',
            'comments' => 'nullable|string|max:500',
            'processes' => 'nullable|string|max:500',
            'cron_jobs' => 'nullable|string|max:500',
        ]);

        $validated['created_by'] = Auth::id();
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
            'assigned_memory' => 'required|integer|min:1024|max:32768',
            'installation_route' => 'required|string|max:100',
            'user_service' => 'required|string|max:20',
            'latest_security_patch' => 'nullable|date',
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

    public function modal(Application $application, string $type)
    {
        $application->load(['owner', 'server', 'gcpMachine', 'creator']);
        
        return match($type) {
            'show' => view('applications.show', compact('application'))->render(),
            'edit' => view('applications.edit', compact('application'))->with([
                'servers' => Server::all(),
                'owners' => Owner::all(),
                'gcpMachines' => GcpMachine::all(),
            ])->render(),
            'delete' => view('applications.delete', compact('application'))->render(),
            default => response('Not found', 404),
        };
    }
}
