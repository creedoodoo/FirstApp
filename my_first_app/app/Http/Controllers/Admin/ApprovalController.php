<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    public function index(Request $request)
    {
        $userId = session('user_id');
        $user   = User::findOrFail($userId);

        $viewMode = $request->query('view', 'active'); // 'active' or 'archived'

        $pendingTeachers = User::where('role', 'teacher')
            ->where('is_approved', false)
            ->orderBy('created_at', 'desc')
            ->get();

        $approvedTeachers = User::where('role', 'teacher')
            ->where('is_approved', true)
            ->where(function ($q) {
                $q->where('is_active', true)->orWhereNull('is_active');
            })
            ->orderBy('name')
            ->get();

        $archivedTeachers = User::where('role', 'teacher')
            ->where('is_approved', true)
            ->where('is_active', false)
            ->orderBy('name')
            ->get();

        $archivedCount = $archivedTeachers->count();

        return view('admin.approvals', compact(
            'user',
            'pendingTeachers',
            'approvedTeachers',
            'archivedTeachers',
            'archivedCount',
            'viewMode'
        ));
    }

    public function approve($id)
    {
        $teacher = User::where('role', 'teacher')->findOrFail($id);
        $teacher->is_approved = true;
        $teacher->is_active = true;
        $teacher->save();

        return redirect()->back()->with('success', "Teacher {$teacher->name} approved successfully.");
    }

    public function reject($id)
    {
        $teacher = User::where('role', 'teacher')->findOrFail($id);
        $name = $teacher->name;
        $teacher->delete();

        return redirect()->back()->with('success', "Teacher registration for {$name} rejected.");
    }

    public function deactivate($id)
    {
        $teacher = User::where('role', 'teacher')->findOrFail($id);
        $teacher->is_active = false;
        $teacher->save();

        return redirect()->back()->with('success', "Teacher {$teacher->name} deactivated successfully.");
    }

    public function reactivate($id)
    {
        $teacher = User::where('role', 'teacher')->findOrFail($id);
        $teacher->is_active = true;
        $teacher->save();

        return redirect()->back()->with('success', "Teacher {$teacher->name} reactivated successfully.");
    }
}
