<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\InternetCard;
use Carbon\Carbon;

class SalesController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $posId = $user->pos_id;
        
        // إحصائيات اليوم
        $today = Carbon::today();
        $todayCards = InternetCard::where('pos_id', $posId)
            ->whereDate('created_at', $today)
            ->count();
            
        $todaySales = InternetCard::where('pos_id', $posId)
            ->whereDate('created_at', $today)
            ->with('package')
            ->get()
            ->sum('package.price');
            
        // إحصائيات الشهر
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        
        $monthlyCards = InternetCard::where('pos_id', $posId)
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->count();
            
        $monthlySales = InternetCard::where('pos_id', $posId)
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->with('package')
            ->get()
            ->sum('package.price');
            
        // أفضل 5 باقات مبيعاً
        $topPackages = InternetCard::where('pos_id', $posId)
            ->selectRaw('package_id, count(*) as total')
            ->with('package')
            ->groupBy('package_id')
            ->orderByDesc('total')
            ->take(5)
            ->get();
            
        return view('pos.sales.index', [
            'todayCards' => $todayCards,
            'todaySales' => $todaySales,
            'monthlyCards' => $monthlyCards,
            'monthlySales' => $monthlySales,
            'topPackages' => $topPackages
        ]);
    }
}