<?php

namespace App\Http\Controllers\Accountant;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\RechargeTransaction;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = RechargeTransaction::with('pos')
            ->where('accountant_id', Auth::id())
            ->latest()
            ->get();

        return view('accountant.transactions', compact('transactions'));
    }
}
