@extends('layouts.app')

@section('title', 'Manage Classes — Teacher Dashboard')

@section('content')
<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem;">
    <div>
        <h1 style="font-size: 1.75rem; font-weight: 700;">My Class Setup</h1>
        <p style="color: var(--color-muted);">Add and manage your scheduled recurring BSIT 3rd Year classes.</p>
    </div>
    <button type="button" onclick="document.getElementById('add-class-modal').style.display = 'flex';" class="btn-primary" style="background: var(--color-brand); color: #FFF; padding: 0.65rem 1.25rem; border: none; border-radius: var(--radius-md); font-weight: 600; cursor: pointer;">
        + Add New Class
    </button>
</div>

<!-- Class Cards Grid -->
<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.25rem;">
    @forelse($classes as $c)
        <div class="card" style="margin-bottom: 0; opacity: {{ $c->is_archived ? '0.7' : '1' }}; border-left: 4px solid {{ $c->is_archived ? '#94A3B8' : 'var(--color-brand)' }}; display: flex; flex-direction: column; justify-content: space-between;">
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
                <form action="{{ route('teacher.classes.archive', $c->id) }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" style="padding: 0.55rem 0.875rem; background: #F1F5F9; color: #475569; border: 1px solid #CBD5E1; border-radius: var(--radius-md); font-weight: 600; font-size: 0.8125rem; cursor: pointer;">
                        {{ $c->is_archived ? 'Restore' : 'Archive' }}
                    </button>
                </form>
            </div>
        </div>
    @empty
        <div class="card" style="grid-column: 1 / -1; text-align: center; padding: 3rem;">
            <p style="color: var(--color-muted); margin-bottom: 1rem;">No classes set up yet.</p>
            <button type="button" onclick="document.getElementById('add-class-modal').style.display = 'flex';" style="background: var(--color-brand); color: #FFF; padding: 0.65rem 1.25rem; border: none; border-radius: var(--radius-md); font-weight: 600; cursor: pointer;">
                + Add Your First Class
            </button>
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
