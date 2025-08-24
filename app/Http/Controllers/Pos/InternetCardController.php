<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use App\Models\InternetCard;
use App\Models\Package;
use App\Models\Transaction;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
//use Illuminate\Support\Facades\Redis;
use App\Models\SystemSetting;
use Exception;
use App\Jobs\ProvisionInternetCardJob;

class internetCardcontroller extends Controller
{
    protected $whatsAppService;

    public function __construct(WhatsAppService $whatsAppService)
    {
        $this->whatsAppService = $whatsAppService;
    }

    public function generateForm()
    {
        $user = Auth::user();
        abort_unless($user->hasRole('pos'), 403);

        $pos = $user->pointOfSale;
        abort_if(is_null($pos), 403, 'حسابك غير مربوط بنقطة بيع.');

        $packages = Package::all();
        $balance  = $pos->balance;

        return view('pos.cards.generate', compact('pos', 'packages', 'balance'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'package_id'     => 'required|exists:packages,id',
            'customer_phone' => 'nullable|string|max:20',
        ]);

        $user = Auth::user();
        abort_unless($user->hasRole('pos'), 403);

        $pos = $user->pointOfSale;
        if (!$pos) {
            return back()->with('error', 'لا توجد نقطة بيع مرتبطة بحسابك.');
        }

        $package = Package::findOrFail($request->package_id);
        if ($pos->balance < $package->price) {
            return back()->with('error', 'رصيدك غير كافٍ لتوليد هذه البطاقة');
        }

        $username = $this->generateUniqueUsername();

        // نسجل البطاقة والمعاملة فورًا ثم نرسل مهمة الخلفية
        $card = DB::transaction(function () use ($username, $package, $pos, $request, $user) {
            $card = InternetCard::create([
                'username'       => $username,
                'package_id'     => $package->id,
                'pos_id'         => $pos->id,
                'expiration_date'=> null,            // تُحدّث بعد نجاح MikroTik
                'status'         => 'pending',       // تظهر فورًا في الواجهة
                'customer_phone' => $request->customer_phone,
            ]);

            // خصم الرصيد وتسجيل المعاملة
            $pos->balance -= $package->price;
            $pos->save();

            Transaction::create([
                'user_id'       => $user->id,
                'pos_id'        => $pos->id,
                'type'          => 'debit',
                'amount'        => $package->price,
                'description'   => "بيع بطاقة إنترنت: {$username} - باقة: {$package->name}",
                'balance_after' => $pos->balance,
            ]);

            return $card;
        });

        // مهمة الخلفية: إنشاء/تفعيل على MikroTik + تحديث البطاقة + إرسال واتساب + في حال الفشل ترجيع الرصيد
        ProvisionInternetCardJob::dispatch($card->id);

return view('pos.cards.result', [
    'card' => $card->load(['package','pos']),
])->with('info', 'جاري تجهيز البطاقة...');
    }

    public function rechargeForm()
    {
        $user = Auth::user();
        abort_unless($user->hasRole('pos'), 403);

        $pos = $user->pointOfSale;
        if (!$pos) {
            return redirect()->route('pos.dashboard')->with('error', 'لا توجد نقطة بيع مرتبطة بحسابك.');
        }

        $packages = Package::all();
        return view('pos.cards.recharge', compact('packages', 'pos'));
    }

