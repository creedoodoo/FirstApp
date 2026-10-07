@extends('layouts.app')

@section('title', 'Teacher Dashboard — BSIT 3rd Year Attendance System')

@section('content')
<div class="card" style="margin-bottom: 2rem; background: linear-gradient(135deg, #8C0D47 0%, #6F0A38 100%); color: #FFFFFF; padding: 1.75rem 2rem; border-radius: 16px; box-shadow: var(--shadow-md);">
    <div style="display: flex; align-items: center; justify-content: space-between; width: 100%; flex-wrap: wrap; gap: 1.5rem;">
        <div style="flex: 1; min-width: 250px;">
            <h1 style="font-size: 1.75rem; font-weight: 700; color: #FFFFFF; margin-bottom: 0.35rem;">Hello- {{ $user->name }}</h1>
            <p style="color: rgba(255, 255, 255, 0.9); font-size: 0.9375rem; margin: 0;">Welcome back! Manage your BSIT 3rd Year classes and take attendance.</p>
        </div>
        <div style="display: flex; gap: 0.75rem; flex-wrap: nowrap; flex-shrink: 0; align-items: center;">
            <a href="{{ route('teacher.classes.index') }}" style="background: #500728; color: #FFFFFF; padding: 0.65rem 1.25rem; border-radius: var(--radius-md); font-weight: 700; text-decoration: none; white-space: nowrap; border: 1px solid rgba(255, 255, 255, 0.25); transition: var(--transition);">
                + Add Class
            </a>
            <a href="{{ route('teacher.students.search') }}" style="background: #FFFFFF; color: #8C0D47; padding: 0.65rem 1.25rem; border-radius: var(--radius-md); font-weight: 700; text-decoration: none; white-space: nowrap; transition: var(--transition);">
                Search Student
            </a>
            <a href="{{ route('teacher.students.create') }}" style="background: #FFFFFF; color: #8C0D47; padding: 0.65rem 1.25rem; border-radius: var(--radius-md); font-weight: 700; text-decoration: none; white-space: nowrap; transition: var(--transition);">
                Register Student
            </a>
        </div>
    </div>
</div>

<!-- My Scheduled Classes Section -->
<div style="margin-bottom: 2.5rem;">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem;">
        <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--color-dark);">My Classes ({{ $classes->count() }})</h2>
        <a href="{{ route('teacher.classes.index') }}" style="color: var(--color-brand); font-weight: 600; text-decoration: none; font-size: 0.875rem;">Manage Classes &rarr;</a>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.25rem;">
        @forelse($classes as $c)
            @php $isToday = ($c->day_of_week == $currentDayOfWeek); @endphp
            <div class="card" style="margin-bottom: 0; position: relative; border-left: 4px solid {{ $isToday ? '#10B981' : 'var(--color-brand)' }}; display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.5rem;">
                        <span style="font-size: 0.8125rem; font-weight: 700; color: var(--color-brand); font-family: monospace; background: var(--color-brand-light); padding: 0.2rem 0.5rem; border-radius: 4px;">
                            {{ optional($c->subject)->code }}
                        </span>
                        @if($isToday)
                            <span style="background: #D1FAE5; color: #065F46; font-size: 0.75rem; font-weight: 700; padding: 0.2rem 0.6rem; border-radius: 20px;">
                                TODAY
                            </span>
                        @endif
                    </div>

                    <h3 style="font-size: 1.125rem; font-weight: 700; margin-bottom: 0.35rem; color: var(--color-dark);">
                        {{ optional($c->subject)->name }}
                    </h3>

                    <div style="font-size: 0.875rem; color: var(--color-muted); margin-bottom: 0.75rem;">
                        <strong>Section:</strong> <span style="color: var(--color-dark); font-weight: 600;">{{ $c->section }}</span><br>
                        <strong>Schedule:</strong> {{ $c->day_name }}, {{ $c->formatted_time }}
                    </div>
                </div>

                <div style="margin-top: 1rem; border-top: 1px solid var(--color-border); padding-top: 0.875rem;">
                    <a href="{{ route('teacher.classes.session', $c->id) }}" style="display: block; text-align: center; background: {{ $isToday ? '#10B981' : 'var(--color-brand)' }}; color: #FFF; padding: 0.6rem; border-radius: var(--radius-md); font-weight: 600; text-decoration: none; font-size: 0.875rem;">
                        {{ $isToday ? 'Take Today\'s Attendance' : 'Open Attendance Sheet' }}
                    </a>
                </div>
            </div>
        @empty
            <div class="card" style="grid-column: 1 / -1; text-align: center; padding: 3rem;">
                <p style="color: var(--color-muted); margin-bottom: 1rem;">You haven't added any classes yet.</p>
                <a href="{{ route('teacher.classes.index') }}" style="display: inline-block; background: var(--color-brand); color: #FFF; padding: 0.65rem 1.25rem; border-radius: var(--radius-md); font-weight: 600; text-decoration: none;">
                    + Add Your First Class
                </a>
            </div>
        @endforelse
    </div>
