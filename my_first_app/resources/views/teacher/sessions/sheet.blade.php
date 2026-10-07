@extends('layouts.app')

@section('title', 'Attendance Sheet — ' . optional(optional($session->schoolClass)->subject)->code)

@section('content')
<!-- Session Header Banner -->
<div class="card" style="margin-bottom: 1.5rem; background: linear-gradient(135deg, #FBF0F5 0%, #FFFFFF 100%);">
    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
        <div>
            <div style="display: flex; align-items: center; gap: 0.65rem; margin-bottom: 0.35rem;">
                <span style="font-size: 0.8125rem; font-weight: 700; color: var(--color-brand); font-family: monospace; background: var(--color-brand-light); padding: 0.2rem 0.6rem; border-radius: 4px;">
                    {{ optional(optional($session->schoolClass)->subject)->code }}
                </span>
                <span style="font-size: 1.125rem; font-weight: 700; color: var(--color-dark);">
                    {{ optional($session->schoolClass)->section }}
                </span>
                @if($session->isSubmitted())
                    <span style="background: #D1FAE5; color: #065F46; font-size: 0.75rem; font-weight: 700; padding: 0.2rem 0.6rem; border-radius: 20px;">
                        SUBMITTED & LOCKED
                    </span>
                @else
                    <span style="background: #FEF3C7; color: #92400E; font-size: 0.75rem; font-weight: 700; padding: 0.2rem 0.6rem; border-radius: 20px;">
                        DRAFT
                    </span>
                @endif
                @if($session->is_makeup)
                    <span style="background: #E0F2FE; color: #075985; font-size: 0.75rem; font-weight: 700; padding: 0.2rem 0.6rem; border-radius: 20px;">
                        MAKEUP CLASS
                    </span>
                @endif
            </div>

            <h2 style="font-size: 1.35rem; font-weight: 700; color: var(--color-dark); margin-bottom: 0.25rem;">
                {{ optional(optional($session->schoolClass)->subject)->name }}
            </h2>
            <div style="font-size: 0.875rem; color: var(--color-muted);">
                <strong>Date:</strong> {{ $session->session_date->format('F d, Y (l)') }} &bull; 
                <strong>Schedule:</strong> {{ optional($session->schoolClass)->formatted_time }}
            </div>

            @if($session->edited_at)
                <div style="font-size: 0.75rem; color: #D97706; margin-top: 0.5rem; font-weight: 600;">
                    Session edited on {{ $session->edited_at->format('M d, Y g:i A') }} by {{ optional($session->editedBy)->name ?? 'User' }}
                </div>
            @endif
        </div>

        <div style="display: flex; gap: 0.5rem; flex-wrap: nowrap; align-items: center;">
            <a href="{{ route('teacher.dashboard') }}" style="padding: 0.55rem 1rem; background: #F1F5F9; color: #475569; border: 1px solid #CBD5E1; border-radius: var(--radius-md); font-weight: 600; text-decoration: none; font-size: 0.875rem; white-space: nowrap;">
                &larr; Dashboard
            </a>
            <button type="button" onclick="document.getElementById('add-student-session-modal').style.display='flex';" style="padding: 0.55rem 1rem; background: #F1F5F9; color: #475569; border: 1px solid #CBD5E1; border-radius: var(--radius-md); font-weight: 600; font-size: 0.875rem; cursor: pointer; white-space: nowrap;">
                + Add Student to Class
            </button>
        </div>
    </div>
</div>

