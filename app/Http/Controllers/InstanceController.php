<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Instance;
use App\Models\Server;

class InstanceController extends Controller
{
    public function index(Request $request)
    {
        $instances = Instance::with('server', 'creator');
        if ($request->filled('search')) {
            $search = $request->search;
            $instances->where(function ($q) use ($search) {
                $q->where('version', 'like', "%{$search}%")
                    ->orWhere('edition', 'like', "%{$search}%")
                    ->orWhereHas('server', function ($sub) use ($search) {
                        $sub->where('hostname_internal', 'like', "%{$search}%");
                    });
            });
        }
        $instances = $instances->orderBy('id', 'desc')->get();
        $servers = Server::orderBy('hostname_internal')->get();
        return view('instances.index', compact('instances', 'servers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'server_id' => 'required|exists:servers,id',
            'memory' => 'required|integer|min:1024|max:32768',
            'version'   => 'required|string|max:255',
            'edition'   => 'nullable|string|max:255',
        ]);

        $validated['created_by'] = auth()->id();
        Instance::create($validated);

        return redirect()
            ->route('instances.index')
            ->with('success', 'Instancia creada correctamente.');
    }

    public function update(Request $request, Instance $instance)
    {
        $validated = $request->validate([
            'server_id' => 'required|exists:servers,id',
            'memory' => 'required|integer|min:1024|max:32768',
            'version'   => 'required|string|max:255',
            'edition'   => 'nullable|string|max:255',
        ]);
        $instance->update($validated);
        return redirect()
            ->route('instances.index')
            ->with('success', 'Instancia actualizada correctamente.');
    }

    public function destroy(Instance $instance)
    {
        $instance->delete();
        return redirect()
            ->route('instances.index')
            ->with('success', 'Instancia eliminada correctamente.');
    }
}
