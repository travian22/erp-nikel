<?php

namespace App\Http\Controllers;

use App\Models\ApplicationLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ApplicationLogController extends Controller
{
    public function index(Request $request): View
    {
        $query = ApplicationLog::with('user')->latest();

        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('activity', 'like', "%{$search}%");
        }

        $logs = $query->paginate(15)->withQueryString();

        return view('logs.index', compact('logs'));
    }
}
