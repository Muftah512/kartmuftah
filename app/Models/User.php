<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * الحقول القابلة للإنشاء الشامل.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'point_of_sale_id',
        'is_active',
    ];

    /**
     * الحقول التي يجب إخفاؤها عند التسلسل.
     *
     * @var string[]
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * التحويلات (casts) للحقول.
     *
     * @var array<string,string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active'         => 'boolean',
    ];

    /**
     * القيم الافتراضية للسمات.
     *
     * @var array<string,mixed>
     */
    protected $attributes = [
        'is_active' => true,
    ];

    /**
     * السمات التي يضاف لها accessor تلقائياً.
     *
     * @var string[]
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * علاقة المستخدم بنقطة البيع (POS).
     */
    public function pointOfSale()
    {

    return $this->hasMany(PointOfSale::class, 'accountant_id');
      }
public function recharges()
{
    return $this->hasMany(Recharge::class, 'accountant_id');
}

public function invoices()
{
    return $this->hasMany(Invoice::class, 'accountant_id');
 }
}
