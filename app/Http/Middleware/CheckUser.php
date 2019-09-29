<?php

namespace App\Http\Middleware;

use App\Models\UserRole;
use Closure;

class CheckUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (session()->has('user')) {
            return $next($request);
        }
        return redirect()->route('loginView')->withErrors(['Need to be authorize as user']);
    }
}
