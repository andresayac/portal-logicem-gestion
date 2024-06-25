<?php

namespace App\Http\Controllers;

use App\Models\DocumentsLog;

class LogsController extends Controller
{
    public function index()
    {
        // check if user is admin
        if (!auth()->user()->is_admin) {
            return redirect()->route('inicio');
        }

        $documents_log = DocumentsLog::orderBy('id', 'desc')->paginate(10);;
        return view('logs.index', compact('documents_log'));
    }

    public function details(DocumentsLog $log)
    {
        // check if user is admin
        if (!auth()->user()->is_admin) {
            return redirect()->route('inicio');
        }

        return view('logs.details', compact('log'));
    }
}
