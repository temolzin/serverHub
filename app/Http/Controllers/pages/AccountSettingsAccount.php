<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AccountSettingsAccount extends Controller
{
    public function index()
    {
        return view('content.pages.pages-account-settings-account');
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'avatar' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        $user->update([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'email' => $request->email,
        ]);

        if ($request->file('avatar')) {
            $user->addMediaFromRequest('avatar')->toMediaCollection('avatars');
        }

        return back()->with('success', 'Perfil actualizado correctamente');
    }

    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'La contraseña actual no es correcta'
            ]);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Contraseña actualizada correctamente');
    }
}
