<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Owner;

class OwnerController extends Controller
{
    public function checkEmail(Request $request)
    {
        $email = $request->query('email');
        $exclude = $request->query('exclude');
        $query = Owner::where('email', $email);

        if ($exclude) {
            $query->where('id', '!=', $exclude);
        }

        return response()->json([
            'exists' => $query->exists()
        ]);
    }

    public function index(Request $request)
    {
        $owners = Owner::with('creator');

        if ($request->filled('search')) {
            $search = $request->search;

            $owners->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('number_phone', 'like', "%{$search}%");
            });
        }
        $owners = $owners->get();
        return view('owners.index', compact('owners'));
    }

    public function create()
    {
        return view('content.table-owner.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:20',
            'last_name'    => 'required|string|max:50',
            'email'        => 'required|email|unique:owners,email',
            'number_phone' => 'required|digits:10',
        ]);

        $validated['created_by'] = auth()->id();

        try {
            Owner::create($validated);
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
