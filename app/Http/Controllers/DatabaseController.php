<?php

namespace App\Http\Controllers;

use App\Models\Database;
use App\Models\Instance;
use App\Models\Owner;
use App\Models\Server;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DatabaseController extends Controller
{
    public function index(Request $request)
    {
        $databases = Database::with('instance.server', 'owner', 'creator');
        $databases = $databases
            ->orderBy('id', 'desc')
            ->get();
        $servers = Server::with('instances', 'owner')
            ->orderBy('hostname_internal')
            ->get();
        $instances = Instance::with('server')->get();
        $owners = Owner::orderBy('name')->get();

        return view('databases.index', compact('databases', 'servers', 'instances', 'owners'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'instance_id' => 'nullable|exists:instances,id',
            'owner_id' => 'required|exists:owners,id',
            'name' => 'required|string|max:50',
            'type' => 'required|in:Oracle,MSSQL,MySQL,DB2',
            'status' => 'required|in:active,inactive',
            'port' => 'nullable|integer|min:1|max:65535',
            'version' => 'nullable|string|max:20',
            'comments' => 'nullable|string|max:500',
            'last_update' => 'nullable|date',
        ]);

        $validated['created_by'] = Auth::id();
        Database::create($validated);

        return redirect()
            ->route('databases.index')
            ->with('success', 'Base de datos creada correctamente.');
    }

    public function update(Request $request, Database $database)
    {
        $validated = $request->validate([
            'instance_id' => 'nullable|exists:instances,id',
            'owner_id' => 'required|exists:owners,id',
            'name' => 'required|string|max:50',
            'type' => 'required|in:Oracle,MSSQL,MySQL,DB2',
            'status' => 'required|in:active,inactive',
            'port' => 'nullable|integer|min:1|max:65535',
            'version' => 'nullable|string|max:20',
            'comments' => 'nullable|string|max:500',
            'last_update' => 'nullable|date',
        ]);

        $validated['created_by'] = Auth::id();
        $database->update($validated);

        return redirect()
            ->route('databases.index')
            ->with('success', 'Base de datos actualizada correctamente.');
    }

    public function destroy(Database $database)
    {
        $database->delete();

        return redirect()
            ->route('databases.index')
            ->with('success', 'Base de datos eliminada correctamente.');
    }

    public function modal(Database $database, string $type)
    {
        $database->load(['instance.server', 'owner', 'creator', 'servers']);

        return match ($type) {
            'show' => view('databases.show', compact('database'))->render(),
            'edit' => view('databases.edit', compact('database'))->with([
                'instances' => Instance::with('server')->get(),
                'owners' => Owner::orderBy('name')->get(),
            ])->render(),
            'delete' => view('databases.delete', compact('database'))->render(),
            default => response('Not found', 404),
        };
    }
}
