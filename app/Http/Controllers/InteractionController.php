<?php

namespace App\Http\Controllers;

use App\Models\Interaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InteractionController extends Controller
{
    /** Allowed types — keeps the table from filling up with arbitrary client input. */
    protected const TYPES = ['contact_click', 'resume_click', 'whatsapp_click'];

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
