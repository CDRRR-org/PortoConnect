<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     * Middleware untuk memastikan user memiliki role tertentu
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!$request->user()) {
            return response()->json([
                'message' => 'Unauthenticated'
            ], 401);
        }

        if ($request->user()->role !== $role) {
            return response()->json([
                'message' => "Unauthorized. Hanya user dengan role '{$role}' yang dapat mengakses endpoint ini."
            ], 403);
        }

        return $next($request);
    }
}

