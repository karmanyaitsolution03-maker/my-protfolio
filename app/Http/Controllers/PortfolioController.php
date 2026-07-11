<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\Availability;
use App\Models\CareerPoint;
use App\Models\Experience;
use App\Models\Project;
use App\Models\Setting;
use App\Models\SkillCategory;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class PortfolioController extends Controller
{
    public function home()
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
            'settings'        => $settings,
            'profileRows'     => $profileRows,
            'availability'    => $availability,
            'careerPoints'    => $careerPoints,
            'about'           => $about,
            'skillCategories' => $skillCategories,
            'experiences'     => $experiences,
            'projects'        => $projects,
            'achievements'    => $achievements,
        ]);
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
