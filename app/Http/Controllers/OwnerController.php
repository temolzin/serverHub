<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Owner;

class OwnerController extends Controller
{
  public function index(Request $request)
  {
    $owners = Owner::query();

    if ($request->filled('name')) {
      $owners->where('name', 'like', '%' . $request->name . '%');
    }

    if ($request->filled('email')) {
      $owners->where('email', 'like', '%' . $request->email . '%');
    }

    if ($request->filled('phone')) {
      $owners->where('number_phone', 'like', '%' . $request->phone . '%');
    }
    if ($request->ajax()) {
      return view('owners.search', [
        'owners' => $owners->get()
      ]);
    }
    return view('owners.index', [
      'owners' => $owners->get()
    ]);
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
      'number_phone' => 'nullable|string|max:10',
    ]);

    Owner::create($request->all());

    return redirect()
      ->route('owners.index')
      ->with('success', 'Propietario creado correctamente');
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
      'number_phone' => 'nullable|string|max:10',
    ]);

    $owner->update($validated);

    return redirect()
      ->route('owners.index')
      ->with('success', 'El propietario fue actualizado correctamente.');
  }

  public function destroy(Owner $owner)
  {
    $owner->delete();

    return redirect()
      ->route('owners.index')
      ->with('success', 'Propietario eliminado correctamente');
  }
}
