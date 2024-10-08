<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  mixed  ...$roles
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $roles)
    {
        $user = Auth::user();
        if (!$user) {
            // User is not authenticated
            return redirect('/login');
        }

        if (!in_array($user->role->name, $roles)) {
            // User does not have any of the required roles
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
