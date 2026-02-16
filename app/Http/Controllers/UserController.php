<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
    public function index()
    {
      $users = User::all();
      return view('users.index', compact('users'));
    }
    public function editPermissions(User $user)
    {
        $permissions = Permission::where('name', '!=', 'viewUser')->get();

        return view('users.permissions', compact('user', 'permissions'));
    }
    public function updatePermissions(Request $request, User $user)
    {
        $user->syncPermissions($request->permissions ?? []);

        return redirect()
        ->route('users.index')
        ->with('success', 'Permisos actualizados correctamente.');
    }
}
