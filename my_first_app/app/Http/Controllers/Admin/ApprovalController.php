<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    public function index()
    {
        $userId = session('user_id');
        $user   = User::findOrFail($userId);

        $pendingTeachers = User::where('role', 'teacher')
            ->where('is_approved', false)
            ->orderBy('created_at', 'desc')
            ->get();

        $approvedTeachers = User::where('role', 'teacher')
            ->where('is_approved', true)
            ->orderBy('name')
            ->get();

        return view('admin.approvals', compact('user', 'pendingTeachers', 'approvedTeachers'));
    }

    public function approve($id)
    {
        $teacher = User::where('role', 'teacher')->findOrFail($id);
        $teacher->is_approved = true;
        $teacher->save();

        return redirect()->back()->with('success', "Teacher {$teacher->name} has been approved.");
    }

    public function reject($id)
    {
        $teacher = User::where('role', 'teacher')->findOrFail($id);
        $name = $teacher->name;
        $teacher->delete();

        return redirect()->back()->with('success', "Registration request for {$name} has been rejected and deleted.");
    }
}
