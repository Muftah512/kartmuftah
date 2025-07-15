<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Recharge extends Model
{
    protected $fillable = [
        'pos_id',
        'accountant_id',
        'amount',
        'payment_method',
        'notes',
        'reference_number'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

public function pointOfSale()
{
    return $this->belongsTo(PointOfSale::class)->withDefault([
        'name' => 'غير معروف',
        'location' => '-'
    ]);
}


    // علاقة الشحن مع المحاسب
    public function accountant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'accountant_id');
    }

    // دالة لتحديد طريقة الدفع بشكل نصي
    public function getPaymentMethodTextAttribute(): string
    {
        return match($this->payment_method) {
            'cash' => 'نقدي',
            'bank_transfer' => 'تحويل بنكي',
            'card' => 'بطاقة',
            'vodafone_cash' => 'فودافون كاش',
            'e_payment' => 'دفع إلكتروني',
            default => 'غير معروف'
        };
    }
}