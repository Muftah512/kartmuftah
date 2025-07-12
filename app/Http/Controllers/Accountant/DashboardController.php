<?php

namespace App\Http\Controllers\Accountant;

use App\Http\Controllers\Controller;
use App\Models\PointOfSale;
use App\Models\Recharge;
use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
public function index()
{
    $user = Auth::user();
    
    $totalPoints = PointOfSale::where('accountant_id', $user->id)->count();
    
    $recentRecharges = Recharge::with('pointOfSale')
        ->where('accountant_id', $user->id)
        ->orderBy('created_at', 'desc')
        ->take(5)
        ->get();
        
    $pendingInvoices = Invoice::where('accountant_id', $user->id)
        ->where('status', 'pending')
        ->count();
        
    $myPoints = PointOfSale::where('accountant_id', $user->id)
        ->orderBy('created_at', 'desc')
        ->take(4)
        ->get();

    return view('accountant.dashboard', compact(
        'totalPoints',
        'recentRecharges',
        'pendingInvoices',
        'myPoints'
    ));
}
}