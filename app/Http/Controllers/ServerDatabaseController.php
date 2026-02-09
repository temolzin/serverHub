<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ServerDatabase;

class ServerDatabaseController extends Controller
{
    public function index()
    {
        $databases = ServerDatabase::with('server')->get();
        return view('databases.index', compact('databases'));
    }
}
