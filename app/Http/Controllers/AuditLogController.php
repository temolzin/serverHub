<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $logs = AuditLog::whereIn('action', ['update', 'delete'])
            ->latest()
            ->get();

        return view('audit_logs.index', compact('logs'));
    }

    public function modal(AuditLog $audit_log, string $type)
    {
        $audit_log->load('user');

        return match ($type) {
            'show' => view('audit_logs.show', compact('audit_log'))->render(),
            default => response('Not found', 404),
        };
    }
}
