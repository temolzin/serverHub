<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Database;

class DatabaseController extends Controller
{
    public function index()
  {
    $databases = Database::with('server')->get();
    return view('databases.index', compact('databases'));
  }
}
