<?php

namespace App\Http\Controllers;

use App\Models\Instance;
use App\Models\Server;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class InstanceController extends Controller
{
    public function index(Request $request)
    {
        $instances = Instance::with('server', 'creator')
            ->orderBy('id', 'desc')
            ->get();

        $servers = Server::orderBy('hostname_internal')->get();

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
                    }),
            ],
            'edition' => 'nullable|string|max:50',
        ]);

        $validated['created_by'] = Auth::id();
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
            'version' => [
                'required',
                'string',
                'max:50',
                Rule::unique('instances')
                    ->where(function ($query) use ($request) {
                        return $query->where('server_id', $request->server_id);
                    })
                    ->ignore($instance->id),
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

    public function modal(Instance $instance, string $type)
    {
        $instance->load('server');

        return match ($type) {
            'show' => view('instances.show', compact('instance'))->render(),
            'edit' => view('instances.edit', ['instance' => $instance, 'servers' => Server::all()])->render(),
            'delete' => view('instances.delete', compact('instance'))->render(),
            default => response('Not found', 404),
        };
    }
}
