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
    $databases = Database::with('instance.server', 'owner');

      if ($request->filled('search')) {
            $search = $request->search;

            $databases->where(function ($q) use ($search) {
              $q->where('name', 'like', "%{$search}%")
              ->orWhere('type', 'like', "%{$search}%")
              ->orWhere('status', 'like', "%{$search}%")
              ->orWhereHas('instance.server', function ($sub) use ($search) {
              $sub->where('hostname_internal', 'like', "%{$search}%"); });
      });
    }

    $databases = $databases
        ->orderBy('id', 'desc')
        ->paginate(10)
        ->withQueryString();

    $instances = Instance::with('server')->get();
    $owners = Owner::orderBy('name')->get();


    if ($request->ajax()) {
        return response()->json([
            'table' => view('databases.search', compact('databases', 'instances', 'owners'))->render(),
            'pagination' => view('databases.pagination', compact('databases'))->render(),
        ]);
    }

    return view('databases.index', compact('databases', 'instances', 'owners'));
}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'instance_id' => 'required|exists:instances,id',
            'owner_id' => 'required|exists:owners,id',
            'name'      => 'required|string|max:255',
            'type'      => 'required|string|max:255',
            'status'    => 'nullable|string|max:255',
            'comments'  => 'nullable|string',
            'port'      => 'nullable|integer',
            'last_update' => 'nullable|date',
        ]);

        Database::create($validated);

          return redirect()
            ->route('databases.index')
            ->with('success', 'Base de datos creada correctamente.');
    }

    public function update(Request $request, Database $database)
    {
        $validated = $request->validate([
            'instance_id' => 'required|exists:instances,id',
            'owner_id'  => 'required|exists:owners,id',
            'name'      => 'required|string|max:255',
            'type'      => 'required|string|max:255',
            'status'    => 'nullable|string|max:255',
            'comments'  => 'nullable|string',
            'port'      => 'nullable|integer',
            'last_update' => 'nullable|date',
        ]);

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
