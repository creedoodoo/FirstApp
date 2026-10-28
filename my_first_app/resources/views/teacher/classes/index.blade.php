@extends('layouts.app')

@section('title', $viewMode === 'archived' ? 'Archived Classes — Teacher Dashboard' : 'Manage Classes — Teacher Dashboard')

@section('content')
<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        @if($viewMode === 'archived')
            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.35rem;">
                <a href="{{ route('teacher.classes.index') }}" style="display: inline-flex; align-items: center; gap: 0.35rem; color: var(--color-brand); text-decoration: none; font-weight: 600; font-size: 0.875rem; padding: 0.35rem 0.75rem; background: var(--color-brand-light); border-radius: var(--radius-md);">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                    Back to Active Classes
                </a>
            </div>
            <h1 style="font-size: 1.75rem; font-weight: 700;">Archived Classes</h1>
            <p style="color: var(--color-muted);">Review your archived classes. You can restore them to your active schedule anytime.</p>
        @else
            <h1 style="font-size: 1.75rem; font-weight: 700;">My Class Setup</h1>
            <p style="color: var(--color-muted);">Add and manage your scheduled recurring BSIT 3rd Year classes.</p>
        @endif
    </div>

    <div style="display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;">
        @if($viewMode === 'archived')
            <a href="{{ route('teacher.classes.index') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.65rem 1.25rem; background: #FFF; color: var(--color-dark); border: 1px solid var(--color-border); border-radius: var(--radius-md); font-weight: 600; text-decoration: none; font-size: 0.875rem; box-shadow: var(--shadow-sm);">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Back to Active Classes
            </a>
        @else
            <a href="{{ route('teacher.classes.index', ['view' => 'archived']) }}" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.65rem 1.25rem; background: #FFF; color: #475569; border: 1px solid #CBD5E1; border-radius: var(--radius-md); font-weight: 600; text-decoration: none; font-size: 0.875rem; box-shadow: var(--shadow-sm);">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="21 8 21 21 3 21 3 8"></polyline><rect x="1" y="3" width="22" height="5"></rect><line x1="10" y1="12" x2="14" y2="12"></line></svg>
                Archived Classes ({{ $archivedCount }})
            </a>
            <button type="button" onclick="document.getElementById('add-class-modal').style.display = 'flex';" class="btn-primary" style="background: var(--color-brand); color: #FFF; padding: 0.65rem 1.25rem; border: none; border-radius: var(--radius-md); font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem; box-shadow: var(--shadow-sm);">
                <span>+ Add New Class</span>
            </button>
        @endif
    </div>
</div>

