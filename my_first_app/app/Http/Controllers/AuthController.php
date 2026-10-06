<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Display the Auth (Login / Signup) page.
     */
    public function showAuth()
    {
        if (session()->has('user_id')) {
            $user = User::find(session('user_id'));
            if ($user && $user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            } elseif ($user && $user->isTeacher() && $user->isApproved()) {
                return redirect()->route('teacher.dashboard');
            }
            return redirect()->route('landing');
        }
        return view('auth');
    }

    /**
     * Handle teacher registration with SHA-256 password & username hashing.
     * New teacher accounts require admin approval before login.
     */
    public function signup(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);

        $email = strtolower(trim($request->email));

        if (User::where('email', $email)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'An account with this email address already exists.'
            ], 422);
        }

        // SHA-256 Hashing for Username and Password
        $usernameHash = hash('sha256', $email);
        $passwordHash = hash('sha256', $request->password);

        User::create([
            'name'            => $request->name,
            'email'           => $email,
            'username_hash'   => $usernameHash,
            'password'        => $passwordHash,
            'role'            => 'teacher',
            'is_approved'     => false,
            'trial_uses_left' => 5,
        ]);

        return response()->json([
            'success'         => true,
            'message'         => 'Teacher registration submitted! Your account is pending admin approval.',
            'switch_to_login' => true
        ]);
    }

    /**
     * Handle user login via SHA-256 password check and role-based redirect.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|string|email',
            'password' => 'required|string',
        ]);

        $email = strtolower(trim($request->email));
        $passwordHash = hash('sha256', $request->password);

        $user = User::where('email', $email)
                    ->where('password', $passwordHash)
                    ->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email or password. Please check your credentials.'
            ], 401);
        }

        // Approval check for teachers
        if ($user->isTeacher() && !$user->isApproved()) {
            return response()->json([
                'success' => false,
                'message' => 'Your account is pending admin approval. Please wait for an administrator to approve your registration.'
            ], 403);
        }

        session(['user_id' => $user->id]);

        $redirectUrl = $user->isAdmin() ? route('admin.dashboard') : route('teacher.dashboard');

        return response()->json([
            'success'  => true,
            'redirect' => $redirectUrl
        ]);
    }

    /**
     * Display the authenticated portal screen.
     */
    public function landing()
    {
        if (!session()->has('user_id')) {
            return redirect()->route('login');
        }

        $user = User::find(session('user_id'));

        if (!$user) {
            session()->forget('user_id');
            return redirect()->route('login');
        }

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isTeacher()) {
            return redirect()->route('teacher.dashboard');
        }

        return view('landing', compact('user'));
    }

    /**
     * Handle user sign out.
     */
    public function logout(Request $request)
    {
        $request->session()->forget('user_id');
        return redirect()->route('login');
    }

    /**
     * Decrement user trial limit action.
     */
    public function useTrial()
    {
        if (!session()->has('user_id')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized session.'], 401);
        }

        $user = User::find(session('user_id'));

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found.'], 404);
        }

        if ($user->trial_uses_left <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Trial limit reached! You have 0 trial uses remaining.'
            ], 400);
        }

        $user->decrement('trial_uses_left');
        $user->refresh();

        return response()->json([
            'success' => true,
            'trial_uses_left' => $user->trial_uses_left,
            'message' => 'Action executed! 1 trial credit consumed.'
        ]);
    }
}
