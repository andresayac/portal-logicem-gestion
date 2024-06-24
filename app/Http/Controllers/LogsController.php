<?php

namespace App\Http\Controllers;

use App\Models\DocumentsLog;

class LogsController extends Controller
{
    public function index()
    {
        $documents_log = DocumentsLog::orderBy('id', 'desc')->paginate(10);;
        return view('logs.index', compact('documents_log'));
    }

    public function details(DocumentsLog $log)
    {
        return view('logs.details', compact('log'));
    }
}
