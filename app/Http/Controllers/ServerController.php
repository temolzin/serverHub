<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Server;

class ServerController extends Controller
{
  public function index()
  {
    $servers = Server::all();
    return view('servers.index', compact('servers'));
  }

  public function create()
  {
    return view('servers.create');
  }

  public function store(Request $request) {}

  public function show($id)
  {
    return view('servers.show');
  }

  public function edit($id)
  {
    return view('servers.edit');
  }

  public function update(Request $request, $id) {}

  public function destroy($id) {}
}
