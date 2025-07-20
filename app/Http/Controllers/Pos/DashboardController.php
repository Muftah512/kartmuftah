<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use App\Models\InternetCard;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $pos = $user->pointOfSale;
        
        // الرصيد الحالي
        $balance = $pos->balance;
        
        // إحصائيات اليوم
        $today = Carbon::today();
        $todayCards = InternetCard::where('pos_id', $pos->id)
            ->whereDate('created_at', $today)
            ->count();
            
        $todaySales = Transaction::where('pos_id', $pos->id)
            ->where('type', 'debit')
            ->whereDate('created_at', $today)
            ->sum('amount');
            
        // آخر 5 معاملات
        $recentTransactions = Transaction::with('user')
            ->where('pos_id', $pos->id)
            ->latest()
            ->take(5)
            ->get();
            
        // آخر 5 كروت مولدة
        $recentCards = InternetCard::with('package')
            ->where('pos_id', $pos->id)
            ->latest()
            ->take(5)
            ->get();
            
        // إحصائيات الأسبوع
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        
        $weeklyData = [];
        for ($i = 0; $i < 7; $i++) {
            $date = $startOfWeek->copy()->addDays($i);
            $dayName = $date->locale('ar')->dayName;
            
            $count = InternetCard::where('pos_id', $pos->id)
                ->whereDate('created_at', $date)
                ->count();
                
            $amount = Transaction::where('pos_id', $pos->id)
                ->where('type', 'debit')
                ->whereDate('created_at', $date)
                ->sum('amount');
                
            $weeklyData[] = [
                'day' => $dayName,
                'date' => $date->format('Y-m-d'),
                'cards' => $count,
                'sales' => $amount
            ];
        }
        
        return view('pos.dashboard', [
            'balance' => $balance,
            'todayCards' => $todayCards,
            'todaySales' => $todaySales,
            'recentTransactions' => $recentTransactions,
            'recentCards' => $recentCards,
            'weeklyData' => $weeklyData,
            'startDate' => $startOfWeek->format('Y-m-d'),
            'endDate' => $endOfWeek->format('Y-m-d')
        ]);
    }
}