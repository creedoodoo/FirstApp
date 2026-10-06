<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use App\Models\ClassModel;
use App\Models\ClassSession;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $userId = session('user_id');
        $user   = User::findOrFail($userId);

        $totalTeachers  = User::where('role', 'teacher')->where('is_approved', true)->count();
        $pendingCount   = User::where('role', 'teacher')->where('is_approved', false)->count();
        $totalStudents  = Student::count();
        $totalSubjects  = Subject::where('is_active', true)->count();

        $sections = config('attendance.sections', []);
        $teachers = User::where('role', 'teacher')->where('is_approved', true)->orderBy('name')->get();
        $subjects = Subject::orderBy('code')->get();

        // Build query for reports
        $query = AttendanceRecord::with(['session.schoolClass.subject', 'session.schoolClass.teacher', 'student'])
            ->whereHas('session');

        if ($request->filled('section')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('section', $request->section);
            });
        }

        if ($request->filled('subject_id')) {
            $query->whereHas('session.schoolClass', function ($q) use ($request) {
                $q->where('subject_id', $request->subject_id);
            });
        }

        if ($request->filled('teacher_id')) {
            $query->whereHas('session.schoolClass', function ($q) use ($request) {
                $q->where('teacher_id', $request->teacher_id);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereHas('session', function ($q) use ($request) {
                $q->whereDate('session_date', '>=', $request->date_from);
            });
        }

        if ($request->filled('date_to')) {
            $query->whereHas('session', function ($q) use ($request) {
                $q->whereDate('session_date', '<=', $request->date_to);
            });
        }

        $records = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        // Overall status counts for filtered or full dataset
        $statusCountsQuery = clone $query;
        $allRecords = $statusCountsQuery->get();

        $countPresent = $allRecords->where('status', 'present')->count();
        $countLate    = $allRecords->where('status', 'late')->count();
        $countAbsent  = $allRecords->where('status', 'absent')->count();
        $countExcused = $allRecords->where('status', 'excused')->count();
        $countUnmarked = $allRecords->whereNull('status')->count();

        return view('admin.dashboard', compact(
            'user', 'totalTeachers', 'pendingCount', 'totalStudents',
            'totalSubjects', 'sections', 'teachers', 'subjects',
            'records', 'countPresent', 'countLate', 'countAbsent',
            'countExcused', 'countUnmarked'
        ));
    }

    public function exportCsv(Request $request)
    {
        $query = AttendanceRecord::with(['session.schoolClass.subject', 'session.schoolClass.teacher', 'student'])
            ->whereHas('session');

        if ($request->filled('section')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('section', $request->section);
            });
        }

        if ($request->filled('subject_id')) {
            $query->whereHas('session.schoolClass', function ($q) use ($request) {
                $q->where('subject_id', $request->subject_id);
            });
        }

        if ($request->filled('teacher_id')) {
            $query->whereHas('session.schoolClass', function ($q) use ($request) {
                $q->where('teacher_id', $request->teacher_id);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereHas('session', function ($q) use ($request) {
                $q->whereDate('session_date', '>=', $request->date_from);
            });
        }

        if ($request->filled('date_to')) {
            $query->whereHas('session', function ($q) use ($request) {
                $q->whereDate('session_date', '<=', $request->date_to);
            });
        }

        $records = $query->orderBy('created_at', 'desc')->get();

        $filename = 'attendance_report_' . date('Y_m_d_His') . '.csv';

        $response = new StreamedResponse(function () use ($records) {
            $handle = fopen('php://output', 'w');

            // Header row
            fputcsv($handle, [
                'Date',
                'Student Number',
                'Student Name',
                'Section',
                'Subject Code',
                'Subject Name',
                'Teacher',
                'Status',
                'Time In',
                'Left Early',
                'Left At',
                'Remarks'
            ]);

            foreach ($records as $row) {
                fputcsv($handle, [
                    optional($row->session)->session_date ? $row->session->session_date->format('Y-m-d') : 'N/A',
                    optional($row->student)->student_number ?? 'N/A',
                    optional($row->student)->full_name ?? 'N/A',
                    optional($row->student)->section ?? 'N/A',
                    optional(optional($row->session)->schoolClass->subject)->code ?? 'N/A',
                    optional(optional($row->session)->schoolClass->subject)->name ?? 'N/A',
                    optional(optional($row->session)->schoolClass->teacher)->name ?? 'N/A',
                    $row->status ? ucfirst($row->status) : 'Unmarked',
                    $row->time_in ? $row->time_in->format('g:i A') : '',
                    $row->left_early ? 'Yes' : 'No',
                    $row->left_at ? $row->left_at->format('g:i A') : '',
                    $row->remarks ?? '',
                ]);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');

        return $response;
    }
}
