<?php

namespace StatisticLv\AdminPanel\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (!Auth::guard('admin')->check()) {
            Log::warning('Unauthorized access attempt', [
                'path' => $request->path(),
                'ip' => $request->ip(),
            ]);
            
            return redirect()->route('admin.login');
        }
        
        // Check if user is active
        $user = Auth::guard('admin')->user();
        if (!$user->is_active) {
            Auth::guard('admin')->logout();
            Log::warning('Inactive user access attempt', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ip' => $request->ip(),
            ]);
            
            return redirect()->route('admin.login')
                ->with('error', 'Your account has been disabled. Please contact the administrator.');
        }

        return $next($request);
    }
}
