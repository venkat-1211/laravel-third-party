<?php

namespace App\Services;

use Twilio\Rest\Client;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class TwilioService
{
    protected $client;
    protected $from;

    public function __construct()
    {
        $sid = Config::get('services.twilio.sid');
        $token = Config::get('services.twilio.token');
        $this->from = Config::get('services.twilio.from');

        $this->client = new Client($sid, $token);
    }

    /**
     * Send SMS via Twilio
     * @param string $to E.164 phone number
     * @param string $message
     * @return \Twilio\Rest\Api\V2010\Account\MessageInstance
     */
    public function sendSms(string $to, string $message)
    {
        try {
            return $this->client->messages->create($to, [
                'from' => $this->from,
                'body' => $message,
            ]);
        } catch (\Throwable $e) {
            Log::error('Twilio sendSms failed: '.$e->getMessage());
            throw $e;
        }
    }
}
