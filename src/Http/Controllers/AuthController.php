<?php

namespace StatisticLv\AdminPanel\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /**
     * Show the admin login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm(): \Illuminate\View\View
    {
        return view('admin-panel::auth.login');
    }

    /**
     * Handle an incoming admin authentication request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request): \Illuminate\Http\RedirectResponse
    {
        $key = 'login:' . $request->ip();
        
        // Rate limiting: 5 attempts per minute
        if (RateLimiter::tooManyAttempts($key, 5)) {
            Log::warning('Admin login rate limit exceeded', ['ip' => $request->ip()]);
            
            return back()->withErrors([
                'email' => 'Too many login attempts. Please try again later.',
            ])->onlyInput('email');
        }
        
        RateLimiter::hit($key, 60);

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('admin')->attempt($credentials, $request->filled('remember'))) {
            RateLimiter::clear($key);
            $request->session()->regenerate();
            
            $user = Auth::guard('admin')->user();
            
            // Check if user is active
            if (!$user->is_active) {
                Auth::guard('admin')->logout();
                Log::warning('Admin login attempted by inactive user', ['email' => $request->email]);
                
                return back()->withErrors([
                    'email' => 'Your account has been disabled. Please contact the administrator.',
                ])->onlyInput('email');
            }
            
            Log::info('Admin login successful', [
                'email' => $request->email,
                'user_id' => $user->id,
                'ip' => $request->ip(),
            ]);
            
            return redirect()->intended(route('admin.dashboard'));
        }

        Log::warning('Admin login failed', [
            'email' => $request->email,
            'ip' => $request->ip(),
        ]);

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Log the admin user out of the application.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request): \Illuminate\Http\RedirectResponse
    {
        $user = Auth::guard('admin')->user();
        
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        Log::info('Admin logout', [
            'user_id' => $user?->id,
            'email' => $user?->email,
        ]);

        return redirect()->route('admin.login');
    }
}
