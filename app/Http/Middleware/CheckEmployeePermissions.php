<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckEmployeePermissions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // تحقق من وجود صلاحيات للمستخدم
        if ($user->getAllPermissions()->isEmpty()) {
            return response()->view('filament.employee.no-permissions', [
                'user' => $user
            ]);
        }
        return $next($request);
    }
}
