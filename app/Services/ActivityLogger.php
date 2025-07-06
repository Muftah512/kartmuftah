<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    public static function log($action, $description, $data = [], $posId = null)
    {
        ActivityLog::create([
            'action' => $action,
            'description' => $description,
            'data' => $data,
            'user_id' => Auth::id(),
            'pos_id' => $posId ?? (Auth::user()->pos_id ?? null),
        ]);
    }
}