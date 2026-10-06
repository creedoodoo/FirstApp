<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\ClassModel;
use App\Models\ClassSession;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = session('user_id');
        $user = User::findOrFail($userId);

        $currentDayOfWeek = date('N'); // 1 = Monday ... 7 = Sunday

        $classes = ClassModel::with('subject')
            ->where('teacher_id', $user->id)
            ->where('is_archived', false)
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        // Get recent sessions for teacher's classes
        $recentSessions = ClassSession::with(['schoolClass.subject'])
            ->whereHas('schoolClass', function ($q) use ($user) {
                $q->where('teacher_id', $user->id);
            })
            ->orderBy('session_date', 'desc')
            ->take(5)
            ->get();

        return view('teacher.dashboard', compact('user', 'classes', 'currentDayOfWeek', 'recentSessions'));
    }
}
