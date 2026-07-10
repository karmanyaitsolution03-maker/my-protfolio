<?php

namespace App\Http\Middleware;

use App\Models\Visit;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackVisitor
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isMethod('GET') && ! $request->is('admin*') && ! $request->is('up')) {
            try {
                $ip = $request->ip();
                $attributes = [
                    'user_agent' => substr((string) $request->userAgent(), 0, 255),
                    'path'       => '/' . ltrim($request->path(), '/'),
                    'referrer'   => $request->headers->get('referer'),
                ];

                // Dedupe repeat visitors by IP: bump the counter on the existing
                // row instead of inserting a new one for every page load.
                $existing = $ip ? Visit::where('ip_address', $ip)->first() : null;

                if ($existing) {
                    $existing->increment('visit_count', 1, $attributes);
                } else {
                    Visit::create($attributes + ['ip_address' => $ip]);
                }
            } catch (\Throwable $e) {
                // Never let logging break the site.
            }
        }

        return $next($request);
    }
}
