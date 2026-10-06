<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    /**
     * Search student by student number.
     */
    public function search(Request $request)
    {
        $userId = session('user_id');
        $user   = User::findOrFail($userId);

        $query  = trim($request->input('student_number', ''));
        $result = null;
        $searched = false;

        if ($query !== '') {
            $searched = true;
            $result   = Student::where('student_number', $query)->first();
        }

        return view('teacher.students.search', compact('user', 'query', 'result', 'searched'));
    }

    /**
     * Student registration form.
     */
    public function create(Request $request)
    {
        $userId   = session('user_id');
        $user     = User::findOrFail($userId);
        $sections = config('attendance.sections', []);
        $defaultStudentNumber = $request->input('student_number', '');

        return view('teacher.students.create', compact('user', 'sections', 'defaultStudentNumber'));
    }

    /**
     * Store new student record.
     */
    public function store(Request $request)
    {
        $sections = config('attendance.sections', []);
        $studentNumberRegex = config('attendance.student_number_regex');

        $request->validate([
            'first_name'     => 'required|string|max:50|regex:/^[A-Za-z\s\-]+$/',
            'last_name'      => 'required|string|max:50|regex:/^[A-Za-z\s\-]+$/',
            'section'        => 'required|in:' . implode(',', $sections),
            'student_number' => ['required', 'string', 'unique:students,student_number', 'regex:' . $studentNumberRegex],
            'photo'          => 'nullable|image|max:2048',
            'photo_webcam'   => 'nullable|string',
        ], [
            'first_name.regex'     => 'First name must contain letters, spaces, or hyphens only.',
            'last_name.regex'      => 'Last name must contain letters, spaces, or hyphens only.',
            'student_number.regex' => 'Student number must follow format 2024-00452-SR-0.',
        ]);

        $photoPath = null;

        // Handle File Upload
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('students', 'public');
        } 
        // Handle Webcam Base64 Capture
        elseif ($request->filled('photo_webcam')) {
            $base64Image = $request->photo_webcam;
            if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $type)) {
                $data = substr($base64Image, strpos($base64Image, ',') + 1);
                $type = strtolower($type[1]);
                $data = base64_decode($data);
                if ($data !== false) {
                    $fileName = 'students/' . uniqid('cam_') . '.' . $type;
                    Storage::disk('public')->put($fileName, $data);
                    $photoPath = $fileName;
                }
            }
        }

        $student = Student::create([
            'first_name'     => trim($request->first_name),
            'last_name'      => trim($request->last_name),
            'section'        => $request->section,
            'student_number' => trim($request->student_number),
            'photo_path'     => $photoPath,
        ]);

        return redirect()->route('teacher.students.show', $student->id)
            ->with('success', 'Student registered successfully!');
    }

    /**
     * View student profile & attendance history for viewing teacher's own classes ONLY.
     */
    public function show($id)
    {
        $userId  = session('user_id');
        $user    = User::findOrFail($userId);
        $student = Student::findOrFail($id);

        // Filter attendance records to viewing teacher's classes ONLY
        $records = AttendanceRecord::with(['session.schoolClass.subject'])
            ->where('student_id', $student->id)
            ->whereHas('session.schoolClass', function ($q) use ($userId) {
                $q->where('teacher_id', $userId);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $totalClasses = $records->count();
        $presentCount = $records->where('status', 'present')->count();
        $lateCount    = $records->where('status', 'late')->count();
        $absentCount  = $records->where('status', 'absent')->count();
        $excusedCount = $records->where('status', 'excused')->count();

        return view('teacher.students.show', compact(
            'user', 'student', 'records', 'totalClasses',
            'presentCount', 'lateCount', 'absentCount', 'excusedCount'
        ));
    }
}
