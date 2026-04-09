<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Server;
use App\Models\GcpMachine;
use App\Models\Application;
use App\Models\Database;
use App\Models\Owner;
use App\Models\User;
use App\Models\Instance;
use App\Models\Storage;
use App\Models\TypeApplication;

class GlobalSearchController extends Controller
{
    public function search(Request $request)
    {
        $q = $request->q;

        return response()->json([
            'servers' => Server::where(function($query) use ($q) {
                    $query->where('hostname_internal', 'like', "%$q%")
                        ->orWhere('primary_ip_address', 'like', "%$q%");
            })
                ->limit(5)
                ->get(['id', 'hostname_internal']),

            'machines' => GcpMachine::where(function($query) use ($q) {
                    $query->where('machine_name', 'like', "%$q%")
                        ->orWhere('internal_ip', 'like', "%$q%");
                })
                ->limit(5)
                ->get(),

            'applications' => Application::where(function($query) use ($q) {
                $query->where('name', 'like', "%$q%");
            })
                ->limit(5)
                ->get(),

            'databases' => Database::where(function($query) use ($q) {
                $query->where('name', 'like', "%$q%");
            })
                ->limit(5)
                ->get(),

            'owners' => Owner::where(function($query) use ($q) {
                $query->where('name', 'like', "%$q%")
                    ->orWhere('last_name', 'like', "%$q%");
            })
                ->limit(5)
                ->get(),

            'users' => User::where(function($query) use ($q) {
                $query->where('name', 'like', "%$q%")
                    ->orWhere('email', 'like', "%$q%");
            })
                ->limit(5)
                ->get(),

            'instances' => Instance::where(function($query) use ($q) {
                $query->where('id', 'like', "%$q%");
            })
                ->limit(5)
                ->get(),

            'storages' => Storage::where(function($query) use ($q) {
                $query->where('id', 'like', "%$q%");
            })
                ->limit(5)
                ->get(),

            'type_applications' => TypeApplication::where(function($query) use ($q) {
                $query->where('id', 'like', "%$q%");
            })
                ->limit(5)
                ->get(),
        ]);
    }
}
