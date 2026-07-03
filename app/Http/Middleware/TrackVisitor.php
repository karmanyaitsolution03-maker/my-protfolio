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
                Visit::create([
                    'ip_address' => $request->ip(),
                    'user_agent' => substr((string) $request->userAgent(), 0, 255),
                    'path'       => '/' . ltrim($request->path(), '/'),
                    'referrer'   => $request->headers->get('referer'),
                ]);
            } catch (\Throwable $e) {
                // Never let logging break the site.
            }
        }

        return $next($request);
    }
}
