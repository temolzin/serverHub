<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Owner;

class OwnerController extends Controller
{
  public function checkEmail(Request $request)
  {
    $email = $request->query('email');
    $exists = Owner::where('email', $email)->exists();
    return response()->json(['exists' => $exists]);
  }
  public function index(Request $request)
  {
    $owners = Owner::query();
    if ($request->filled('search')) {
      $search = $request->search;
      $owners->where(function ($q) use ($search) {
        $q->where('name', 'like', "%{$search}%")
          ->orWhere('last_name', 'like', "%{$search}%")
          ->orWhere('email', 'like', "%{$search}%")
          ->orWhere('number_phone', 'like', "%{$search}%");
      });
    }

    $owners = $owners
      ->orderBy('id', 'desc')
      ->paginate(10)
      ->withQueryString();

    if ($request->ajax()) {
      return response()->json([
        'table' => view('owners.search', compact('owners'))->render(),
        'pagination' => $owners->links()->render(),
      ]);
    }

    return view('owners.index', compact('owners'));
  }

  public function create()
  {
    return view('content.table-owner.create');
  }

  public function store(Request $request)
  {
    $request->validate([
      'name' => 'required|string|max:20',
      'last_name' => 'required|string|max:50',
      'email' => 'required|email|unique:owners,email',
      'number_phone' => 'required|digits:10',
    ]);

    try {
      Owner::create($request->all());
      return redirect()
        ->route('owners.index')
        ->with('success', 'Propietario creado correctamente');
    } catch (\Illuminate\Database\QueryException $e) {
      return redirect()
        ->back()
        ->withInput()
        ->with('error', 'El correo ya existe, por favor ingresa uno diferente.');
    }
  }

  public function edit($id)
  {
    return view('content.table-owner.edit');
  }

  public function update(Request $request, Owner $owner)
  {
    $validated = $request->validate([
      'name'         => 'required|string|max:20',
      'last_name'    => 'required|string|max:50',
      'email'        => 'required|email|unique:owners,email,' . $owner->id,
      'number_phone' => 'required|digits:10',
    ]);

    $owner->update($validated);
    return redirect()
      ->route('owners.index')
      ->with('success', 'El propietario fue actualizado correctamente.');
  }

  public function show(Owner $owner)
  {
    return view('owners.show', compact('owner'));
  }

  public function destroy(Owner $owner)
  {
    $owner->delete();
    return redirect()
      ->route('owners.index')
      ->with('success', 'Propietario eliminado correctamente');
  }
}
