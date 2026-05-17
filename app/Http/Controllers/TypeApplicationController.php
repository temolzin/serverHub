<?php

namespace App\Http\Controllers;

use App\Models\TypeApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TypeApplicationController extends Controller
{
    public function index(Request $request)
    {
        $typeApplications = TypeApplication::with('creator')
            ->orderBy('id', 'desc')
            ->get();

        return view('type-applications.index', compact('typeApplications'));
    }

    public function create()
    {
        return view('type-applications.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type_application' => 'required|string|max:50',
            'name_application' => 'required|string|max:100',
        ]);

        $validated['created_by'] = Auth::id();
        TypeApplication::create($validated);

        return redirect()
            ->route('type-applications.index')
            ->with('success', 'Tipo de aplicación creado correctamente');
    }

    public function edit($id)
    {
        $typeApplication = TypeApplication::findOrFail($id);
        return view('type-applications.edit', compact('typeApplication'));
    }

    public function update(Request $request, TypeApplication $typeApplication)
    {
        $validated = $request->validate([
            'type_application' => 'required|string|max:50',
            'name_application' => 'required|string|max:100',
        ]);
        $typeApplication->update($validated);

        return redirect()
            ->route('type-applications.index')
            ->with('success', 'Tipo de aplicación actualizado correctamente.');
    }

    public function show(TypeApplication $typeApplication)
    {
        return view('type-applications.show', compact('typeApplication'));
    }

    public function destroy(TypeApplication $typeApplication)
    {
        $typeApplication->delete();

        return redirect()
            ->route('type-applications.index')
            ->with('success', 'Tipo de aplicación eliminado correctamente');
    }

    public function modal(TypeApplication $typeApplication, string $type)
    {
        return match($type) {
            'show' => view('type-applications.show', ['type' => $typeApplication])->render(),
            'edit' => view('type-applications.edit', ['type' => $typeApplication])->render(),
            'delete' => view('type-applications.delete', ['type' => $typeApplication])->render(),
            default => response('Not found', 404),
        };
    }
}
