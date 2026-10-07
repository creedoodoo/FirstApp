@extends('layouts.app')

@section('title', 'Student Profile — ' . $student->full_name)

@section('content')
<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem;">
    <div>
        <h1 style="font-size: 1.75rem; font-weight: 700;">Student Attendance Profile</h1>
        <p style="color: var(--color-muted);">Attendance history for {{ $student->full_name }} in your classes.</p>
    </div>
    <a href="{{ route('teacher.students.search') }}" style="padding: 0.55rem 1rem; background: #F1F5F9; color: #475569; border: 1px solid #CBD5E1; border-radius: var(--radius-md); font-weight: 600; text-decoration: none; font-size: 0.875rem;">
        &larr; Search Another Student
    </a>
</div>

<!-- Student Info Card -->
<div class="card" style="margin-bottom: 2rem;">
    <div style="display: flex; align-items: center; gap: 1.5rem; flex-wrap: wrap;">
        <div style="width: 80px; height: 80px; border-radius: 50%; overflow: hidden; background: #E2E8F0; display: flex; align-items: center; justify-content: center; border: 2px solid var(--color-brand-light); flex-shrink: 0;">
            @if($student->photo_path)
                <img src="{{ asset('storage/' . $student->photo_path) }}" alt="{{ $student->full_name }}" style="width: 100%; height: 100%; object-fit: cover;">
            @else
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#94A3B8" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
            @endif
        </div>

        <div>
            <span style="background: var(--color-brand-light); color: var(--color-brand); font-size: 0.75rem; font-weight: 700; padding: 0.2rem 0.6rem; border-radius: 20px;">
                {{ $student->section }}
            </span>
            <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--color-dark); margin: 0.25rem 0 0.15rem;">
                {{ $student->full_name }}
            </h2>
            <div style="font-family: monospace; font-size: 0.9375rem; color: var(--color-muted); font-weight: 600;">
                Student Number: {{ $student->student_number }}
            </div>
        </div>
    </div>

    <!-- Attendance Summary Bar -->
    <div style="margin-top: 1.5rem; padding-top: 1.25rem; border-top: 1px solid var(--color-border); display: flex; gap: 1.5rem; flex-wrap: wrap; font-size: 0.875rem;">
        <div><strong>Total Classes Attended:</strong> {{ $totalClasses }}</div>
        <div><span style="color: #059669; font-weight: 700;">Present:</span> {{ $presentCount }}</div>
        <div><span style="color: #D97706; font-weight: 700;">Late:</span> {{ $lateCount }}</div>
        <div><span style="color: #DC2626; font-weight: 700;">Absent:</span> {{ $absentCount }}</div>
        <div><span style="color: #0284C7; font-weight: 700;">Excused:</span> {{ $excusedCount }}</div>
    </div>
</div>

<!-- Attendance Record History Table (Teacher Scoped) -->
<div class="card" style="padding: 0; overflow: hidden;">
    <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--color-border); background: #FAFBFD;">
        <h3 style="font-size: 1.0625rem; font-weight: 700;">Attendance History in Your Classes</h3>
    </div>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.875rem;">
            <thead>
                <tr style="background: #F8FAFC; border-bottom: 1px solid var(--color-border); color: var(--color-muted); font-size: 0.75rem; text-transform: uppercase;">
                    <th style="padding: 0.875rem 1.25rem;">Date</th>
                    <th style="padding: 0.875rem 1.25rem;">Subject Code & Name</th>
                    <th style="padding: 0.875rem 1.25rem;">Status</th>
                    <th style="padding: 0.875rem 1.25rem;">Time In</th>
                    <th style="padding: 0.875rem 1.25rem;">Left Early</th>
                    <th style="padding: 0.875rem 1.25rem;">Remarks</th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $rec)
                    <tr style="border-bottom: 1px solid #F1F5F9;">
                        <td style="padding: 0.875rem 1.25rem; font-weight: 600;">
                            {{ optional($rec->session)->session_date ? $rec->session->session_date->format('M d, Y') : 'N/A' }}
                        </td>
                        <td style="padding: 0.875rem 1.25rem;">
                            <span style="font-weight: 700; color: var(--color-brand); font-family: monospace;">{{ optional(optional($rec->session)->schoolClass->subject)->code }}</span>
                            - {{ optional(optional($rec->session)->schoolClass->subject)->name }}
                        </td>
                        <td style="padding: 0.875rem 1.25rem;">
                            @if($rec->status === 'present')
                                <span style="background: #D1FAE5; color: #065F46; padding: 0.25rem 0.6rem; border-radius: 20px; font-weight: 700; font-size: 0.75rem;">PRESENT</span>
                            @elseif($rec->status === 'late')
                                <span style="background: #FEF3C7; color: #92400E; padding: 0.25rem 0.6rem; border-radius: 20px; font-weight: 700; font-size: 0.75rem;">LATE</span>
                            @elseif($rec->status === 'absent')
                                <span style="background: #FEE2E2; color: #991B1B; padding: 0.25rem 0.6rem; border-radius: 20px; font-weight: 700; font-size: 0.75rem;">ABSENT</span>
                            @elseif($rec->status === 'excused')
                                <span style="background: #E0F2FE; color: #075985; padding: 0.25rem 0.6rem; border-radius: 20px; font-weight: 700; font-size: 0.75rem;">EXCUSED</span>
                            @else
                                <span style="background: #F1F5F9; color: #64748B; padding: 0.25rem 0.6rem; border-radius: 20px; font-weight: 600; font-size: 0.75rem;">UNMARKED</span>
                            @endif
                        </td>
                        <td style="padding: 0.875rem 1.25rem; color: var(--color-muted);">
                            {{ $rec->time_in ? $rec->time_in->format('g:i A') : '-' }}
                        </td>
                        <td style="padding: 0.875rem 1.25rem;">
                            @if($rec->left_early)
                                <span style="color: #DC2626; font-weight: 600;">Yes ({{ $rec->left_at ? $rec->left_at->format('g:i A') : 'Early' }})</span>
                            @else
                                <span style="color: var(--color-muted);">No</span>
                            @endif
                        </td>
                        <td style="padding: 0.875rem 1.25rem; color: var(--color-muted);">
                            {{ $rec->remarks ?? '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding: 2.5rem; text-align: center; color: var(--color-muted);">
                            No attendance history recorded for this student in your classes yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
