<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request; // يجب استيراد Request لاستخدامه
use Illuminate\Support\Facades\Auth;
use App\Models\InternetCard;
use App\Models\Package; // تم استيراد نموذج Package
use Carbon\Carbon;

class SalesController extends Controller
{
    public function index(Request $request) // تصحيح: إضافة Request كمعامل للدالة
    {
        $user = Auth::user();

        // التحقق من وجود نقطة بيع مرتبطة بالمستخدم
        $pos = $user->pointOfSale()->first();
        if (!$pos) {
            return redirect()->route('pos.dashboard')->with('error', 'لا توجد نقطة بيع مرتبطة بهذا الحساب.');
        }

        $posId = $pos->id;

        // منطق التصفية
        $query = InternetCard::where('pos_id', $posId);

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        if ($request->filled('package_id')) {
            $query->where('package_id', $request->package_id);
        }

        // جلب بيانات المبيعات (InternetCard)
        $sales = $query->with('package')
                       ->orderByDesc('created_at')
                       ->paginate(15); // استخدام paginate للتقسيم

        // جلب جميع الباقات لتمريرها إلى نموذج التصفية
        $packages = Package::all();
        
        // حساب إجمالي المبيعات
        $totalSales = $sales->sum(function ($card) {
            return $card->package->price ?? 0;
        });

        return view('pos.sales.index', compact('sales', 'packages', 'totalSales'));
    }
}
