<?php

namespace App\Http\Controllers;

use App\Models\PowerLog;
use Illuminate\Http\Request;
use App\Models\Server;
use App\Models\GcpMachine;

class PowerLogController extends Controller
{
    public function index(Request $request)
    {
        $logs = PowerLog::with(['powerable', 'user'])
            ->latest()
            ->get();

        return view('power-logs.index', compact('logs'));
    }
}
