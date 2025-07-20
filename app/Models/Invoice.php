<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    protected $fillable = [
        'pos_id', // <--- تم تغيير هذا من 'point_of_sale_id' إلى 'pos_id' ليتوافق مع المايجريشن
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

    /**
     * Get the point of sale that owns the Invoice.
     * علاقة الفاتورة مع نقطة البيع
     */
    public function pointOfSale(): BelongsTo
    {
        // تم تحديد المفتاح الأجنبي 'pos_id' صراحة هنا ليتوافق مع المايجريشن
        // هذا يحل مشكلة "Attempt to read property 'name' on null" المتعلقة بنقطة البيع
        return $this->belongsTo(PointOfSale::class, 'pos_id');
    }

    /**
     * Get the accountant (User) that owns the Invoice.
     * علاقة الفاتورة مع المحاسب (المستخدم)
     */
    public function accountant(): BelongsTo
    {
        // هذه العلاقة كانت صحيحة بالفعل، حيث تم تحديد المفتاح الأجنبي 'accountant_id' صراحة
        return $this->belongsTo(User::class, 'accountant_id');
    }

    /**
     * Get the status of the invoice as a readable string.
     * دالة لتحديد حالة الفاتورة بشكل نصي
     */
    public function getStatusTextAttribute(): string
    {
        return [
            'pending' => 'معلقة',
            'paid' => 'مدفوعة',
            'overdue' => 'متأخرة'
        ][$this->status] ?? 'غير معروف';
    }

    /**
     * Get the payment method of the invoice as a readable string.
     * دالة لتحديد طريقة الدفع بشكل نصي
     */
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

public function transactions()
{
return $this->hasMany(Transaction::class);
}
}

