<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\Availability;
use App\Models\CareerPoint;
use App\Models\Experience;
use App\Models\Project;
use App\Models\Setting;
use App\Models\SkillCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class PortfolioController extends Controller
{
    public function home(Request $request)
    {
        $skillCategories = SkillCategory::with('skills')->orderBy('position')->get();
        $experiences     = Experience::orderBy('position')->get();
        $projects        = Project::orderBy('position')->get();
        $achievements    = Achievement::orderBy('position')->get();
        $availability    = Availability::orderBy('position')->orderBy('id')->get();
        $careerPoints    = CareerPoint::orderBy('position')->orderBy('id')->get();

        $settings = Setting::resolved();
        $settings = $this->applyTokens($settings, $experiences->count(), $projects->count(), $achievements->count());

        // Profile report rows (Module 01). "v" supports raw HTML for counters.
        $profileRows = [
            ['k' => 'NAME',              'v' => strtoupper($settings['name']),                                            'cls' => ''],
            ['k' => 'DESIGNATION',       'v' => strtoupper($settings['designation']),                                    'cls' => 'cy'],
            ['k' => 'CORE FRAMEWORKS',   'v' => strtoupper($settings['frameworks']),                                     'cls' => ''],
            ['k' => 'SPECIALIZATION',    'v' => strtoupper($settings['specialization']),                                 'cls' => ''],
            ['k' => 'FIELD EXPERIENCE',  'v' => '<span data-count="' . (float) $settings['years'] . '">0</span>+ YEARS', 'cls' => ''],
            ['k' => 'BASE OF OPERATIONS','v' => strtoupper($settings['location']),                                       'cls' => ''],
            ['k' => 'RELIABILITY SCORE', 'v' => 'EXCEPTIONAL ★',                                                          'cls' => 'gn'],
            ['k' => 'STATUS',            'v' => '● ' . strtoupper($settings['status_label']),                            'cls' => 'gn'],
        ];

        $about = array_values(array_filter([
            $settings['about_1'] ?? null,
            $settings['about_2'] ?? null,
            $settings['about_3'] ?? null,
        ]));

        return view('portfolio', [
            'settings'          => $settings,
            'profileRows'       => $profileRows,
            'availability'      => $availability,
            'careerPoints'      => $careerPoints,
            'about'             => $about,
            'skillCategories'   => $skillCategories,
            'experiences'       => $experiences,
            'projects'          => $projects,
            'achievements'      => $achievements,
            'referrerGreeting'  => $this->referrerGreeting($request),
            'suggestedMode'     => $this->suggestedMode($request),
        ]);
    }

    /** Lowercased referrer host with any "www." stripped, or '' when there isn't one. */
    protected function refererHost(Request $request): string
    {
        $referrer = $request->headers->get('referer');
        if (! $referrer) {
            return '';
        }

        $host = strtolower((string) parse_url($referrer, PHP_URL_HOST));
        return preg_replace('/^www\./', '', $host);
    }

    /**
     * A one-line "thanks for coming from X" greeting spliced into the intro narration
     * when the visitor arrived via a link on a known platform. Null for direct visits,
     * search engines, or anywhere unrecognized — not every visit needs a special line.
     */
    protected function referrerGreeting(Request $request): ?string
    {
        $host = $this->refererHost($request);
        if ($host === '') {
            return null;
        }

        return match (true) {
            str_contains($host, 'linkedin.com')  => 'Thanks for connecting on LinkedIn!',
            str_contains($host, 'github.com')    => 'Thanks for stopping by from GitHub!',
            in_array($host, ['twitter.com', 'x.com'], true) => 'Thanks for the visit from X!',
            str_contains($host, 'instagram.com') => 'Thanks for stopping by from Instagram!',
            str_contains($host, 'facebook.com')  => 'Thanks for stopping by from Facebook!',
            str_contains($host, 'reddit.com')    => 'Thanks for the visit from Reddit!',
            str_contains($host, 'wa.me'), str_contains($host, 'whatsapp.com') => 'Welcome in from WhatsApp!',
            default => null,
        };
    }

    /**
     * Best-guess visitor intent from the referrer, used only to pre-select the
     * recruiter/client toggle — the visitor can always override it with one tap,
     * so a wrong guess costs nothing.
     */
    protected function suggestedMode(Request $request): ?string
    {
        $host = $this->refererHost($request);
        if ($host === '') {
            return null;
        }

        return match (true) {
            str_contains($host, 'linkedin.com'),
            str_contains($host, 'naukri.com'),
            str_contains($host, 'indeed.com'),
            str_contains($host, 'glassdoor.com') => 'recruiter',

            str_contains($host, 'github.com'),
            str_contains($host, 'dev.to'),
            str_contains($host, 'news.ycombinator.com'),
            str_contains($host, 'producthunt.com') => 'client',

            default => null,
        };
    }

    /** Replace :name/:first/:last/:exp/:proj/:ach tokens across all setting values. */
    protected function applyTokens(array $settings, int $exp, int $proj, int $ach): array
    {
        $first = $settings['first_name'] ?? '';
        $last  = $settings['last_name'] ?? '';
        $settings['name'] = trim($first . ' ' . $last);

        $map = [
            ':name'  => $settings['name'],
            ':first' => $first,
            ':last'  => $last,
            ':exp'   => (string) $exp,
            ':proj'  => (string) $proj,
            ':ach'   => (string) $ach,
        ];

        foreach ($settings as $key => $value) {
            if (is_string($value) && str_contains($value, ':')) {
                $settings[$key] = strtr($value, $map);
            }
        }
        return $settings;
    }

    public function resume(): Response
    {
        $settings = Setting::resolved();
        $name = trim(($settings['first_name'] ?? 'Rishabh') . ' ' . ($settings['last_name'] ?? 'Parekh'));

        if (!empty($settings['resume_file']) && Storage::disk('public')->exists($settings['resume_file'])) {
            return response()->download(
                Storage::disk('public')->path($settings['resume_file']),
                str_replace(' ', '_', $name) . '_Resume.pdf'
            );
        }

        $lines = ["=== SUBJECT FILE: " . strtoupper($name) . " ===", ''];
        $lines[] = 'DESIGNATION: ' . ($settings['designation'] ?? 'Backend Software Engineer');
        $lines[] = 'CORE: ' . ($settings['frameworks'] ?? 'Laravel & Symfony') . ' · ' . ($settings['specialization'] ?? 'API Development');
        $lines[] = 'BASE: ' . ($settings['location'] ?? 'Gujarat, India') . ' · EXPERIENCE: ' . ($settings['years'] ?? '2') . '+ years';
        $lines[] = '';
        $lines[] = 'EXPERIENCE DATABASE:';
        foreach (Experience::orderBy('position')->get() as $i => $xp) {
            $lines[] = sprintf('  MISSION LOG %02d · %s — %s (%s)', $i + 1, $xp->period, $xp->company, $xp->role);
            if ($xp->responsibilities) {
                $lines[] = '    ' . implode(' · ', $xp->responsibilities);
            }
        }
        $lines[] = '';
        $lines[] = 'PROJECT RECORDS: ' . Project::orderBy('position')->pluck('title')->implode(' · ');
        $lines[] = '';
        $lines[] = 'COMMS: ' . ($settings['email'] ?? '') . ' · ' . ($settings['linkedin_label'] ?? '');

        return response(implode("\n", $lines), 200, [
            'Content-Type'        => 'text/plain; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . str_replace(' ', '_', $name) . '_Subject_File.txt"',
        ]);
    }
}
