<?php

namespace App\Http\Controllers;

use App\Models\Interaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InteractionController extends Controller
{
    /** Allowed types — keeps the table from filling up with arbitrary client input. */
    protected const TYPES = [
        'contact_click', 'resume_click', 'whatsapp_click',
        'nudge_shown', 'nudge_resume_click', 'nudge_call_click', 'nudge_dismissed',
        'view_projects', 'contact_hover',
        'mode_recruiter_selected', 'mode_client_selected',
        'jd_match_run',
    ];

    public function track(Request $request): JsonResponse
    {
        $data = $request->validate([
            'type' => 'required|string|in:' . implode(',', self::TYPES),
        ]);

        Interaction::create([
            'type'       => $data['type'],
            'ip_address' => $request->ip(),
        ]);

        return response()->json(['ok' => true]);
    }
}
