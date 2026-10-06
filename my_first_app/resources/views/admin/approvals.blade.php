@extends('layouts.app')

@section('title', 'Teacher Approvals — Admin Panel')

@section('content')
<div style="margin-bottom: 2rem;">
    <h1 style="font-size: 1.75rem; font-weight: 700;">Teacher Account Approvals</h1>
    <p style="color: var(--color-muted);">Review and approve new teacher account registrations before they can log in.</p>
</div>

<!-- Pending Teacher Approvals Card -->
<div class="card" style="padding: 0; overflow: hidden; margin-bottom: 2rem;">
    <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--color-border); background: #FAFBFD; display: flex; align-items: center; justify-content: space-between;">
        <h3 style="font-size: 1.0625rem; font-weight: 700;">Pending Registration Requests</h3>
        <span style="background: #FEF2F2; color: #DC2626; padding: 0.2rem 0.6rem; border-radius: 20px; font-weight: 700; font-size: 0.75rem;">
            {{ $pendingTeachers->count() }} PENDING
        </span>
    </div>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.875rem;">
            <thead>
                <tr style="background: #F8FAFC; border-bottom: 1px solid var(--color-border); color: var(--color-muted); font-size: 0.75rem; text-transform: uppercase;">
                    <th style="padding: 0.875rem 1.25rem;">Date Requested</th>
                    <th style="padding: 0.875rem 1.25rem;">Teacher Name</th>
                    <th style="padding: 0.875rem 1.25rem;">Email Address</th>
                    <th style="padding: 0.875rem 1.25rem;">Role</th>
                    <th style="padding: 0.875rem 1.25rem; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendingTeachers as $pt)
                    <tr style="border-bottom: 1px solid #F1F5F9;">
                        <td style="padding: 0.875rem 1.25rem; color: var(--color-muted);">
                            {{ $pt->created_at->format('M d, Y - g:i A') }}
                        </td>
                        <td style="padding: 0.875rem 1.25rem; font-weight: 700; color: var(--color-dark);">
                            {{ $pt->name }}
                        </td>
                        <td style="padding: 0.875rem 1.25rem;">
                            {{ $pt->email }}
                        </td>
                        <td style="padding: 0.875rem 1.25rem;">
                            <span style="background: #FBF0F5; color: var(--color-brand); padding: 0.2rem 0.6rem; border-radius: 20px; font-weight: 700; font-size: 0.75rem;">
                                TEACHER
                            </span>
                        </td>
                        <td style="padding: 0.875rem 1.25rem; text-align: right;">
                            <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                                <form action="{{ route('admin.approvals.approve', $pt->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" style="padding: 0.45rem 0.875rem; background: #10B981; color: #FFF; border: none; border-radius: var(--radius-md); font-weight: 600; cursor: pointer;">
                                        Approve Teacher
                                    </button>
                                </form>
                                <form action="{{ route('admin.approvals.reject', $pt->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to reject and delete this registration?');">
                                    @csrf
                                    <button type="submit" style="padding: 0.45rem 0.875rem; background: #EF4444; color: #FFF; border: none; border-radius: var(--radius-md); font-weight: 600; cursor: pointer;">
                                        Reject
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding: 3rem; text-align: center; color: var(--color-muted);">
                            No pending teacher registration requests found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Active Approved Teachers List -->
<div class="card" style="padding: 0; overflow: hidden;">
    <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--color-border); background: #FAFBFD;">
        <h3 style="font-size: 1.0625rem; font-weight: 700;">Approved Active Teachers ({{ $approvedTeachers->count() }})</h3>
    </div>
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.875rem;">
            <thead>
                <tr style="background: #F8FAFC; border-bottom: 1px solid var(--color-border); color: var(--color-muted); font-size: 0.75rem; text-transform: uppercase;">
                    <th style="padding: 0.875rem 1.25rem;">Teacher Name</th>
                    <th style="padding: 0.875rem 1.25rem;">Email Address</th>
                    <th style="padding: 0.875rem 1.25rem;">Status</th>
                    <th style="padding: 0.875rem 1.25rem;">Date Approved / Joined</th>
                </tr>
            </thead>
            <tbody>
                @forelse($approvedTeachers as $at)
                    <tr style="border-bottom: 1px solid #F1F5F9;">
                        <td style="padding: 0.875rem 1.25rem; font-weight: 700;">{{ $at->name }}</td>
                        <td style="padding: 0.875rem 1.25rem;">{{ $at->email }}</td>
                        <td style="padding: 0.875rem 1.25rem;">
                            <span style="background: #D1FAE5; color: #065F46; padding: 0.2rem 0.6rem; border-radius: 20px; font-weight: 700; font-size: 0.75rem;">
                                APPROVED & ACTIVE
                            </span>
                        </td>
                        <td style="padding: 0.875rem 1.25rem; color: var(--color-muted);">
                            {{ $at->created_at->format('M d, Y') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="padding: 2rem; text-align: center; color: var(--color-muted);">
                            No approved teacher accounts yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
