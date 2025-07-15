<?php

namespace App\Http\Controllers\Accountant;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\RechargeTransaction;

class TransactionController extends Controller
{
public function index()
{
    $recharges = Transaction::with('pointOfSale','user')
                     ->where('type','credit')
                     ->latest()
                     ->paginate(10);

    return view('accountant.recharges.index', compact('recharges'));
}
}
