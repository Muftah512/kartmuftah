<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WhatsAppService
{
    public static function sendMessage($to, $message)
    {
        $url = "https://api.ultramsg.com/" . env('ULTRAMSG_INSTANCE_ID') . "/messages/chat";
        return Http::asForm()->post($url, [
            'token' => env('ULTRAMSG_TOKEN'),
            'to' => $to,
            'body' => $message
        ]);
    }
}
