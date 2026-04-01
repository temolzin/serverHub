<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
            'avatar' => 'nullable|image|max:2048'
        ]);

        $user->update([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'email' => $request->email,
        ]);

        if ($request->hasFile('avatar')) {

            $path = public_path('storage/avatars');
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            $file = $request->file('avatar');
            $name = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            $file->move($path, $name);

            $user->avatar = 'storage/avatars/' . $name;
            $user->save();
        }

        return back()->with('success', 'Perfil actualizado correctamente');
    }
}
