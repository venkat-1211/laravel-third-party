<?php

namespace App\Mail;

use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SendGridTransport extends AbstractTransport
{
    protected $client;
    protected $apiKey;

    public function __construct(HttpClientInterface $client, string $apiKey)
    {
        parent::__construct();   // 🟢 Fixes dispatcher not initialized error

        $this->client = $client;
        $this->apiKey = $apiKey;
    }

    protected function doSend(SentMessage $message): void
    {
        $email = $message->getOriginalMessage();

        $payload = [
            'personalizations' => [
                [
                    'to' => array_map(
                        fn($a) => ['email' => $a->getAddress()], 
                        $email->getTo()
                    ),
                    'subject' => $email->getSubject(),
                ],
            ],
            'from' => [
                'email' => $email->getFrom()[0]->getAddress(),
            ],
            'content' => [
                [
                    'type'  => 'text/html',
                    'value' => $email->getHtmlBody() ?: '',
                ],
            ],
        ];

        $response = $this->client->request('POST', 'https://api.sendgrid.com/v3/mail/send', [
            'headers' => [
                'Authorization' => 'Bearer '.$this->apiKey,
                'Content-Type'  => 'application/json',
            ],
            'json' => $payload,
        ]);

        if ($response->getStatusCode() >= 400) {
            throw new \Exception('SendGrid Error: '.$response->getContent(false));
        }
    }

    public function __toString(): string
    {
        return 'sendgrid';
    }
}
