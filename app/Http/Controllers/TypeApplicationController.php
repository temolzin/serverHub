<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TypeApplication;

class TypeApplicationController extends Controller
{
  public function index()
  {
    $typeApplications = TypeApplication::all();
    return view('type-application.index', compact('typeApplications'));
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
