<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MessageTriageService
{
    protected const CATEGORIES = ['recruiter', 'client', 'spam', 'other'];

    /**
     * Classify a contact message, summarize it, and draft a ready-to-send reply in
     * the owner's voice (grounded in their real portfolio settings, not invented).
     * $settings is the resolved Setting::resolved() array — used only for context,
     * safe to omit. Returns null on any failure.
     */
    public function triage(string $name, string $email, string $message, array $settings = []): ?array
    {
        $apiKey = config('services.openai.key');
        if (! $apiKey) {
            return null;
        }

        $ownerFirst      = $settings['first_name'] ?? 'the portfolio owner';
        $designation     = $settings['designation'] ?? '';
        $statusLabel     = $settings['status_label'] ?? '';
        $responseTime    = $settings['response_time'] ?? '';
        $location        = $settings['location'] ?? '';
        $timezone        = $settings['timezone'] ?? '';
        $categories      = implode('|', self::CATEGORIES);

        $systemPrompt = <<<PROMPT
        Triage an incoming portfolio contact message on behalf of {$ownerFirst}.

        Respond with JSON only: {"category": one of {$categories}, "summary": a plain one-sentence summary under 20 words, "reply": a ready-to-send reply}.

        For "reply": write it in first person as {$ownerFirst}, addressed to the sender by name. Thank them, respond to what they specifically asked, and naturally mention availability or response time only if it's relevant to their question. Under 120 words, no subject line, no signature (one is added automatically when it's sent). Never invent experience, rates, or availability beyond what's given below. If category is "spam", leave "reply" as an empty string.

        Context about {$ownerFirst}:
        Designation: {$designation}
        Availability status: {$statusLabel}
        Typical response time: {$responseTime}
        Location / timezone: {$location} ({$timezone})
        PROMPT;

        try {
            $response = Http::withToken($apiKey)
                ->timeout(12)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model'      => 'gpt-4o-mini',
                    'max_tokens' => 400,
                    'response_format' => ['type' => 'json_object'],
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => "Name: {$name}\nEmail: {$email}\nMessage: {$message}"],
                    ],
                ]);

            if (! $response->successful()) {
                Log::error('OpenAI triage request failed: ' . $response->body());
                return null;
            }

            $content = $response->json('choices.0.message.content');
            $parsed = json_decode((string) $content, true);

            if (! is_array($parsed) || ! in_array($parsed['category'] ?? null, self::CATEGORIES, true)) {
                return null;
            }

            return [
                'category' => $parsed['category'],
                'summary'  => (string) ($parsed['summary'] ?? ''),
                'reply'    => trim((string) ($parsed['reply'] ?? '')),
            ];
        } catch (\Throwable $e) {
            Log::error('OpenAI triage error: ' . $e->getMessage());
            return null;
        }
    }
}