    // يمكنك لاحقًا عملها كـ Job بنفس فكرة التوليد لتكون سلسة
    // الإبقاء كما هو الآن لتقليل التغيير.
    public function recharge(Request $request)
{
    $request->validate([
        'username'       => ['required', 'regex:/^\d{8,10}$/'],
        'package_id'     => 'required|exists:packages,id',
        'customer_phone' => 'nullable|string|max:20',
    ]);

    $user = Auth::user();
    abort_unless($user->hasRole('pos'), 403);

    $pos = $user->pointOfSale;
    if (!$pos) {
        return back()->with('error', 'لا توجد نقطة بيع مرتبطة بحسابك.');
    }

    $package = Package::findOrFail($request->package_id);
    $username = $request->username;

    $card = InternetCard::where('username', $username)->first();
    if (!$card) {
        return back()->with('error', 'البطاقة غير موجودة.');
    }

    if ($pos->balance < $package->price) {
        return back()->with('error', 'رصيدك غير كافٍ لشحن هذه البطاقة');
    }

    DB::transaction(function () use ($pos, $package, $user, $card, $request) {
        $pos->balance -= $package->price;
        $pos->save();

        Transaction::create([
            'user_id'       => $user->id,
            'pos_id'        => $pos->id,
            'type'          => 'debit',
            'amount'        => $package->price,
            'description'   => "تجديد بطاقة إنترنت: {$card->username} - باقة: {$package->name}",
            'balance_after' => $pos->balance,
        ]);

        $card->update([
            'package_id'     => $package->id,
            'status'         => 'pending',
            'customer_phone' => $request->customer_phone,
            'expiration_date'=> now()->addDays($package->validity_days),
        ]);
    });

    ProvisionInternetCardJob::dispatch($card->id, true);

    return redirect()->route('pos.cards.result', $card)->with('info', 'جاري تجديد البطاقة...');
}

    public function result(InternetCard $card)
    {
        return view('pos.cards.result', compact('card'));
    }

    // Endpoint خفيف للـ polling
    public function status(InternetCard $card)
    {
        return response()->json([
            'status'         => $card->status,
            'expirationDate' => optional($card->expiration_date)->format('Y-m-d'),
            'package'        => optional($card->package)->name,
        ]);
    }

private function generateUniqueUsername(int $min = 8, int $max = 10): string
{
    do {
        $len = random_int($min, $max);

        // توليد أرقام فقط
        $username = '';
        for ($i = 0; $i < $len; $i++) {
            $username .= (string) random_int(0, 9);
        }

        // تأكد من عدم التكرار
        $exists = \App\Models\InternetCard::where('username', $username)->exists();
    } while ($exists);

    return $username;
}

    // نفس دوال الواتساب لديك (إن احتجتها هنا مباشرة)
public function sendViaWhatsApp(?\App\Models\InternetCard $card = null)
{
    // لو ما وصل موديل بالباراميتر، جرّب card_id من الـPOST
    if (!$card && request()->filled('card_id')) {
        $card = \App\Models\InternetCard::find((int) request('card_id'));
    }

    // لو ما قدرنا نحدد بطاقة، لا نرمي استثناء — نرجع برسالة معلومات
    if (!$card) {
        return back()->with('info', 'لم يتم تحديد بطاقة لإرسال الواتساب (اختياري).');
    }

    // لو أُدخل رقم الآن من المودال، خزّنه (اختياري)
    if (request()->filled('customer_phone')) {
        $clean = preg_replace('/\D/', '', request('customer_phone'));
        $card->customer_phone = $clean;
        $card->save();
    }

    // لو ما في رقم، لا نرسل ولا نعتبرها خطأ
    if (!$card->customer_phone) {
        return back()->with('info', 'لم يتم إرسال واتساب لأن رقم العميل غير موجود (اختياري).');
    }

    $ok = $this->sendCardViaWhatsApp($card, $card->customer_phone);

    return back()->with(
        $ok ? 'success' : 'error',
        $ok ? 'تم إرسال البطاقة عبر واتساب بنجاح' : 'فشل إرسال البطاقة عبر واتساب'
    );
}

    private function createWhatsAppMessage(InternetCard $card)
    {
        $messageTemplate = SystemSetting::getValue('whatsapp_message_template',
            "مرحباً بك في {company_name}!\n\nتفاصيل اشتراكك:\n👤 اسم المستخدم: *{username}*\n📦 الباقة: {package}\n💰 السعر: {price} ريال يمني\n⏳ مدة الصلاحية: {days} يوم\n📅 تاريخ الانتهاء: {expiry_date}\n\nشكراً لاختياركنا!\nللتواصل: {support_phone}");

        $replacements = [
            '{company_name}' => SystemSetting::getValue('company_name', 'شركتنا'),
            '{username}'     => $card->username,
            '{package}'      => $card->package->name,
            '{price}'        => number_format($card->package->price),
            '{days}'         => $card->package->validity_days,
            '{expiry_date}'  => optional($card->expiration_date)->format('d/m/Y'), 
            '{support_phone}'=> SystemSetting::getValue('support_phone', '773377968'),
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $messageTemplate);
    }
}
