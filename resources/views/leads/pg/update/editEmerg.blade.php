@extends('layouts.app')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    :root {
        --slate-900: #0f172a;
        --slate-700: #334155;
        --slate-600: #475569;
        --slate-400: #94a3b8;
        --slate-300: #cbd5e1;
        --slate-200: #e2e8f0;
        --slate-100: #f1f5f9;
        --white:     #ffffff;
        --red:       #dc2626;
        --red-light: #fff1f2;
        --red-mid:   #fecaca;
        --green:     #10b981;
        --danger:    #ef4444;
    }

    * { box-sizing: border-box; }

    .edit-page {
        font-family: 'Montserrat', sans-serif;
        background: var(--slate-900);
        min-height: 100vh;
    }

    /* ── Two-column layout ── */
    .edit-layout {
        display: grid;
        grid-template-columns: 260px 1fr;
        min-height: 100vh;
    }

    /* ── Sidebar ── */
    .edit-sidebar {
        background: #0a0f1a;
        padding: 2rem 1.5rem;
        display: flex;
        flex-direction: column;
        gap: 0;
        border-right: 1px solid rgba(255,255,255,0.06);
        position: sticky;
        top: 0;
        height: 100vh;
        overflow-y: auto;
    }
    .sidebar-back {
        display: inline-flex; align-items: center; gap: 0.4rem;
        font-size: 0.72rem; font-weight: 600;
        color: var(--slate-400); text-decoration: none;
        margin-bottom: 2rem;
        transition: color 0.2s;
    }
    .sidebar-back:hover { color: var(--white); }

    .sidebar-label {
        font-size: 0.6rem; font-weight: 700;
        letter-spacing: 0.12em; text-transform: uppercase;
        color: var(--slate-400); margin-bottom: 0.5rem;
    }
    .sidebar-title {
        font-family: 'Montserrat', sans-serif;
        font-size: 1.1rem; font-weight: 800;
        color: var(--white); line-height: 1.3;
        margin-bottom: 0.4rem; letter-spacing: -0.02em;
    }
    .sidebar-meta {
        font-size: 0.72rem; color: var(--slate-400);
        line-height: 1.6; margin-bottom: 2rem;
    }

    /* Sidebar nav */
    .sidebar-nav { display: flex; flex-direction: column; gap: 0.25rem; }
    .nav-item {
        display: flex; align-items: center; gap: 0.65rem;
        padding: 0.6rem 0.75rem; border-radius: 8px;
        font-size: 0.78rem; font-weight: 600;
        color: var(--slate-400); cursor: pointer;
        transition: all 0.2s; text-decoration: none;
        border: none; background: none; width: 100%; text-align: left;
    }
    .nav-item:hover { background: rgba(255,255,255,0.05); color: var(--white); }
    .nav-item.active { background: rgba(220,38,38,0.15); color: #fca5a5; }
    .nav-item i { width: 16px; text-align: center; font-size: 0.85rem; }

    .sidebar-divider { border: none; border-top: 1px solid rgba(255,255,255,0.06); margin: 1rem 0; }

    .btn-save-sidebar {
        display: flex; align-items: center; justify-content: center; gap: 0.5rem;
        font-family: 'Montserrat', sans-serif; font-weight: 700;
        font-size: 0.82rem; padding: 0.7rem;
        border-radius: 10px; border: none;
        background: var(--red); color: var(--white);
        cursor: pointer; margin-top: auto;
        transition: filter 0.2s;
        width: 100%;
    }
    .btn-save-sidebar:hover { filter: brightness(1.1); }

    /* ── Main content ── */
    .edit-main {
        background: var(--slate-100);
        padding: 2rem 2.5rem 3rem;
        overflow-y: auto;
    }

    .section-title {
        font-family: 'Montserrat', sans-serif;
        font-size: 0.7rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.1em;
        color: var(--slate-400); margin: 0 0 1.25rem;
        display: flex; align-items: center; gap: 0.5rem;
    }
    .section-title::after {
        content: ''; flex: 1; height: 1px;
        background: var(--slate-200);
    }

    /* ── Field groups ── */
    .field-group {
        background: var(--white);
        border-radius: 12px;
        border: 1px solid var(--slate-200);
        margin-bottom: 1.25rem;
        overflow: hidden;
    }
    .field-group-body { padding: 1.25rem; }

    /* ── Form controls ── */
    .form-label {
        font-size: 0.68rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.07em;
        color: var(--slate-400); margin-bottom: 0.3rem;
        display: block;
    }
    .form-control, .form-select {
        font-family: 'Montserrat', sans-serif;
        font-size: 0.88rem; width: 100%;
        border: 1.5px solid var(--slate-200); border-radius: 9px;
        padding: 0.6rem 0.85rem; color: var(--slate-900);
        background: var(--white);
        transition: border-color 0.2s, box-shadow 0.2s;
        appearance: auto;
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--red);
        box-shadow: 0 0 0 3px rgba(220,38,38,0.1);
        outline: none;
    }
    .form-control[readonly] { background: var(--slate-100); color: var(--slate-500); }

    /* Job number */
    .job-num {
        font-family: 'Montserrat', sans-serif;
        font-weight: 800; font-size: 1.1rem;
        color: var(--red); letter-spacing: 0.06em;
        padding: 0.5rem 0;
        display: flex; align-items: center; gap: 0.5rem;
    }
    .job-num-badge {
        font-size: 0.6rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.08em;
        background: var(--red-mid); color: var(--red);
        padding: 0.15rem 0.5rem; border-radius: 99px;
    }

    /* Terms */
    .terms-toggle {
        display: flex; align-items: center; justify-content: space-between;
        padding: 0.9rem 1.1rem; border-radius: 9px;
        border: 1.5px solid var(--slate-200); background: var(--slate-100);
        margin-bottom: 0.6rem; cursor: pointer; transition: border-color 0.2s;
        gap: 1rem;
    }
    .terms-toggle:has(input:checked) { border-color: var(--green); background: #ecfdf5; }
    .terms-toggle-text { flex: 1; }
    .terms-toggle-label { font-weight: 600; font-size: 0.85rem; color: var(--slate-900); }
    .terms-toggle-sub { font-size: 0.75rem; color: var(--slate-400); margin-top: 0.1rem; }
    .terms-toggle input[type="checkbox"] { width: 18px; height: 18px; flex-shrink: 0; accent-color: var(--green); cursor: pointer; }

    /* Team */
    .team-card {
        display: flex; align-items: center; gap: 0.75rem;
        padding: 0.7rem 0.9rem; border-radius: 9px;
        border: 1.5px solid var(--slate-200); background: var(--slate-100);
        cursor: pointer; transition: all 0.15s; margin-bottom: 0.45rem;
    }
    .team-card:has(input:checked) { border-color: var(--red); background: var(--red-light); }
    .team-avatar {
        width: 32px; height: 32px; border-radius: 8px;
        background: linear-gradient(135deg, var(--slate-700), var(--slate-900));
        display: flex; align-items: center; justify-content: center;
        font-weight: 800; font-size: 0.7rem; color: var(--white); flex-shrink: 0;
    }
    .team-card:has(input:checked) .team-avatar { background: linear-gradient(135deg, var(--red), #991b1b); }
    .team-card input[type="checkbox"] { width: 16px; height: 16px; margin-left: auto; accent-color: var(--red); flex-shrink: 0; cursor: pointer; }
    .team-name { font-weight: 600; font-size: 0.82rem; color: var(--slate-900); }
    .team-role { font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--slate-400); }

    /* Files */
    .file-zone {
        border: 1px solid var(--slate-200); border-radius: 10px; overflow: hidden; margin-bottom: 0.85rem;
    }
    .file-zone-head {
        padding: 0.6rem 0.9rem; background: var(--slate-100);
        border-bottom: 1px solid var(--slate-200);
        display: flex; align-items: center; gap: 0.5rem;
        font-size: 0.72rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.05em; color: var(--slate-600);
    }
    .file-zone-head i { color: var(--red); font-size: 0.85rem; }
    .file-zone-body { padding: 0.85rem; }

    .existing-file {
        display: flex; align-items: center; gap: 0.6rem;
        padding: 0.5rem 0.7rem; border-radius: 7px;
        background: var(--white); border: 1px solid var(--slate-200);
        margin-bottom: 0.35rem;
    }
    .file-icon-sm { width: 26px; height: 26px; border-radius: 5px; background: var(--slate-100); display: flex; align-items: center; justify-content: center; color: var(--slate-500); flex-shrink: 0; font-size: 0.78rem; }
    .file-link { font-size: 0.78rem; font-weight: 500; color: var(--slate-700); flex-grow: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; text-decoration: none; }
    .file-link:hover { color: var(--red); }
    .btn-del { background: none; border: none; cursor: pointer; color: var(--slate-300); font-size: 0.8rem; padding: 0.15rem 0.3rem; border-radius: 4px; flex-shrink: 0; transition: color 0.2s, background 0.2s; }
    .btn-del:hover { color: var(--danger); background: var(--red-light); }

    .new-entry { display: flex; align-items: center; gap: 0.4rem; margin-bottom: 0.35rem; }
    .new-entry input[type="file"] { flex: 1; font-size: 0.78rem; font-family: 'Montserrat', sans-serif; border: 1.5px solid var(--slate-200); border-radius: 7px; padding: 0.35rem 0.6rem; background: var(--white); cursor: pointer; }
    .btn-add { display: inline-flex; align-items: center; gap: 0.25rem; font-size: 0.72rem; font-weight: 600; padding: 0.25rem 0.65rem; border-radius: 6px; background: var(--slate-200); color: var(--slate-700); border: none; cursor: pointer; margin-top: 0.4rem; transition: background 0.2s; }
    .btn-add:hover { background: var(--slate-300); }
    .btn-remove-file { background: none; border: none; color: var(--danger); cursor: pointer; font-size: 0.9rem; padding: 0; flex-shrink: 0; line-height: 1; }

    .divider-dashed { border: none; border-top: 1px dashed var(--slate-200); margin: 0.65rem 0; }

    @media (max-width: 768px) {
        .edit-layout { grid-template-columns: 1fr; }
        .edit-sidebar { position: static; height: auto; }
        .edit-main { padding: 1.5rem 1rem 2rem; }
    }
</style>

<div class="edit-layout">

    {{-- ── Sidebar ── --}}
    <aside class="edit-sidebar">
        <a href="{{ route('emergency.show', $emergency->id) }}" class="sidebar-back">
            <i class="bi bi-arrow-left"></i> Back to details
        </a>

        <span class="sidebar-label">Editing emergency</span>
        <h2 class="sidebar-title">{{ $emergency->job_number_name }}</h2>
        <div class="sidebar-meta">
            <i class="bi bi-building me-1"></i> {{ $emergency->company_name }}<br>
            <i class="bi bi-calendar3 me-1"></i> {{ $emergency->date_submitted }}
        </div>

        <nav class="sidebar-nav">
            <span class="sidebar-label">Sections</span>
            <a href="#sec-general" class="nav-item active"><i class="bi bi-info-circle"></i> General Info</a>
            <a href="#sec-terms"   class="nav-item"><i class="bi bi-file-earmark-check"></i> Terms</a>
            <a href="#sec-team"    class="nav-item"><i class="bi bi-people"></i> Team</a>
            <a href="#sec-files"   class="nav-item"><i class="bi bi-paperclip"></i> Files</a>
        </nav>

        <hr class="sidebar-divider">

        <button type="submit" form="editForm" class="btn-save-sidebar">
            <i class="bi bi-check-circle"></i> Save Changes
        </button>
    </aside>

    {{-- ── Main ── --}}
    <main class="edit-main">
        <form method="POST" action="{{ route('emergency.update', $emergency->id) }}"
              enctype="multipart/form-data" id="editForm">
            @csrf
            @method('PUT')

            {{-- General Info --}}
            <p class="section-title" id="sec-general"><i class="bi bi-info-circle-fill"></i> General Information</p>

            <div class="field-group">
                <div class="field-group-body">
                    <div class="row g-3">

                        <div class="col-12">
                            <label class="form-label">Job Number</label>
                            <div class="job-num">
                                {{ $emergency->job_number_name }}
                                <span class="job-num-badge">Locked</span>
                            </div>
                            <input type="hidden" name="job_number_name" value="{{ $emergency->job_number_name }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="date_submitted">Date Submitted</label>
                            <input type="date" name="date_submitted" id="date_submitted" class="form-control"
                                   value="{{ old('date_submitted', \Carbon\Carbon::parse($emergency->date_submitted)->format('Y-m-d')) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="type_of_supplement">Type of Supplement</label>
                            <select name="type_of_supplement" id="type_of_supplement" class="form-select" required>
                                @foreach([
                                    'New roof installed by a Contracting Alliance sub is leaking (warranty)',
                                    'New job, please identify and Stop Leak (minimum $750 charge)',
                                    'Emergency Hurricane Tarping Labor and Materials',
                                    'Emergency Hurricane Tarping Labor Only',
                                ] as $opt)
                                    <option value="{{ $opt }}" {{ old('type_of_supplement', $emergency->type_of_supplement) === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Company Name</label>
                            <input type="text" name="company_name" class="form-control"
                                   value="{{ old('company_name', $emergency->company_name) }}" readonly>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="company_contact_email">Contact Email</label>
                            <input type="email" name="company_contact_email" id="company_contact_email" class="form-control"
                                   value="{{ old('company_contact_email', $emergency->company_contact_email) }}" required>
                        </div>

                        <div class="col-md-8">
                            <label class="form-label" for="job_address">Street Address</label>
                            <input type="text" name="job_address" id="job_address" class="form-control"
                                   value="{{ old('job_address', $emergency->job_address) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="job_address_line2">Line 2</label>
                            <input type="text" name="job_address_line2" id="job_address_line2" class="form-control"
                                   value="{{ old('job_address_line2', $emergency->job_address_line2) }}">
                        </div>
                        <div class="col-md-5">
                            <label class="form-label" for="job_city">City</label>
                            <input type="text" name="job_city" id="job_city" class="form-control"
                                   value="{{ old('job_city', $emergency->job_city) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="job_state">State</label>
                            <select name="job_state" id="job_state" class="form-select" required>
                                @foreach(['AL'=>'Alabama','AK'=>'Alaska','AZ'=>'Arizona','AR'=>'Arkansas','CA'=>'California','CO'=>'Colorado','CT'=>'Connecticut','DE'=>'Delaware','DC'=>'District of Columbia','FL'=>'Florida','GA'=>'Georgia','HI'=>'Hawaii','ID'=>'Idaho','IL'=>'Illinois','IN'=>'Indiana','IA'=>'Iowa','KS'=>'Kansas','KY'=>'Kentucky','LA'=>'Louisiana','ME'=>'Maine','MD'=>'Maryland','MA'=>'Massachusetts','MI'=>'Michigan','MN'=>'Minnesota','MS'=>'Mississippi','MO'=>'Missouri','MT'=>'Montana','NE'=>'Nebraska','NV'=>'Nevada','NH'=>'New Hampshire','NJ'=>'New Jersey','NM'=>'New Mexico','NY'=>'New York','NC'=>'North Carolina','ND'=>'North Dakota','OH'=>'Ohio','OK'=>'Oklahoma','OR'=>'Oregon','PA'=>'Pennsylvania','RI'=>'Rhode Island','SC'=>'South Carolina','SD'=>'South Dakota','TN'=>'Tennessee','TX'=>'Texas','UT'=>'Utah','VT'=>'Vermont','VA'=>'Virginia','WA'=>'Washington','WV'=>'West Virginia','WI'=>'Wisconsin','WY'=>'Wyoming'] as $abbr => $name)
                                    <option value="{{ $abbr }}" {{ old('job_state', $emergency->job_state) === $abbr ? 'selected' : '' }}>{{ $abbr }} – {{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="job_zip_code">ZIP</label>
                            <input type="text" name="job_zip_code" id="job_zip_code" class="form-control"
                                   value="{{ old('job_zip_code', $emergency->job_zip_code) }}" maxlength="10" required>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Terms --}}
            <p class="section-title" id="sec-terms"><i class="bi bi-file-earmark-check-fill"></i> Terms & Conditions</p>
            <div class="field-group">
                <div class="field-group-body">
                    <label class="terms-toggle" for="terms_conditions">
                        <div class="terms-toggle-text">
                            <div class="terms-toggle-label">Supplement Submission Responsibility</div>
                            <div class="terms-toggle-sub">My company is responsible for submitting the supplement.</div>
                        </div>
                        <input type="checkbox" name="terms_conditions" value="1" id="terms_conditions" {{ $emergency->terms_conditions ? 'checked' : '' }}>
                    </label>
                    <label class="terms-toggle" for="requirements">
                        <div class="terms-toggle-text">
                            <div class="terms-toggle-label">Supplement Processing</div>
                            <div class="terms-toggle-sub">Speed and accuracy depend on information provided.</div>
                        </div>
                        <input type="checkbox" name="requirements" value="1" id="requirements" {{ $emergency->requirements ? 'checked' : '' }}>
                    </label>
                </div>
            </div>

            {{-- Team --}}
            <p class="section-title" id="sec-team"><i class="bi bi-people-fill"></i> Assign Team Members</p>
            <div class="field-group">
                <div class="field-group-body">
                    @php $grouped = $teamMembers->groupBy('role'); @endphp
                    @foreach($grouped as $role => $members)
                        <p style="font-size:0.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--slate-400);margin:0.75rem 0 0.4rem;">
                            {{ ucfirst(str_replace('_',' ',$role)) }}
                        </p>
                        <div class="row g-0">
                            @foreach($members as $member)
                                <div class="col-md-6 pe-md-2">
                                    <label class="team-card" for="m_{{ $member->id }}">
                                        <div class="team-avatar">{{ strtoupper(substr($member->name,0,2)) }}</div>
                                        <div>
                                            <div class="team-name">{{ $member->name }}</div>
                                            <div class="team-role">{{ ucfirst(str_replace('_',' ',$member->role)) }}</div>
                                        </div>
                                        <input type="checkbox" name="assigned_team_members[]"
                                               value="{{ $member->id }}" id="m_{{ $member->id }}"
                                               {{ $emergency->teamMembers->contains($member->id) ? 'checked' : '' }}>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Files --}}
            <p class="section-title" id="sec-files"><i class="bi bi-paperclip"></i> File Attachments</p>

            @php
                $sections = [
                    ['label'=>'Aerial Measurements', 'icon'=>'bi-map',                   'field'=>'aerial_measurement',  'files'=>$emergency->aerial_measurement_path  ?? []],
                    ['label'=>'Contract Uploads',     'icon'=>'bi-file-earmark-richtext', 'field'=>'contract_upload',     'files'=>$emergency->contract_upload_path     ?? []],
                    ['label'=>'Additional Pictures',  'icon'=>'bi-images',                'field'=>'file_picture_upload', 'files'=>$emergency->file_picture_upload_path ?? []],
                ];
            @endphp

            @foreach($sections as $sec)
                <div class="file-zone">
                    <div class="file-zone-head">
                        <i class="bi {{ $sec['icon'] }}"></i> {{ $sec['label'] }}
                        <span style="margin-left:auto;font-size:0.62rem;font-weight:500;color:var(--slate-400);">{{ count($sec['files']) }} file(s)</span>
                    </div>
                    <div class="file-zone-body">

                        @forelse($sec['files'] as $file)
                            @php
                                if (is_string($file) && \Illuminate\Support\Str::startsWith($file, '{')) $file = json_decode($file, true);
                                $filePath = is_array($file) ? ($file['path'] ?? '') : trim($file, '[]"');
                                $fileName = is_array($file) ? ($file['original_name'] ?? $file['name'] ?? basename($filePath)) : basename($filePath);
                                $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
                                $ico = match($ext) { 'pdf' => 'bi-file-pdf-fill', 'jpg','jpeg','png','gif','webp' => 'bi-file-image-fill', default => 'bi-file-earmark-text' };
                            @endphp
                            <div class="existing-file">
                                <div class="file-icon-sm"><i class="bi {{ $ico }}"></i></div>
                                <a href="{{ asset('storage/'.$filePath) }}" target="_blank" class="file-link">{{ $fileName }}</a>
                                <span style="font-size:0.6rem;color:var(--slate-400);text-transform:uppercase;flex-shrink:0;">{{ $ext }}</span>
                                <button type="button" class="btn-del" onclick="deleteFile('{{ $filePath }}', {{ $emergency->id }}, this)">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        @empty
                            <p style="font-size:0.78rem;color:var(--slate-400);font-style:italic;margin:0 0 0.65rem;">No files yet.</p>
                        @endforelse

                        <hr class="divider-dashed">

                        <div id="{{ $sec['field'] }}-entries">
                            <div class="new-entry">
                                <input type="file" name="{{ $sec['field'] }}[]" accept=".pdf,.jpg,.jpeg,.png">
                            </div>
                        </div>
                        <button type="button" class="btn-add" data-field="{{ $sec['field'] }}">
                            <i class="bi bi-plus"></i> Add file
                        </button>

                    </div>
                </div>
            @endforeach

        </form>
    </main>

</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Add file rows
document.querySelectorAll('.btn-add').forEach(btn => {
    btn.addEventListener('click', function () {
        const field = this.dataset.field;
        const container = document.getElementById(field + '-entries');
        const entry = document.createElement('div');
        entry.className = 'new-entry';
        entry.innerHTML = `<input type="file" name="${field}[]" accept=".pdf,.jpg,.jpeg,.png">
            <button type="button" class="btn-remove-file">&times;</button>`;
        container.appendChild(entry);
        entry.querySelector('.btn-remove-file').addEventListener('click', () => entry.remove());
    });
});

// Sidebar nav active
document.querySelectorAll('.nav-item').forEach(item => {
    item.addEventListener('click', () => {
        document.querySelectorAll('.nav-item').forEach(i => i.classList.remove('active'));
        item.classList.add('active');
    });
});

// Delete file
function deleteFile(filePath, emergencyId, btnEl) {
    Swal.fire({
        title: 'Delete this file?', text: 'Cannot be undone.', icon: 'warning',
        showCancelButton: true, confirmButtonColor: '#dc2626', cancelButtonColor: '#334155',
        confirmButtonText: 'Delete', cancelButtonText: 'Cancel', reverseButtons: true,
    }).then(result => {
        if (!result.isConfirmed) return;
        fetch("{{ route('emergency.file.delete') }}", {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ file_path: filePath, emergency_id: emergencyId })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                btnEl.closest('.existing-file').remove();
                Swal.fire({ icon: 'success', title: 'Deleted', timer: 1200, showConfirmButton: false });
            } else Swal.fire('Error', data.message, 'error');
        })
        .catch(() => Swal.fire('Error', 'Something went wrong.', 'error'));
    });
}
</script>

@endsection