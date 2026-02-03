<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Owner;

class OwnerController extends Controller
{
  public function index()
  {
    $owners = Owner::whereNull('deleted_at')->get();
    return view('owners.index', compact('owners'));
  }

  public function create()
  {
    return view('content.table-owner.create');
  }

  public function store(Request $request) {}

  public function edit($id)
  {
    return view('content.table-owner.edit');
  }

  public function update(Request $request, $id) {}

  public function destroy($id) {}
}
