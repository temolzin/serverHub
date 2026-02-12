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
    $ip = $request->ip;
    $exclude = $request->exclude;
    $exists = Server::where('primary_ip_address', $ip)
      ->when($exclude, fn($q) => $q->where('id', '!=', $exclude))
      ->exists();
    return response()->json(['exists' => $exists]);
  }

  public function index(Request $request)
  {
    $query = Server::with(['owner', 'typeApplication']);

    if ($request->search) {
      $search = $request->search;
      $query->where(function ($q) use ($search) {
        $q->where('primary_ip_address', 'like', "%$search%")
          ->orWhere('dns_name', 'like', "%$search%")
          ->orWhere('hostname_internal', 'like', "%$search%")
          ->orWhereHas('owner', function ($sub) use ($search) {
            $sub->where('name', 'like', "%$search%");
          });
      });
    }
    $servers = $query->latest()->paginate(10);
    $owners = Owner::orderBy('name')->get();
    $typeApplications = TypeApplication::orderBy('name_application')->get();

    if ($request->ajax()) {

      $owners = Owner::orderBy('name')->get();
      $typeApplications = TypeApplication::orderBy('name_application')->get();
      return response()->json([
        'table' => view('servers.search', compact('servers', 'owners', 'typeApplications'))->render(),
        'pagination' => $request->search
          ? ''
          : (string) $servers->links('pagination::bootstrap-5')
      ]);
    }
    return view('servers.index', compact('servers', 'owners', 'typeApplications'));
  }

  public function create()
  {
    return view('servers.create');
  }

  public function store(Request $request)
  {
    $request->validate([
      'primary_ip_address' => 'required|unique:servers,primary_ip_address',
      'owner_id' => 'required',
      'type_application_id' => 'required'
    ]);
    Server::create($request->all());
    return redirect()->route('servers.index')
      ->with('success', 'Servidor creado correctamente');
  }

  public function edit($id)
  {
    $owners = Owner::orderBy('name')->get();
    $typeApplications = TypeApplication::orderBy('name_application')->get();
    return view('servers.edit', compact(
      'server',
      'owners',
      'typeApplications'
    ));
  }

  public function update(Request $request, Server $server)
  {
    $request->validate([
      'primary_ip_address' => 'required|unique:servers,primary_ip_address,' . $server->id,
      'owner_id' => 'required',
      'type_application_id' => 'required'
    ]);
    $server->update($request->all());
    return redirect()->route('servers.index')
      ->with('success', 'Servidor actualizado correctamente');
  }

  public function destroy(Server $server)
  {
    $server->delete();
    return redirect()->route('servers.index')
      ->with('success', 'Servidor eliminado correctamente');
  }
}
