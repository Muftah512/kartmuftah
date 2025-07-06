<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\InternetCard;
use App\Models\PointOfSale;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\MikroTikController;
use App\Services\ActivityLogger;

class PosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('pos');
    }

    // توليد كرت جديد
    public function generateCard(Request $request)
    {        $request->validate([
            'package_id' => 'required|exists:packages,id'
        ]);

        $user = auth()->user();
        $pos = PointOfSale::find($user->pos_id);
        $package = Package::find($request->package_id);

        // التحقق من الرصيد الكافي
        if ($pos->balance < $package->price) {
            return response()->json([
                'success' => false,
                'message' => 'الرصيد غير كافي لإتمام العملية'
            ], 400);
        }

        // توليد اسم مستخدم فريد
        $username = 'KM' . strtoupper(Str::random(8));
        
        // إنشاء الكرت في MikroTik
            $mikrotik = new MikroTikController();
    if (!$mikrotik->createUser($username, $package->mikrotik_profile, $package->validity_days)) {
        return redirect()->back()
            ->with('error', 'فشل في إنشاء الكرت في النظام. الرجاء المحاولة لاحقًا.');
    }

        // تسجيل الكرت في قاعدة البيانات
        $card = InternetCard::create([
            'username' => $username,
            'package_id' => $package->id,
            'pos_id' => $pos->id,
            'expiration_date' => now()->addDays($package->validity_days),
        ]);

        // خصم المبلغ من رصيد نقطة البيع
        $pos->decrement('balance', $package->price);

        // تسجيل المعاملة
        Transaction::create([
            'type' => 'debit',
            'amount' => $package->price,
            'pos_id' => $pos->id,
            'user_id' => $user->id,
            'description' => 'بيع كرت جديد: ' . $username,
        ]);

        return response()->json([
            'success' => true,
            'card' => $card,
            'username' => $username,
            'package' => $package->name,
            'validity' => $package->validity_days . ' أيام'
        ]);
    }

    // إعادة شحن كرت
  public function rechargeCard(Request $request)
{
    $request->validate([
        'username' => 'required',
        'package_id' => 'required|exists:packages,id'
    ]);

    $user = auth()->user();
    $pos = PointOfSale::find($user->pos_id);
    $package = Package::find($request->package_id);

    // التحقق من وجود المستخدم في MikroTik
    $mikrotik = new MikroTikController();
    if (!$mikrotik->userExists($request->username)) {
        return redirect()->back()
            ->with('error', 'اسم المستخدم غير موجود في النظام.');
    }

        // التحقق من الرصيد الكافي
        if ($pos->balance < $package->price) {
            return response()->json([
                'success' => false,
                'message' => 'الرصيد غير كافي لإتمام العملية'
            ], 400);
        }

        // إعادة الشحن في MikroTik
        $mikrotik = new MikroTikController();
           if (!$mikrotik->rechargeUser($request->username, $package->mikrotik_profile, $package->validity_days)) {
        return redirect()->back()
            ->with('error', 'فشل في إعادة شحن الكرت. الرجاء المحاولة لاحقًا.');
    }            return response()->json([
                'success' => false,
                'message' => 'فشل في إعادة شحن الكرت'
            ], 500);
        }

        // تحديث سجل الكروت
        $card = InternetCard::where('username', $request->username)->first();
        if ($card) {
            $card->update([
                'package_id' => $package->id,
                'expiration_date' => now()->addDays($package->validity_days),
            ]);
        }

        // خصم المبلغ من رصيد نقطة البيع
        $pos->decrement('balance', $package->price);

        // تسجيل المعاملة
        Transaction::create([
            'type' => 'debit',
            'amount' => $package->price,
            'pos_id' => $pos->id,
            'user_id' => $user->id,
            'description' => 'إعادة شحن كرت: ' . $request->username,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم إعادة شحن الكرت بنجاح'
        ]);
    }
}