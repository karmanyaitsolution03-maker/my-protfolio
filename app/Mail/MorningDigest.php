<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MorningDigest extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public array $data, public array $settings)
    {
    }

    public function build(): self
    {
        $totalSignals = $this->data['visitors']['total']
            + $this->data['hotLeads']->count()
            + $this->data['messages']['total'];

        $subject = $totalSignals > 0
            ? "Morning digest — {$this->data['visitors']['total']} visits, {$this->data['hotLeads']->count()} hot leads, {$this->data['messages']['total']} messages"
            : 'Morning digest — quiet night, nothing new';

        return $this->subject($subject)->view('emails.morning-digest');
    }
}
