<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    /**
     * ������ ������� ������� �������.
     */
protected $fillable = [
  'name','price','validity_days','size_mb','status',
  'download_speed','upload_speed','device_limit',
  'mikrotik_profile',
];
    /**
     * ��������� (Casts) ����� features �������� �������.
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
