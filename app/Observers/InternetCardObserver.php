<?php

namespace App\Observers;

use App\Models\InternetCard;
use App\Models\User;
use App\Notifications\InternetCardCreated;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;

class InternetCardObserver
{
    public function created(InternetCard $card): void
    {
        try {
            $card->loadMissing('pos','package');

            $admins = User::role('admin')->get();
            Notification::send($admins, new InternetCardCreated($card));

            $accId = optional($card->pos)->accountant_id;
            if ($accId && ($acc = User::find($accId))) {
                $acc->notify(new InternetCardCreated($card));
            }

            Log::info('InternetCardObserver: notification dispatched', [
                'card_id' => $card->id,
                'pos_id'  => $card->pos_id,
                'admins'  => $admins->pluck('id'),
                'acc_id'  => $accId,
            ]);
        } catch (\Throwable $e) {
            Log::error('InternetCardObserver error: '.$e->getMessage(), ['trace'=>$e->getTraceAsString()]);
        }
    }
}
