@extends('layouts.app')

@section('title', 'Admin Dashboard — BSIT 3rd Year Attendance System')

@section('content')
<div class="card" style="margin-bottom: 2rem; background: linear-gradient(135deg, #8C0D47 0%, #6F0A38 100%); color: #FFFFFF; padding: 1.75rem 2.5rem; border-radius: 28px; box-shadow: var(--shadow-md);">
    <div style="display: flex; align-items: center; justify-content: space-between; width: 100%; flex-wrap: wrap; gap: 1.5rem;">
        <div style="flex: 1; min-width: 250px;">
            <h1 style="font-size: 1.75rem; font-weight: 700; color: #FFFFFF; margin-bottom: 0.35rem;">Admin Attendance Dashboard</h1>
            <p style="color: rgba(255, 255, 255, 0.9); font-size: 0.9375rem; margin: 0;">BSIT 3rd Year Attendance Reports across all sections, subjects, teachers, and dates.</p>
        </div>
        <div>
            <a href="{{ route('admin.export.csv', request()->query()) }}" style="display: inline-flex; align-items: center; gap: 0.5rem; background: #FFFFFF; color: #8C0D47; padding: 0.65rem 1.35rem; border-radius: 50px; font-weight: 700; text-decoration: none; white-space: nowrap; transition: var(--transition);">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#8C0D47" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Export CSV Report
            </a>
        </div>
    </div>
</div>

<!-- Key Stat Cards -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.25rem; margin-bottom: 2rem;">
    <div class="card" style="margin-bottom: 0;">
        <div style="font-size: 0.8125rem; font-weight: 600; color: var(--color-muted); text-transform: uppercase;">Active Teachers</div>
        <div style="font-size: 2rem; font-weight: 700; color: var(--color-dark); margin: 0.25rem 0;">{{ $totalTeachers }}</div>
        <div style="font-size: 0.8125rem; color: var(--color-brand); font-weight: 600;">
            <a href="{{ route('admin.approvals.index') }}" style="color: inherit; text-decoration: none;">{{ $pendingCount }} Pending Approvals &rarr;</a>
        </div>
    </div>

    <div class="card" style="margin-bottom: 0;">
        <div style="font-size: 0.8125rem; font-weight: 600; color: var(--color-muted); text-transform: uppercase;">BSIT 3rd Year Students</div>
        <div style="font-size: 2rem; font-weight: 700; color: var(--color-dark); margin: 0.25rem 0;">{{ $totalStudents }}</div>
        <div style="font-size: 0.8125rem; color: var(--color-muted);">BSIT 3-1 to 3-4</div>
    </div>

    <div class="card" style="margin-bottom: 0;">
        <div style="font-size: 0.8125rem; font-weight: 600; color: var(--color-muted); text-transform: uppercase;">Active Subjects</div>
        <div style="font-size: 2rem; font-weight: 700; color: var(--color-dark); margin: 0.25rem 0;">{{ $totalSubjects }}</div>
        <div style="font-size: 0.8125rem; color: var(--color-brand); font-weight: 600;">
            <a href="{{ route('admin.subjects.index') }}" style="color: inherit; text-decoration: none;">Manage Subjects &rarr;</a>
        </div>
    </div>

    <div class="card" style="margin-bottom: 0;">
        <div style="font-size: 0.8125rem; font-weight: 600; color: var(--color-muted); text-transform: uppercase;">Total Filtered Records</div>
        <div style="font-size: 2rem; font-weight: 700; color: var(--color-dark); margin: 0.25rem 0;">{{ $records->total() }}</div>
        <div style="font-size: 0.8125rem; color: var(--color-muted);">
            P: {{ $countPresent }} | L: {{ $countLate }} | A: {{ $countAbsent }} | E: {{ $countExcused }}
        </div>
    </div>
</div>

