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
        'supervisor_id'
    ];

public function scopeActive($query)
{
    return $query->where('is_active', 1);
}

    public function cards(): HasMany
    {
        return $this->hasMany(Card::class);
    }

    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'pos_id');
    }
}
