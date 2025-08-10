<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PointOfSale;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InternetCard extends Model
{
    use HasFactory;

    /**
     * الحقول المسموح تعبئتها بالجملة.
     */
    protected $fillable = [
        'username',
        'package_id',
        'pos_id',
        'status',
        'expiration_date',  // تم التعديل لتوحيد التسمية مع العمود في قاعدة البيانات
        'customer_phone',
    ];

    /**
     * التحويلات (Casts) للحقل.
     */
    protected $casts = [
        'expiration_date' => 'datetime',
    ];

    /**
     * علاقة الكرت بنقطة البيع.
     */
    public function pos(): BelongsTo
    {
        return $this->belongsTo(PointOfSale::class, 'pos_id');
    }
public function pointOfSale()
{
    return $this->belongsTo(PointOfSale::class, 'pos_id');
}

    /**
     * علاقة الكرت بالباقة.
     */
    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * خاصية تحقّق انتهاء الصلاحية.
     */
    public function getIsExpiredAttribute(): bool
    {
        return $this->expiration_date->isPast();
    }

    /**
     * تنسيق رقم العميل مع رمز الدولة.
     */
    public function getFormattedPhoneAttribute(): ?string
    {
        if (!$this->customer_phone) {
            return null;
        }

        return '+967' . $this->customer_phone;
    }
}
