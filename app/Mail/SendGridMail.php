<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendGridMail extends Mailable
{
    use Queueable, SerializesModels;

    public function build()
    {
        return $this->subject('Testing SendGrid Email')
                    ->view('emails.sendgrid');
    }
}
