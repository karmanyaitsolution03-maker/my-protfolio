<?php

namespace App\Mail;

use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminReply extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Message $contactMessage, public string $body, public array $settings)
    {
    }

    public function build(): self
    {
        $mail = $this
            ->subject('Re: your message to ' . ($this->settings['name'] ?? 'me'))
            ->view('emails.admin-reply');

        // Replies from the visitor should land in the owner's real inbox, not the
        // site's outgoing mail address.
        if (! empty($this->settings['email'])) {
            $mail->replyTo($this->settings['email'], $this->settings['name'] ?? null);
        }

        return $mail;
    }
}
