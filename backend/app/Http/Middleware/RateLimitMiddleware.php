<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cache;

class RateLimitMiddleware
{
    /**
     * Handle an incoming request.
     * Rate limiting untuk mencegah abuse
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, int $maxAttempts = 60, int $decayMinutes = 1): Response
    {
        $key = $this->resolveRequestSignature($request);

        if (Cache::has($key)) {
            $attempts = Cache::get($key);
            
            if ($attempts >= $maxAttempts) {
                return response()->json([
                    'message' => 'Terlalu banyak request. Silakan coba lagi nanti.',
                    'retry_after' => $decayMinutes * 60
                ], 429);
            }

            Cache::put($key, $attempts + 1, now()->addMinutes($decayMinutes));
        } else {
            Cache::put($key, 1, now()->addMinutes($decayMinutes));
        }

        return $next($request);
    }

    /**
     * Resolve request signature untuk rate limiting
     */
    protected function resolveRequestSignature(Request $request): string
    {
        $user = $request->user();
        $ip = $request->ip();
        $route = $request->route()?->getName() ?? $request->path();

        if ($user) {
            return 'rate_limit:' . $user->id . ':' . $route;
        }

        return 'rate_limit:' . $ip . ':' . $route;
    }
}

