<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Database;
use App\Models\Server;
use App\Models\Instance;
use App\Models\Owner;

class DatabaseController extends Controller
{
    public function index(Request $request)
    {
        $databases = Database::with('instance.server', 'owner', 'creator');

        if ($request->filled('search')) {
                $search = $request->search;

            $databases->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('type', 'like', "%{$search}%")
                ->orWhere('status', 'like', "%{$search}%")
                ->orWhereHas('instance.server', function ($sub) use ($search) {
                    $sub->where('hostname_internal', 'like', "%{$search}%");
                });
            });
        }

        $databases = $databases
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();
        $servers = Server::with('instances', 'owner')
            ->orderBy('hostname_internal')
            ->get();
        $instances = Instance::with('server')->get();
        $owners = Owner::orderBy('name')->get();

        if ($request->ajax()) {
            return response()->json([
                'table' => view('databases.search', compact('databases', 'servers', 'instances', 'owners'))->render(),
                'pagination' => view('databases.pagination', compact('databases'))->render(),
            ]);
        }

        return view('databases.index', compact('databases', 'servers', 'instances', 'owners'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'instance_id' => 'required|exists:instances,id',
            'owner_id' => 'required|exists:owners,id',
            'name' => 'required|string|max:50',
            'type' => 'required|in:Oracle,MSSQL,MySQL,DB2',
            'status' => 'required|in:active,inactive',
            'port' => 'nullable|integer|min:1|max:65535',
            'version' => 'nullable|string|max:20',
            'comments' => 'nullable|string|max:500',
            'last_update' => 'nullable|date',
        ]);

        $validated['created_by'] = auth()->id();
        Database::create($validated);

            return redirect()
            ->route('databases.index')
            ->with('success', 'Base de datos creada correctamente.');
    }

    public function update(Request $request, Database $database)
    {
        $validated = $request->validate([
            'instance_id' => 'required|exists:instances,id',
            'owner_id' => 'required|exists:owners,id',
            'name' => 'required|string|max:50',
            'type' => 'required|in:Oracle,MSSQL,MySQL,DB2',
            'status' => 'required|in:active,inactive',
            'port' => 'nullable|integer|min:1|max:65535',
            'version' => 'nullable|string|max:20',
            'comments' => 'nullable|string|max:500',
            'last_update' => 'nullable|date',
        ]);

        $validated['created_by'] = auth()->id();
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
}
