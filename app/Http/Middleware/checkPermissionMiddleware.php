<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Providers\RouteServiceProvider;

class checkPermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        if ($user->hasRole('admin')) {
            return redirect(RouteServiceProvider::ADMIN);
            // return redirect()->intended(RouteServiceProvider::ADMIN);
        }

        if ($user->hasRole('employee')) {
            if ($user->getAllPermissions()->isNotEmpty()) {
                return redirect(RouteServiceProvider::EMPLOYEE);
                // return redirect()->intended(RouteServiceProvider::EMPLOYEE);
            } else {
                abort(403, 'Employee has no permissions');
            }
        }

        return $next($request);
    }
}
