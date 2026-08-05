<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AdminReply;
use App\Models\Achievement;
use App\Models\Availability;
use App\Models\CareerPoint;
use App\Models\Experience;
use App\Models\Interaction;
use App\Models\Message;
use App\Models\Project;
use App\Models\Setting;
use App\Models\Skill;
use App\Models\SkillCategory;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    /**
     * Generic CRUD config.
     * Field types: text, number, textarea, checkbox, json, category (skill category select)
     */
    protected array $resources = [
        'skill-categories' => [
            'model'  => SkillCategory::class,
            'title'  => 'Skill Categories',
            'list'   => ['name', 'icon', 'wide', 'position'],
            'fields' => [
                'name'     => ['text', 'Name'],
                'icon'     => ['text', 'Icon (emoji)'],
                'wide'     => ['checkbox', 'Wide card (spans 3 columns)'],
                'position' => ['number', 'Order'],
            ],
        ],
        'skills' => [
            'model'  => Skill::class,
            'title'  => 'Skills',
            'list'   => ['name', 'level', 'skill_category_id', 'position'],
            'fields' => [
                'skill_category_id' => ['category', 'Category'],
                'name'              => ['text', 'Name'],
                'level'             => ['number', 'Level % (0–100)'],
                'position'          => ['number', 'Order'],
            ],
        ],
        'experiences' => [
            'model'  => Experience::class,
            'title'  => 'Experiences (Mission Logs)',
            'list'   => ['company', 'role', 'period', 'live', 'position'],
            'fields' => [
                'company'          => ['text', 'Company'],
                'sub'              => ['text', 'Sub line (e.g. parent company)'],
                'role'             => ['text', 'Role'],
                'period'           => ['text', 'Period (e.g. 2024 — 2026)'],
                'live'             => ['checkbox', 'Live transmission (current job)'],
                'responsibilities' => ['json', 'Responsibilities — JSON array, e.g. ["Backend development","Bug tracking"]'],
                'position'         => ['number', 'Order'],
            ],
        ],
        'projects' => [
            'model'  => Project::class,
            'title'  => 'Projects',
            'list'   => ['title', 'kicker', 'color', 'wide', 'position'],
            'fields' => [
                'title'       => ['text', 'Title'],
                'kicker'      => ['text', 'Kicker (e.g. HEALTHCARE MANAGEMENT PLATFORM)'],
                'description' => ['textarea', 'Description'],
                'color'       => ['text', 'Accent color (hex, e.g. #54F0A8)'],
                'wide'        => ['checkbox', 'Wide card (featured)'],
                'arch'        => ['json', 'Architecture — JSON: [{"label":"WEB + MOBILE","sub":"CLIENTS"},{"label":"LARAVEL API","sub":"AUTH","hot":true},{"label":"MYSQL","sub":"CORE DB"}]'],
                'metrics'     => ['json', 'Metrics — JSON: [{"value":"30+","label":"ENDPOINTS"}]'],
                'tags'        => ['json', 'Tags — JSON: ["Laravel","MySQL"]'],
                'position'    => ['number', 'Order'],
            ],
        ],
        'availability' => [
            'model'  => Availability::class,
            'title'  => 'Availability (Career Snapshot)',
            'list'   => ['label', 'value', 'accent', 'position'],
            'fields' => [
                'label'    => ['text', 'Label (e.g. CURRENT CTC)'],
                'value'    => ['text', 'Value (e.g. ₹3,00,000)'],
                'accent'   => ['text', 'Accent color — leave blank, or "cyan" / "green" to highlight'],
                'position' => ['number', 'Order'],
            ],
        ],
        'career-points' => [
            'model'  => CareerPoint::class,
            'title'  => 'Career Highlight Points',
            'list'   => ['text', 'position'],
            'fields' => [
                'text'     => ['textarea', 'Point text (HTML allowed, e.g. I have a <b>2-month notice period</b>.)'],
                'position' => ['number', 'Order'],
            ],
        ],
        'achievements' => [
            'model'  => Achievement::class,
            'title'  => 'Achievements',
            'list'   => ['value', 'count', 'label', 'position'],
            'fields' => [
                'count'    => ['number', 'Count (animated, e.g. 2). Leave empty to show text value instead'],
                'suffix'   => ['text', 'Suffix after count (default +)'],
                'value'    => ['text', 'Text value (used when count empty, e.g. API)'],
                'label'    => ['text', 'Label'],
                'position' => ['number', 'Order'],
            ],
        ],
    ];

    /* ---------- auth (email + password against users table) ---------- */

    protected function gate(): void
    {
        abort_unless(session('is_admin') === true, 403);
    }

    public function loginForm()
    {
        return session('is_admin') === true
            ? redirect()->route('admin.dashboard')
            : view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if ($user && Hash::check($credentials['password'], $user->password)) {
            session(['is_admin' => true]);
            return redirect()->route('admin.dashboard');
        }

        return back()
            ->withErrors(['email' => 'Invalid email or password.'])
            ->onlyInput('email');
    }

    public function logout()
    {
        session()->forget('is_admin');
        return redirect()->route('admin.login');
    }

    /* ---------- pages ---------- */

    public function dashboard()
    {
        $this->gate();
        $counts = [];
        foreach ($this->resources as $key => $cfg) {
            $counts[$key] = ['title' => $cfg['title'], 'count' => $cfg['model']::count()];
        }
        // Reverse-IP company lookup is a live, cached-per-IP HTTP call (see Visit::getCompanyAttribute),
        // so only run it over a small, recent slice of visits — keeps the dashboard fast even on a
        // cold cache — and surface just the ones that actually resolved to a real company.
        $notableVisitors = Visit::whereNotNull('ip_address')
            ->latest('updated_at')
            ->limit(15)
            ->get()
            ->filter(fn ($v) => $v->company !== null)
            ->take(6)
            ->values();

        $hotLeadQuery = Visit::hotLeadsQuery();
        $hotLeadsTotal = (clone $hotLeadQuery)->count();
        $hotLeads = $hotLeadQuery->latest('updated_at')->limit(10)->get();

        $visits        = Visit::sum('visit_count');
        $projectViews  = Interaction::where('type', 'view_projects')->count();
        $contactClicks = Interaction::where('type', 'contact_click')->count();
        $messages      = Message::count();

        $funnel = $this->buildFunnel([
            ['label' => 'Visits',          'value' => $visits],
            ['label' => 'Project views',   'value' => $projectViews],
            ['label' => 'Contact clicks',  'value' => $contactClicks],
            ['label' => 'Submissions',     'value' => $messages],
        ]);

        return view('admin.dashboard', [
            'counts'        => $counts,
            'messages'      => $messages,
            'visits'        => $visits,
            'contactClicks'  => $contactClicks,
            'resumeClicks'   => Interaction::where('type', 'resume_click')->count(),
            'whatsappClicks' => Interaction::where('type', 'whatsapp_click')->count(),
            'nudgeShown'      => Interaction::where('type', 'nudge_shown')->count(),
            'nudgeResume'     => Interaction::where('type', 'nudge_resume_click')->count(),
            'nudgeCall'       => Interaction::where('type', 'nudge_call_click')->count(),
            'nudgeDismissed'  => Interaction::where('type', 'nudge_dismissed')->count(),
            'notableVisitors' => $notableVisitors,
            'hotLeads'        => $hotLeads,
            'hotLeadsTotal'   => $hotLeadsTotal,
            'funnel'          => $funnel,
        ]);
    }

    /**
     * Turns an ordered list of ['label' => ..., 'value' => ...] stages into a funnel:
     * each stage's share of the top stage (for bar width), its retention from the
     * previous stage, and which single stage bleeds the most visitors — the one
     * thing worth fixing first.
     */
    protected function buildFunnel(array $stages): array
    {
        $top = $stages[0]['value'] ?? 0;
        $prevValue = null;
        $worstIndex = null;
        $worstDropPct = -1;

        foreach ($stages as $i => &$stage) {
            $stage['pct_of_top'] = $top > 0 ? round($stage['value'] / $top * 100) : 0;

            if ($i === 0) {
                $stage['pct_of_prev'] = null;
                $stage['drop_off_pct'] = null;
            } else {
                $stage['pct_of_prev'] = $prevValue > 0 ? round($stage['value'] / $prevValue * 100) : 0;
                $stage['drop_off_pct'] = 100 - $stage['pct_of_prev'];
                if ($stage['drop_off_pct'] > $worstDropPct) {
                    $worstDropPct = $stage['drop_off_pct'];
                    $worstIndex = $i;
                }
            }

            $prevValue = $stage['value'];
        }
        unset($stage);

        return [
            'stages'        => $stages,
            'worst_index'   => $worstIndex,
            'worst_drop_pct' => $worstDropPct,
        ];
    }

    public function migrate()
    {
        $this->gate();

        Artisan::call('migrate', ['--force' => true]);

        return back()->with('ok', trim(Artisan::output()) ?: 'Migrations are already up to date.');
    }

    public function settings()
    {
        $this->gate();
        return view('admin.settings', [
            'groups' => Setting::groups(),
            'values' => Setting::resolved(),
        ]);
    }

    public function settingsSave(Request $request)
    {
        $this->gate();

        $request->validate([
            'settings.resume_file' => 'nullable|file|mimes:pdf|max:10240',
            'settings.og_image'    => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
            'settings.photo'       => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
        ]);

        foreach ((array) $request->input('settings', []) as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        foreach ((array) $request->file('settings', []) as $key => $file) {
            if ($file && $file->isValid()) {
                $folder = match ($key) {
                    'og_image' => 'og-images',
                    'photo'    => 'profile',
                    default    => 'resumes',
                };
                $path = $file->storeAs($folder, $key . '.' . $file->getClientOriginalExtension(), 'public');
                Setting::updateOrCreate(['key' => $key], ['value' => $path]);
            }
        }

        return back()->with('ok', 'Settings saved.');
    }

    public function messages(Request $request)
    {
        $this->gate();
        $q = trim((string) $request->query('q', ''));

        $unreadIds = Message::whereNull('read_at')->pluck('id')->all();
        $rows = Message::when($q !== '', fn ($query) => $query->where(function ($w) use ($q) {
                $w->where('name', 'like', "%{$q}%")
                  ->orWhere('email', 'like', "%{$q}%")
                  ->orWhere('message', 'like', "%{$q}%");
            }))
            ->latest()
            ->paginate(20)
            ->withQueryString();
        Message::whereNull('read_at')->update(['read_at' => now()]);

        return view('admin.messages', ['rows' => $rows, 'unreadIds' => $unreadIds, 'q' => $q]);
    }

    public function messagesExport(Request $request): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $this->gate();
        $q = trim((string) $request->query('q', ''));

        $rows = Message::when($q !== '', fn ($query) => $query->where(function ($w) use ($q) {
                $w->where('name', 'like', "%{$q}%")
                  ->orWhere('email', 'like', "%{$q}%")
                  ->orWhere('message', 'like', "%{$q}%");
            }))
            ->latest()
            ->get();

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Date', 'Name', 'Email', 'Message']);
            foreach ($rows as $m) {
                fputcsv($out, [$m->created_at->format('Y-m-d H:i'), $m->name, $m->email, $m->message]);
            }
            fclose($out);
        }, 'messages-' . now()->format('Y-m-d') . '.csv', ['Content-Type' => 'text/csv']);
    }

    public function messageDelete(Message $message)
    {
        $this->gate();
        $message->delete();
        return back()->with('ok', 'Message deleted.');
    }

    /** Send the (possibly hand-edited) AI-drafted reply — one click from the Messages page. */
    public function messageReply(Request $request, Message $message)
    {
        $this->gate();
        $data = $request->validate(['reply' => 'required|string|max:5000']);

        $settings = Setting::resolved();
        $settings['name'] = trim(($settings['first_name'] ?? '') . ' ' . ($settings['last_name'] ?? ''));

        try {
            Mail::to($message->email)->send(new AdminReply($message, $data['reply'], $settings));
        } catch (\Throwable $e) {
            Log::error('Failed to send admin reply email: ' . $e->getMessage());
            return back()->withErrors(['reply' => 'Failed to send — please try again.']);
        }

        $message->update(['ai_reply_draft' => $data['reply'], 'replied_at' => now()]);

        return back()->with('ok', 'Reply sent to ' . $message->email . '.');
    }

    public function visitors()
    {
        $this->gate();
        return view('admin.visitors', [
            'total'      => Visit::sum('visit_count'),
            'uniqueIps'  => Visit::whereNotNull('ip_address')->count(),
            'today'      => Visit::whereDate('updated_at', now()->toDateString())->count(),
            'rows'       => Visit::latest('updated_at')->paginate(30),
        ]);
    }

    /* ---------- generic CRUD ---------- */

    protected function cfg(string $resource): array
    {
        abort_unless(isset($this->resources[$resource]), 404);
        return $this->resources[$resource];
    }

    public function index(string $resource)
    {
        $this->gate();
        $cfg = $this->cfg($resource);
        return view('admin.index', [
            'resource' => $resource,
            'cfg'      => $cfg,
            'rows'     => $cfg['model']::orderBy('position')->orderBy('id')->get(),
        ]);
    }

    public function create(string $resource)
    {
        $this->gate();
        return view('admin.form', [
            'resource'   => $resource,
            'cfg'        => $this->cfg($resource),
            'row'        => null,
            'categories' => SkillCategory::orderBy('position')->get(),
        ]);
    }

    public function store(Request $request, string $resource)
    {
        $this->gate();
        $cfg = $this->cfg($resource);
        $cfg['model']::create($this->payload($request, $cfg));
        return redirect()->route('admin.res.index', $resource)->with('ok', 'Created.');
    }

    public function edit(string $resource, int $id)
    {
        $this->gate();
        $cfg = $this->cfg($resource);
        return view('admin.form', [
            'resource'   => $resource,
            'cfg'        => $cfg,
            'row'        => $cfg['model']::findOrFail($id),
            'categories' => SkillCategory::orderBy('position')->get(),
        ]);
    }

    public function update(Request $request, string $resource, int $id)
    {
        $this->gate();
        $cfg = $this->cfg($resource);
        $cfg['model']::findOrFail($id)->update($this->payload($request, $cfg));
        return redirect()->route('admin.res.index', $resource)->with('ok', 'Updated.');
    }

    public function destroy(string $resource, int $id)
    {
        $this->gate();
        $cfg = $this->cfg($resource);
        $cfg['model']::findOrFail($id)->delete();
        return back()->with('ok', 'Deleted.');
    }

    /** Normalize the submitted form into a model payload. */
    protected function payload(Request $request, array $cfg): array
    {
        $data = [];
        foreach ($cfg['fields'] as $name => [$type]) {
            $value = $request->input($name);
            $data[$name] = match ($type) {
                'checkbox' => $request->boolean($name),
                'number'   => $value === null || $value === '' ? null : (int) $value,
                'json'     => $this->decodeJson($value, $name),
                default    => $value,
            };
        }
        return $data;
    }

    protected function decodeJson(?string $value, string $field): ?array
    {
        if ($value === null || trim($value) === '') {
            return null;
        }
        $decoded = json_decode($value, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            abort(422, "Field '{$field}' must be valid JSON: " . json_last_error_msg());
        }
        return $decoded;
    }
}
