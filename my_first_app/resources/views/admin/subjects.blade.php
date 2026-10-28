@extends('layouts.app')

@section('title', 'Subject Management — Admin Panel')

@section('content')
<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem;">
    <div>
        <h1 style="font-size: 1.75rem; font-weight: 700;">Subject Management</h1>
        <p style="color: var(--color-muted);">Manage official active BSIT 3rd year subjects for teacher class selection.</p>
    </div>
    <button type="button" onclick="document.getElementById('add-subject-modal').style.display = 'flex';" class="btn-primary" style="background: var(--color-brand); color: #FFF; padding: 0.65rem 1.25rem; border: none; border-radius: var(--radius-md); font-weight: 600; cursor: pointer;">
        + Add New Subject
    </button>
</div>

<!-- Subjects Table Card -->
<div class="card" style="padding: 0; overflow: hidden;">
    <div class="table-card-header" style="padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(0,0,0,0.1); background: var(--color-brand-header); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.75rem;">
        <h3 style="font-size: 1.0625rem; font-weight: 700; color: var(--color-dark); margin: 0;">Official BSIT Subject List</h3>
        <span style="background: #FFFFFF; color: var(--color-brand); padding: 0.2rem 0.65rem; border-radius: 20px; font-weight: 700; font-size: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.12);">
            {{ $subjects->count() }} SUBJECTS
        </span>
    </div>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.875rem;">
            <thead>
                <tr style="background: #F8FAFC; border-bottom: 1px solid var(--color-border); color: var(--color-dark); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">
                    <th style="padding: 0.875rem 1.25rem; color: var(--color-dark); vertical-align: middle; text-align: left;">Subject Code</th>
                    <th style="padding: 0.875rem 1.25rem; color: var(--color-dark); vertical-align: middle; text-align: left;">Subject Name</th>
                    <th style="padding: 0.875rem 1.25rem; color: var(--color-dark); vertical-align: middle; text-align: left;">Status</th>
                    <th style="padding: 0.875rem 1.25rem; color: var(--color-dark); vertical-align: middle; text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subjects as $sub)
                    <tr style="border-bottom: 1px solid #F1F5F9;">
                        <td style="padding: 0.875rem 1.25rem; font-weight: 700; font-family: monospace; font-size: 0.9375rem; color: var(--color-brand); vertical-align: middle; text-align: left;">
                            {{ $sub->code }}
                        </td>
                        <td style="padding: 0.875rem 1.25rem; font-weight: 600; color: var(--color-dark); vertical-align: middle; text-align: left;">
                            {{ $sub->name }}
                        </td>
                        <td style="padding: 0.875rem 1.25rem; vertical-align: middle; text-align: left;">
                            @if($sub->is_active)
                                <span style="background: #D1FAE5; color: #065F46; padding: 0.25rem 0.6rem; border-radius: 20px; font-weight: 700; font-size: 0.75rem;">
                                    ACTIVE
                                </span>
                            @else
                                <span style="background: #F1F5F9; color: #64748B; padding: 0.25rem 0.6rem; border-radius: 20px; font-weight: 600; font-size: 0.75rem;">
                                    DEACTIVATED
                                </span>
                            @endif
                        </td>
                        <td style="padding: 0.875rem 1.25rem; text-align: right; vertical-align: middle;">
                            <button type="button" onclick="openConfirmModal({
                                title: '{{ $sub->is_active ? 'Deactivate Subject' : 'Activate Subject' }}',
                                message: 'Are you sure you want to {{ $sub->is_active ? 'deactivate' : 'activate' }} {{ $sub->code }} ({{ $sub->name }})?',
                                actionUrl: '{{ route('admin.subjects.toggle', $sub->id) }}',
                                buttonText: '{{ $sub->is_active ? 'Deactivate' : 'Activate' }}',
                                buttonColor: '{{ $sub->is_active ? '#EF4444' : '#10B981' }}'
                            })" style="padding: 0.45rem 1rem; background: {{ $sub->is_active ? '#EF4444' : '#10B981' }}; color: #FFF; border: none; border-radius: 50px; font-size: 0.8125rem; font-weight: 700; cursor: pointer; transition: var(--transition);">
                                {{ $sub->is_active ? 'Deactivate' : 'Activate' }}
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="padding: 3rem; text-align: center; color: var(--color-muted);">
                            No subjects configured yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Add Subject Modal -->
<div id="add-subject-modal" class="modal-overlay" style="display: none;">
    <div class="modal-card" style="text-align: left; max-width: 480px;">
        <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1.25rem;">Add New BSIT Subject</h3>
        <form action="{{ route('admin.subjects.store') }}" method="POST">
            @csrf
            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.8125rem; font-weight: 600; margin-bottom: 0.35rem;">Subject Code (e.g. COMP 019)</label>
                <input type="text" name="code" placeholder="COMP 019" required style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-md); text-transform: uppercase;">
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.8125rem; font-weight: 600; margin-bottom: 0.35rem;">Subject Description / Name</label>
                <input type="text" name="name" placeholder="Advanced Software Engineering" required style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-md);">
            </div>

            <div style="display: flex; gap: 0.75rem;">
                <button type="button" onclick="document.getElementById('add-subject-modal').style.display = 'none';" class="btn-neutral">Cancel</button>
                <button type="submit" style="flex: 1; padding: 0.75rem 1rem; background: var(--color-brand); color: #FFF; border: none; border-radius: var(--radius-md); font-weight: 600; cursor: pointer;">Add Subject</button>
            </div>
        </form>
    </div>
</div>
@endsection
