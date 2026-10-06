<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use App\Models\ClassModel;
use App\Models\ClassSession;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    /**
     * Get or create attendance session for a class on a specified date.
     */
    public function openSession(Request $request, $classId)
    {
        $userId = session('user_id');
        $user   = User::findOrFail($userId);

        $class = ClassModel::with('subject')
            ->where('teacher_id', $user->id)
            ->findOrFail($classId);

        $date = $request->input('date', date('Y-m-d'));
        $isMakeup = (bool) $request->input('is_makeup', false);

        $session = ClassSession::firstOrCreate(
            [
                'class_id'     => $class->id,
                'session_date' => $date,
            ],
            [
                'status'    => 'draft',
                'is_makeup' => $isMakeup,
            ]
        );

        // Populate Unmarked records for all students in this section if none exist yet
        $existingStudentIds = AttendanceRecord::where('class_session_id', $session->id)
            ->pluck('student_id')
            ->toArray();

        $students = Student::where('section', $class->section)
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        foreach ($students as $student) {
            if (!in_array($student->id, $existingStudentIds)) {
                AttendanceRecord::create([
                    'class_session_id' => $session->id,
                    'student_id'       => $student->id,
                    'status'           => null, // Unmarked
                ]);
            }
        }

        return redirect()->route('teacher.sessions.sheet', $session->id);
    }

    /**
     * View the attendance sheet for a session.
     */
    public function sheet($sessionId)
    {
        $userId = session('user_id');
        $user   = User::findOrFail($userId);

        $session = ClassSession::with(['schoolClass.subject', 'records.student', 'editedBy'])
            ->whereHas('schoolClass', function ($q) use ($user) {
                $q->where('teacher_id', $user->id);
            })
            ->findOrFail($sessionId);

        $records = AttendanceRecord::with('student')
            ->where('class_session_id', $session->id)
            ->join('students', 'attendance_records.student_id', '=', 'students.id')
            ->orderBy('students.last_name')
            ->orderBy('students.first_name')
            ->select('attendance_records.*')
            ->get();

        // Calculate counts
        $totalStudents = $records->count();
        $unmarkedCount = $records->whereNull('status')->count();
        $markedCount   = $totalStudents - $unmarkedCount;
        $presentCount  = $records->where('status', 'present')->count();
        $lateCount     = $records->where('status', 'late')->count();
        $absentCount   = $records->where('status', 'absent')->count();
        $excusedCount  = $records->where('status', 'excused')->count();

        // Eligible students to add if any exist
        $existingStudentIds = $records->pluck('student_id')->toArray();
        $allStudents = Student::orderBy('last_name')->orderBy('first_name')->get();

        return view('teacher.sessions.sheet', compact(
            'user', 'session', 'records', 'totalStudents',
            'unmarkedCount', 'markedCount', 'presentCount',
            'lateCount', 'absentCount', 'excusedCount', 'allStudents'
        ));
    }

    /**
     * Autosave individual record status via AJAX.
     */
    public function updateRecord(Request $request, $sessionId, $recordId)
    {
        $userId = session('user_id');

        $session = ClassSession::whereHas('schoolClass', function ($q) use ($userId) {
            $q->where('teacher_id', $userId);
        })->findOrFail($sessionId);

        $record = AttendanceRecord::where('class_session_id', $session->id)
            ->findOrFail($recordId);

        $request->validate([
            'status'     => 'nullable|in:present,late,absent,excused',
            'left_early' => 'nullable|boolean',
            'left_at'    => 'nullable|string',
            'remarks'    => 'nullable|string|max:255',
        ]);

        $newStatus = $request->status;

        // Auto fill time_in when marking Present or Late
        if (in_array($newStatus, ['present', 'late'])) {
            if (!$record->time_in) {
                $record->time_in = now();
            }
        }

        $record->status = $newStatus;
        $record->marked_at = now();

        if ($request->has('left_early')) {
            $record->left_early = (bool) $request->left_early;
            if ($record->left_early && !$record->left_at) {
                $record->left_at = now();
            }
        }

        if ($request->has('left_at_custom')) {
            $record->left_at = $request->left_at_custom ? date('Y-m-d H:i:s', strtotime($request->left_at_custom)) : null;
        }

        if ($request->has('remarks')) {
            $record->remarks = $request->remarks;
        }

        // Audit stamp if editing after submit
        if ($session->isSubmitted()) {
            $record->edited_at = now();
            $record->edited_by = $userId;

            $session->edited_at = now();
            $session->edited_by = $userId;
            $session->save();
        }

        $record->save();

        // Refresh counts
        $allRecords = AttendanceRecord::where('class_session_id', $session->id)->get();
        $total = $allRecords->count();
        $unmarked = $allRecords->whereNull('status')->count();

        return response()->json([
            'success'   => true,
            'status'    => $record->status,
            'time_in'   => $record->time_in ? $record->time_in->format('g:i A') : null,
            'unmarked'  => $unmarked,
            'marked'    => $total - $unmarked,
            'total'     => $total,
            'present'   => $allRecords->where('status', 'present')->count(),
            'late'      => $allRecords->where('status', 'late')->count(),
            'absent'    => $allRecords->where('status', 'absent')->count(),
            'excused'   => $allRecords->where('status', 'excused')->count(),
        ]);
    }

    /**
     * Mark all Unmarked students as Present.
     */
    public function markAllPresent($sessionId)
    {
        $userId = session('user_id');

        $session = ClassSession::whereHas('schoolClass', function ($q) use ($userId) {
            $q->where('teacher_id', $userId);
        })->findOrFail($sessionId);

        $now = now();

        AttendanceRecord::where('class_session_id', $session->id)
            ->whereNull('status')
            ->update([
                'status'    => 'present',
                'time_in'   => $now,
                'marked_at' => $now,
            ]);

        return redirect()->back()->with('success', 'All unmarked students marked as Present.');
    }

    /**
     * Mark all remaining Unmarked students as Absent.
     */
    public function markRemainingAbsent($sessionId)
    {
        $userId = session('user_id');

        $session = ClassSession::whereHas('schoolClass', function ($q) use ($userId) {
            $q->where('teacher_id', $userId);
        })->findOrFail($sessionId);

        $now = now();

        AttendanceRecord::where('class_session_id', $session->id)
            ->whereNull('status')
            ->update([
                'status'    => 'absent',
                'marked_at' => $now,
            ]);

        return redirect()->back()->with('success', 'All remaining unmarked students marked as Absent.');
    }

    /**
     * Add a student to this class session sheet manually.
     */
    public function addStudent(Request $request, $sessionId)
    {
        $userId = session('user_id');

        $session = ClassSession::whereHas('schoolClass', function ($q) use ($userId) {
            $q->where('teacher_id', $userId);
        })->findOrFail($sessionId);

        $request->validate([
            'student_id' => 'required|exists:students,id',
        ]);

        AttendanceRecord::firstOrCreate(
            [
                'class_session_id' => $session->id,
                'student_id'       => $request->student_id,
            ],
            [
                'status' => null, // Unmarked
            ]
        );

        return redirect()->back()->with('success', 'Student added to attendance sheet.');
    }

    /**
     * Finalize and submit attendance sheet.
     */
    public function submit($sessionId)
    {
        $userId = session('user_id');

        $session = ClassSession::whereHas('schoolClass', function ($q) use ($userId) {
            $q->where('teacher_id', $userId);
        })->findOrFail($sessionId);

        $unmarkedCount = AttendanceRecord::where('class_session_id', $session->id)
            ->whereNull('status')
            ->count();

        if ($unmarkedCount > 0) {
            return redirect()->back()->with('error', "Submit blocked: Please mark all students before submitting ({$unmarkedCount} student(s) remain Unmarked).");
        }

        $session->status       = 'submitted';
        $session->submitted_at = now();
        $session->save();

        return redirect()->back()->with('success', 'Attendance session submitted successfully and locked.');
    }

    /**
     * View history of past sessions for teacher's classes.
     */
    public function history()
    {
        $userId = session('user_id');
        $user   = User::findOrFail($userId);

        $sessions = ClassSession::with(['schoolClass.subject', 'records'])
            ->whereHas('schoolClass', function ($q) use ($user) {
                $q->where('teacher_id', $user->id);
            })
            ->orderBy('session_date', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        return view('teacher.sessions.index', compact('user', 'sessions'));
    }
}
