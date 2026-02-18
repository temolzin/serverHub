<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Instance;
use App\Models\Server;

class InstanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
      $query = Instance::with('server');

        if ($request->filled('search')) {
          $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('version', 'like', "%{$search}%")
                  ->orWhere('edition', 'like', "%{$search}%")
                  ->orWhereHas('server', function ($sub) use ($search) {
                    $sub->where('hostname_internal', 'like', "%{$search}%");
                });
            });
        }

        $instances = $query->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        $servers = Server::orderBy('hostname_internal')->get();

        if ($request->ajax()) {
            return response()->json([
                'table' => view('instances.search', compact('instances', 'servers'))->render(),
                'pagination' => view('instances.pagination', compact('instances'))->render(),
            ]);
        }

        return view('instances.index', compact('instances', 'servers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $servers = Server::all();
        return view('instances.create', compact('servers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'server_id' => 'required|exists:servers,id',
            'memory' => 'required|integer',
            'version' => 'required|string|max:255',
            'edition' => 'required|string|max:255',
        ]);

        Instance::create($validated);

        return redirect()->route('instances.index')
            ->with('success', 'Instancia creada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Instance $instance)
    {
      $validated = $request->validate([
        'server_id' => 'required|exists:servers,id',
        'memory' => 'required|integer',
        'version' => 'required|string|max:255',
        'edition' => 'required|string|max:255',
      ]);

      $instance->update($validated);

      return redirect()
        ->route('instances.index')
        ->with('success', 'Instancia actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Instance $instance)
    {
        $instance->delete();

        return redirect()->route('instances.index')
            ->with('success', 'Instancia eliminada correctamente.');
    }
}
