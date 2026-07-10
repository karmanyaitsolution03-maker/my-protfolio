<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class Visit extends Model
{
    protected $fillable = ['ip_address', 'user_agent', 'path', 'referrer', 'visit_count'];

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
