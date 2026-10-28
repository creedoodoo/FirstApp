@extends('layouts.app')

@section('title', 'Teacher Approvals — Admin Panel')

@section('content')
<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1 style="font-size: 1.75rem; font-weight: 700;">
            {{ $viewMode === 'archived' ? 'Deactivated Teachers Archive' : 'Teacher Account Approvals' }}
        </h1>
        <p style="color: var(--color-muted);">
            {{ $viewMode === 'archived' 
                ? 'Review and reactivate deactivated teacher accounts.' 
                : 'Review and approve new teacher account registrations before they can log in.' }}
        </p>
    </div>

    @if($viewMode === 'archived')
        <a href="{{ route('admin.approvals.index') }}" style="padding: 0.55rem 1.25rem; background: #FFFFFF; color: var(--color-brand); border: 1.5px solid var(--color-brand); border-radius: 50px; font-weight: 700; text-decoration: none; font-size: 0.875rem; display: inline-flex; align-items: center; gap: 0.4rem; transition: var(--transition);">
            &larr; Back to Active Teachers
        </a>
    @endif
</div>

@if($viewMode === 'archived')
    <!-- Archived / Deactivated Teachers Table -->
    <div class="card" style="padding: 0; overflow: hidden;">
        <div class="table-card-header" style="padding: 1.25rem 1.5rem; background: var(--color-brand-header); border-bottom: 1px solid rgba(0,0,0,0.1); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.75rem;">
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <h3 style="font-size: 1.0625rem; font-weight: 700; color: var(--color-dark); margin: 0;">Deactivated Teacher Accounts</h3>
                <span style="background: #FFFFFF; color: #DC2626; padding: 0.2rem 0.65rem; border-radius: 20px; font-weight: 700; font-size: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.12);">
                    {{ $archivedTeachers->count() }} ARCHIVED
                </span>
            </div>
            <a href="{{ route('admin.approvals.index') }}" style="background: #FFFFFF; color: var(--color-brand); padding: 0.35rem 0.85rem; border-radius: 50px; font-size: 0.8125rem; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 0.35rem; box-shadow: 0 1px 3px rgba(0,0,0,0.12);">
                &larr; Back to Active Teachers
            </a>
        </div>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.875rem;">
                <thead>
                    <tr style="background: #F8FAFC; border-bottom: 1px solid var(--color-border); color: var(--color-dark); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">
                        <th style="padding: 0.875rem 1.25rem; color: var(--color-dark); vertical-align: middle; text-align: left;">Teacher Name</th>
                        <th style="padding: 0.875rem 1.25rem; color: var(--color-dark); vertical-align: middle; text-align: left;">Email Address</th>
                        <th style="padding: 0.875rem 1.25rem; color: var(--color-dark); vertical-align: middle; text-align: left;">Status</th>
                        <th style="padding: 0.875rem 1.25rem; color: var(--color-dark); vertical-align: middle; text-align: left;">Date Registered</th>
                        <th style="padding: 0.875rem 1.25rem; color: var(--color-dark); vertical-align: middle; text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($archivedTeachers as $at)
                        <tr style="border-bottom: 1px solid #F1F5F9;">
                            <td style="padding: 0.875rem 1.25rem; font-weight: 700; color: var(--color-dark); vertical-align: middle; text-align: left;">
                                {{ $at->name }}
                            </td>
                            <td style="padding: 0.875rem 1.25rem; vertical-align: middle; text-align: left;">
                                {{ $at->email }}
                            </td>
                            <td style="padding: 0.875rem 1.25rem; vertical-align: middle; text-align: left;">
                                <span style="background: #FEE2E2; color: #991B1B; padding: 0.25rem 0.65rem; border-radius: 20px; font-weight: 700; font-size: 0.75rem;">
                                    DEACTIVATED
                                </span>
                            </td>
                            <td style="padding: 0.875rem 1.25rem; color: var(--color-muted); vertical-align: middle; text-align: left;">
                                {{ $at->created_at->format('M d, Y') }}
                            </td>
                            <td style="padding: 0.875rem 1.25rem; text-align: right; vertical-align: middle;">
                                <button type="button" onclick="openConfirmModal({
                                    title: 'Reactivate Teacher Account',
                                    message: 'Are you sure you want to reactivate {{ $at->name }}? They will regain access to their classes and attendance sessions.',
                                    actionUrl: '{{ route('admin.approvals.reactivate', $at->id) }}',
                                    buttonText: 'Reactivate Teacher',
                                    buttonColor: '#10B981'
                                })" style="padding: 0.45rem 1rem; background: #10B981; color: #FFFFFF; border: none; border-radius: 50px; font-weight: 700; font-size: 0.8125rem; cursor: pointer; transition: var(--transition);">
                                    Reactivate
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="padding: 3rem; text-align: center; color: var(--color-muted);">
                                No deactivated teacher accounts in the archive.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@else

    <!-- Pending Teacher Approvals Card -->
    <div class="card" style="padding: 0; overflow: hidden; margin-bottom: 2rem;">
        <div class="table-card-header" style="padding: 1.25rem 1.5rem; background: var(--color-brand-header); border-bottom: 1px solid rgba(0,0,0,0.1); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.75rem;">
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <h3 style="font-size: 1.0625rem; font-weight: 700; color: var(--color-dark); margin: 0;">Pending Registration Requests</h3>
                <span style="background: #FFFFFF; color: #DC2626; padding: 0.2rem 0.65rem; border-radius: 20px; font-weight: 700; font-size: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.12);">
                    {{ $pendingTeachers->count() }} PENDING
                </span>
            </div>
        </div>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.875rem;">
                <thead>
                    <tr style="background: #F8FAFC; border-bottom: 1px solid var(--color-border); color: var(--color-dark); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">
                        <th style="padding: 0.875rem 1.25rem; color: var(--color-dark); vertical-align: middle; text-align: left;">Date Requested</th>
                        <th style="padding: 0.875rem 1.25rem; color: var(--color-dark); vertical-align: middle; text-align: left;">Teacher Name</th>
                        <th style="padding: 0.875rem 1.25rem; color: var(--color-dark); vertical-align: middle; text-align: left;">Email Address</th>
                        <th style="padding: 0.875rem 1.25rem; color: var(--color-dark); vertical-align: middle; text-align: left;">Role</th>
                        <th style="padding: 0.875rem 1.25rem; color: var(--color-dark); vertical-align: middle; text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendingTeachers as $pt)
                        <tr style="border-bottom: 1px solid #F1F5F9;">
                            <td style="padding: 0.875rem 1.25rem; color: var(--color-muted); vertical-align: middle; text-align: left;">
                                {{ $pt->created_at->format('M d, Y - g:i A') }}
                            </td>
                            <td style="padding: 0.875rem 1.25rem; font-weight: 700; color: var(--color-dark); vertical-align: middle; text-align: left;">
                                {{ $pt->name }}
                            </td>
                            <td style="padding: 0.875rem 1.25rem; vertical-align: middle; text-align: left;">
                                {{ $pt->email }}
                            </td>
                            <td style="padding: 0.875rem 1.25rem; vertical-align: middle; text-align: left;">
                                <span style="background: #FBF0F5; color: var(--color-brand); padding: 0.2rem 0.6rem; border-radius: 20px; font-weight: 700; font-size: 0.75rem;">
                                    TEACHER
                                </span>
                            </td>
                            <td style="padding: 0.875rem 1.25rem; text-align: right; vertical-align: middle;">
                                <div style="display: flex; gap: 0.5rem; justify-content: flex-end; align-items: center;">
                                    <button type="button" onclick="openConfirmModal({
                                        title: 'Approve Teacher Registration',
                                        message: 'Are you sure you want to approve {{ $pt->name }} ({{ $pt->email }}) as a Teacher? They will be granted immediate access to log in.',
                                        actionUrl: '{{ route('admin.approvals.approve', $pt->id) }}',
                                        buttonText: 'Approve Teacher',
                                        buttonColor: '#10B981'
                                    })" style="padding: 0.45rem 0.95rem; background: #10B981; color: #FFF; border: none; border-radius: 50px; font-weight: 700; font-size: 0.8125rem; cursor: pointer; transition: var(--transition);">
                                        Approve Teacher
                                    </button>
                                    <button type="button" onclick="openConfirmModal({
                                        title: 'Reject Registration',
                                        message: 'Are you sure you want to reject and permanently delete {{ $pt->name }}\'s registration request?',
                                        actionUrl: '{{ route('admin.approvals.reject', $pt->id) }}',
                                        buttonText: 'Reject & Delete',
                                        buttonColor: '#EF4444'
                                    })" style="padding: 0.45rem 0.95rem; background: #EF4444; color: #FFF; border: none; border-radius: 50px; font-weight: 700; font-size: 0.8125rem; cursor: pointer; transition: var(--transition);">
                                        Reject
                                    </button>
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
        <div class="table-card-header" style="padding: 1.25rem 1.5rem; background: var(--color-brand-header); border-bottom: 1px solid rgba(0,0,0,0.1); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.75rem;">
            <div style="display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;">
                <h3 style="font-size: 1.0625rem; font-weight: 700; color: var(--color-dark); margin: 0;">
                    Approved Active Teachers ({{ $approvedTeachers->count() }})
                </h3>
                <a href="{{ route('admin.approvals.index', ['view' => 'archived']) }}" style="padding: 0.35rem 0.85rem; background: #FFFFFF; color: var(--color-brand); border: none; border-radius: 50px; font-weight: 700; font-size: 0.75rem; text-decoration: none; display: inline-flex; align-items: center; gap: 0.35rem; box-shadow: 0 1px 3px rgba(0,0,0,0.12); transition: var(--transition);">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>
                    View Deactivated Archive ({{ $archivedCount }})
                </a>
            </div>
        </div>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.875rem;">
                <thead>
                    <tr style="background: #F8FAFC; border-bottom: 1px solid var(--color-border); color: var(--color-dark); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">
                        <th style="padding: 0.875rem 1.25rem; color: var(--color-dark); vertical-align: middle; text-align: left;">Teacher Name</th>
                        <th style="padding: 0.875rem 1.25rem; color: var(--color-dark); vertical-align: middle; text-align: left;">Email Address</th>
                        <th style="padding: 0.875rem 1.25rem; color: var(--color-dark); vertical-align: middle; text-align: left;">Status</th>
                        <th style="padding: 0.875rem 1.25rem; color: var(--color-dark); vertical-align: middle; text-align: left;">Date Approved / Joined</th>
                        <th style="padding: 0.875rem 1.25rem; color: var(--color-dark); vertical-align: middle; text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($approvedTeachers as $at)
                        <tr style="border-bottom: 1px solid #F1F5F9;">
                            <td style="padding: 0.875rem 1.25rem; font-weight: 700; color: var(--color-dark); vertical-align: middle; text-align: left;">
                                {{ $at->name }}
                            </td>
                            <td style="padding: 0.875rem 1.25rem; vertical-align: middle; text-align: left;">
                                {{ $at->email }}
                            </td>
                            <td style="padding: 0.875rem 1.25rem; vertical-align: middle; text-align: left;">
                                <span style="background: #D1FAE5; color: #065F46; padding: 0.25rem 0.65rem; border-radius: 20px; font-weight: 700; font-size: 0.75rem;">
                                    APPROVED & ACTIVE
                                </span>
                            </td>
                            <td style="padding: 0.875rem 1.25rem; color: var(--color-muted); vertical-align: middle; text-align: left;">
                                {{ $at->created_at->format('M d, Y') }}
                            </td>
                            <td style="padding: 0.875rem 1.25rem; text-align: right; vertical-align: middle;">
                                <button type="button" onclick="openConfirmModal({
                                    title: 'Deactivate Teacher Account',
                                    message: 'Are you sure you want to deactivate {{ $at->name }}? They will no longer be able to log in, and their record will be moved to the deactivated archive.',
                                    actionUrl: '{{ route('admin.approvals.deactivate', $at->id) }}',
                                    buttonText: 'Deactivate Teacher',
                                    buttonColor: '#EF4444'
                                })" style="padding: 0.45rem 0.95rem; background: #EF4444; color: #FFFFFF; border: none; border-radius: 50px; font-weight: 700; font-size: 0.8125rem; cursor: pointer; transition: var(--transition);">
                                    Deactivate
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="padding: 2.5rem; text-align: center; color: var(--color-muted);">
                                No approved teacher accounts yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endif
@endsection
