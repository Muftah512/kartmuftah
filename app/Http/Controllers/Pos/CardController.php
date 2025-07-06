<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\Package;
use App\Services\MikroTikService;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Package;
use Illuminate\Support\Str;

class CardController extends Controller
{
    protected $mikroTikService;
    protected $whatsAppService;

    public function __construct(MikroTikService $mikroTikService, WhatsAppService $whatsAppService)
    {
        $this->mikroTikService = $mikroTikService;
        $this->whatsAppService = $whatsAppService;
    }

    // ÚÑÖ æÇÌåÉ ÊæáíÏ ßÑÊ ÌÏíÏ
    public function generateForm()
    {
        $packages = Package::all();
        $user = Auth::user();
        $balance = $user->pointOfSale->balance;
        
        return view('pos.cards.generate', compact('packages', 'balance'));
    }

    // ÚãáíÉ ÊæáíÏ ßÑÊ ÌÏíÏ
    public function generate(Request $request)
    {
        $request->validate([
            'package_id' => 'required|exists:packages,id',
            'customer_phone' => 'nullable|string|max:20'
        ]);

        $user = Auth::user();
        $pos = $user->pointOfSale;
        $package = Package::find($request->package_id);

        // ÇáÊÍÞÞ ãä ÇáÑÕíÏ
        if ($pos->balance < $package->price) {
            return redirect()->back()->with('error', 'ÑÕíÏ ÛíÑ ßÇÝí áÊæáíÏ ÇáßÑÊ');
        }

        // ÊæáíÏ ÇÓã ãÓÊÎÏã ÝÑíÏ
        $username = $this->generateUniqueUsername();

        // ÅäÔÇÁ ÇáãÓÊÎÏã Ýí MikroTik
        $created = $this->mikroTikService->createUser($username, $package);
        if (!$created) {
            return redirect()->back()->with('error', 'ÝÔá Ýí ÅäÔÇÁ ÇáßÑÊ Ýí äÙÇã MikroTik');
        }

        // ÅäÔÇÁ ÓÌá ÇáßÑÊ
        $card = Card::create([
            'username' => $username,
            'package_id' => $package->id,
            'pos_id' => $pos->id,
            'expires_at' => now()->addDays($package->validity_days),
            'status' => 'active',
            'customer_phone' => $request->customer_phone
        ]);

        // ÎÕã ÇáãÈáÛ ãä ÑÕíÏ äÞØÉ ÇáÈíÚ
        $pos->balance -= $package->price;
        $pos->save();

        // ÅÑÓÇá ÇáßÑÊ ÚÈÑ ÇáæÇÊÓÇÈ ÅÐÇ Êã ÊÞÏíã ÑÞã åÇÊÝ
        if ($request->customer_phone) {
            $this->sendCardViaWhatsApp($card, $request->customer_phone);
        }

        return redirect()->route('pos.cards.result', $card);
    }

    // ÚÑÖ æÇÌåÉ ÅÚÇÏÉ ÔÍä ßÑÊ
    public function rechargeForm()
    {
        return view('pos.cards.recharge');
    }

