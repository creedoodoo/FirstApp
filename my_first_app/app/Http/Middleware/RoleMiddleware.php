<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $userId = session('user_id');

        if (!$userId) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);
            }
            return redirect()->route('login');
        }

        $user = User::find($userId);

        if (!$user) {
            session()->forget('user_id');
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'User account not found.'], 401);
            }
            return redirect()->route('login');
        }

        // Teacher approval check
        if ($user->isTeacher() && !$user->isApproved()) {
            session()->forget('user_id');
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your account is pending admin approval. Please wait for approval.'
                ], 403);
            }
            return redirect()->route('login')->with('error', 'Your teacher account is pending admin approval.');
        }

        // Role check
        if ($user->role !== $role) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized access.'], 403);
            }
            abort(403, 'Unauthorized access. Required role: ' . ucfirst($role));
        }

        return $next($request);
    }
}
