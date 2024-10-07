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
     * @param  \Closure  $next
     * @param  string  $roles  Comma-separated roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $roles)
    {
        if (!Auth::check()) {
            // User is not authenticated
            return redirect('/login');
        }

        $user = Auth::user();
        $rolesArray = array_map('trim', explode(',', $roles));

        if (!in_array($user->role->name, $rolesArray)) {
            // User does not have any of the required roles
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
