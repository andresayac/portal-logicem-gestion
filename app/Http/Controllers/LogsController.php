<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Hana;
use App\Models\DocumentsLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
use App\Traits\SapApi;
use Illuminate\Support\Facades\Auth;

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
