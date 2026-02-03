<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GcpMachine;

class GcpMachineController extends Controller
{
  public function index()
  {
    $gcpMachines = GcpMachine::with('owner')->get();
    return view('gcp-machines.index', compact('gcpMachines'));
  }

  public function create()
  {
    return view('content.table-gcp-machines.create');
  }

  public function store(Request $request) {}

  public function edit($id)
  {
    return view('content.table-gcp-machines.edit');
  }

  public function update(Request $request, $id) {}

  public function destroy($id) {}
}