<!-- Filters Card -->
<div class="card">
    <h3 style="font-size: 1.125rem; font-weight: 700; margin-bottom: 1rem;">Filter Attendance Records</h3>
    <form method="GET" action="{{ route('admin.dashboard') }}" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; align-items: end;">
        <div>
            <label style="display: block; font-size: 0.8125rem; font-weight: 600; margin-bottom: 0.35rem;">Section</label>
            <select name="section" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-md);">
                <option value="">All Sections</option>
                @foreach($sections as $sec)
                    <option value="{{ $sec }}" {{ request('section') == $sec ? 'selected' : '' }}>{{ $sec }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label style="display: block; font-size: 0.8125rem; font-weight: 600; margin-bottom: 0.35rem;">Subject</label>
            <select name="subject_id" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-md);">
                <option value="">All Subjects</option>
                @foreach($subjects as $sub)
                    <option value="{{ $sub->id }}" {{ request('subject_id') == $sub->id ? 'selected' : '' }}>{{ $sub->code }} - {{ $sub->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label style="display: block; font-size: 0.8125rem; font-weight: 600; margin-bottom: 0.35rem;">Teacher</label>
            <select name="teacher_id" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-md);">
                <option value="">All Teachers</option>
                @foreach($teachers as $t)
                    <option value="{{ $t->id }}" {{ request('teacher_id') == $t->id ? 'selected' : '' }}>{{ $t->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label style="display: block; font-size: 0.8125rem; font-weight: 600; margin-bottom: 0.35rem;">Status</label>
            <select name="status" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-md);">
                <option value="">All Statuses</option>
                <option value="present" {{ request('status') == 'present' ? 'selected' : '' }}>Present</option>
                <option value="late" {{ request('status') == 'late' ? 'selected' : '' }}>Late</option>
                <option value="absent" {{ request('status') == 'absent' ? 'selected' : '' }}>Absent</option>
                <option value="excused" {{ request('status') == 'excused' ? 'selected' : '' }}>Excused</option>
            </select>
        </div>

        <div>
            <label style="display: block; font-size: 0.8125rem; font-weight: 600; margin-bottom: 0.35rem;">From Date</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}" style="width: 100%; padding: 0.55rem; border: 1px solid var(--color-border); border-radius: var(--radius-md);">
        </div>

        <div>
            <label style="display: block; font-size: 0.8125rem; font-weight: 600; margin-bottom: 0.35rem;">To Date</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}" style="width: 100%; padding: 0.55rem; border: 1px solid var(--color-border); border-radius: var(--radius-md);">
        </div>

        <div style="display: flex; gap: 0.5rem;">
            <button type="submit" style="flex: 1; padding: 0.6rem 1rem; background: var(--color-brand); color: #FFF; border: none; border-radius: var(--radius-md); font-weight: 600; cursor: pointer;">
                Apply Filter
            </button>
            <a href="{{ route('admin.dashboard') }}" style="padding: 0.6rem 1rem; background: #F1F5F9; color: #475569; border: 1px solid #CBD5E1; border-radius: var(--radius-md); font-weight: 600; text-decoration: none;">
                Reset
            </a>
        </div>
    </form>
</div>

<!-- Attendance Records Table -->
<div class="card" style="padding: 0; overflow: hidden;">
    <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--color-border); background: #FAFBFD;">
        <h3 style="font-size: 1.0625rem; font-weight: 700;">Attendance Log Records</h3>
    </div>
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.875rem;">
            <thead>
                <tr style="background: #F8FAFC; border-bottom: 1px solid var(--color-border); color: var(--color-muted); font-size: 0.75rem; text-transform: uppercase;">
                    <th style="padding: 0.875rem 1.25rem;">Date</th>
                    <th style="padding: 0.875rem 1.25rem;">Student Number</th>
                    <th style="padding: 0.875rem 1.25rem;">Student Name</th>
                    <th style="padding: 0.875rem 1.25rem;">Section</th>
                    <th style="padding: 0.875rem 1.25rem;">Subject</th>
                    <th style="padding: 0.875rem 1.25rem;">Teacher</th>
                    <th style="padding: 0.875rem 1.25rem;">Status</th>
                    <th style="padding: 0.875rem 1.25rem;">Time In / Left</th>
                    <th style="padding: 0.875rem 1.25rem;">Remarks</th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $rec)
                    <tr style="border-bottom: 1px solid #F1F5F9;">
                        <td style="padding: 0.875rem 1.25rem; font-weight: 600;">
                            {{ optional($rec->session)->session_date ? $rec->session->session_date->format('M d, Y') : 'N/A' }}
                        </td>
                        <td style="padding: 0.875rem 1.25rem; font-family: monospace;">
                            {{ optional($rec->student)->student_number ?? 'N/A' }}
                        </td>
                        <td style="padding: 0.875rem 1.25rem; font-weight: 600;">
                            {{ optional($rec->student)->full_name ?? 'N/A' }}
                        </td>
                        <td style="padding: 0.875rem 1.25rem;">
                            <span style="background: #F1F5F9; padding: 0.2rem 0.5rem; border-radius: 4px; font-weight: 600; font-size: 0.75rem;">
                                {{ optional($rec->student)->section ?? 'N/A' }}
                            </span>
                        </td>
                        <td style="padding: 0.875rem 1.25rem;">
                            {{ optional(optional($rec->session)->schoolClass->subject)->code ?? 'N/A' }}
                        </td>
                        <td style="padding: 0.875rem 1.25rem;">
                            {{ optional(optional($rec->session)->schoolClass->teacher)->name ?? 'N/A' }}
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
                        <td style="padding: 0.875rem 1.25rem; font-size: 0.8125rem; color: var(--color-muted);">
                            @if($rec->time_in) In: {{ $rec->time_in->format('g:i A') }} @endif
                            @if($rec->left_early) <br><span style="color: #DC2626;">Left: {{ $rec->left_at ? $rec->left_at->format('g:i A') : 'Yes' }}</span> @endif
                        </td>
                        <td style="padding: 0.875rem 1.25rem; font-size: 0.8125rem; color: var(--color-muted);">
                            {{ $rec->remarks ?? '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" style="padding: 3rem; text-align: center; color: var(--color-muted);">
                            No attendance records match your filter criteria.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($records->hasPages())
        <div style="padding: 1rem 1.5rem; border-top: 1px solid var(--color-border);">
            {{ $records->links() }}
        </div>
    @endif
</div>
@endsection
