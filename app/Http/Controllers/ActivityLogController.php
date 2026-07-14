<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;

class ActivityLogController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin|owner');
    }

    public function index()
    {
        $title = 'Log Aktivitas';
        $logs = ActivityLog::with('user')
            ->orderByDesc('created_at')
            ->paginate(25);

        return view('activity-logs.index', compact('title', 'logs'));
    }
}
