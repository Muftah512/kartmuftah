<?php

namespace App\Jobs;

use App\Models\InternetCard;
use App\Models\Transaction;
use App\Services\MikroTikService;
use App\Services\WhatsAppService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProvisionInternetCardJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $cardId;
    public bool $isRecharge;

    public function __construct(int $cardId, bool $isRecharge = false)
    {
        $this->cardId = $cardId;
        $this->isRecharge = $isRecharge;
        $this->onQueue('provision'); // اختياري: صف مخصص
    }

    public function handle(): void
    {
        /** @var InternetCard $card */
        $card = InternetCard::with(['package', 'pos'])->find($this->cardId);
        if (!$card || !$card->package || !$card->pos) {
            Log::error('Provision job: card/package/pos missing', ['cardId' => $this->cardId]);
            return;
        }

        $mikrotik = app(MikroTikService::class);
        $ok = $mikrotik->createUser($card->username, $card->package);

        if (!$ok) {
            // فشل—ارجاع الرصيد وتحديث الحالة
            DB::transaction(function () use ($card) {
                $pos = $card->pos;
                $pos->balance += $card->package->price;
                $pos->save();

                Transaction::create([
                    'user_id'       => $pos->user_id ?? null,
                    'pos_id'        => $pos->id,
                    'type'          => 'credit',
                    'amount'        => $card->package->price,
                    'description'   => "استرجاع فشل إنشاء البطاقة: {$card->username} - باقة: {$card->package->name}",
                    'balance_after' => $pos->balance,
                ]);

                $card->update(['status' => 'failed']);
            });

            return;
        }

        // نجاح—تحديث حالة البطاقة وحساب الانتهاء
        $expiry = now()->addDays($card->package->validity_days);

        $card->update([
            'status'          => 'active',
            'expiration_date' => $expiry,
        ]);

        // إرسال واتساب إن كان موجود
        if ($card->customer_phone) {
            try {
                /** @var WhatsAppService $wa */
                $wa = app(WhatsAppService::class);
                $msg = "تم تفعيل بطاقتك بنجاح\nاسم المستخدم: {$card->username}\nالباقة: {$card->package->name}\nتاريخ الانتهاء: ".$expiry->format('d/m/Y');
                $wa->sendMessage($card->customer_phone, $msg);
            } catch (\Throwable $e) {
                Log::warning('WhatsApp send failed (provision): '.$e->getMessage());
            }
        }
    }
}
