<?php

namespace Database\Seeders;

use App\Models\Achievement;
use App\Models\Experience;
use App\Models\Project;
use App\Models\Setting;
use App\Models\Skill;
use App\Models\SkillCategory;
use Illuminate\Database\Seeder;

class PortfolioSeeder extends Seeder
{
    public function run(): void
    {
        /* ---------- settings (all content keys + defaults live in config/portfolio.php) ---------- */
        foreach (Setting::defaults() as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        /* ---------- skills ---------- */
        $cats = [
            ['name' => 'Backend Development', 'icon' => '⚙️', 'wide' => true, 'position' => 1, 'skills' => [
                ['PHP', 92], ['Laravel', 94], ['Symfony', 86], ['REST APIs', 93], ['Auth & Authorization · MVC', 90],
            ]],
            ['name' => 'Project Coordination', 'icon' => '🧭', 'wide' => true, 'position' => 2, 'skills' => [
                ['Requirement gathering', 91], ['Client communication', 92], ['Task & timeline management', 88], ['Production support', 93],
            ]],
            ['name' => 'Databases', 'icon' => '🗄️', 'wide' => false, 'position' => 3, 'skills' => [
                ['MySQL', 91], ['MongoDB', 84],
            ]],
            ['name' => 'Frontend Integration', 'icon' => '🎨', 'wide' => false, 'position' => 4, 'skills' => [
                ['JavaScript · AJAX', 80], ['Bootstrap · Blade', 85],
            ]],
            ['name' => 'Tools', 'icon' => '🧰', 'wide' => false, 'position' => 5, 'skills' => [
                ['Git', 90], ['Postman · API testing', 93], ['Debugging', 89],
            ]],
        ];
        foreach ($cats as $c) {
            $cat = SkillCategory::create(collect($c)->except('skills')->toArray());
            foreach ($c['skills'] as $i => [$name, $level]) {
                Skill::create(['skill_category_id' => $cat->id, 'name' => $name, 'level' => $level, 'position' => $i + 1]);
            }
        }

        /* ---------- experience ---------- */
        Experience::create([
            'company' => 'Iqonic Design', 'sub' => 'Goldenmace IT Solutions',
            'role' => 'Backend Developer & Technical Coordinator', 'period' => '2024 — 2026',
            'live' => false, 'position' => 1,
            'responsibilities' => ['Backend development', 'Client communication', 'Requirement gathering', 'Production support', 'Deployment coordination', 'Bug tracking', 'Project delivery'],
        ]);
        Experience::create([
            'company' => 'Digilance Infotech', 'sub' => 'Current station',
            'role' => 'Backend Developer', 'period' => '2026 — PRESENT',
            'live' => true, 'position' => 2,
            'responsibilities' => ['Laravel development', 'Symfony development', 'REST API integration', 'Authentication systems', 'Database management', 'Debugging', 'System maintenance'],
        ]);

        /* ---------- projects ---------- */
        $projects = [
            ['title' => 'KiviCare', 'kicker' => 'HEALTHCARE MANAGEMENT PLATFORM', 'color' => '#54F0A8', 'wide' => true, 'position' => 1,
                'description' => 'Clinic & patient management — appointments, encounters, billing and role-based access. Backend APIs serving web and mobile clients in production.',
                'arch' => [['label' => 'WEB + MOBILE', 'sub' => 'CLIENTS'], ['label' => 'LARAVEL API', 'sub' => 'AUTH · RBAC', 'hot' => true], ['label' => 'MYSQL', 'sub' => 'CORE DB']],
                'metrics' => [['value' => '30+', 'label' => 'ENDPOINTS'], ['value' => '4', 'label' => 'USER ROLES'], ['value' => 'RBAC', 'label' => 'AUTH MODEL']],
                'tags' => ['Laravel', 'MySQL', 'REST API']],
            ['title' => 'Streamit', 'kicker' => 'OTT PLATFORM', 'color' => '#FF6B9D', 'wide' => false, 'position' => 2,
                'description' => 'Streaming backend — catalogs, subscriptions, watch-state APIs.',
                'arch' => [['label' => 'APPS', 'sub' => 'VIEWERS'], ['label' => 'API', 'sub' => 'SUBS', 'hot' => true], ['label' => 'MYSQL']],
                'metrics' => [['value' => '1000s', 'label' => 'VIEWERS'], ['value' => '3', 'label' => 'TIERS']],
                'tags' => ['Laravel', 'Subscriptions']],
            ['title' => 'Handyman Service', 'kicker' => 'SERVICE PLATFORM', 'color' => '#9D6BFF', 'wide' => false, 'position' => 3,
                'description' => 'Bookings, provider matching, payments and live status flows.',
                'arch' => [['label' => 'APP', 'sub' => 'USERS'], ['label' => 'API', 'sub' => 'MATCHING', 'hot' => true], ['label' => 'DB']],
                'metrics' => [['value' => 'E2E', 'label' => 'BOOKINGS'], ['value' => 'LIVE', 'label' => 'STATUS']],
                'tags' => ['Laravel', 'REST API']],
            ['title' => 'Bizinvoice', 'kicker' => 'INVOICE MANAGEMENT', 'color' => '#FFC56E', 'wide' => false, 'position' => 4,
                'description' => 'Invoicing engine — clients, line items, tax rules, PDF exports.',
                'arch' => [['label' => 'DASH', 'sub' => 'B2B'], ['label' => 'ENGINE', 'sub' => 'TAX', 'hot' => true], ['label' => 'PDF', 'sub' => 'EXPORT']],
                'metrics' => [['value' => 'TAX', 'label' => 'RULES'], ['value' => 'PDF', 'label' => 'EXPORT']],
                'tags' => ['PHP', 'MySQL', 'Blade']],
            ['title' => 'Expense Tracker', 'kicker' => 'FINANCIAL TRACKING', 'color' => '#3DE8FF', 'wide' => false, 'position' => 5,
                'description' => 'Categorized spend, budgets and monthly insight reports.',
                'arch' => [['label' => 'UI', 'sub' => 'AJAX'], ['label' => 'API', 'sub' => 'REPORTS', 'hot' => true], ['label' => 'DB']],
                'metrics' => [['value' => 'CRUD+', 'label' => 'REPORTS'], ['value' => 'CHARTS', 'label' => 'INSIGHTS']],
                'tags' => ['Laravel', 'JavaScript']],
        ];
        foreach ($projects as $p) {
            Project::create($p);
        }

        /* ---------- achievements ---------- */
        Achievement::create(['count' => 2, 'suffix' => '+', 'value' => '2', 'label' => 'YEARS EXPERIENCE', 'position' => 1]);
        Achievement::create(['count' => 5, 'suffix' => '+', 'value' => '5', 'label' => 'MAJOR PROJECTS', 'position' => 2]);
        Achievement::create(['count' => null, 'value' => 'BE', 'label' => 'BACKEND ENGINEERING SPECIALIST', 'position' => 3]);
        Achievement::create(['count' => null, 'value' => 'API', 'label' => 'API DEVELOPMENT EXPERT', 'position' => 4]);
    }
}
