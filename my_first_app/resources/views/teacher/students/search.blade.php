@extends('layouts.app')

@section('title', 'Search Student Number — Teacher Dashboard')

@section('content')
<div style="max-width: 640px; margin: 0 auto;">
    <div style="text-align: center; margin-bottom: 2rem;">
        <h1 style="font-size: 1.75rem; font-weight: 700;">Student Lookup</h1>
        <p style="color: var(--color-muted);">Search BSIT 3rd Year students by student number (Format: <code>2024-00452-SR-0</code>).</p>
    </div>

    <!-- Search Form Card -->
    <div class="card" style="margin-bottom: 2rem;">
        <form action="{{ route('teacher.students.search') }}" method="GET">
            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 0.5rem;">Student Number</label>
                <input type="text" name="student_number" value="{{ $query }}" placeholder="2024-00452-SR-0" required style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--color-border); border-radius: var(--radius-md); font-family: monospace; font-size: 1rem;">
                <span style="font-size: 0.75rem; color: var(--color-muted); margin-top: 0.25rem; display: block;">Format rule: <code>YYYY-XXXXX-SR-N</code></span>
            </div>

            <button type="submit" style="width: 100%; padding: 0.75rem; background: var(--color-brand); color: #FFF; border: none; border-radius: var(--radius-md); font-weight: 700; font-size: 1rem; cursor: pointer;">
                🔍 Search Student
            </button>
        </form>
    </div>

    <!-- Results Section -->
    @if($searched)
        @if($result)
            <div class="card" style="border-left: 4px solid #10B981;">
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <span style="background: #D1FAE5; color: #065F46; font-size: 0.75rem; font-weight: 700; padding: 0.2rem 0.6rem; border-radius: 20px;">
                            STUDENT RECORD FOUND
                        </span>
                        <h3 style="font-size: 1.25rem; font-weight: 700; margin: 0.5rem 0 0.25rem; color: var(--color-dark);">
                            {{ $result->full_name }}
                        </h3>
                        <div style="font-size: 0.875rem; color: var(--color-muted);">
                            <strong>Student Number:</strong> <code style="font-weight: 700; color: var(--color-brand);">{{ $result->student_number }}</code><br>
                            <strong>Section:</strong> <span style="font-weight: 600;">{{ $result->section }}</span>
                        </div>
                    </div>

                    <a href="{{ route('teacher.students.show', $result->id) }}" style="padding: 0.65rem 1.15rem; background: var(--color-brand); color: #FFF; border-radius: var(--radius-md); font-weight: 600; text-decoration: none; font-size: 0.875rem;">
                        View Attendance History &rarr;
                    </a>
                </div>
            </div>
        @else
            <div class="card" style="border-left: 4px solid #F59E0B; text-align: center; padding: 2.5rem 1.5rem;">
                <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">🔎</div>
                <h3 style="font-size: 1.125rem; font-weight: 700; color: var(--color-dark); margin-bottom: 0.5rem;">
                    No student found matching "{{ $query }}"
                </h3>
                <p style="color: var(--color-muted); font-size: 0.9375rem; margin-bottom: 1.5rem;">
                    This student number is not registered in the system yet.
                </p>

                <a href="{{ route('teacher.students.create', ['student_number' => $query]) }}" style="display: inline-block; padding: 0.75rem 1.5rem; background: var(--color-brand); color: #FFF; border-radius: var(--radius-md); font-weight: 700; text-decoration: none;">
                    + Register This Student Now
                </a>
            </div>
        @endif
    @endif
</div>
@endsection
