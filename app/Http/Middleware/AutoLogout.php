<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AutoLogout
{
    // Auto-logout after 6 hours of inactivity
    protected $timeout = 21600; // 6 hours in seconds

    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $lastActivity = $request->session()->get('last_activity');

            if ($lastActivity && (time() - $lastActivity > $this->timeout)) {
                Auth::logout();
                $request->session()->invalidate();
                return redirect('/login')->with('error', 'You have been logged out due to inactivity.');
            }

            $request->session()->put('last_activity', time());
        }

        return $next($request);
    }
}
