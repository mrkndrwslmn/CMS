<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        \Log::info('RoleMiddleware: Checking role', [
            'user_id' => Auth::id(),
            'required_role' => $role,
            'user_role' => Auth::check() ? Auth::user()->role : 'not authenticated',
            'request_path' => $request->path(),
        ]);

        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if ($user->status !== 'active') {
            Auth::logout();
            return redirect()->route('login')->withErrors(['account' => 'Your account has been deactivated.']);
        }

        if (!$user->hasRole($role)) {
            // Redirect to their appropriate dashboard instead of showing unauthorized
            \Log::warning('RoleMiddleware: User does not have required role', [
                'user_id' => $user->userID,
                'user_role' => $user->role,
                'required_role' => $role,
                'redirecting_to' => $user->getDashboardRoute(),
            ]);
            return redirect()->route($user->getDashboardRoute())
                ->withErrors(['access' => 'You do not have permission to access that area.']);
        }

        \Log::info('RoleMiddleware: Role check passed, proceeding');
        return $next($request);
    }
}