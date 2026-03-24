<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AuditLog;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $logs = AuditLog::whereIn('action', ['update', 'delete'])
            ->latest()
            ->paginate(10);

        return view('audit_logs.index', compact('logs'));
    }
}
