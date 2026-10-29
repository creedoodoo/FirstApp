@extends('layouts.app')

@section('title', 'My Sessions History — Teacher Dashboard')

@section('content')
<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem;">
    <div>
        <h1 style="font-size: 1.75rem; font-weight: 700;">My Sessions History</h1>
        <p style="color: var(--color-muted);">Review past class attendance sessions, status counts, and edits.</p>
    </div>
    <a href="{{ route('teacher.dashboard') }}" style="padding: 0.65rem 1.15rem; background: #F1F5F9; color: #475569; border: 1px solid #CBD5E1; border-radius: var(--radius-md); font-weight: 600; text-decoration: none;">
        &larr; Back to Dashboard
    </a>
</div>

<!-- Sessions Table Card -->
<div class="card" style="padding: 0; overflow: hidden;">
    <div class="table-card-header" style="padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(0,0,0,0.1); background: var(--color-brand-header); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.75rem;">
        <h3 style="font-size: 1.0625rem; font-weight: 700; color: var(--color-dark); margin: 0;">Completed & Draft Sessions</h3>
        <span style="background: #FFFFFF; color: var(--color-brand); padding: 0.2rem 0.65rem; border-radius: 20px; font-weight: 700; font-size: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.12);">
            {{ $sessions->count() }} SESSIONS
        </span>
    </div>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.875rem;">
            <thead>
                <tr style="background: #F8FAFC; border-bottom: 1px solid var(--color-border); color: var(--color-dark); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">
                    <th style="padding: 0.875rem 1.25rem; color: var(--color-dark); vertical-align: middle; text-align: left;">Date</th>
                    <th style="padding: 0.875rem 1.25rem; color: var(--color-dark); vertical-align: middle; text-align: left;">Subject</th>
                    <th style="padding: 0.875rem 1.25rem; color: var(--color-dark); vertical-align: middle; text-align: left;">Section</th>
                    <th style="padding: 0.875rem 1.25rem; color: var(--color-dark); vertical-align: middle; text-align: left;">Status Breakdown</th>
                    <th style="padding: 0.875rem 1.25rem; color: var(--color-dark); vertical-align: middle; text-align: left;">Session Status</th>
                    <th style="padding: 0.875rem 1.25rem; color: var(--color-dark); vertical-align: middle; text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sessions as $sess)
                    @php
                        $recs = $sess->records;
                        $p = $recs->where('status', 'present')->count();
                        $l = $recs->where('status', 'late')->count();
                        $a = $recs->where('status', 'absent')->count();
                        $e = $recs->where('status', 'excused')->count();
                    @endphp
                    <tr style="border-bottom: 1px solid #F1F5F9;">
                        <td style="padding: 0.875rem 1.25rem; font-weight: 700; vertical-align: middle; text-align: left;">
                            {{ $sess->session_date->format('M d, Y') }}
                            @if($sess->is_makeup)
                                <span style="background: #E0F2FE; color: #075985; font-size: 0.7rem; padding: 0.15rem 0.4rem; border-radius: 4px; margin-left: 0.35rem;">MAKEUP</span>
                            @endif
                        </td>
                        <td style="padding: 0.875rem 1.25rem; vertical-align: middle; text-align: left;">
                            <span style="font-weight: 700; color: var(--color-brand); font-family: monospace;">{{ optional(optional($sess->schoolClass)->subject)->code }}</span>
                            - {{ optional(optional($sess->schoolClass)->subject)->name }}
                        </td>
                        <td style="padding: 0.875rem 1.25rem; font-weight: 600; vertical-align: middle; text-align: left;">
                            {{ optional($sess->schoolClass)->section }}
                        </td>
                        <td style="padding: 0.875rem 1.25rem; font-size: 0.8125rem; vertical-align: middle; text-align: left;">
                            <span style="color: #059669; font-weight: 600;">P: {{ $p }}</span> &bull; 
                            <span style="color: #a406d9; font-weight: 600;">L: {{ $l }}</span> &bull; 
                            <span style="color: #DC2626; font-weight: 600;">A: {{ $a }}</span> &bull; 
                            <span style="color: #0284C7; font-weight: 600;">E: {{ $e }}</span>
                        </td>
                        <td style="padding: 0.875rem 1.25rem; vertical-align: middle; text-align: left;">
                            @if($sess->isSubmitted())
                                <span style="background: #D1FAE5; color: #065F46; padding: 0.2rem 0.6rem; border-radius: 20px; font-weight: 700; font-size: 0.75rem;">SUBMITTED</span>
                            @else
                                <span style="background: #FEF3C7; color: #92400E; padding: 0.2rem 0.6rem; border-radius: 20px; font-weight: 700; font-size: 0.75rem;">DRAFT</span>
                            @endif
                        </td>
                        <td style="padding: 0.875rem 1.25rem; text-align: right; vertical-align: middle;">
                            <a href="{{ route('teacher.sessions.sheet', $sess->id) }}" style="padding: 0.45rem 0.95rem; background: var(--color-brand); color: #FFF; border-radius: 50px; font-weight: 700; text-decoration: none; font-size: 0.8125rem; display: inline-block;">
                                {{ $sess->isSubmitted() ? 'View / Edit Sheet' : 'Open Sheet' }}
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding: 3rem; text-align: center; color: var(--color-muted);">
                            No attendance session history found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
