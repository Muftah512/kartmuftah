<?php

namespace App\Http\Controllers\Accountant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Accountant\RechargeRequest;
use App\Models\PointOfSale;
use App\Models\Invoice;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RechargeController extends Controller
{
    /**
     * إظهار نموذج الشحن
     */
    public function create()
    {
        $points = PointOfSale::where('accountant_id', Auth::id())->get();
        return view('accountant.recharges.create', compact('points'));
    }

    /**
     * معالجة طلب الشحن
     */
    public function store(RechargeRequest $request)
    {
        $data = $request->validated();

        // 1. زيادة رصيد نقطة البيع
        $pos = PointOfSale::findOrFail($data['pos_id']);
        $pos->balance += $data['amount'];
        $pos->save();

        // 2. إنشاء فاتورة
        $invoice = Invoice::create([
            'pos_id' => $pos->id,
            'accountant_id'    => Auth::id(),
            'amount'           => $data['amount'],
            'description'      => 'شحن رصيد',
            'status'           => 'paid',
            'due_date'         => Carbon::now(),
        ]);

        // 3. تسجيل المعاملة
        Transaction::create([
            'pos_id' => $pos->id,
            'type'             => 'credit',
            'amount'           => $data['amount'],
            'description'      => 'إضافة رصيد بعد الفاتورة #' . $invoice->id,
            'balance_after'    => $pos->balance,
            'payment_method'   => $data['payment_method'],
            'notes'            => $data['notes'] ?? null,
            'reference_id'     => $invoice->id,
        ]);

        return redirect()
            ->route('accountant.recharges.index')
            ->with('success', 'تم شحن الرصيد بنجاح.');
    }
}
