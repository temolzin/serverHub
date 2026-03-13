<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Instance;
use App\Models\Server;
use Illuminate\Validation\Rule;

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

        $instances = $instances
            ->orderBy('id', 'desc')
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


    public function store(Request $request)
    {
        $validated = $request->validate([
            'server_id' => 'required|exists:servers,id',
            'memory' => 'required|integer|min:1024|max:32768',
            'version' => [
                'required',
                'string',
                'max:50',
                Rule::unique('instances')
                    ->where(function ($query) use ($request) {
                        return $query->where('server_id', $request->server_id);
                    })
            ],
            'edition' => 'nullable|string|max:50',
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
            'version' => ['required', 'string','max:50',
                Rule::unique('instances')
                    ->where(function ($query) use ($request) {
                        return $query->where('server_id', $request->server_id);
                    })
                    ->ignore($instance->id)
            ],
            'edition' => 'nullable|string|max:50',
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
