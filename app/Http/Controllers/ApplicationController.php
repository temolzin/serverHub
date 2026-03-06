<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\Owner;
use App\Models\Server;

class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        $query = Application::with(['owner', 'server']);

        if ($request->filled('search')) {
        $search = trim($request->search);
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
            ->orWhereHas('server', function ($sub) use ($search) {
                $sub->where('hostname_internal', 'like', "%{$search}%");
            })
            ->orWhereHas('owner', function ($sub) use ($search) {
                $sub->where('name', 'like', "%{$search}%");
            });
        });
        }

        $applications = $query
        ->orderBy('id', 'desc')
        ->get();
        $owners = Owner::orderBy('name')->get();
        $servers = Server::orderBy('hostname_internal')->get();
        return view('applications.index', compact('applications', 'owners', 'servers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
        'owner_id' => 'required|exists:owners,id',
        'server_id' => 'required|exists:servers,id',
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

        Application::create($validated);

        return redirect()
        ->route('applications.index')
        ->with('success', 'AplicaciÃ³n creada con Ã©xito');
    }

    public function update(Request $request, Application $application)
    {
        $validated = $request->validate([
        'owner_id' => 'required|exists:owners,id',
        'server_id' => 'required|exists:servers,id',
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
        ->with('success', 'AplicaciÃ³n actualizada con Ã©xito');
    }

    public function destroy(Application $application)
    {
        $application->delete();

        return redirect()
        ->route('applications.index')
        ->with('success', 'AplicaciÃ³n eliminada con Ã©xito');
    }
}
