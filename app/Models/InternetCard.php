<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PointOfSale;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InternetCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'username',
        'package_id',
        'pos_id',
        'status',
        'expires_at',
        'customer_phone'
    ];

    protected $casts = [
        'expires_at' => 'datetime'
    ];

     public function pos(): BelongsTo
    {
        return $this->belongsTo(PointOfSale::class, 'pos_id');
    }

    // علاقة الكرت بالباقة
    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    // علاقة الكرت بنقطة البيع
    public function pointOfSale()
    {
        return $this->belongsTo(PointOfSale::class, 'pos_id');
    }

    // التحقق من انتهاء الصلاحية
    public function getIsExpiredAttribute()
    {
        return $this->expires_at->isPast();
    }

    // تنسيق رقم العميل مع +967
    public function getFormattedPhoneAttribute()
    {
        if (!$this->customer_phone) return null;
        
        return '+967' . $this->customer_phone;
    }
}
