<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MessageTriageService
{
    protected const CATEGORIES = ['recruiter', 'client', 'spam', 'other'];

    /** Classify + summarize a contact message. Returns null on any failure. */
    public function triage(string $name, string $email, string $message): ?array
    {
        $apiKey = config('services.openai.key');
        if (! $apiKey) {
            return null;
        }

        try {
            $response = Http::withToken($apiKey)
                ->timeout(10)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4o-mini',
                    'response_format' => ['type' => 'json_object'],
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'Classify the incoming portfolio contact message. Respond with JSON only: '
                                . '{"category": one of ' . implode('|', self::CATEGORIES) . ', "summary": a plain one-sentence summary under 20 words}.',
                        ],
                        [
                            'role' => 'user',
                            'content' => "Name: {$name}\nEmail: {$email}\nMessage: {$message}",
                        ],
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
            ];
        } catch (\Throwable $e) {
            Log::error('OpenAI triage error: ' . $e->getMessage());
            return null;
        }
    }
}
