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
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'is_active',
        'role',
        'point_of_sale_id' 
    ];
protected $table = 'users'; // إذا حسابات POS مخزنة في users

    /**
     * الحقول التي يجب إخفاؤها عند التسلسل.
     *
     * @var string[]
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
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
     * علاقة المستخدم بنقاط البيع التي يديرها
     */
public function pointOfSale(): BelongsTo
    {
        return $this->belongsTo(PointOfSale::class, 'point_of_sale_id', 'id');
    }

    public function managedPoints(): HasMany
    {
        return $this->hasMany(PointOfSale::class, 'accountant_id', 'id');
    }
    /**
     * علاقة المستخدم مع عمليات الشحن
     */
    public function recharges(): HasMany
    {
        // يجب أن يكون لديك نموذج Recharge لهذه العلاقة 
        return $this->hasMany(Recharge::class, 'accountant_id');
    }

    /**
     * التحقق من وجود نقطة بيع نشطة للمستخدم
     */
    public function hasActivePOS(): bool
    {
        // استخدم where بدلاً من first للحصول على النقاط النشطة
        return $this->pointOfSale()
                   ->where('status', 'active')
                   ->exists();
    }

    /**
     * علاقة المستخدم مع الفواتير
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'accountant_id');
    }

    /**
     * تحديد ما إذا كان المستخدم لديه دور معين
     */
 public function hasRole($role): bool
{
    return $this->role === $role;
}
}