<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use App\Models\Experience;
use App\Models\Message;
use App\Models\Project;
use App\Models\Setting;
use App\Models\Skill;
use App\Models\SkillCategory;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
        return view('admin.dashboard', [
            'counts'   => $counts,
            'messages' => Message::count(),
            'visits'   => Visit::count(),
        ]);
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
        ]);

        foreach ((array) $request->input('settings', []) as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        foreach ((array) $request->file('settings', []) as $key => $file) {
            if ($file && $file->isValid()) {
                $path = $file->storeAs('resumes', $key . '.' . $file->getClientOriginalExtension(), 'public');
                Setting::updateOrCreate(['key' => $key], ['value' => $path]);
            }
        }

        return back()->with('ok', 'Settings saved.');
    }

    public function messages()
    {
        $this->gate();
        return view('admin.messages', ['rows' => Message::latest()->paginate(20)]);
    }

    public function messageDelete(Message $message)
    {
        $this->gate();
        $message->delete();
        return back()->with('ok', 'Message deleted.');
    }

    public function visitors()
    {
        $this->gate();
        return view('admin.visitors', [
            'total'      => Visit::count(),
            'uniqueIps'  => Visit::whereNotNull('ip_address')->distinct('ip_address')->count('ip_address'),
            'today'      => Visit::whereDate('created_at', now()->toDateString())->count(),
            'rows'       => Visit::latest()->paginate(30),
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
