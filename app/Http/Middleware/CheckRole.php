<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to access this page.');
        }

        $userRole = Auth::user()->role;

        if (!in_array($userRole, $roles, true)) {
            $errorMessage = 'You do not have permission to access this resource.';
            if ($request->expectsJson()) {
                return response()->json(['error' => $errorMessage], 403);
            }
            return redirect()->route('dashboard')->with('error', $errorMessage);
        }

        return $next($request);
    }
}