    // ÚãáíÉ ÅÚÇÏÉ ÔÍä ßÑÊ
    public function recharge(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'package_id' => 'required|exists:packages,id',
            'customer_phone' => 'nullable|string|max:20'
        ]);

        $user = Auth::user();
        $pos = $user->pointOfSale;
        $package = Package::find($request->package_id);
        $username = $request->username;

        // ÇáÈÍË Úä ÇáßÑÊ
        $card = Card::where('username', $username)->firstOrFail();

        // ÇáÊÍÞÞ ãä ÕáÇÍíÉ ÅÚÇÏÉ ÇáÔÍä
        if (!$card->is_expired) {
            return redirect()->back()->with('error', 'áÇ íãßä ÅÚÇÏÉ ÔÍä ÇáßÑÊ ÞÈá ÇäÊåÇÁ ÕáÇÍíÊå');
        }

        // ÇáÊÍÞÞ ãä ÇáÑÕíÏ
        if ($pos->balance < $package->price) {
            return redirect()->back()->with('error', 'ÑÕíÏ ÛíÑ ßÇÝí áÅÚÇÏÉ ÇáÔÍä');
        }

        // ÅÚÇÏÉ ÇáÔÍä Ýí MikroTik
        $recharged = $this->mikroTikService->rechargeUser($username, $package);
        if (!$recharged) {
            return redirect()->back()->with('error', 'ÝÔá Ýí ÅÚÇÏÉ ÔÍä ÇáßÑÊ Ýí äÙÇã MikroTik');
        }

        // ÊÍÏíË ÓÌá ÇáßÑÊ
        $card->update([
            'package_id' => $package->id,
            'expires_at' => now()->addDays($package->validity_days),
            'status' => 'recharged',
            'customer_phone' => $request->customer_phone
        ]);

        // ÎÕã ÇáãÈáÛ ãä ÑÕíÏ äÞØÉ ÇáÈíÚ
        $pos->balance -= $package->price;
        $pos->save();

        // ÅÑÓÇá ÇáßÑÊ ÚÈÑ ÇáæÇÊÓÇÈ ÅÐÇ Êã ÊÞÏíã ÑÞã åÇÊÝ
        if ($request->customer_phone) {
            $this->sendCardViaWhatsApp($card, $request->customer_phone);
        }

        return redirect()->route('pos.cards.result', $card);
    }

    // ÚÑÖ äÊíÌÉ ÇáßÑÊ
    public function result(Card $card)
    {
        return view('pos.cards.result', compact('card'));
    }

    // ÅÑÓÇá ÇáßÑÊ ÚÈÑ ÇáæÇÊÓÇÈ
    public function sendViaWhatsApp(Card $card)
    {
        $phone = $card->customer_phone;
        
        if (!$phone) {
            return back()->with('error', 'áÇ íæÌÏ ÑÞã åÇÊÝ Úãíá ãÓÌá áåÐÇ ÇáßÑÊ');
        }

        $result = $this->sendCardViaWhatsApp($card, $phone);
        
        if ($result) {
            return back()->with('success', 'Êã ÅÑÓÇá ÇáßÑÊ ÚÈÑ ÇáæÇÊÓÇÈ ÈäÌÇÍ');
        }

        return back()->with('error', 'ÝÔá Ýí ÅÑÓÇá ÇáßÑÊ ÚÈÑ ÇáæÇÊÓÇÈ');
    }

    // ======== ÇáÏæÇá ÇáãÓÇÚÏÉ ======== //
    
    // ÊæáíÏ ÇÓã ãÓÊÎÏã ÝÑíÏ
    private function generateUniqueUsername()
    {
        do {
            $username = 'user_' . Str::random(8);
            $exists = Card::where('username', $username)->exists();
        } while ($exists);

        return $username;
    }

    // ÅÑÓÇá ÇáßÑÊ ÚÈÑ ÇáæÇÊÓÇÈ
    private function sendCardViaWhatsApp(Card $card, $phone)
    {
        // ÊäÓíÞ ÑÞã ÇáåÇÊÝ (ÅÖÇÝÉ +967)
        $formattedPhone = $this->formatPhoneNumber($phone);
        
        // ÅäÔÇÁ ãÍÊæì ÇáÑÓÇáÉ
        $message = $this->createWhatsAppMessage($card);
        
        // ÅÑÓÇá ÇáÑÓÇáÉ
        return $this->whatsAppService->sendMessage($formattedPhone, $message);
    }

    // ÊäÓíÞ ÑÞã ÇáåÇÊÝ
    private function formatPhoneNumber($phone)
    {
        // ÅÒÇáÉ Ãí ÃÍÑÝ ÛíÑ ÑÞãíÉ
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // ÅÖÇÝÉ 967 ÅÐÇ ßÇä ÇáÑÞã íÈÏÃ ÈÜ 7 (ááíãä)
        if (preg_match('/^7[0-9]{8}$/', $phone)) {
            return '+967' . $phone;
        }
        
        // ÅÖÇÝÉ 967 ÅÐÇ ßÇä ÇáÑÞã íÈÏÃ ÈÜ 0
        if (preg_match('/^0[0-9]{9}$/', $phone)) {
            return '+967' . substr($phone, 1);
        }
        
        // ÅÐÇ ßÇä ÇáÑÞã íÍÊæí Úáì 9 ÃÑÞÇã (ÈÏæä 7 Ýí ÇáÈÏÇíÉ)
        if (preg_match('/^[0-9]{9}$/', $phone)) {
            return '+9677' . $phone;
        }
        
        return '+967' . $phone;
    }

    // ÅäÔÇÁ ãÍÊæì ÑÓÇáÉ ÇáæÇÊÓÇÈ
    private function createWhatsAppMessage(Card $card)
    {
        return "ãÑÍÈÇð Èß Ýí ÎÏãÉ ßÑÊ ÇáãÝÊÇÍ!\n\n" .
               "ÊÝÇÕíá ßÑÊ ÇáÅäÊÑäÊ ÇáÎÇÕ Èß:\n" .
               "?? ÇÓã ÇáãÓÊÎÏã: *{$card->username}*\n" .
               "?? ÇáÈÇÞÉ: {$card->package->name}\n" .
               "?? ÇáÓÚÑ: " . number_format($card->package->price) . " ÑíÇá íãäí\n" .
               "? ãÏÉ ÇáÕáÇÍíÉ: {$card->package->validity_days} íæã\n" .
               "?? ÊÇÑíÎ ÇáÇäÊåÇÁ: {$card->expires_at->format('d/m/Y')}\n\n" .
               "ÔßÑÇð áÇÓÊÎÏÇãß ÎÏãÇÊäÇ!\n" .
               "ááÇÓÊÝÓÇÑ: 773377968";
    }
    $replacements = [
        '{company_name}' => SystemSetting::getValue('company_name', 'ßÑÊ ÇáãÝÊÇÍ'),
        '{username}' => $card->username,
        '{package}' => $card->package->name,
        '{price}' => number_format($card->package->price),
        '{days}' => $card->package->validity_days,
        '{expiry_date}' => $card->expires_at->format('d/m/Y'),
        '{support_phone}' => SystemSetting::getValue('support_phone', '773377968')
    ];

    return str_replace(
        array_keys($replacements),
        array_values($replacements),
        $messageTemplate
    );
}