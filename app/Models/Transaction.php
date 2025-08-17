<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $fillable = [
        'type',
        'amount',
        'pos_id',
        'user_id',
        'description',
        'balance_after',
        'payment_method',
        'notes',
        'invoice_id',
        'reference_id'

    ];

    public function pos(): BelongsTo
    {
        return $this->belongsTo(PointOfSale::class, 'pos_id');
    }
    public function pointOfSale()
    {
        return $this->pos();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
