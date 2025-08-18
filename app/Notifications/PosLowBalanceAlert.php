<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\PointOfSale;

class PosLowBalanceAlert extends Notification
{
    use Queueable;

    public function __construct(public PointOfSale $pos, public float $balance, public float $threshold) {}

    public function via($notifiable)
    {
        return ['database']; // سريع، بدون بريد/طابور الآن
    }

    public function toArray($notifiable)
    {
        $p = $this->pos;

        return [
            'type'       => 'pos_low_balance',
            'title'      => 'تنبيه: رصيد نقطة بيع منخفض',
            'pos_id'     => $p->id,
            'pos_name'   => $p->name ?? ("POS #{$p->id}"),
            'balance'    => $this->balance,
            'threshold'  => $this->threshold,
            'created_at' => now()->toDateTimeString(),
            'url'        => route('admin.accountants.topups.index', [
                                'accountant_id' => $p->accountant_id,
                                'date_from'     => now()->startOfMonth()->toDateString(),
                                'date_to'       => now()->toDateString(),
                            ]),
        ];
    }
}
