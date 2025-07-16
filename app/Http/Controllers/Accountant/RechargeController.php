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

    $pos = PointOfSale::findOrFail($data['pos_id']);
    $pos->balance += $data['amount'];
    $pos->save();

    // استخدم 'pos_id' بدلاً من 'point_of_sale_id'
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

    // توجيه إلى صفحة إنشاء شحن جديدة مع رسالة نجاح
    return redirect()
        ->route('accountant.recharges.create')
        ->with([
            'success' => 'تم شحن الرصيد بنجاح',
            'new_balance' => $pos->balance,
            'pos_id' => $pos->id
        ]);
}
}
