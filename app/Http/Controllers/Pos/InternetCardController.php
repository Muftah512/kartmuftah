<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use App\Models\InternetCard;
use App\Models\Package;
use App\Services\MikroTikService;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\SystemSetting;
use App\Models\PointOfSale;
use App\Models\Transaction;
use Exception;

class InternetCardController extends Controller
{
    protected $mikroTikService;
    protected $whatsAppService;

    public function __construct(MikroTikService $mikroTikService, WhatsAppService $whatsAppService)
    {
        $this->mikroTikService = $mikroTikService;
        $this->whatsAppService = $whatsAppService;
    }

    /**
     * يعرض نموذج توليد كرت جديد.
     */
    public function generateForm()
    {
        $user = Auth::user();
        $pos = $user->pointOfSale()->first();

        // **تم إزالة التحقق من حالة نقطة البيع**
        // إذا كنت متأكدًا من وجود نقطة بيع مرتبطة بالمستخدم
        if (!$pos) {
             return redirect()->route('pos.dashboard')->with('error', 'لا توجد نقطة بيع مرتبطة بحسابك.');
        }

        $packages = Package::all();
        $balance = $pos->balance;

        return view('pos.cards.generate', compact('packages', 'balance'));
    }

    /**
     * ينشئ كرتًا جديدًا.
     */
    public function generate(Request $request)
    {
        $request->validate([
            'package_id' => 'required|exists:packages,id',
            'customer_phone' => 'nullable|string|max:20'
        ]);

        $user = Auth::user();
        $pos = $user->pointOfSale()->first();
        $package = Package::find($request->package_id);

        if (!$pos) {
            return redirect()->back()->with('error', 'لا توجد نقطة بيع مرتبطة بحسابك.');
        }
        
        if ($pos->balance < $package->price) {
            return redirect()->back()->with('error', 'رصيدك غير كافٍ لتوليد هذه البطاقة');
        }

        $username = $this->generateUniqueUsername();
        
        try {
            $this->mikroTikService->createUser($username, $package);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'فشل في إنشاء المستخدم على MikroTik: ' . $e->getMessage());
        }

        $card = InternetCard::create([
            'username' => $username,
            'package_id' => $package->id,
            'pos_id' => $pos->id,
            'expiration_date' => now()->addDays($package->validity_days),
            'status' => 'active',
            'customer_phone' => $request->customer_phone
        ]);

        $pos->balance -= $package->price;
        $pos->save();

        if ($request->customer_phone) {
            $this->sendCardViaWhatsApp($card, $request->customer_phone);
        }

        return redirect()->route('pos.cards.result', $card);
    }

    /**
     * يعرض نموذج شحن كرت.
     */
    public function rechargeForm()
    {
        $user = Auth::user();
        $pos = $user->pointOfSale()->first();
        
        // **تم إزالة التحقق من حالة نقطة البيع**
        // إذا كنت متأكدًا من وجود نقطة بيع مرتبطة بالمستخدم
        if (!$pos) {
            return redirect()->route('pos.dashboard')->with('error', 'لا توجد نقطة بيع مرتبطة بحسابك.');
        }

        $packages = Package::all();
        $balance = $pos->balance;
        
        return view('pos.cards.recharge', compact('packages', 'balance'));
    }

    /**
     * يشحن كرت إنترنت.
     */
    public function recharge(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'package_id' => 'required|exists:packages,id',
            'customer_phone' => 'nullable|string|max:20'
        ]);

        $user = Auth::user();
        $pos = $user->pointOfSale()->first();
        $package = Package::find($request->package_id);
        $username = $request->username;

        if (!$pos) {
            return redirect()->back()->with('error', 'لا توجد نقطة بيع مرتبطة بحسابك.');
        }

        $card = InternetCard::where('username', $username)->first();

        if (!$card) {
            return redirect()->back()->with('error', 'البطاقة غير موجودة.');
        }

        if ($card->expiration_date > now()) {
            return redirect()->back()->with('error', 'البطاقة لم تنته صلاحيتها بعد');
        }

        if ($pos->balance < $package->price) {
            return redirect()->back()->with('error', 'رصيدك غير كافٍ لشحن هذه البطاقة');
        }

        try {
            $this->mikroTikService->rechargeUser($username, $package);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'فشل في شحن المستخدم على MikroTik: ' . $e->getMessage());
        }

        $card->update([
            'package_id' => $package->id,
            'expiration_date' => now()->addDays($package->validity_days),
            'status' => 'active',
            'customer_phone' => $request->customer_phone
        ]);

        $pos->balance -= $package->price;
        $pos->save();

        if ($request->customer_phone) {
            $this->sendCardViaWhatsApp($card, $request->customer_phone);
        }

        return redirect()->route('pos.cards.result', ['card' => $card, 'recharge' => true]);
    }

    /**
     * بقية الدوال
     */
    public function result(InternetCard $card)
    {
        return view('pos.cards.result', compact('card'));
    }

    public function sendViaWhatsApp(InternetCard $card)
    {
        $phone = $card->customer_phone;
        
        if (!$phone) {
            return back()->with('error', 'لم يتم تحديد رقم هاتف العميل');
        }

        $result = $this->sendCardViaWhatsApp($card, $phone);
        
        if ($result) {
            return back()->with('success', 'تم إرسال البطاقة عبر واتساب بنجاح');
        }

        return back()->with('error', 'فشل إرسال البطاقة عبر واتساب');
    }

    private function generateUniqueUsername()
    {
        do {
            $username = 'user_' . Str::random(8);
            $exists = InternetCard::where('username', $username)->exists();
        } while ($exists);

        return $username;
    }

    private function sendCardViaWhatsApp(InternetCard $card, $phone)
    {
        $formattedPhone = $this->formatPhoneNumber($phone);
        $message = $this->createWhatsAppMessage($card);
        return $this->whatsAppService->sendMessage($formattedPhone, $message);
    }

    private function formatPhoneNumber($phone)
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        if (preg_match('/^7[0-9]{8}$/', $phone)) {
            return '+967' . $phone;
        }
        
        if (preg_match('/^0[0-9]{9}$/', $phone)) {
            return '+967' . substr($phone, 1);
        }
        
        if (preg_match('/^[0-9]{9}$/', $phone)) {
            return '+9677' . $phone;
        }
        
        return '+967' . $phone;
    }

    private function createWhatsAppMessage(InternetCard $card)
    {
        $messageTemplate = SystemSetting::getValue('whatsapp_message_template', 
            "مرحباً بك في {company_name}!\n\nتفاصيل اشتراكك:\n👤 اسم المستخدم: *{username}*\n📦 الباقة: {package}\n💰 السعر: {price} ريال يمني\n⏳ مدة الصلاحية: {days} يوم\n📅 تاريخ الانتهاء: {expiry_date}\n\nشكراً لاختياركنا!\nللتواصل: {support_phone}");

        $replacements = [
            '{company_name}' => SystemSetting::getValue('company_name', 'شركتنا'),
            '{username}' => $card->username,
            '{package}' => $card->package->name,
            '{price}' => number_format($card->package->price),
            '{days}' => $card->package->validity_days,
            '{expiry_date}' => $card->expiration_date->format('d/m/Y'),
            '{support_phone}' => SystemSetting::getValue('support_phone', '773377968')
        ];

        return str_replace(
            array_keys($replacements),
            array_values($replacements),
            $messageTemplate
        );
    }
}
