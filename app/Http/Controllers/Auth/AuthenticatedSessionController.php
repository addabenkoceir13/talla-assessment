<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        Auth::logout();
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();
        // dd($user->hasRole('admin'), $user->hasRole('employee'));

        if ($user->hasRole('admin')) {
            return  redirect(RouteServiceProvider::ADMIN);
            // return  redirect()->intended(RouteServiceProvider::ADMIN);
        }

        if ($user->hasRole('employee')) {
            if ($user->getAllPermissions()->isNotEmpty()) {
                return redirect(RouteServiceProvider::EMPLOYEE);
                // return redirect()->intended(RouteServiceProvider::EMPLOYEE);
            } else {
                abort(403);
            }
        }

        return redirect(RouteServiceProvider::HOME);
        // return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