<!-- Sticky Progress Counter & Action Bar -->
<div class="card" style="position: sticky; top: 72px; z-index: 900; background: #FFFFFF; border-left: 4px solid var(--color-brand); margin-bottom: 1.5rem; box-shadow: var(--shadow-md);">
    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
        <!-- Live Counter -->
        <div style="display: flex; align-items: center; gap: 1.25rem;">
            <div>
                <span id="counter-marked" style="font-size: 1.25rem; font-weight: 800; color: var(--color-brand);">{{ $markedCount }}/{{ $totalStudents }}</span>
                <span style="font-size: 0.8125rem; color: var(--color-muted); font-weight: 600; margin-left: 0.25rem;">Marked</span>
            </div>
            <div style="font-size: 0.8125rem; color: var(--color-muted);">
                <span id="count-unmarked" style="color: {{ $unmarkedCount > 0 ? '#DC2626' : '#10B981' }}; font-weight: 700;">{{ $unmarkedCount }}</span> Unmarked
            </div>
            <div style="font-size: 0.8125rem; color: var(--color-muted); display: flex; gap: 0.75rem;">
                <span style="color: #059669; font-weight: 600;">P: <strong id="count-present">{{ $presentCount }}</strong></span>
                <span style="color: #D97706; font-weight: 600;">L: <strong id="count-late">{{ $lateCount }}</strong></span>
                <span style="color: #DC2626; font-weight: 600;">A: <strong id="count-absent">{{ $absentCount }}</strong></span>
                <span style="color: #0284C7; font-weight: 600;">E: <strong id="count-excused">{{ $excusedCount }}</strong></span>
            </div>
        </div>

        <!-- Quick Action Shortcuts -->
        <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: nowrap;">
            <!-- Filter Search Input -->
            <input type="text" id="sheet-search" placeholder="Filter student..." style="padding: 0.45rem 0.75rem; border: 1px solid var(--color-border); border-radius: var(--radius-md); font-size: 0.8125rem; width: 150px;">

            <form action="{{ route('teacher.sessions.mark_all_present', $session->id) }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" style="padding: 0.45rem 0.85rem; background: #ECFDF5; color: #065F46; border: 1px solid #A7F3D0; border-radius: var(--radius-md); font-weight: 600; font-size: 0.8125rem; cursor: pointer; white-space: nowrap;">
                    Mark All Present
                </button>
            </form>

            <form action="{{ route('teacher.sessions.mark_remaining_absent', $session->id) }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" style="padding: 0.45rem 0.85rem; background: #FEF2F2; color: #991B1B; border: 1px solid #FCA5A5; border-radius: var(--radius-md); font-weight: 600; font-size: 0.8125rem; cursor: pointer; white-space: nowrap;">
                    Mark Remaining Absent
                </button>
            </form>

            <form action="{{ route('teacher.sessions.submit', $session->id) }}" method="POST" style="margin: 0;" onsubmit="return confirm('Submit attendance sheet? Once submitted, the session is finalized.');">
                @csrf
                <button type="submit" id="btn-submit-sheet" style="padding: 0.5rem 1.25rem; background: var(--color-brand); color: #FFF; border: none; border-radius: var(--radius-md); font-weight: 700; font-size: 0.875rem; cursor: pointer; white-space: nowrap;">
                    {{ $session->isSubmitted() ? 'Re-Submit Updates' : 'Submit Sheet' }}
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Attendance Sheet Table -->
<div class="card" style="padding: 0; overflow: hidden;">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.875rem;" id="attendance-table">
            <thead>
                <tr style="background: #F8FAFC; border-bottom: 1px solid var(--color-border); color: var(--color-muted); font-size: 0.75rem; text-transform: uppercase;">
                    <th style="padding: 0.875rem 1rem; width: 40px;">#</th>
                    <th style="padding: 0.875rem 1rem;">Student Number</th>
                    <th style="padding: 0.875rem 1rem;">Student Name</th>
                    <th style="padding: 0.875rem 1rem; text-align: center;">Status Selector</th>
                    <th style="padding: 0.875rem 1rem;">Time In / Left Early</th>
                    <th style="padding: 0.875rem 1rem;">Remarks</th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $index => $rec)
                    @php $st = $rec->student; @endphp
                    <tr class="student-row" data-name="{{ strtolower($st->full_name) }}" data-number="{{ strtolower($st->student_number) }}" style="border-bottom: 1px solid #F1F5F9; background: {{ is_null($rec->status) ? '#FFFDF5' : '#FFFFFF' }};">
                        <td style="padding: 0.875rem 1rem; color: var(--color-muted);">{{ $index + 1 }}</td>
                        <td style="padding: 0.875rem 1rem; font-family: monospace; font-weight: 600;">
                            <a href="{{ route('teacher.students.show', $st->id) }}" style="color: inherit; text-decoration: none;" title="View Student History">
                                {{ $st->student_number }}
                            </a>
                        </td>
                        <td style="padding: 0.875rem 1rem; font-weight: 700; color: var(--color-dark);">
                            {{ $st->last_name }}, {{ $st->first_name }}
                        </td>

                        <!-- Status Button Group -->
                        <td style="padding: 0.875rem 1rem; text-align: center;">
                            <div style="display: inline-flex; gap: 0.35rem; background: #F1F5F9; padding: 0.25rem; border-radius: var(--radius-md);">
                                <button type="button" onclick="updateStatus({{ $rec->id }}, 'present')" id="btn-present-{{ $rec->id }}" style="padding: 0.4rem 0.75rem; border: none; border-radius: var(--radius-sm); font-weight: 700; font-size: 0.75rem; cursor: pointer; transition: var(--transition); background: {{ $rec->status === 'present' ? '#10B981' : 'transparent' }}; color: {{ $rec->status === 'present' ? '#FFF' : '#475569' }}; white-space: nowrap;">
                                    Present
                                </button>

                                <button type="button" onclick="updateStatus({{ $rec->id }}, 'late')" id="btn-late-{{ $rec->id }}" style="padding: 0.4rem 0.75rem; border: none; border-radius: var(--radius-sm); font-weight: 700; font-size: 0.75rem; cursor: pointer; transition: var(--transition); background: {{ $rec->status === 'late' ? '#F59E0B' : 'transparent' }}; color: {{ $rec->status === 'late' ? '#FFF' : '#475569' }}; white-space: nowrap;">
                                    Late
                                </button>

                                <button type="button" onclick="updateStatus({{ $rec->id }}, 'absent')" id="btn-absent-{{ $rec->id }}" style="padding: 0.4rem 0.75rem; border: none; border-radius: var(--radius-sm); font-weight: 700; font-size: 0.75rem; cursor: pointer; transition: var(--transition); background: {{ $rec->status === 'absent' ? '#EF4444' : 'transparent' }}; color: {{ $rec->status === 'absent' ? '#FFF' : '#475569' }}; white-space: nowrap;">
                                    Absent
                                </button>

                                <button type="button" onclick="openExcusedModal({{ $rec->id }}, '{{ addslashes($rec->remarks) }}')" id="btn-excused-{{ $rec->id }}" style="padding: 0.4rem 0.75rem; border: none; border-radius: var(--radius-sm); font-weight: 700; font-size: 0.75rem; cursor: pointer; transition: var(--transition); background: {{ $rec->status === 'excused' ? '#0284C7' : 'transparent' }}; color: {{ $rec->status === 'excused' ? '#FFF' : '#475569' }}; white-space: nowrap;">
                                    Excused
                                </button>
                            </div>
                        </td>

                        <!-- Time In & Left Early -->
                        <td style="padding: 0.875rem 1rem; font-size: 0.8125rem;">
                            <span id="time-in-val-{{ $rec->id }}" style="font-weight: 600; color: #065F46;">
                                {{ $rec->time_in ? $rec->time_in->format('g:i A') : '-' }}
                            </span>

                            <div style="margin-top: 0.35rem;">
                                <button type="button" onclick="openLeftEarlyModal({{ $rec->id }}, {{ $rec->left_early ? 1 : 0 }}, '{{ addslashes($rec->remarks) }}')" id="btn-leftearly-{{ $rec->id }}" style="padding: 0.2rem 0.5rem; background: {{ $rec->left_early ? '#FEE2E2' : '#F1F5F9' }}; color: {{ $rec->left_early ? '#DC2626' : '#64748B' }}; border: 1px solid {{ $rec->left_early ? '#FCA5A5' : '#CBD5E1' }}; border-radius: 4px; font-size: 0.7rem; font-weight: 600; cursor: pointer; white-space: nowrap;">
                                    {{ $rec->left_early ? 'Left Early (' . ($rec->left_at ? $rec->left_at->format('g:i A') : 'Yes') . ')' : 'Left Early' }}
                                </button>
                            </div>
                        </td>

                        <!-- Remarks -->
                        <td style="padding: 0.875rem 1rem; font-size: 0.8125rem; color: var(--color-muted);" id="remarks-val-{{ $rec->id }}">
                            {{ $rec->remarks ?? '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding: 3rem; text-align: center; color: var(--color-muted);">
                            No students enrolled in section {{ optional($session->schoolClass)->section }}.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Excused Remarks Modal -->
<div id="modal-excused" class="modal-overlay" style="display: none;">
    <div class="modal-card" style="text-align: left; max-width: 440px;">
        <h3 style="font-size: 1.125rem; font-weight: 700; margin-bottom: 0.75rem;">Mark Student as Excused</h3>
        <input type="hidden" id="excused-rec-id">
        <div style="margin-bottom: 1.25rem;">
            <label style="display: block; font-size: 0.8125rem; font-weight: 600; margin-bottom: 0.35rem;">Reason / Remarks (Optional)</label>
            <input type="text" id="excused-remarks-input" placeholder="Medical excuse, official event, etc." style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-md);">
        </div>
        <div style="display: flex; gap: 0.75rem;">
            <button type="button" onclick="document.getElementById('modal-excused').style.display='none';" class="btn-neutral">Cancel</button>
            <button type="button" onclick="saveExcused()" style="flex: 1; padding: 0.75rem; background: #0284C7; color: #FFF; border: none; border-radius: var(--radius-md); font-weight: 600; cursor: pointer;">Save Excused Status</button>
        </div>
    </div>
</div>

<!-- Left Early Modal -->
<div id="modal-left-early" class="modal-overlay" style="display: none;">
    <div class="modal-card" style="text-align: left; max-width: 440px;">
        <h3 style="font-size: 1.125rem; font-weight: 700; margin-bottom: 0.75rem;">Student Left Class Early</h3>
        <input type="hidden" id="left-early-rec-id">
        <div style="margin-bottom: 1rem;">
            <label style="display: flex; align-items: center; gap: 0.5rem; font-weight: 600; font-size: 0.875rem;">
                <input type="checkbox" id="left-early-checkbox" checked style="width: 18px; height: 18px;">
                Mark as Left Early
            </label>
        </div>
        <div style="margin-bottom: 1rem;">
            <label style="display: block; font-size: 0.8125rem; font-weight: 600; margin-bottom: 0.35rem;">Departure Time (Optional)</label>
            <input type="time" id="left-early-time" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-md);">
        </div>
        <div style="margin-bottom: 1.25rem;">
            <label style="display: block; font-size: 0.8125rem; font-weight: 600; margin-bottom: 0.35rem;">Reason / Remarks (Optional)</label>
            <input type="text" id="left-early-remarks" placeholder="Emergency, health clinic, etc." style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-md);">
        </div>
        <div style="display: flex; gap: 0.75rem;">
            <button type="button" onclick="document.getElementById('modal-left-early').style.display='none';" class="btn-neutral">Cancel</button>
            <button type="button" onclick="saveLeftEarly()" style="flex: 1; padding: 0.75rem; background: #DC2626; color: #FFF; border: none; border-radius: var(--radius-md); font-weight: 600; cursor: pointer;">Save Departure Record</button>
        </div>
    </div>
