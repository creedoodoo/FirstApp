<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        $userId = session('user_id');
        $user   = User::findOrFail($userId);

        $subjects = Subject::orderBy('code')->get();

        return view('admin.subjects', compact('user', 'subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:20|unique:subjects,code',
            'name' => 'required|string|max:255',
        ]);

        Subject::create([
            'code'      => strtoupper(trim($request->code)),
            'name'      => trim($request->name),
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'New subject added successfully.');
    }

    public function toggleActive($id)
    {
        $subject = Subject::findOrFail($id);
        $subject->is_active = !$subject->is_active;
        $subject->save();

        $status = $subject->is_active ? 'activated' : 'deactivated';
        return redirect()->back()->with('success', "Subject {$subject->code} has been {$status}.");
    }
}
