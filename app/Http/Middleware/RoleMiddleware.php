<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request by role if need.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next, string $roles): Response
    {
        if (!$request->user()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $allowedRoles = explode('|', $roles);

        if (!in_array($request->user()->role?->name, $allowedRoles, true)) {
            return response()->json(['message' => 'Access denied.'], 403);
        }

        return $next($request);
    }
}
