<?php

namespace App\Http\Controllers;

use App\Models\Owner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

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
            'exists' => $query->exists(),
        ]);
    }

    public function index()
    {
        $owners = Owner::with('creator')
            ->orderBy('id', 'desc')
            ->get();

        return view('owners.index', compact('owners'));
    }

    public function create()
    {
        return view('content.table-owner.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:20',
            'last_name' => 'required|string|max:50',
            'email' => [
                'required',
                'email',
                'max:100',
                'unique:owners,email',
            ],
            'number_phone' => 'required|digits:10',
        ]);
        $validated['created_by'] = Auth::id();
        Owner::create($validated);

        return redirect()
            ->route('owners.index')
            ->with('success', 'Propietario creado correctamente');
    }

    public function edit(Owner $owner)
    {
        return view('content.table-owner.edit', compact('owner'));
    }

    public function update(Request $request, Owner $owner)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:20',
            'last_name' => 'required|string|max:50',
            'email' => [
                'required',
                'email',
                'max:100',
                Rule::unique('owners', 'email')->ignore($owner->id),
            ],
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

    public function modal(Owner $owner, string $type)
    {
        return match ($type) {
            'show' => view('owners.show', compact('owner'))->render(),
            'edit' => view('owners.edit', compact('owner'))->render(),
            'delete' => view('owners.delete', compact('owner'))->render(),
            default => response('Not found', 404),
        };
    }
}
