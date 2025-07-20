<?php

namespace App\Http\Controllers\Accountant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Accountant\RechargeRequest;
use App\Models\PointOfSale;
use App\Models\Invoice;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
// استيراد ضروري لتصدير الإكسل (إذا أردت استخدامه في المستقبل)
// use Maatwebsite\Excel\Facades\Excel;
// use App\Exports\RechargesExport;

class RechargeController extends Controller
{
    /**
     * Display a listing of the resource (recharges/invoices).
     */
    public function index()
    {
        // جلب فواتير الشحن مع التجزئة
        $recharges = Invoice::with(['pointOfSale', 'accountant'])
                            ->where('description', 'شحن رصيد')
                            ->where('accountant_id', Auth::id())
                            ->orderBy('created_at', 'desc')
                            ->paginate(10); // 10 عناصر لكل صفحة

        // نصائح التطوير المستقبلي:
        // 1. إضافة فلترة حسب نقطة البيع
        // $recharges = $this->applyFilters($recharges);
        // 2. إعداد خيارات التصدير
        // $this->setupExportOptions();

        return view('accountant.recharges.index', compact('recharges'));
    }

    /**
     * Show the form for creating a new recharge.
     */
    public function create()
    {
        $points = PointOfSale::where('accountant_id', Auth::id())->get();
        
        // نصائح التطوير المستقبلي:
        // إضافة اختيار المحاسبين إذا سمحت الصلاحيات
        // if(Auth::user()->can('assign_accountants')) {
        //     $accountants = User::where('role', 'accountant')->get();
        //     return view('accountant.recharges.create', compact('points', 'accountants'));
        // }
        
        return view('accountant.recharges.create', compact('points'));
    }

    /**
     * Store a newly created recharge in storage.
     */
    public function store(RechargeRequest $request)
    {
        $data = $request->validated();

        $pos = PointOfSale::findOrFail($data['pos_id']);
        $pos->balance += $data['amount'];
        $pos->save();

        $invoice = Invoice::create([
            'pos_id'         => $pos->id,
            'accountant_id'  => Auth::id(),
            'amount'         => $data['amount'],
            'description'    => 'شحن رصيد',
            'status'         => 'paid',
            'due_date'       => Carbon::now(),
        ]);

        Transaction::create([
            'pos_id'          => $pos->id,
            'type'            => 'credit',
            'amount'          => $data['amount'],
            'description'     => 'إضافة رصيد بعد الفاتورة #' . $invoice->id,
            'balance_after'   => $pos->balance,
            'payment_method'  => $data['payment_method'],
            'notes'           => $data['notes'] ?? null,
            'reference_id'    => $invoice->id,
            'user_id'         => Auth::id(),
        ]);

        // نصائح التطوير المستقبلي:
        // إرسال إشعار لنقطة البيع بالشحن الجديد
        // $this->sendRechargeNotification($pos, $data['amount']);

        return redirect()
            ->route('accountant.recharges.index') // تم التعديل للعودة للقائمة بدلاً من نموذج الإنشاء
            ->with([
                'success' => 'تم شحن الرصيد بنجاح.',
                'new_balance' => $pos->balance,
                'pos_id' => $pos->id
            ]);
    }

    /**********************************************************************
     * 
     * نصائح التطوير المستقبلية (يمكن تفعيلها عند الحاجة)
     * 
     **********************************************************************/
    
    /**
     * تطبيق الفلاتر على قائمة الشحنات
     */
    /*
    protected function applyFilters($query)
    {
        // فلترة حسب نقطة البيع
        if(request('pos_id')) {
            $query->where('pos_id', request('pos_id'));
        }
        
        // فلترة حسب طريقة الدفع
        if(request('payment_method')) {
            $query->whereHas('transactions', function($q) {
                $q->where('payment_method', request('payment_method'));
            });
        }
        
        // فلترة حسب التاريخ
        if(request('from_date') && request('to_date')) {
            $query->whereBetween('created_at', [
                Carbon::parse(request('from_date'))->startOfDay(),
                Carbon::parse(request('to_date'))->endOfDay()
            ]);
        }
        
        return $query;
    }
    */
    
    /**
     * حذف عملية شحن
     */
    /*
    public function destroy($id)
    {
        $recharge = Invoice::findOrFail($id);
        
        // التراجع عن الشحن
        $pos = $recharge->pointOfSale;
        $pos->balance -= $recharge->amount;
        $pos->save();
        
        // حذف الفاتورة والمعاملة المرتبطة
        $recharge->transactions()->delete();
        $recharge->delete();
        
        return redirect()->route('accountant.recharges.index')
                         ->with('success', 'تم حذف عملية الشحن بنجاح');
    }
    */
    
    /**
     * تصدير الشحنات إلى Excel
     */
    /*
    public function export()
    {
        $recharges = Invoice::with(['pointOfSale', 'accountant'])
                            ->where('description', 'شحن رصيد')
                            ->where('accountant_id', Auth::id())
                            ->orderBy('created_at', 'desc')
                            ->get();
        
        return Excel::download(new RechargesExport($recharges), 'recharges-'.now()->format('Y-m-d').'.xlsx');
    }
    */
    
    /**
     * إرسال إشعار بالشحن الجديد
     */
    /*
    protected function sendRechargeNotification($pos, $amount)
    {
        // إرسال إشعار للمدير
        if($pos->manager) {
            Notification::send($pos->manager, new BalanceRechargedNotification($pos, $amount));
        }
        
        // إرسال إشعار لمالك نقطة البيع
        if($pos->owner) {
            Notification::send($pos->owner, new BalanceRechargedNotification($pos, $amount));
        }
        
        // إرسال رسالة SMS
        if($pos->phone) {
            SMS::send($pos->phone, "تم شحن رصيدك بمبلغ {$amount} ريال. الرصيد الجديد: {$pos->balance} ريال");
        }
    }
    */
    
    /**
     * عرض تقرير إحصائي عن الشحنات
     */
    /*
    public function report()
    {
        $stats = [
            'total_recharges' => Invoice::where('description', 'شحن رصيد')
                                        ->where('accountant_id', Auth::id())
                                        ->sum('amount'),
            'monthly_recharges' => Invoice::where('description', 'شحن رصيد')
                                          ->where('accountant_id', Auth::id())
                                          ->whereMonth('created_at', now()->month)
                                          ->sum('amount'),
            'top_pos' => PointOfSale::withSum(['invoices as total_recharged' => function($query) {
                $query->where('description', 'شحن رصيد');
            }], 'amount')
            ->orderByDesc('total_recharged')
            ->first()
        ];
        
        return view('accountant.recharges.report', compact('stats'));
    }
    */
}