<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Package;
use App\Models\InternetCard;
use App\Models\PointOfSale;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Invoice;
use App\Models\User;
//use App\Http\Controllers\MikroTikController;
use App\Services\ActivityLogger;
//use App\Models\Transaction;
//use App\Models\InternetCard;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {

    $user = Auth::user();
    
    // الحل المباشر: البحث عن نقطة البيع المرتبطة بالمستخدم
    $pos = PointOfSale::where('accountant_id', $user->id)->first();
    
    // إذا لم نجد، نبحث بالبريد الإلكتروني (كحل بديل)
    if (!$pos) {
        $pos = PointOfSale::where('email', $user->email)->first();
    }
    
    // إذا لم نجد بعد، ننشئ نقطة بيع تلقائياً (فقط في بيئة التطوير)
    if (!$pos && app()->environment('local')) {
        $pos = PointOfSale::create([
            'name' => $user->name,
            'email' => $user->email,
            'password' => bcrypt('temp_password'), 
            'accountant_id' => $user->id,
            'balance' => 0,
            'is_active' => true
        ]);
    }
    
    // إذا استمر عدم العثور، نعرض خطأ
    if (!$pos) {
        return view('pos.error', [
            'message' => 'لم يتم تعيين نقطة بيع لهذا المستخدم. الرجاء التواصل مع المسؤول.'
        ]);
    }

    // الرصيد الحالي (مباشرة من المستخدم)
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
                
            $amount = Transaction::where('user_id', $pos->id)
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