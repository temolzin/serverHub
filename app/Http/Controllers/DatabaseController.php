<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Database;
use App\Models\Server;

class DatabaseController extends Controller
{
  public function index(Request $request)
  {
    $databases = Database::with('server');

      if ($request->filled('search')) {
            $search = $request->search;

            $databases->where(function ($q) use ($search) {
              $q->where('name', 'like', "%{$search}%")
              ->orWhere('type', 'like', "%{$search}%")
              ->orWhere('status', 'like', "%{$search}%")
              ->orWhereHas('server', function ($sub) use ($search) {
              $sub->where('hostname_internal', 'like', "%{$search}%");
          });
      });
    }

    $databases = $databases
        ->orderBy('id', 'desc')
        ->paginate(10)
        ->withQueryString();

    $servers = Server::orderBy('hostname_internal')->get();

    if ($request->ajax()) {
        return response()->json([
            'table' => view('databases.search', compact('databases', 'servers'))->render(),
            'pagination' => view('databases.pagination', compact('databases'))->render(),
        ]);
    }

    return view('databases.index', compact('databases', 'servers'));
}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'server_id' => 'required|exists:servers,id',
            'name'      => 'required|string|max:255',
            'type'      => 'required|string|max:255',
            'status'    => 'nullable|string|max:255',
            'comments'  => 'nullable|string',
            'port'      => 'nullable|integer',
            'version'   => 'nullable|string|max:255',
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
            'server_id' => 'required|exists:servers,id',
            'name'      => 'required|string|max:255',
            'type'      => 'required|string|max:255',
            'status'    => 'nullable|string|max:255',
            'comments'  => 'nullable|string',
            'port'      => 'nullable|integer',
            'version'   => 'nullable|string|max:255',
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
