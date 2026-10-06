<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\ClassModel;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index()
    {
        $userId = session('user_id');
        $user   = User::findOrFail($userId);

        $classes = ClassModel::with('subject')
            ->where('teacher_id', $user->id)
            ->orderBy('is_archived')
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        $subjects = Subject::where('is_active', true)->orderBy('code')->get();
        $sections = config('attendance.sections', []);

        return view('teacher.classes.index', compact('user', 'classes', 'subjects', 'sections'));
    }

    public function store(Request $request)
    {
        $userId = session('user_id');

        $sections = config('attendance.sections', []);

        $request->validate([
            'subject_id'  => 'required|exists:subjects,id',
            'section'     => 'required|in:' . implode(',', $sections),
            'day_of_week' => 'required|integer|min:1|max:7',
            'start_time'  => 'required|date_format:H:i',
            'end_time'    => 'required|date_format:H:i|after:start_time',
        ]);

        // Overlap check for this teacher
        $overlap = ClassModel::where('teacher_id', $userId)
            ->where('is_archived', false)
            ->where('day_of_week', $request->day_of_week)
            ->where(function ($q) use ($request) {
                $q->whereBetween('start_time', [$request->start_time, $request->end_time])
                  ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                  ->orWhere(function ($q2) use ($request) {
                      $q2->where('start_time', '<=', $request->start_time)
                         ->where('end_time', '>=', $request->end_time);
                  });
            })
            ->exists();

        $class = ClassModel::create([
            'teacher_id'  => $userId,
            'subject_id'  => $request->subject_id,
            'section'     => $request->section,
            'day_of_week' => $request->day_of_week,
            'start_time'  => $request->start_time,
            'end_time'    => $request->end_time,
            'is_archived' => false,
        ]);

        $message = 'Class added successfully!';
        if ($overlap) {
            $message .= ' Warning: This class time overlaps with another of your scheduled classes.';
        }

        return redirect()->route('teacher.classes.index')->with('success', $message);
    }

    public function toggleArchive($id)
    {
        $userId = session('user_id');
        $class  = ClassModel::where('teacher_id', $userId)->findOrFail($id);

        $class->is_archived = !$class->is_archived;
        $class->save();

        $status = $class->is_archived ? 'archived' : 'restored';
        return redirect()->back()->with('success', "Class has been {$status}.");
    }
}
