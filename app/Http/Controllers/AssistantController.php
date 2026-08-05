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

        // exists() alone isn't enough to trust the cache: an earlier failed write can
        // leave a 0-byte (or otherwise empty) file behind, and since the filename is
        // deterministic from the text, that broken file would be served forever without
        // this check ever regenerating it.
        if (! $disk->exists($path) || $disk->size($path) === 0) {
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

                // put() returning true only means Flysystem didn't throw — it does not
                // guarantee the bytes actually landed on disk (silent failure on a full
                // disk, a quota limit, or a permissions/IO hiccup), so re-check the file
                // itself before ever handing back a URL for it.
                $disk->put($path, $response->body());
                if (! $disk->exists($path) || $disk->size($path) === 0) {
                    Log::error("TTS write failed or produced an empty file: {$path}");
                    return response()->json(['url' => null]);
                }
            } catch (\Throwable $e) {
                Log::error('TTS error: ' . $e->getMessage());
                return response()->json(['url' => null]);
            }
        }

        // Serve the audio bytes inline as a data: URI instead of a separate URL to
        // storage/app/public. The disk write still happens above purely as a cache
        // (so the same line is never re-generated via OpenAI), but the browser never
        // makes a second request to fetch it — it gets the bytes directly in this
        // response, which sidesteps any webserver/CDN/firewall rule that blocks or
        // interferes with direct requests to the storage path.
        $bytes = $disk->get($path);
        if ($bytes === null) {
            Log::error("TTS cache read failed after write: {$path}");
            return response()->json(['url' => null]);
        }

        return response()->json(['url' => 'data:audio/mpeg;base64,' . base64_encode($bytes)]);
    }

    public function chat(Request $request): JsonResponse
    {
        $data = $request->validate([
            'question' => 'required|string|max:500',
            'history'  => 'nullable|array|max:8',
            'history.*.role'    => 'required_with:history|in:user,assistant',
            'history.*.content' => 'required_with:history|string|max:1000',
            'mode'     => 'nullable|string|in:recruiter,client',
        ]);

        $apiKey = config('services.openai.key');
        if (! $apiKey) {
            return response()->json(['answer' => "The AI assistant isn't configured right now — feel free to use the contact form instead."]);
        }

        $messages = array_merge(
            [['role' => 'system', 'content' => $this->buildContext($data['mode'] ?? null)]],
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

    /**
     * A recruiter pastes a job description; the assistant scores how well the real
     * profile data fits it — matched skills, gaps, and an honest 0-100 estimate.
     * Never invents overlap that isn't actually in the profile data below.
     */
    public function match(Request $request): JsonResponse
    {
        $data = $request->validate([
            'jd' => 'required|string|min:20|max:4000',
        ]);

        $apiKey = config('services.openai.key');
        if (! $apiKey) {
            return response()->json(['ok' => false, 'message' => "The AI assistant isn't configured right now — feel free to use the contact form instead."]);
        }

        $settings = Setting::resolved();
        $profile = $this->profileData($settings);
        $firstName = $settings['first_name'] ?? $profile['name'];

        $systemPrompt = <<<PROMPT
        You are evaluating how well {$firstName} fits a job description a recruiter just pasted, using ONLY the real profile data below — never invent skills, experience, or numbers that aren't listed here.

        Respond with JSON only: {"fitScore": integer 0-100, "verdict": "one punchy sentence", "matched": ["short phrase", ... up to 8], "gaps": ["short phrase", ... up to 6, empty array if nothing notable is missing], "spoken": "the verdict again, but with any numbers/percentages spelled out as words for text-to-speech, e.g. \"eighty-two percent\" not \"82%\""}.

        fitScore is an honest estimate, never inflated — base it strictly on real overlap between the job description's requirements and the profile below. "matched" lists specific overlapping skills/technologies/experience; "gaps" lists specific things the JD asks for that aren't evidenced below.

        PROFILE — {$profile['name']}, {$settings['designation']}, {$settings['years']} years of experience, based in {$settings['location']}:

        SKILLS:
        {$profile['skills']}

        EXPERIENCE:
        {$profile['experience']}

        PROJECTS:
        {$profile['projects']}

        ACHIEVEMENTS:
        {$profile['achievements']}
        PROMPT;

        try {
            $response = Http::withToken($apiKey)
                ->timeout(20)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model'           => 'gpt-4o-mini',
                    'max_tokens'      => 500,
                    'response_format' => ['type' => 'json_object'],
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => "JOB DESCRIPTION:\n" . $data['jd']],
                    ],
                ]);

            if (! $response->successful()) {
                Log::error('JD match request failed: ' . $response->body());
                return response()->json(['ok' => false, 'message' => "Signal interference — couldn't analyze that right now. Try again in a moment."]);
            }

            $parsed = json_decode((string) $response->json('choices.0.message.content'), true);
            if (! is_array($parsed) || ! isset($parsed['fitScore'])) {
                return response()->json(['ok' => false, 'message' => "Couldn't make sense of that — try pasting the core requirements section of the job description."]);
            }

            $fitScore = max(0, min(100, (int) $parsed['fitScore']));
            $stringsOnly = fn ($v) => array_values(array_filter((array) $v, 'is_string'));

            return response()->json([
                'ok'       => true,
                'fitScore' => $fitScore,
                'verdict'  => (string) ($parsed['verdict'] ?? ''),
                'matched'  => array_slice($stringsOnly($parsed['matched'] ?? []), 0, 8),
                'gaps'     => array_slice($stringsOnly($parsed['gaps'] ?? []), 0, 6),
                'spoken'   => (string) ($parsed['spoken'] ?? $parsed['verdict'] ?? ''),
            ]);
        } catch (\Throwable $e) {
            Log::error('JD match error: ' . $e->getMessage());
            return response()->json(['ok' => false, 'message' => "Signal interference — couldn't analyze that right now. Try again in a moment."]);
        }
    }

    /** Section ids that exist on the public page — the assistant may ask the page to scroll to one of these. */
    protected const SECTIONS = ['deck', 'profile', 'skills', 'logs', 'projects', 'achievements', 'contact', 'career'];

    /**
     * The real portfolio data, formatted into text blocks — the single source of
     * truth both the chat assistant and the JD matcher ground their answers in.
     */
    protected function profileData(array $settings): array
    {
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

        return compact('name', 'skills', 'experience', 'projects', 'achievements', 'availability', 'careerPoints', 'about');
    }

    /**
     * Build a system prompt grounded in the real portfolio data.
     * $mode is the visitor's self-identified intent from the hero toggle (recruiter/
     * client/null) — steers which topics the assistant leads with, nothing more.
     */
    protected function buildContext(?string $mode = null): string
    {
        $settings = Setting::resolved();
        $profile = $this->profileData($settings);
        $name = $profile['name'];
        $firstName = $settings['first_name'] ?? $name;

        $modeNote = match ($mode) {
            'recruiter' => "This visitor identified themselves as hiring/a recruiter (via the hero toggle) — lean toward experience, availability, notice period, and the resume download when relevant.",
            'client'    => "This visitor identified themselves as looking to build something/a potential client (via the hero toggle) — lean toward projects, what he can build, and how to start a conversation when relevant.",
            default     => "",
        };

        return <<<PROMPT
You are "{$firstName}'s Assistant" — the AI assistant on {$name}'s personal portfolio website, answering questions from visitors (recruiters, clients, hiring managers) on their behalf. If asked who or what you are, say you're {$firstName}'s assistant — never claim to be ChatGPT, a generic AI, or name the underlying model/provider.

Your replies are shown as text AND read aloud by text-to-speech, so write numbers the way you'd say them out loud, not as raw digits. For money, use words like "3.84 lakh rupees" or "three lakh eighty-four thousand rupees" — never symbols or comma-grouped digits like "₹3,84,000". For years of experience or counts, spell out small numbers ("two and a half years") rather than digits with decimals.

Answer only using the information below. If something isn't covered, say you don't have that detail and suggest using the contact form. Keep answers short — 2-3 sentences max, unless the visitor asks for a full overview, in which case you may give a longer structured answer. Speak about {$name} in the third person. Do not invent experience, skills, or numbers not listed here. When relevant, proactively mention the contact form, WhatsApp, or resume download as ways to follow up.

{$modeNote}

Respond with a JSON object: {"segments": [{"section": id or null, "text": "..."}]}. Break your reply into segments in the order you'd naturally say them — each segment's "text" is one or two sentences about a single topic, and "section" is the page section id it belongs to (one of "deck", "profile", "skills", "logs", "projects", "achievements", "contact", "career" — "logs" is experience/work-history, "career" is availability/CTC/notice period), or null if that segment doesn't correspond to a page section (e.g. a closing "feel free to reach out" line). The page will scroll to each segment's section right as it's spoken, so keep sections in the order the visitor should see them. For a narrow question this is usually just one or two segments; for a broad "tell me everything" question, use one segment per topic (profile, skills, logs, projects, career, etc. — whichever are covered).

NAME: {$name}
DESIGNATION: {$settings['designation']}
LOCATION: {$settings['location']}
YEARS OF EXPERIENCE: {$settings['years']}

ABOUT:
{$profile['about']}

WHY WORK WITH HIM:
{$profile['careerPoints']}

SKILLS:
{$profile['skills']}

EXPERIENCE:
{$profile['experience']}

PROJECTS:
{$profile['projects']}

ACHIEVEMENTS:
{$profile['achievements']}

AVAILABILITY:
{$profile['availability']}

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
