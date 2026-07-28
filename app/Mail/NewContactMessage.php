<?php

namespace App\Mail;

use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewContactMessage extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Message $contactMessage)
    {
    }

    public function build(): self
    {
        return $this
            ->subject('Portfolio: new message from ' . $this->contactMessage->name)
            ->replyTo($this->contactMessage->email, $this->contactMessage->name)
            ->view('emails.new-contact-message');
    }
}
