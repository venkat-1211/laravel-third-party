<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class Msg91Service
{
    protected $authkey;

    public function __construct()
    {
        $this->authkey = config('services.msg91.authkey');
    }

    // Send OTP
    public function sendOtp($mobile)
    {
        $response = Http::withHeaders([
            'authkey' => $this->authkey,
            'Content-Type' => 'application/json',
        ])->post('https://control.msg91.com/api/v5/otp', [
            'mobile' => "91$mobile",
            'template_id' => '32435565756565'
        ]);

        return $response->json();
    }

    // Verify OTP
    public function verifyOtp($mobile, $otp)
    {
        $response = Http::withHeaders([
            'authkey' => $this->authkey,
        ])->get('https://control.msg91.com/api/v5/otp/verify', [
            'mobile' => "91$mobile",
            'otp' => $otp
        ]);

        return $response->json();
    }
}