<!-- Class Cards Grid -->
<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.25rem;">
    @forelse($classes as $c)
        <div class="card" style="margin-bottom: 0; opacity: {{ $c->is_archived ? '0.85' : '1' }}; border-left: 4px solid {{ $c->is_archived ? '#94A3B8' : 'var(--color-brand)' }}; display: flex; flex-direction: column; justify-content: space-between;">
            <div>
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span style="font-size: 0.8125rem; font-weight: 700; color: var(--color-brand); font-family: monospace; background: var(--color-brand-light); padding: 0.2rem 0.5rem; border-radius: 4px;">
                        {{ optional($c->subject)->code }}
                    </span>
                    @if($c->is_archived)
                        <span style="background: #F1F5F9; color: #64748B; font-size: 0.75rem; font-weight: 700; padding: 0.2rem 0.6rem; border-radius: 20px;">
                            ARCHIVED
                        </span>
                    @else
                        <span style="background: #ECFDF5; color: #065F46; font-size: 0.75rem; font-weight: 700; padding: 0.2rem 0.6rem; border-radius: 20px;">
                            ACTIVE
                        </span>
                    @endif
                </div>

                <h3 style="font-size: 1.125rem; font-weight: 700; margin-bottom: 0.35rem; color: var(--color-dark);">
                    {{ optional($c->subject)->name }}
                </h3>

                <div style="font-size: 0.875rem; color: var(--color-muted); margin-bottom: 1rem;">
                    <strong>Section:</strong> <span style="color: var(--color-dark); font-weight: 600;">{{ $c->section }}</span><br>
                    <strong>Day:</strong> {{ $c->day_name }}<br>
                    <strong>Time:</strong> {{ $c->formatted_time }}
                </div>
            </div>

            <div style="display: flex; gap: 0.5rem; border-top: 1px solid var(--color-border); padding-top: 0.875rem;">
                <a href="{{ route('teacher.classes.session', $c->id) }}" style="flex: 1; text-align: center; background: var(--color-brand); color: #FFF; padding: 0.55rem; border-radius: var(--radius-md); font-weight: 600; text-decoration: none; font-size: 0.8125rem;">
                    Open Session Sheet
                </a>
                @if($c->is_archived)
                    <button type="button" 
                        onclick="window.openConfirmModal({
                            title: 'Restore Class',
                            message: 'Are you sure you want to restore {{ addslashes(optional($c->subject)->code) }} (Section {{ $c->section }}) back to your active schedule?',
                            actionUrl: '{{ route('teacher.classes.archive', $c->id) }}',
                            buttonText: 'Restore Class',
                            buttonColor: '#10B981'
                        })"
                        style="padding: 0.55rem 0.875rem; background: #ECFDF5; color: #065F46; border: 1px solid #A7F3D0; border-radius: var(--radius-md); font-weight: 600; font-size: 0.8125rem; cursor: pointer;">
                        Restore
                    </button>
                @else
                    <button type="button" 
                        onclick="window.openConfirmModal({
                            title: 'Archive Class',
                            message: 'Are you sure you want to archive {{ addslashes(optional($c->subject)->code) }} (Section {{ $c->section }})? It will be moved to your archived classes tab and hidden from active schedule.',
                            actionUrl: '{{ route('teacher.classes.archive', $c->id) }}',
                            buttonText: 'Archive Class',
                            buttonColor: '#EF4444'
                        })"
                        style="padding: 0.55rem 0.875rem; background: #F1F5F9; color: #475569; border: 1px solid #CBD5E1; border-radius: var(--radius-md); font-weight: 600; font-size: 0.8125rem; cursor: pointer;">
                        Archive
                    </button>
                @endif
            </div>
        </div>
    @empty
        <div class="card" style="grid-column: 1 / -1; text-align: center; padding: 3.5rem 2rem;">
            @if($viewMode === 'archived')
                <div style="width: 56px; height: 56px; border-radius: 50%; background: #F1F5F9; color: #64748B; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 1rem;">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="21 8 21 21 3 21 3 8"></polyline><rect x="1" y="3" width="22" height="5"></rect><line x1="10" y1="12" x2="14" y2="12"></line></svg>
                </div>
                <h3 style="font-size: 1.125rem; font-weight: 700; margin-bottom: 0.35rem; color: var(--color-dark);">No Archived Classes</h3>
                <p style="color: var(--color-muted); margin-bottom: 1.25rem;">You do not have any archived classes right now.</p>
                <a href="{{ route('teacher.classes.index') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; background: var(--color-brand); color: #FFF; padding: 0.65rem 1.25rem; border-radius: var(--radius-md); font-weight: 600; text-decoration: none;">
                    &larr; Back to Active Classes
                </a>
            @else
                <div style="width: 56px; height: 56px; border-radius: 50%; background: var(--color-brand-light); color: var(--color-brand); display: inline-flex; align-items: center; justify-content: center; margin-bottom: 1rem;">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                </div>
                <h3 style="font-size: 1.125rem; font-weight: 700; margin-bottom: 0.35rem; color: var(--color-dark);">You currently have no classes yet set-up.</h3>
                <p style="color: var(--color-muted); margin-bottom: 1.25rem;">Set up your scheduled BSIT classes to start taking attendance and managing student records.</p>
                <button type="button" onclick="document.getElementById('add-class-modal').style.display = 'flex';" style="background: var(--color-brand); color: #FFF; padding: 0.65rem 1.25rem; border: none; border-radius: var(--radius-md); font-weight: 600; cursor: pointer;">
                    + Add Your First Class
                </button>
            @endif
        </div>
    @endforelse
</div>

<!-- Add Class Modal -->
<div id="add-class-modal" class="modal-overlay" style="display: none;">
    <div class="modal-card" style="text-align: left; max-width: 500px;">
        <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1.25rem;">Set Up New Class</h3>

        <form action="{{ route('teacher.classes.store') }}" method="POST">
            @csrf
            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.8125rem; font-weight: 600; margin-bottom: 0.35rem;">Subject</label>
                <select name="subject_id" required style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-md);">
                    <option value="">Select Official Subject</option>
                    @foreach($subjects as $sub)
                        <option value="{{ $sub->id }}">{{ $sub->code }} - {{ $sub->name }}</option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.8125rem; font-weight: 600; margin-bottom: 0.35rem;">Section (BSIT 3rd Year Only)</label>
                <select name="section" required style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-md);">
                    <option value="">Select Section</option>
                    @foreach($sections as $sec)
                        <option value="{{ $sec }}">{{ $sec }}</option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.8125rem; font-weight: 600; margin-bottom: 0.35rem;">Day of Week</label>
                <select name="day_of_week" required style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-md);">
                    <option value="1">Monday</option>
                    <option value="2">Tuesday</option>
                    <option value="3">Wednesday</option>
                    <option value="4">Thursday</option>
                    <option value="5">Friday</option>
                    <option value="6">Saturday</option>
                    <option value="7">Sunday</option>
                </select>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                <div>
                    <label style="display: block; font-size: 0.8125rem; font-weight: 600; margin-bottom: 0.35rem;">Start Time</label>
                    <input type="time" name="start_time" required style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-md);">
                </div>
                <div>
                    <label style="display: block; font-size: 0.8125rem; font-weight: 600; margin-bottom: 0.35rem;">End Time</label>
                    <input type="time" name="end_time" required style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-md);">
                </div>
            </div>

            <div style="display: flex; gap: 0.75rem;">
                <button type="button" onclick="document.getElementById('add-class-modal').style.display = 'none';" class="btn-neutral">Cancel</button>
                <button type="submit" style="flex: 1; padding: 0.75rem 1rem; background: var(--color-brand); color: #FFF; border: none; border-radius: var(--radius-md); font-weight: 600; cursor: pointer;">Save Class</button>
            </div>
        </form>
    </div>
</div>
@endsection
