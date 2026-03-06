<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TypeApplication;

class TypeApplicationController extends Controller
{
    public function index(Request $request)
    {
        $typeApplications = TypeApplication::query();
        if ($request->filled('search')) {
        $search = $request->search;
        $typeApplications->where(function ($q) use ($search) {
            $q->where('type_application', 'like', "%{$search}%")
            ->orWhere('name_application', 'like', "%{$search}%");
        });
        }

        $typeApplications = $typeApplications->orderBy('id', 'desc')->get();

        return view('type-applications.index', compact('typeApplications'));
    }

    public function create()
    {
        return view('type-applications.create');
    }

    public function store(Request $request)
    {
        $request->validate([
        'type_application' => 'required|string|max:50',
        'name_application' => 'required|string|max:100',
        ]);
        TypeApplication::create($request->all());
        return redirect()
        ->route('type-applications.index')
        ->with('success', 'Tipo de aplicaciÃ³n creado correctamente');
    }

    public function edit($id)
    {
        return view('type-applications.edit');
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
        ->with('success', 'Tipo de aplicaciÃ³n actualizado correctamente.');
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
        ->with('success', 'Tipo de aplicaciÃ³n eliminado correctamente');
    }
}
