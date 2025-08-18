<?php

namespace App\Observers;

use App\Models\Transaction;
use App\Models\User;
use App\Notifications\TopupCreated;
use App\Notifications\PosLowBalanceAlert;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class TransactionObserver
{
    public function created(Transaction $transaction): void
    {
        try {
            $transaction->loadMissing('pos');

            // إشعار الشحن فقط (credit)
            if ($transaction->type === 'credit') {
                $admins = User::role('admin')->get();
                Notification::send($admins, new TopupCreated($transaction));

                $accId = optional($transaction->pos)->accountant_id;
                if ($accId && ($acc = User::find($accId))) {
                    $acc->notify(new TopupCreated($transaction));
                }

                Log::info('TransactionObserver: credit notification dispatched', [
                    'tx_id' => $transaction->id,
                    'pos_id'=> $transaction->pos_id,
                    'amount'=> $transaction->amount,
                ]);
            }

            // --- فحص الرصيد المنخفض لكل من credit/debit ---
            $pos = $transaction->pos;
            if (!$pos) return;

            $threshold = (float)($pos->low_balance_threshold ?? config('pos.low_balance_threshold', 1000));

            $credits = (float) DB::table('transactions')->where('pos_id',$pos->id)->where('type','credit')->sum('amount');
            $debits  = (float) DB::table('transactions')->where('pos_id',$pos->id)->where('type','debit')->sum('amount');
            $balance = $credits - $debits;

            $key = 'pos_low_balance_'.$pos->id;

            if ($balance <= $threshold && !Cache::get($key)) {
                $admins = User::role('admin')->get();
                Notification::send($admins, new PosLowBalanceAlert($pos, $balance, $threshold));

                if ($pos->accountant_id && ($acc = User::find($pos->accountant_id))) {
                    $acc->notify(new PosLowBalanceAlert($pos, $balance, $threshold));
                }
                Cache::put($key, 1, now()->addHours(12));

                Log::warning('POS low balance alert sent', [
                    'pos_id' => $pos->id, 'balance' => $balance, 'threshold'=>$threshold
                ]);
            }

            if ($balance > ($threshold * 1.2)) {
                Cache::forget($key);
            }
        } catch (\Throwable $e) {
            Log::error('TransactionObserver error: '.$e->getMessage(), ['trace'=>$e->getTraceAsString()]);
        }
    }
}
