<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Display the Auth (Login / Signup) page.
     * Automatically logs in users holding a valid "Remember Me" cookie.
     */
    public function showAuth()
    {
        // 1. Check active session
        if (session()->has('user_id')) {
            $user = User::find(session('user_id'));
            if ($user && method_exists($user, 'isAdmin') && $user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            } elseif ($user && method_exists($user, 'isTeacher') && method_exists($user, 'isApproved') && $user->isTeacher() && $user->isApproved()) {
                return redirect()->route('teacher.dashboard');
            }
            return redirect()->route('landing');
        }

        // 2. Check persistent 'remember_web' cookie if no session exists
        if (request()->hasCookie('remember_web')) {
            $rememberToken = request()->cookie('remember_web');
            if (!empty($rememberToken)) {
                $user = User::where('remember_token', $rememberToken)->first();
                if ($user) {
                    session(['user_id' => $user->id]);
                    if (method_exists($user, 'isAdmin') && $user->isAdmin()) {
                        return redirect()->route('admin.dashboard');
                    } elseif (method_exists($user, 'isTeacher') && method_exists($user, 'isApproved') && $user->isTeacher() && $user->isApproved()) {
                        return redirect()->route('teacher.dashboard');
                    }
                    return redirect()->route('landing');
                }
            }
        }

        return view('auth');
    }

    /**
     * Handle user registration with Bcrypt password & username hashing.
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

        // Bcrypt Hashing for Password
        $usernameHash = hash('sha256', $email);
        $passwordHash = Hash::make($request->password);

        User::create([
            'name'          => $request->name,
            'email'         => $email,
            'username_hash' => $usernameHash,
            'password'      => $passwordHash,
            'role'          => 'teacher',
            'is_approved'   => false,
        ]);

        return response()->json([
            'success'         => true,
            'message'         => 'Teacher registration submitted! Your account is pending admin approval.',
            'switch_to_login' => true
        ]);
    }

    /**
     * Handle user login via Bcrypt password check with legacy SHA-256 fallback & "Remember Me" support.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|string|email',
            'password' => 'required|string',
            'remember' => 'nullable|boolean',
        ]);

        $email = strtolower(trim($request->email));

        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email or password. Please check your credentials.'
            ], 401);
        }

        $passwordMatches = false;

        // Check if password is formatted as a valid Bcrypt hash (starts with $2y$)
        if (str_starts_with($user->password, '$2y$') || str_starts_with($user->password, '$2a$')) {
            $passwordMatches = Hash::check($request->password, $user->password);
        } else {
            // Legacy Fallback: Check old SHA-256 hash (for accounts created before switching to Bcrypt)
            $oldSha256Hash = hash('sha256', $request->password);
            if (hash_equals($user->password, $oldSha256Hash)) {
                $passwordMatches = true;

                // Seamlessly upgrade legacy SHA-256 password to Bcrypt in database
                $user->password = Hash::make($request->password);
                $user->save();
            }
        }

        if (!$passwordMatches) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email or password. Please check your credentials.'
            ], 401);
        }

        // Approval check for teachers
        if (method_exists($user, 'isTeacher') && method_exists($user, 'isApproved')) {
            if ($user->isTeacher() && !$user->isApproved()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your account is pending admin approval. Please wait for an administrator to approve your registration.'
                ], 403);
            }
        }

        session(['user_id' => $user->id]);

        // Handle "Remember Me" persistent token & cookie
        if ($request->boolean('remember')) {
            $rememberToken = Str::random(60);
            $user->remember_token = $rememberToken;
            $user->save();

            // Set 30-day persistent cookie (43,200 minutes)
            Cookie::queue('remember_web', $rememberToken, 43200);
        } else {
            // If unchecked, clear any existing token and cookie
            $user->remember_token = null;
            $user->save();
            Cookie::queue(Cookie::forget('remember_web'));
        }

        $redirectUrl = route('landing');
        if (method_exists($user, 'isAdmin') && $user->isAdmin()) {
            $redirectUrl = route('admin.dashboard');
        } elseif (method_exists($user, 'isTeacher') && $user->isTeacher()) {
            $redirectUrl = route('teacher.dashboard');
        }

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
            // Fallback check for remember_web cookie if session expired
            if (request()->hasCookie('remember_web')) {
                $rememberToken = request()->cookie('remember_web');
                if (!empty($rememberToken)) {
                    $user = User::where('remember_token', $rememberToken)->first();
                    if ($user) {
                        session(['user_id' => $user->id]);
                        return view('landing', compact('user'));
                    }
                }
            }
            return redirect()->route('login');
        }

        $user = User::find(session('user_id'));

        if (!$user) {
            session()->forget('user_id');
            return redirect()->route('login');
        }

        if (method_exists($user, 'isAdmin') && $user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif (method_exists($user, 'isTeacher') && method_exists($user, 'isApproved') && $user->isTeacher() && $user->isApproved()) {
            return redirect()->route('teacher.dashboard');
        }

        return view('landing', compact('user'));
    }

    /**
     * Handle user sign out.
     */
    public function logout(Request $request)
    {
        if (session()->has('user_id')) {
            $user = User::find(session('user_id'));
            if ($user) {
                $user->remember_token = null;
                $user->save();
            }
        }

        $request->session()->forget('user_id');
        Cookie::queue(Cookie::forget('remember_web'));

        return redirect()->route('login');
    }
}
