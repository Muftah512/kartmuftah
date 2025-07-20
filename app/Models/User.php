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
use Illuminate\Database\Eloquent\Relations\HasMany; // إضافة هذا الاستيراد
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
        'user_id', // <--- تم حذف هذا! المستخدم لا ينتمي لنقطة بيع واحدة كـ FK
        'is_active',
           'role' 
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
     * علاقة المستخدم بنقاط البيع التي يديرها (User is an accountant for multiple POS).
     */
    public function pointOfSale(): HasMany // تحديد نوع العلاقة صراحة
    {
        // المستخدم (المحاسب) لديه (hasMany) العديد من نقاط البيع، حيث 'accountant_id'
        // في جدول 'point_of_sales' هو المفتاح الأجنبي الذي يشير إلى هذا المستخدم.
        return $this->hasMany(PointOfSale::class, 'accountant_id');
    }

    /**
     * علاقة المستخدم (المحاسب) مع عمليات الشحن (Recharges) التي قام بها.
     * بما أنك لا تملك موديل 'Recharge' منفصل، فقد تشير هذه العلاقة إلى 'Invoice'
     * إذا كانت 'Recharge' هي في الأساس فواتير شحن.
     */
    public function recharges(): HasMany // تحديد نوع العلاقة صراحة
    {
        // افتراضًا أن 'Recharge' غير موجود كموديل منفصل، هذه العلاقة قد تكون مضللة.
        // إذا كانت عمليات الشحن تسجل كفواتير، فربما يجب أن تكون:
        return $this->hasMany(Invoice::class, 'accountant_id')->where('description', 'شحن رصيد');
        // أو إذا كان لديك موديل 'Recharge' فعليًا، فتأكد من وجوده ومساره.
    }

 public function hasActivePOS(): bool
{
    $pos = $this->pointOfSale->first();
    return $pos && $pos->status == 'active';
}


    /**
     * علاقة المستخدم (المحاسب) مع الفواتير (Invoices) التي أنشأها/مرتبطة به.
     */
    public function invoices(): HasMany // تحديد نوع العلاقة صراحة
    {
        // المستخدم (المحاسب) لديه (hasMany) العديد من الفواتير، حيث 'accountant_id'
        // في جدول 'invoices' هو المفتاح الأجنبي الذي يشير إلى هذا المستخدم.
        return $this->hasMany(Invoice::class, 'accountant_id');
    }
}

