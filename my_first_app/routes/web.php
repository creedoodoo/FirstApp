<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboard;
use App\Http\Controllers\Teacher\ClassController as TeacherClass;
use App\Http\Controllers\Teacher\SessionController as TeacherSession;
use App\Http\Controllers\Teacher\StudentController as TeacherStudent;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\ApprovalController as AdminApproval;
use App\Http\Controllers\Admin\SubjectController as AdminSubject;

// Public & Auth Routes
Route::get('/', [AuthController::class, 'showAuth'])->name('login');
Route::get('/auth', [AuthController::class, 'showAuth']);

Route::post('/api/signup', [AuthController::class, 'signup'])->name('api.signup');
Route::post('/api/login', [AuthController::class, 'login'])->name('api.login');
Route::get('/landing', [AuthController::class, 'landing'])->name('landing');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Teacher Routes (Middleware: role:teacher)
Route::middleware(['role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [TeacherDashboard::class, 'index'])->name('dashboard');

    // Class Management
    Route::get('/classes', [TeacherClass::class, 'index'])->name('classes.index');
    Route::post('/classes', [TeacherClass::class, 'store'])->name('classes.store');
    Route::post('/classes/{class}/archive', [TeacherClass::class, 'toggleArchive'])->name('classes.archive');

    // Attendance Sessions
    Route::get('/classes/{class}/session', [TeacherSession::class, 'openSession'])->name('classes.session');
    Route::get('/sessions', [TeacherSession::class, 'history'])->name('sessions.index');
    Route::get('/sessions/{session}', [TeacherSession::class, 'sheet'])->name('sessions.sheet');
    Route::post('/sessions/{session}/records/{record}', [TeacherSession::class, 'updateRecord'])->name('sessions.records.update');
    Route::post('/sessions/{session}/mark-all-present', [TeacherSession::class, 'markAllPresent'])->name('sessions.mark_all_present');
    Route::post('/sessions/{session}/mark-remaining-absent', [TeacherSession::class, 'markRemainingAbsent'])->name('sessions.mark_remaining_absent');
    Route::post('/sessions/{session}/add-student', [TeacherSession::class, 'addStudent'])->name('sessions.add_student');
    Route::post('/sessions/{session}/submit', [TeacherSession::class, 'submit'])->name('sessions.submit');

    // Student Management & Search
    Route::get('/students/search', [TeacherStudent::class, 'search'])->name('students.search');
    Route::get('/students/create', [TeacherStudent::class, 'create'])->name('students.create');
    Route::post('/students', [TeacherStudent::class, 'store'])->name('students.store');
    Route::get('/students/{student}', [TeacherStudent::class, 'show'])->name('students.show');
});

// Admin Routes (Middleware: role:admin)
Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
    Route::get('/export/csv', [AdminDashboard::class, 'exportCsv'])->name('export.csv');

    // Teacher Approvals
    Route::get('/approvals', [AdminApproval::class, 'index'])->name('approvals.index');
    Route::post('/approvals/{user}/approve', [AdminApproval::class, 'approve'])->name('approvals.approve');
    Route::post('/approvals/{user}/reject', [AdminApproval::class, 'reject'])->name('approvals.reject');

    // Subject Management
    Route::get('/subjects', [AdminSubject::class, 'index'])->name('subjects.index');
    Route::post('/subjects', [AdminSubject::class, 'store'])->name('subjects.store');
    Route::post('/subjects/{subject}/toggle', [AdminSubject::class, 'toggleActive'])->name('subjects.toggle');
});
