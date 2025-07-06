<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Card;

class SalesController extends Controller
{
    public function index()
    {
        $sales = Card::with('package')
            ->where('pos_id', Auth::id())
            ->latest()
            ->get();

        return view('pos.sales-report', compact('sales'));
    }
}
