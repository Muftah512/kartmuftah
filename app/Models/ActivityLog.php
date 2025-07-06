<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'action', 
        'description', 
        'data', 
        'user_id', 
        'pos_id'
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pointOfSale()
    {
        return $this->belongsTo(PointOfSale::class, 'pos_id');
    }
}