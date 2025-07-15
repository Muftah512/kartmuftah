<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    protected $fillable = [
        'pos_id',
        'accountant_id',
        'amount',
        'description',
        'due_date',
        'status',
        'payment_method',
        'paid_at',
        'reference_number',
        'notes'
    ];

    protected $casts = [
        'due_date' => 'date',
        'paid_at' => 'datetime',
        'amount' => 'float'
    ];

    // علاقة الفاتورة مع نقطة البيع
    public function pointOfSale(): BelongsTo
    {
        return $this->belongsTo(PointOfSale::class);
    }

    // علاقة الفاتورة مع المحاسب
    public function accountant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'accountant_id');
    }

    // دالة لتحديد حالة الفاتورة بشكل نصي
    public function getStatusTextAttribute(): string
    {
        return [
            'pending' => 'معلقة',
            'paid' => 'مدفوعة',
            'overdue' => 'متأخرة'
        ][$this->status] ?? 'غير معروف';
    }

    // دالة لتحديد حالة الدفع بشكل نصي
    public function getPaymentMethodTextAttribute(): string
    {
        return match($this->payment_method) {
            'cash' => 'نقدي',
            'bank_transfer' => 'تحويل بنكي',
            'card' => 'بطاقة',
            'vodafone_cash' => 'فودافون كاش',
            'e_payment' => 'دفع إلكتروني',
            default => 'غير محدد'
        };
    }
}