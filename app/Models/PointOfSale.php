<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PointOfSale extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
        'balance',
        'phone',
        'accountant_id',
        'is_active',
        'email',
        'password',
        'user_id',
    ];

    /**
     * نطاق استعلام لإحضار النقاط النشطة
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id')
                    ->withDefault([ 'name' => 'غير معروف' ]);
    }

    /**
     * علاقة 1 - N مع البطاقات
     */
    public function cards(): HasMany
    {
        return $this->hasMany(Card::class);
    }
public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}

    /**
     * ربط نقطة البيع بالمحاسب (User) عبر accountant_id
     */
    public function accountant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'accountant_id');
    }

    /**
     * علاقة 1 - N مع الفواتير (إن وجدت)
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * علاقة 1 - N مع المعاملات
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'pos_id');
    }
}
