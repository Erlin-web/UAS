<?php

namespace App\Http\Controllers;

use App\Models\LogActivity;
use App\Models\LogDatabase;
use App\Models\LogError;
use Illuminate\Http\Request;

class LogController extends Controller
{
     public function activity(Request $request)
    {
        $logs = LogActivity::orderBy('tanggal', 'desc')->paginate(25);
        return response()->json($logs);
    }

    public function error()
    {
        $logs = LogError::orderBy('tanggal', 'desc')->paginate(25);
        return response()->json($logs);
    }

    public function database()
    {
        $logs = LogDatabase::orderBy('tanggal', 'desc')->paginate(10);
        return response()->json($logs);
    }
}