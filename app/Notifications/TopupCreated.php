<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Transaction;

class TopupCreated extends Notification
{
    use Queueable;

    public function __construct(public Transaction $transaction) {}

    public function via($notifiable)
    {
        return ['database']; // سهل وسريع — بدون إرسال بريد الآن
    }

    public function toArray($notifiable)
    {
        $t = $this->transaction;
        return [
            'type'       => 'topup',
            'title'      => 'تم شحن رصيد لنقطة بيع',
            'amount'     => $t->amount,
            'pos_id'     => $t->pos_id,
            'pos_name'   => optional($t->pos)->name,
            'by_user_id' => $t->user_id ?? null,
            'created_at' => optional($t->created_at)?->toDateTimeString(),
            // رابط مفيد يفتح تقرير المحاسب المشرف على هذه النقطة
            'url'        => route('admin.accountants.topups.index', [
                                'accountant_id' => optional($t->pos)->accountant_id,
                                'date_from' => now()->startOfMonth()->toDateString(),
                                'date_to'   => now()->toDateString(),
                            ]),
        ];
    }
}
