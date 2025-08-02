<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\InternetCard;
use App\Models\PointOfSale;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Services\MikroTikService;

class PosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('pos');
    }

    // توليد كرت جديد
    public function generateCard(Request $request)
    {
        $request->validate([
            'package_id' => 'required|exists:packages,id'
        ]);

        $user = auth()->user();
        $pos = PointOfSale::find($user->pos_id);
        $package = Package::find($request->package_id);

        if ($pos->balance < $package->price) {
            return response()->json([
                'success' => false,
                'message' => 'الرصيد غير كافي لإتمام العملية'
            ], 400);
        }

        $username = 'KM' . strtoupper(Str::random(8));

        try {
            $mikrotik = new MikroTikService($pos->host, $pos->username, $pos->password, $pos->port ?? 8728);

            if (!$mikrotik->createUser($username, $package)) {
                return response()->json([
                    'success' => false,
                    'message' => 'فشل في إنشاء الكرت في النظام. الرجاء المحاولة لاحقًا.'
                ], 500);
            }

            $card = InternetCard::create([
                'username' => $username,
                'package_id' => $package->id,
                'pos_id' => $pos->id,
                'expiration_date' => now()->addDays($package->validity_days),
            ]);

            $pos->decrement('balance', $package->price);

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
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'خطأ في الاتصال بالسيرفر: ' . $e->getMessage()
            ], 500);
        }
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

        try {
            $mikrotik = new MikroTikService($pos->host, $pos->username, $pos->password, $pos->port ?? 8728);

            if (!$mikrotik->rechargeUser($request->username, $package)) {
                return response()->json([
                    'success' => false,
                    'message' => 'فشل في إعادة شحن الكرت. الرجاء المحاولة لاحقًا.'
                ], 500);
            }

            if ($pos->balance < $package->price) {
                return response()->json([
                    'success' => false,
                    'message' => 'الرصيد غير كافي لإتمام العملية'
                ], 400);
            }

            $card = InternetCard::where('username', $request->username)->first();
            if ($card) {
                $card->update([
                    'package_id' => $package->id,
                    'expiration_date' => now()->addDays($package->validity_days),
                ]);
            }

            $pos->decrement('balance', $package->price);

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
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'خطأ في الاتصال بالسيرفر: ' . $e->getMessage()
            ], 500);
        }
    }
}
