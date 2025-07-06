<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Card extends Model
{
    protected $fillable = [
        'username',
        'package_id',
        'pos_id',
        'status',
        'expires_at'
    ];

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function pos(): BelongsTo
    {
        return $this->belongsTo(PointOfSale::class);
    }
}
