<?php

namespace App\Http\Controllers;

use App\Models\Storage;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StorageController extends Controller
{
    public function index()
    {
        $storages = Storage::with('creator')
            ->orderBy('id','desc')
            ->paginate(10);

        return view('storages.index', compact('storages'));
    }

    public function create()
    {
        return view('storages.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([

            'hostname' => [
                'required',
                'string',
                'max:50',
                'unique:storages,hostname'
            ],

            'data_ip' => [
                'required',
                'ip',
                'unique:storages,data_ip'
            ],
            'platform' => 'required|string|max:50',
            'os_name' => 'required|string|max:50',
            'os_internal' => 'required|string|max:50',
            'operations_system' => 'required|string|max:50',
            'internal_ip' => 'required|ip',
            'environment' => 'required|string|max:20',
            'datacenter' => 'required|string|max:50',
        ]);

        $validated['created_by'] = auth()->id();

        Storage::create($validated);

        return redirect()
            ->route('storages.index')
            ->with('success', 'Almacenamiento creado correctamente');
    }

    public function edit(Storage $storage)
    {
        return view('storages.edit', compact('storage'));
    }

    public function update(Request $request, Storage $storage)
    {
        $validated = $request->validate([

            'hostname' => [
                'required',
                'string',
                'max:50',
                Rule::unique('storages')->ignore($storage->id)
            ],

            'data_ip' => [
                'required',
                'ip',
                Rule::unique('storages','data_ip')->ignore($storage->id)
            ],
            'platform' => 'required|string|max:50',
            'os_name' => 'required|string|max:50',
            'os_internal' => 'required|string|max:50',
            'operations_system' => 'required|string|max:50',
            'internal_ip' => 'required|ip',
            'environment' => 'required|string|max:20',
            'datacenter' => 'required|string|max:50',
        ]);

        $storage->update($validated);

        return redirect()
            ->route('storages.index')
            ->with('success', 'Almacenamiento actualizado correctamente');
    }

    public function destroy(Storage $storage)
    {
        $storage->delete();
        return redirect()
            ->route('storages.index')
            ->with('success', 'Almacenamiento eliminado correctamente');
    }
}
