<?php

namespace App\Mail;

use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactAutoReply extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Message $contactMessage, public array $settings)
    {
    }

    public function build(): self
    {
        return $this
            ->subject('Got your message — ' . ($this->settings['name'] ?? 'thanks for reaching out'))
            ->view('emails.contact-auto-reply');
    }
}
