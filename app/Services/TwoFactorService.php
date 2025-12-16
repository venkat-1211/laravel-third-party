<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TwoFactorService
{
    protected string $base;
    protected string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.twofactor.api_key');
        $this->base   = rtrim(config('services.twofactor.base_url'), '/');
    }

    /**
     * Send OTP (AUTO-GENERATED OTP)
     * -----------------------------
     * API Format:
     * https://2factor.in/API/V1/API_KEY/SMS/AUTOGEN/MOBILE
     */
    public function sendOtp(string $mobile): array
    {
        $templateName = config('services.twofactor.template_name');
        $url = "{$this->base}/{$this->apiKey}/SMS/{$mobile}/AUTOGEN/{$templateName}";

        $resp = Http::timeout(20)->get($url);

        if ($resp->failed()) {
            return [
                'status'      => 'error',
                'http_status' => $resp->status(),
                'body'        => $resp->body(),
            ];
        }

        return $resp->json();
    }

    /**
     * Verify OTP
     * -----------------------------
     * session_id from sendOtp()
     * and user-entered OTP
     *
     * API Format:
     * https://2factor.in/API/V1/API_KEY/SMS/VERIFY/SESSION_ID/OTP
     */
    public function verifyOtp(string $sessionId, string $otp): array
    {
        $url = "{$this->base}/{$this->apiKey}/SMS/VERIFY/{$sessionId}/{$otp}";    // session id or phone number vachi verify pannikkalam.

        $resp = Http::timeout(20)->get($url);

        if ($resp->failed()) {
            return [
                'status'      => 'error',
                'http_status' => $resp->status(),
                'body'        => $resp->body(),
            ];
        }

        return $resp->json();
    }
}
