<?php

namespace App\Console\Commands;

use App\Mail\MorningDigest;
use App\Models\Setting;
use App\Services\MorningDigestService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendMorningDigest extends Command
{
    protected $signature = 'digest:morning';

    protected $description = 'Email a daily summary of visitors, companies, hot leads, and new messages';

    public function handle(MorningDigestService $digest): int
    {
        $settings = Setting::resolved();
        $settings['name'] = trim(($settings['first_name'] ?? '') . ' ' . ($settings['last_name'] ?? ''));

        $to = $settings['email'] ?? config('mail.from.address');
        if (! $to) {
            $this->error('No destination email configured — set it in Admin → Settings, or MAIL_FROM_ADDRESS in .env.');
            return self::FAILURE;
        }

        $data = $digest->compile();

        try {
            Mail::to($to)->send(new MorningDigest($data, $settings));
        } catch (\Throwable $e) {
            Log::error('Failed to send morning digest: ' . $e->getMessage());
            $this->error('Failed to send: ' . $e->getMessage());
            return self::FAILURE;
        }

        $this->info(sprintf(
            'Morning digest sent to %s — %d visitors, %d companies, %d hot leads, %d messages.',
            $to,
            $data['visitors']['total'],
            $data['companies']->count(),
            $data['hotLeads']->count(),
            $data['messages']['total'],
        ));

        return self::SUCCESS;
    }
}
