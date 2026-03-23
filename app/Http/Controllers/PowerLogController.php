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
            ->when($request->search, function ($query) use ($request) {
                $search = $request->search;

                $query->where(function ($q) use ($search) {

                    $q->where('motive', 'like', "%{$search}%");

                    if (is_numeric($search)) {
                        $q->orWhere('id', $search);
                    }

                    $q->orWhereHas('user', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%");
                    });

                    $q->orWhereHasMorph(
                        'powerable',
                        [
                            GcpMachine::class,
                            Server::class
                        ],
                        function ($q3, $type) use ($search) {

                            if ($type === GcpMachine::class) {
                                $q3->where('machine_name', 'like', "%{$search}%");
                            }

                            if ($type === Server::class) {
                                $q3->where('hostname_internal', 'like', "%{$search}%");
                            }
                        }
                    );

                    if (is_numeric($search)) {
                        $q->orWhereHasMorph(
                            'powerable',
                            [
                                GcpMachine::class,
                                Server::class
                            ],
                            function ($q3) use ($search) {
                                $q3->where('id', $search);
                            }
                        );
                    }

                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('power-logs.index', compact('logs'));
    }
}
