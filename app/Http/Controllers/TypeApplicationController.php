<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TypeApplication;

class TypeApplicationController extends Controller
{
  public function index(Request $request)
  {
    $typeApplications = TypeApplication::with('creator');
    if ($request->filled('search')) {
      $search = $request->search;
      $typeApplications->where(function ($q) use ($search) {
        $q->where('type_application', 'like', "%{$search}%")
          ->orWhere('name_application', 'like', "%{$search}%");
      });
    }

    $typeApplications = $typeApplications
      ->orderBy('id', 'desc')
      ->paginate(10)
      ->withQueryString();

    if ($request->ajax()) {
      return response()->json([
        'table' => view('type-applications.search', compact('typeApplications'))->render(),
        'pagination' => $typeApplications->links()->render(),
      ]);
    }

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

        $validated['created_by'] = auth()->id();
        TypeApplication::create($validated);

        return redirect()
            ->route('type-applications.index')
            ->with('success', 'Tipo de aplicación creado correctamente');
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
}
