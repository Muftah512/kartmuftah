<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    /**
     * ÇáÍÞæá ÇáãÓãæÍ ÊÚÈÆÊåÇ ÈÇáÌãáÉ.
     */
protected $fillable = [
  'name','price','validity_days','size_mb','status',
  'download_speed','upload_speed','device_limit',
  'mikrotik_profile',
];
    /**
     * ÇáÊÍæíáÇÊ (Casts) ááÍÞá features æÇáÈæÇÚË ÇáÑÞãíÉ.
     */
    protected $casts = [
        'features'       => 'array',
        'size_mb'        => 'integer',
        'validity_days'  => 'integer',
        'download_speed' => 'integer',
        'upload_speed'   => 'integer',
        'device_limit'   => 'integer',
        'price'          => 'decimal:2',
    ];
}
