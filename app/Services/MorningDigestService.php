<?php

namespace App\Services;

use App\Models\Message;
use App\Models\Visit;
use Illuminate\Support\Carbon;

class MorningDigestService
{
    /** How many rows to run the reverse-IP company/geo lookup over — each is a live HTTP call. */
    protected const LOOKUP_LIMIT = 25;

    /**
     * Everything the morning digest needs: who visited, which companies showed up,
     * who's a hot lead, and what messages came in — all since $since (default: 24h ago).
     */
    public function compile(?Carbon $since = null): array
    {
        $since ??= Carbon::now()->subDay();

        $recentVisits = Visit::whereNotNull('ip_address')
            ->where('updated_at', '>=', $since)
            ->latest('updated_at')
            ->limit(self::LOOKUP_LIMIT)
            ->get();

        $companies = $recentVisits
            ->map(fn (Visit $v) => $v->company)
            ->filter()
            ->unique()
            ->values();

        $hotLeads = Visit::hotLeadsQuery()
            ->where('updated_at', '>=', $since)
            ->latest('updated_at')
            ->limit(10)
            ->get();

        $messages = Message::where('created_at', '>=', $since)->latest()->get();

        return [
            'since' => $since,
            'visitors' => [
                'total'      => $recentVisits->count(),
                'new'        => $recentVisits->where('created_at', '>=', $since)->count(),
                'returning'  => $recentVisits->where('created_at', '<', $since)->count(),
            ],
            'companies' => $companies,
            'hotLeads'  => $hotLeads,
            'messages'  => [
                'total'       => $messages->count(),
                'byCategory'  => $messages->countBy(fn (Message $m) => $m->ai_category ?? 'uncategorized'),
                'items'       => $messages->take(8),
            ],
        ];
    }
}
