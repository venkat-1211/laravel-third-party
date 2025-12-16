<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FcmService
{
    public function sendToMultiple($tokens, $title, $body, $data = [])
    {
        $payload = [
            "registration_ids" => $tokens,
            "notification" => [
                "title" => $title,
                "body"  => $body,
                "sound" => "default"
            ],
            "data" => $data
        ];

        return Http::withHeaders([
            "Authorization" => "key=" . env('FCM_SERVER_KEY'),
            "Content-Type"  => "application/json",
        ])->post(env('FCM_API_URL'), $payload)->json();
    }
}