</div>

<!-- Add Student to Session Modal -->
<div id="add-student-session-modal" class="modal-overlay" style="display: none;">
    <div class="modal-card" style="text-align: left; max-width: 480px;">
        <h3 style="font-size: 1.125rem; font-weight: 700; margin-bottom: 1rem;">Add Student to Attendance Sheet</h3>
        <form action="{{ route('teacher.sessions.add_student', $session->id) }}" method="POST">
            @csrf
            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.8125rem; font-weight: 600; margin-bottom: 0.35rem;">Select Student</label>
                <select name="student_id" required style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-md);">
                    <option value="">Select registered student</option>
                    @foreach($allStudents as $st)
                        <option value="{{ $st->id }}">{{ $st->last_name }}, {{ $st->first_name }} ({{ $st->student_number }} - {{ $st->section }})</option>
                    @endforeach
                </select>
            </div>
            <div style="display: flex; gap: 0.75rem;">
                <button type="button" onclick="document.getElementById('add-student-session-modal').style.display='none';" class="btn-neutral">Cancel</button>
                <button type="submit" style="flex: 1; padding: 0.75rem; background: var(--color-brand); color: #FFF; border: none; border-radius: var(--radius-md); font-weight: 600; cursor: pointer;">Add Student</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const sessionId = {{ $session->id }};

    // Filter student list by text search
    document.getElementById('sheet-search').addEventListener('input', function() {
        const val = this.value.toLowerCase().trim();
        document.querySelectorAll('.student-row').forEach(row => {
            const name = row.getAttribute('data-name');
            const num = row.getAttribute('data-number');
            if (name.includes(val) || num.includes(val)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // AJAX Update Status Function
    function updateStatus(recordId, status, extraData = {}) {
        const url = `/teacher/sessions/${sessionId}/records/${recordId}`;
        const payload = Object.assign({ status: status }, extraData);

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Update Button Active States
                const btnPresent = document.getElementById(`btn-present-${recordId}`);
                const btnLate    = document.getElementById(`btn-late-${recordId}`);
                const btnAbsent  = document.getElementById(`btn-absent-${recordId}`);
                const btnExcused = document.getElementById(`btn-excused-${recordId}`);

                [btnPresent, btnLate, btnAbsent, btnExcused].forEach(btn => {
                    btn.style.background = 'transparent';
                    btn.style.color = '#475569';
                });

                if (status === 'present') { btnPresent.style.background = '#10B981'; btnPresent.style.color = '#FFF'; }
                if (status === 'late')    { btnLate.style.background = '#F59E0B'; btnLate.style.color = '#FFF'; }
                if (status === 'absent')  { btnAbsent.style.background = '#EF4444'; btnAbsent.style.color = '#FFF'; }
                if (status === 'excused') { btnExcused.style.background = '#0284C7'; btnExcused.style.color = '#FFF'; }

                // Update Time In Text
                if (data.time_in) {
                    document.getElementById(`time-in-val-${recordId}`).innerText = 'In: ' + data.time_in;
                }

                // Update Live Counters
                document.getElementById('counter-marked').innerText = `${data.marked}/${data.total}`;
                document.getElementById('count-unmarked').innerText = data.unmarked;
                document.getElementById('count-present').innerText = data.present;
                document.getElementById('count-late').innerText    = data.late;
                document.getElementById('count-absent').innerText  = data.absent;
                document.getElementById('count-excused').innerText = data.excused;
            }
        });
    }

    // Modal Handlers for Excused & Left Early
    function openExcusedModal(recordId, remarks) {
        document.getElementById('excused-rec-id').value = recordId;
        document.getElementById('excused-remarks-input').value = remarks || '';
        document.getElementById('modal-excused').style.display = 'flex';
    }

    function saveExcused() {
        const recordId = document.getElementById('excused-rec-id').value;
        const remarks  = document.getElementById('excused-remarks-input').value;
        updateStatus(recordId, 'excused', { remarks: remarks });
        document.getElementById(`remarks-val-${recordId}`).innerText = remarks || '-';
        document.getElementById('modal-excused').style.display = 'none';
    }

    function openLeftEarlyModal(recordId, leftEarly, remarks) {
        document.getElementById('left-early-rec-id').value = recordId;
        document.getElementById('left-early-checkbox').checked = leftEarly == 1;
        document.getElementById('left-early-remarks').value = remarks || '';
        document.getElementById('modal-left-early').style.display = 'flex';
    }

    function saveLeftEarly() {
        const recordId  = document.getElementById('left-early-rec-id').value;
        const leftEarly = document.getElementById('left-early-checkbox').checked;
        const leftTime  = document.getElementById('left-early-time').value;
        const remarks   = document.getElementById('left-early-remarks').value;

        // Current status stays present or late
        const currentBtnPresent = document.getElementById(`btn-present-${recordId}`).style.background !== 'transparent';
        const status = currentBtnPresent ? 'present' : 'late';

        updateStatus(recordId, status, {
            left_early: leftEarly ? 1 : 0,
            left_at_custom: leftTime,
            remarks: remarks
        });

        const btnLE = document.getElementById(`btn-leftearly-${recordId}`);
        if (leftEarly) {
            btnLE.style.background = '#FEE2E2';
            btnLE.style.color = '#DC2626';
            btnLE.innerText = 'Left Early';
        } else {
            btnLE.style.background = '#F1F5F9';
            btnLE.style.color = '#64748B';
            btnLE.innerText = 'Left Early';
        }

        if (remarks) {
            document.getElementById(`remarks-val-${recordId}`).innerText = remarks;
        }

        document.getElementById('modal-left-early').style.display = 'none';
    }
</script>
@endsection
