<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TypeApplicationController extends Controller
{
  public function index()
  {
    return view('type-application.index');
  }

  public function create()
  {
    return view('type-application.create');
  }

  public function store(Request $request) {}

  public function edit($id)
  {
    return view('type-application.edit');
  }

  public function update(Request $request, $id) {}

  public function destroy($id) {}
}
