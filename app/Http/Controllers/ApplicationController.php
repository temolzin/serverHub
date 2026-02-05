<?php

namespace App\Http\Controllers;

use App\Models\Application;

class ApplicationController extends Controller
{
  public function index()
  {
    $applications = Application::with(['owner', 'server'])->get();
    return view('applications.index', compact('applications'));
  }

  public function create()
  {
    return view('applications.create');
  }

  public function store() {}

  public function edit($id)
  {
    return view('applications.edit');
  }

  public function update() {}

  public function destroy() {}
}
