<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Server;
use App\Models\Owner;
use App\Models\TypeApplication;

class ServerController extends Controller
{
  public function checkIp(Request $request)
  {
    $ip = $request->query('ip');
    $exclude = $request->query('exclude');

    $query = Server::where('primary_ip_address', $ip);

    if ($exclude) {
      $query->where('id', '!=', $exclude);
    }
    return response()->json([
      'exists' => $query->exists()
    ]);
  }

  public function index(Request $request)
  {
    $servers = Server::with(['owner', 'typeApplication']);

    if ($request->filled('search')) {
      $search = $request->search;
      $servers->where(function ($q) use ($search) {
        $q->where('hostname_internal', 'like', "%{$search}%")
          ->orWhere('primary_ip_address', 'like', "%{$search}%")
          ->orWhere('environment', 'like', "%{$search}%")
          ->orWhereHas('typeApplication', function ($sub) use ($search) {
            $sub->where('name_application', 'like', "%{$search}%");
          });
      });
    }

    $servers = $servers
      ->orderBy('id', 'desc')
      ->paginate(10)
      ->withQueryString();
    $owners = Owner::orderBy('name')->get();
    $typeApplications = TypeApplication::orderBy('name_application')->get();

    if ($request->ajax()) {
      return response()->json([
        'table' => view('servers.search', compact('servers', 'owners', 'typeApplications'))->render(),
        'pagination' => view('servers.pagination', compact('servers'))->render(),
      ]);
    }
    return view('servers.index', compact('servers', 'owners', 'typeApplications'));
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'owner_id'                 => 'required|exists:owners,id',
      'type_application_id'      => 'required|exists:type_applications,id',
      'vm_according_to_the_vmware' => 'required|string|max:255',
      'primary_ip_address'       => 'required|unique:servers,primary_ip_address',
      'environment'              => 'required|string|max:255',
      'datacenter'               => 'required|string|max:255',
      'os_according_to_the_vmware' => 'required|string|max:255',
      'os_version_internal'      => 'required|string|max:255',
      'hostname_internal'        => 'required|string|max:255',
      'ram_memory'               => 'required|integer',
      'swap_memory'              => 'required|integer',
      'dns_name'                 => 'nullable|string|max:255',
      'ip_user'                  => 'nullable|string|max:255',
      'ip_monitoring'            => 'nullable|string|max:255',
      'other_ips'                => 'nullable|string',
      'latest_security_patch'    => 'nullable|date',
      'comments'                 => 'nullable|string',
    ]);

    Server::create($validated);
    return redirect()
      ->route('servers.index')
      ->with('success', 'Servidor creado correctamente');
  }

  public function update(Request $request, Server $server)
  {
    $validated = $request->validate([
      'owner_id'                 => 'required|exists:owners,id',
      'type_application_id'      => 'required|exists:type_applications,id',
      'vm_according_to_the_vmware' => 'required|string|max:255',
      'primary_ip_address'       => 'required|unique:servers,primary_ip_address,' . $server->id,
      'environment'              => 'required|string|max:255',
      'datacenter'               => 'required|string|max:255',
      'os_according_to_the_vmware' => 'required|string|max:255',
      'os_version_internal'      => 'required|string|max:255',
      'hostname_internal'        => 'required|string|max:255',
      'ram_memory'               => 'required|integer',
      'swap_memory'              => 'required|integer',
      'dns_name'                 => 'nullable|string|max:255',
      'ip_user'                  => 'nullable|string|max:255',
      'ip_monitoring'            => 'nullable|string|max:255',
      'other_ips'                => 'nullable|string',
      'latest_security_patch'    => 'nullable|date',
      'comments'                 => 'nullable|string',
    ]);
    $server->update($validated);
    return redirect()
      ->route('servers.index', ['page' => $request->page])
      ->with('success', 'El servidor fue actualizado correctamente.');
  }

  public function destroy(Request $request, Server $server)
  {
    $server->delete();
    return redirect()
      ->route('servers.index', ['page' => $request->page])
      ->with('success', 'Servidor eliminado correctamente');
  }
}
