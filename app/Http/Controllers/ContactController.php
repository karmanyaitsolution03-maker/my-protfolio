<?php

namespace App\Http\Controllers;

use App\Mail\ContactAutoReply;
use App\Mail\NewContactMessage;
use App\Models\Message;
use App\Models\Setting;
use App\Services\MessageTriageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        // Honeypot: real visitors never see or fill this field, only bots do.
        // Pretend success so the bot doesn't know to adapt, but do nothing.
        if ($request->filled('hp_check')) {
            return response()->json(['ok' => true]);
        }

        $data = $request->validate([
            'name'    => 'required|string|max:120',
            'email'   => 'required|email|max:190',
            'message' => 'required|string|max:5000',
        ]);

        $message = Message::create($data);

        $triage = app(MessageTriageService::class)->triage($data['name'], $data['email'], $data['message']);
        if ($triage) {
            $message->update(['ai_category' => $triage['category'], 'ai_summary' => $triage['summary']]);
        }

        $settings = Setting::resolved();
        $settings['name'] = trim(($settings['first_name'] ?? '') . ' ' . ($settings['last_name'] ?? ''));

        $notifyAddress = $settings['email'] ?? config('mail.from.address');

        if ($notifyAddress) {
            try {
                Mail::to($notifyAddress)->send(new NewContactMessage($message));
            } catch (\Throwable $e) {
                Log::error('Failed to send contact notification email: ' . $e->getMessage());
            }
        }

        try {
            Mail::to($message->email)->send(new ContactAutoReply($message, $settings));
        } catch (\Throwable $e) {
            Log::error('Failed to send contact auto-reply email: ' . $e->getMessage());
        }

        return response()->json(['ok' => true]);
    }
}
