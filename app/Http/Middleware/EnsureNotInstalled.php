<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class EnsureNotInstalled
{
    public function handle(Request $request, Closure $next)
    {
        if (Config::get('installer.installed')) {
            return redirect()->route('home')->with('error', 'The application is already installed.');
        }

        return $next($request);
    }
}
