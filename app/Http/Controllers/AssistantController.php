<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\Availability;
use App\Models\CareerPoint;
use App\Models\Experience;
use App\Models\Project;
use App\Models\Setting;
use App\Models\SkillCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AssistantController extends Controller
{
    /** The single voice used everywhere — same MP3 audio on every device, no browser/OS voices. */
    protected const TTS_VOICE = 'nova';
    protected const TTS_MODEL = 'gpt-4o-mini-tts';
    /**
     * Pace/tone is steered via natural-language instructions rather than the
     * `speed` API parameter — `speed` does a blunt post-process time-stretch on
     * this model and makes words run together unnaturally at anything but 1.0.
     */
    protected const TTS_INSTRUCTIONS = 'Speak in a warm, natural, professional tone at a normal, natural conversational pace — '
        . 'not slow, not rushed. Enunciate each word clearly and take a brief, natural pause at every comma and full stop.';

    /**
     * Turn a line of text into speech via OpenAI TTS and return the MP3 URL.
     * Identical text (with the same voice/speed) is generated once and reused
     * from disk on every later request — never regenerated, never a browser voice.
     */
    public function speak(Request $request): JsonResponse
    {
        $data = $request->validate([
            'text' => 'required|string|max:800',
        ]);

        $apiKey = config('services.openai.key');
        if (! $apiKey) {
            return response()->json(['url' => null]);
        }

        $text = trim($data['text']);
        if ($text === '') {
            return response()->json(['url' => null]);
        }

        $hash = sha1(self::TTS_VOICE . '|' . self::TTS_INSTRUCTIONS . '|' . $text);
        $path = "tts/{$hash}.mp3";
        $disk = Storage::disk('public');

        if (! $disk->exists($path)) {
            try {
                $response = Http::withToken($apiKey)
                    ->timeout(30)
                    ->post('https://api.openai.com/v1/audio/speech', [
                        'model'           => self::TTS_MODEL,
                        'voice'           => self::TTS_VOICE,
                        'instructions'    => self::TTS_INSTRUCTIONS,
                        'response_format' => 'mp3',
                        'input'           => $text,
                    ]);

                if (! $response->successful()) {
                    Log::error('TTS request failed: ' . $response->body());
                    return response()->json(['url' => null]);
                }

                $disk->put($path, $response->body());
            } catch (\Throwable $e) {
                Log::error('TTS error: ' . $e->getMessage());
                return response()->json(['url' => null]);
            }
        }

        return response()->json(['url' => $disk->url($path)]);
    }

    public function chat(Request $request): JsonResponse
    {
        $data = $request->validate([
            'question' => 'required|string|max:500',
            'history'  => 'nullable|array|max:8',
            'history.*.role'    => 'required_with:history|in:user,assistant',
            'history.*.content' => 'required_with:history|string|max:1000',
        ]);

        $apiKey = config('services.openai.key');
        if (! $apiKey) {
            return response()->json(['answer' => "The AI assistant isn't configured right now — feel free to use the contact form instead."]);
        }

        $messages = array_merge(
            [['role' => 'system', 'content' => $this->buildContext()]],
            $data['history'] ?? [],
            [['role' => 'user', 'content' => $data['question']]]
        );

        try {
            $response = Http::withToken($apiKey)
                ->timeout(15)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model'           => 'gpt-4o-mini',
                    'max_tokens'      => 450,
                    'response_format' => ['type' => 'json_object'],
                    'messages'        => $messages,
                ]);

            if (! $response->successful()) {
                Log::error('Assistant chat request failed: ' . $response->body());
                return response()->json(['answer' => "Signal interference — couldn't reach the AI right now. Try the contact form below."]);
            }

            $parsed = json_decode((string) $response->json('choices.0.message.content'), true);
            $rawSegments = array_slice((array) ($parsed['segments'] ?? []), 0, 6);

            $segments = [];
            foreach ($rawSegments as $seg) {
                $text = trim((string) ($seg['text'] ?? ''));
                if ($text === '') {
                    continue;
                }
                $section = $seg['section'] ?? null;
                $segments[] = [
                    'section' => in_array($section, self::SECTIONS, true) ? $section : null,
                    'text'    => $text,
                ];
            }

            if (empty($segments)) {
                return response()->json(['answer' => "I don't have a good answer for that — try the contact form below.", 'segments' => []]);
            }

            $answer = implode(' ', array_column($segments, 'text'));

            return response()->json(['answer' => $answer, 'segments' => $segments]);
        } catch (\Throwable $e) {
            Log::error('Assistant chat error: ' . $e->getMessage());
            return response()->json(['answer' => "Signal interference — couldn't reach the AI right now. Try the contact form below."]);
        }
    }

    /** Section ids that exist on the public page — the assistant may ask the page to scroll to one of these. */
    protected const SECTIONS = ['deck', 'profile', 'skills', 'logs', 'projects', 'achievements', 'contact', 'career'];

    /** Build a system prompt grounded in the real portfolio data. */
    protected function buildContext(): string
    {
        $settings = Setting::resolved();
        $name = trim(($settings['first_name'] ?? '') . ' ' . ($settings['last_name'] ?? ''));

        $skills = SkillCategory::with('skills')->orderBy('position')->get()
            ->map(fn ($cat) => $cat->name . ': ' . $cat->skills->pluck('name')->implode(', '))
            ->implode("\n");

        $experience = Experience::orderBy('position')->get()
            ->map(fn ($e) => "{$e->period} — {$e->role} at {$e->company}" . ($e->responsibilities ? ' (' . implode('; ', (array) $e->responsibilities) . ')' : ''))
            ->implode("\n");

        $projects = Project::orderBy('position')->get()
            ->map(fn ($p) => $p->title . ($p->description ? ': ' . $p->description : ''))
            ->implode("\n");

        $achievements = Achievement::orderBy('position')->get()
            ->map(fn ($a) => ($a->count !== null ? $a->count . $a->suffix : $a->value) . ' ' . $a->label)
            ->implode("\n");

        $availability = Availability::orderBy('position')->get()
            ->map(fn ($a) => "{$a->label}: {$a->value}")
            ->implode("\n");

        $careerPoints = CareerPoint::orderBy('position')->pluck('text')
            ->map(fn ($t) => strip_tags((string) $t))
            ->implode("\n");

        $about = collect([$settings['about_1'] ?? null, $settings['about_2'] ?? null, $settings['about_3'] ?? null])
            ->filter()
            ->map(fn ($t) => strip_tags($t))
            ->implode(' ');

        $firstName = $settings['first_name'] ?? $name;

        return <<<PROMPT
You are "{$firstName}'s Assistant" — the AI assistant on {$name}'s personal portfolio website, answering questions from visitors (recruiters, clients, hiring managers) on their behalf. If asked who or what you are, say you're {$firstName}'s assistant — never claim to be ChatGPT, a generic AI, or name the underlying model/provider.

Your replies are shown as text AND read aloud by text-to-speech, so write numbers the way you'd say them out loud, not as raw digits. For money, use words like "3.84 lakh rupees" or "three lakh eighty-four thousand rupees" — never symbols or comma-grouped digits like "₹3,84,000". For years of experience or counts, spell out small numbers ("two and a half years") rather than digits with decimals.

Answer only using the information below. If something isn't covered, say you don't have that detail and suggest using the contact form. Keep answers short — 2-3 sentences max, unless the visitor asks for a full overview, in which case you may give a longer structured answer. Speak about {$name} in the third person. Do not invent experience, skills, or numbers not listed here. When relevant, proactively mention the contact form, WhatsApp, or resume download as ways to follow up.

Respond with a JSON object: {"segments": [{"section": id or null, "text": "..."}]}. Break your reply into segments in the order you'd naturally say them — each segment's "text" is one or two sentences about a single topic, and "section" is the page section id it belongs to (one of "deck", "profile", "skills", "logs", "projects", "achievements", "contact", "career" — "logs" is experience/work-history, "career" is availability/CTC/notice period), or null if that segment doesn't correspond to a page section (e.g. a closing "feel free to reach out" line). The page will scroll to each segment's section right as it's spoken, so keep sections in the order the visitor should see them. For a narrow question this is usually just one or two segments; for a broad "tell me everything" question, use one segment per topic (profile, skills, logs, projects, career, etc. — whichever are covered).

NAME: {$name}
DESIGNATION: {$settings['designation']}
LOCATION: {$settings['location']}
YEARS OF EXPERIENCE: {$settings['years']}

ABOUT:
{$about}

WHY WORK WITH HIM:
{$careerPoints}

SKILLS:
{$skills}

EXPERIENCE:
{$experience}

PROJECTS:
{$projects}

ACHIEVEMENTS:
{$achievements}

AVAILABILITY:
{$availability}

CONTACT:
Email: {$settings['email']}
LinkedIn: {$settings['linkedin_label']} ({$settings['linkedin']})
WhatsApp: available via the WhatsApp button in the contact section
Response time: {$settings['response_time']}
Timezone: {$settings['timezone']}
Resume: downloadable via the "DOWNLOAD RESUME" link in the contact section
PROMPT;
    }
}
