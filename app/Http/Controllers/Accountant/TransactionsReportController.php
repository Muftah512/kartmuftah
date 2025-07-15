<?php

namespace App\Http\Controllers\Accountant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TransactionsReportController extends Controller
{
    public function index()
    {
        return view('accountant.reports.transactions');
    }
}
