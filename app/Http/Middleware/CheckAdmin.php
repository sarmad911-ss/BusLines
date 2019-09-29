<?php

namespace App\Http\Middleware;

use App\Models\UserRole;
use Closure;

class CheckAdmin
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
        if (session()->has('user') && (session()->get('user')->role_id == UserRole::ADMIN)) {
            return $next($request);
        }
        return redirect()->route('loginAdminView')->withErrors(['Need to be authorize as admin']);
    }
}
