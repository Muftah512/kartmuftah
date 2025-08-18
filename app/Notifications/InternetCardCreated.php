<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\InternetCard;

class InternetCardCreated extends Notification
{
    use Queueable;

    public function __construct(public InternetCard $card) {}

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $c = $this->card;
        return [
            'type'       => 'card',
            'title'      => 'تم إنشاء كرت إنترنت جديد',
            'username'   => $c->username,
            'package'    => optional($c->package)->name,
            'price'      => optional($c->package)->price,
            'pos_id'     => $c->pos_id,
            'pos_name'   => optional($c->pos)->name,
            'created_at' => optional($c->created_at)?->toDateTimeString(),
            'url'        => route('admin.reports.cards'), // عدِّل لو عندك مسار أدق
        ];
    }
}
