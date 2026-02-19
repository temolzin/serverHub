<?php

namespace App\Http\Controllers;

use App\Models\Storage;
use Illuminate\Http\Request;

class StorageController extends Controller
{
    public function index()
    {
        $storages = Storage::paginate(10);
        return view('storages.index', compact('storages'));
    }

    public function create()
    {
        return view('storages.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'hostname' => 'required|string|max:255',
            'data_ip' => 'nullable|string|max:255',
            'platform' => 'nullable|string|max:255',
            'os_name' => 'nullable|string|max:255',
            'os_internal' => 'nullable|string|max:255',
            'operations_system' => 'nullable|string|max:255',
            'internal_ip' => 'nullable|string|max:255',
            'environment' => 'nullable|string|max:255',
            'datacenter' => 'nullable|string|max:255',
        ]);

        Storage::create($validated);
        return redirect()->route('storages.index')
            ->with('success', 'Almacenamiento creado correctamente');
    }

    public function edit(Storage $storage)
    {
        return view('storages.edit', compact('storage'));
    }

    public function update(Request $request, Storage $storage)
    {
        $validated = $request->validate([
            'hostname' => 'required|string|max:255',
            'data_ip' => 'nullable|string|max:255',
            'platform' => 'nullable|string|max:255',
            'os_name' => 'nullable|string|max:255',
            'os_internal' => 'nullable|string|max:255',
            'operations_system' => 'nullable|string|max:255',
            'internal_ip' => 'nullable|string|max:255',
            'environment' => 'nullable|string|max:255',
            'datacenter' => 'nullable|string|max:255',
        ]);

        $storage->update($validated);
        return redirect()->route('storages.index')
            ->with('success', 'Almacenamiento actualizado correctamente');
    }
    public function destroy(Storage $storage)
    {
        $storage->delete();
        return redirect()->route('storages.index')
            ->with('success', 'Almacenamiento eliminado correctamente');
    }
}
