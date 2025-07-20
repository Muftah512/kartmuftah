<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class CheckRole
{
    /**
     * Handle an incoming request.
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
        Log::warning('Unauthenticated access attempt');
        return redirect()->route('login')->with('error', 'يجب تسجيل الدخول أولاً');
    }

    // التحقق من صلاحية الوصول للدور المطلوب
    if (!$user->hasRole($role)) {
        Log::warning('Unauthorized access attempt', [
            'user_id' => $user->id,
            'user_roles' => $user->roles->pluck('name')->implode(', '), // يعرض كل الأدوار
            'required_role' => $role
        ]);
        
        return redirect()->route('home')->with('error', 'ليس لديك صلاحية الوصول لهذه الصفحة');
    }

    // -- التحقق الخاص بنقاط البيع (POS) --
    // يتم تنفيذ هذا الكود فقط إذا كان الدور المطلوب هو 'pos'
    if ($role === 'pos') {
        // احصل على أول نقطة بيع مرتبطة بالمستخدم
        $pos = $user->pointOfSale->first();

        // إذا لم توجد نقطة بيع أو كانت غير نشطة
        if (!$pos || $pos->status !== 'active') {
            Log::warning('Inactive or non-existent POS access attempt', [
                'user_id' => $user->id,
                'pos_id' => $pos->id ?? 'none',         // التصحيح الأول: نحصل على المعرف من الكائن
                'pos_status' => $pos->status ?? 'none'  // التصحيح الثاني: نحصل على الحالة من الكائن
            ]);
            
            auth()->logout();
            return redirect()->route('login')->with('error', 'نقطة البيع غير نشطة أو غير موجودة');
        }
    

    return $next($request);
}

}
}
