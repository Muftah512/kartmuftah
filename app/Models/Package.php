<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    /**
     * ÇבÍÞזב ÇבדÓדזÍ ÊÚÈÆÊוÇ ÈÇבÌדבÉ.
     */
    protected $fillable = [
        'name',
        'price',
        'size_mb',
        'validity_days',
        'mikrotik_profile',
    ];
}
