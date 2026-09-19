<?php

namespace App\Services;

use App\Models\ApplicationLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLogger
{
    /**
     * Catat aktivitas ke tabel application_logs.
     */
    public static function log(
        string $activity,
        string $module,
        ?string $referenceTable = null,
        ?int $referenceId = null,
        ?int $userId = null
    ): ApplicationLog {
        return ApplicationLog::create([
            'user_id' => $userId ?? Auth::id(),
            'activity' => $activity,
            'module' => $module,
            'reference_table' => $referenceTable,
            'reference_id' => $referenceId,
            'ip_address' => Request::ip(),
            'created_at' => now(),
        ]);
    }
}
