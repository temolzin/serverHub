<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OwnerController extends Controller
{
  public function index()
  {
    return view('content.table-owner.index');
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
