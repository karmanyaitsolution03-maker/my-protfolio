<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class Visit extends Model
{
    protected $fillable = ['ip_address', 'user_agent', 'path', 'referrer', 'visit_count'];

    /** Visit count at or above this makes someone a "returning" visitor for hot-lead purposes. */
    public const RETURN_VISIT_THRESHOLD = 3;

    /** Best-effort "City, Country" for the visitor's IP, cached for a week. */
    public function getLocationAttribute(): string
    {
        $ip = $this->ip_address;

        if (! $ip || ! filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
            return 'Local / Private';
        }

        return Cache::remember("geo:{$ip}", now()->addDays(7), function () use ($ip) {
            try {
                $data = Http::timeout(2)->get("http://ip-api.com/json/{$ip}", [
                    'fields' => 'status,country,city',
                ])->json();

                if (($data['status'] ?? null) === 'success') {
                    $label = collect([$data['city'] ?? null, $data['country'] ?? null])->filter()->implode(', ');
                    return $label !== '' ? $label : 'Unknown';
                }
            } catch (\Throwable $e) {
                //
            }
            return 'Unknown';
        });
    }

    /**
     * Best-effort company/organization name behind the visitor's IP (reverse-IP lookup
     * via ipinfo.io — works on their free tier, or with more accuracy/rate limit if
     * IPINFO_TOKEN is set). Returns null for private IPs, unresolvable IPs, or when the
     * IP belongs to a residential ISP / cloud-hosting provider rather than a real company
     * — those are noise, not leads. Cached for a week per IP.
     */
    public function getCompanyAttribute(): ?string
    {
        $ip = $this->ip_address;

        if (! $ip || ! filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
            return null;
        }

        return Cache::remember("company:{$ip}", now()->addDays(7), function () use ($ip) {
            try {
                $data = Http::timeout(2)->get("https://ipinfo.io/{$ip}/json", array_filter([
                    'token' => config('services.ipinfo.token'),
                ]))->json();

                // Paid "Company" add-on (only present with a token that has it enabled):
                // gives a clean name plus a type (business/isp/hosting/education) — trust it directly.
                if (! empty($data['company']['name'])) {
                    $type = $data['company']['type'] ?? null;
                    return ($type === null || $type === 'business') ? $data['company']['name'] : null;
                }

                // Free-tier fallback: "org" looks like "AS15169 Google LLC" — strip the ASN id.
                $org = trim((string) preg_replace('/^AS\d+\s+/', '', $data['org'] ?? ''));

                return ($org !== '' && ! self::looksLikeNoise($org)) ? $org : null;
            } catch (\Throwable $e) {
                return null;
            }
        });
    }

    /** Filters out consumer ISPs and cloud/hosting ASNs — common on a public site, never a real lead. */
    protected static function looksLikeNoise(string $org): bool
    {
        static $needles = [
            'comcast', 'charter communications', 'spectrum', 'cox communications', 'at&t', 'verizon',
            't-mobile', 'vodafone', 'reliance jio', 'bharti airtel', 'bsnl', 'mtnl', 'centurylink',
            'frontier communications', 'xfinity', 'deutsche telekom', 'orange s.a', 'bt group',
            'sky broadband', 'virgin media', 'ntt docomo', 'softbank', 'china telecom', 'china mobile',
            'china unicom', 'pldt', 'telstra', 'optus', 'rogers communications', 'bell canada', 'telus',
            'sk broadband', 'kt corporation', 'etisalat', 'starlink', 'google llc', 'google cloud',
            'amazon.com', 'amazon technologies', 'microsoft corporation', 'microsoft azure',
            'digitalocean', 'ovh sas', 'hetzner', 'cloudflare', 'linode', 'akamai', 'fastly',
        ];
        $hay = strtolower($org);
        foreach ($needles as $needle) {
            if (str_contains($hay, $needle)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Human-readable reasons this visitor is flagged as a hot lead — empty when not one.
     * A lead runs hot if they've returned {@see RETURN_VISIT_THRESHOLD}+ times, or if in
     * a single visit they viewed the projects section and lingered on the contact section
     * (interest + intent, short of actually submitting).
     */
    public function getHotLeadReasonsAttribute(): array
    {
        $reasons = [];

        if ($this->visit_count >= self::RETURN_VISIT_THRESHOLD) {
            $reasons[] = "Returned {$this->visit_count}\u{00d7}";
        }

        if ($this->ip_address) {
            $types = Interaction::where('ip_address', $this->ip_address)->pluck('type');
            if ($types->contains('view_projects') && $types->contains('contact_hover')) {
                $reasons[] = 'Viewed projects & lingered on contact';
            }
        }

        return $reasons;
    }

    public function getIsHotLeadAttribute(): bool
    {
        return $this->hot_lead_reasons !== [];
    }

    /**
     * The query behind the hot-lead definition — shared by the dashboard card and the
     * morning digest so "hot lead" means exactly one thing everywhere: returned
     * {@see RETURN_VISIT_THRESHOLD}+ times, or viewed projects and lingered on contact.
     */
    public static function hotLeadsQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $engagedIps = Interaction::whereIn('type', ['view_projects', 'contact_hover'])
            ->whereNotNull('ip_address')
            ->select('ip_address')
            ->groupBy('ip_address')
            ->havingRaw('COUNT(DISTINCT type) = 2')
            ->pluck('ip_address');

        return static::whereNotNull('ip_address')
            ->where(function ($q) use ($engagedIps) {
                $q->where('visit_count', '>=', self::RETURN_VISIT_THRESHOLD)
                  ->orWhereIn('ip_address', $engagedIps);
            });
    }

    /** Human-readable "Chrome on Windows" style label parsed from the raw user agent. */
    public function getDeviceLabelAttribute(): string
    {
        $ua = (string) $this->user_agent;
        if ($ua === '') {
            return 'Unknown';
        }

        $browser = match (true) {
            str_contains($ua, 'Edg/') => 'Edge',
            str_contains($ua, 'OPR/'), str_contains($ua, 'Opera') => 'Opera',
            str_contains($ua, 'CriOS'), (str_contains($ua, 'Chrome') && ! str_contains($ua, 'Chromium')) => 'Chrome',
            str_contains($ua, 'FxiOS'), str_contains($ua, 'Firefox') => 'Firefox',
            str_contains($ua, 'Safari') && ! str_contains($ua, 'Chrome') => 'Safari',
            default => 'Other browser',
        };

        $os = match (true) {
            str_contains($ua, 'Windows') => 'Windows',
            str_contains($ua, 'iPhone'), str_contains($ua, 'iPad') => 'iOS',
            str_contains($ua, 'Mac OS') => 'macOS',
            str_contains($ua, 'Android') => 'Android',
            str_contains($ua, 'Linux') => 'Linux',
            default => null,
        };

        return $os ? "{$browser} on {$os}" : $browser;
    }
}