</div>

<!-- Recent Sessions Card -->
<div class="card" style="padding: 0; overflow: hidden;">
    <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--color-border); background: #FAFBFD; display: flex; align-items: center; justify-content: space-between;">
        <h3 style="font-size: 1.0625rem; font-weight: 700;">Recent Sessions</h3>
        <a href="{{ route('teacher.sessions.index') }}" style="color: var(--color-brand); font-weight: 600; font-size: 0.875rem; text-decoration: none;">View All Sessions &rarr;</a>
    </div>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.875rem;">
            <thead>
                <tr style="background: #F8FAFC; border-bottom: 1px solid var(--color-border); color: var(--color-muted); font-size: 0.75rem; text-transform: uppercase;">
                    <th style="padding: 0.875rem 1.25rem;">Date</th>
                    <th style="padding: 0.875rem 1.25rem;">Subject</th>
                    <th style="padding: 0.875rem 1.25rem;">Section</th>
                    <th style="padding: 0.875rem 1.25rem;">Status</th>
                    <th style="padding: 0.875rem 1.25rem; text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentSessions as $sess)
                    <tr style="border-bottom: 1px solid #F1F5F9;">
                        <td style="padding: 0.875rem 1.25rem; font-weight: 600;">
                            {{ $sess->session_date->format('M d, Y') }}
                            @if($sess->is_makeup)
                                <span style="background: #FEF3C7; color: #92400E; font-size: 0.7rem; padding: 0.15rem 0.4rem; border-radius: 4px; margin-left: 0.35rem;">MAKEUP</span>
                            @endif
                        </td>
                        <td style="padding: 0.875rem 1.25rem;">
                            {{ optional(optional($sess->schoolClass)->subject)->code }} - {{ optional(optional($sess->schoolClass)->subject)->name }}
                        </td>
                        <td style="padding: 0.875rem 1.25rem; font-weight: 600;">
                            {{ optional($sess->schoolClass)->section }}
                        </td>
                        <td style="padding: 0.875rem 1.25rem;">
                            @if($sess->status === 'submitted')
                                <span style="background: #D1FAE5; color: #065F46; padding: 0.2rem 0.6rem; border-radius: 20px; font-weight: 700; font-size: 0.75rem;">SUBMITTED</span>
                            @else
                                <span style="background: #FEF3C7; color: #92400E; padding: 0.2rem 0.6rem; border-radius: 20px; font-weight: 700; font-size: 0.75rem;">DRAFT</span>
                            @endif
                        </td>
                        <td style="padding: 0.875rem 1.25rem; text-align: right;">
                            <a href="{{ route('teacher.sessions.sheet', $sess->id) }}" style="color: var(--color-brand); font-weight: 600; text-decoration: none;">
                                Open Sheet &rarr;
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding: 2.5rem; text-align: center; color: var(--color-muted);">
                            No recent attendance sessions found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
