<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log; // تأكد من استيراد Log

class CheckRole
{
    /**
     * التعامل مع طلب وارد.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @param  string   $role
     * @return Response
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        // إذا لم يكن المستخدم مسجلاً دخوله
        if (!$user) {
            Log::warning('Unauthenticated access attempt: User not logged in.');
            return redirect()->route('login')->with('error', 'يجب تسجيل الدخول أولاً');
        }

        // التحقق من صلاحية الوصول للدور المطلوب
        if (!$user->hasRole($role)) {
            Log::warning('Unauthorized access attempt:', [
                'user_id' => $user->id,
                'user_email' => $user->email, // إضافة البريد الإلكتروني للمزيد من الوضوح
                'user_roles' => $user->getRoleNames()->implode(', '), // استخدام getRoleNames() من Spatie
                'required_role' => $role,
                'path' => $request->path(), // إضافة المسار الذي حاول المستخدم الوصول إليه
            ]);

            return redirect()->route('home')->with('error', 'ليس لديك صلاحية الوصول لهذه الصفحة');
        }

        // -- التحقق الخاص بنقاط البيع (POS) --
        // يتم تنفيذ هذا الكود فقط إذا كان الدور المطلوب هو 'pos'
        if ($role === 'pos') {
            // احصل على أول نقطة بيع مرتبطة بالمستخدم
            // تأكد أن علاقة pointOfSale في نموذج User ترجع HasOne أو HasMany
            // إذا كانت HasMany، فـ first() سيعطيك أول واحدة.
            $pos = $user->pointOfSale->first();

            // إذا لم توجد نقطة بيع أو كانت غير نشطة
            if (!$pos || $pos->status !== 'active') {
                Log::warning('Inactive or non-existent POS access attempt:', [
                    'user_id' => $user->id,
                    'user_email' => $user->email,
                    'pos_id' => $pos->id ?? 'none',
                    'pos_status' => $pos->status ?? 'none',
                    'required_role' => $role,
                    'path' => $request->path(),
                ]);

                auth()->logout();
                return redirect()->route('login')->with('error', 'نقطة البيع غير نشطة أو غير موجودة. تم تسجيل خروجك.');
            }
        }

        return $next($request);
    }
}
