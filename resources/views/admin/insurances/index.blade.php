@extends('admin.layouts.superadmin')
@section('title', 'Insurance Management')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
*, *::before, *::after { box-sizing: border-box; }
.ins { font-family: 'Montserrat', sans-serif; padding: 28px 32px; max-width: 1540px; }

:root {
    --ink:  #0f1117; --ink2: #3c4353; --ink3: #8c95a6;
    --bg:   #f4f5f8; --surf: #ffffff;
    --bd:   #e4e7ed; --bd2:  #eef0f4;
    --blue: #1855e0; --blt:  #eef2ff; --bbd:  #c7d4fb;
    --grn:  #0d9e6a; --glt:  #edfaf4; --gbd:  #9fe6c8;
    --red:  #d92626; --rlt:  #fff0f0; --rbd:  #fbcfcf;
    --amb:  #d97706; --alt:  #fffbeb; --abd:  #fde68a;
    --r:    8px; --rlg: 13px; --rxl: 18px;
}

/* ── HERO ── */
.ins-hero {
    position: relative; border-radius: var(--rxl);
    padding: 30px 36px; margin-bottom: 22px;
    display: flex; align-items: center; justify-content: space-between;
    gap: 20px; background: var(--ink); overflow: hidden;
}
.ins-hero-glow {
    position: absolute; pointer-events: none;
    width: 600px; height: 300px;
    background: radial-gradient(ellipse, rgba(13,158,106,.3) 0%, transparent 70%);
    right: -60px; top: -60px;
}
.ins-hero-accent {
    position: absolute; left: 0; top: 0; bottom: 0; width: 4px;
    background: linear-gradient(180deg,#34d399 0%,#0d9e6a 50%,transparent 100%);
    border-radius: 0 2px 2px 0;
}
.ins-hero-grid {
    position: absolute; inset: 0; pointer-events: none;
    background-image:
        linear-gradient(rgba(255,255,255,.025) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,.025) 1px, transparent 1px);
    background-size: 48px 48px;
}
.ins-hero-left { position: relative; display: flex; align-items: center; gap: 16px; }
.ins-hero-badge {
    width: 50px; height: 50px; border-radius: 13px; flex-shrink: 0;
    background: rgba(13,158,106,.2); border: 1px solid rgba(13,158,106,.35);
    display: flex; align-items: center; justify-content: center;
    font-size: 18px; color: #34d399;
}
.ins-hero-title { font-size: 20px; font-weight: 800; color: #fff; letter-spacing: -.4px; line-height: 1; }
.ins-hero-sub   { font-size: 12px; color: rgba(255,255,255,.38); margin-top: 5px; font-weight: 500; }
.ins-hero-right { position: relative; display: flex; align-items: center; gap: 10px; }

/* ── SEARCH ── */
.ins-search-wrap {
    display: flex; align-items: center; gap: 8px;
    background: var(--surf); border: 1px solid var(--bd);
    border-radius: var(--rlg); padding: 10px 16px;
    margin-bottom: 22px; box-shadow: 0 1px 4px rgba(0,0,0,.04);
}
.ins-search-ico   { font-size: 14px; color: var(--ink3); flex-shrink: 0; }
.ins-search-input {
    flex: 1; border: none; outline: none;
    font-size: 13px; font-weight: 500; font-family: 'Montserrat', sans-serif;
    color: var(--ink); background: transparent;
}
.ins-search-input::placeholder { color: var(--ink3); }
.ins-search-count {
    font-size: 12px; font-weight: 700; color: var(--grn);
    background: var(--glt); border: 1px solid var(--gbd);
    border-radius: 9999px; padding: 2px 10px;
    display: none; white-space: nowrap;
}

/* ── SUB BLOCK ── */
.ins-block { margin-bottom: 18px; }
.ins-block-head {
    display: flex; align-items: center; justify-content: space-between;
    padding: 16px 22px; background: var(--surf);
    border: 1px solid var(--bd); border-radius: var(--rlg) var(--rlg) 0 0;
    border-bottom: none;
}
.ins-block-head-l { display: flex; align-items: center; gap: 12px; }
.ins-sub-av {
    width: 38px; height: 38px; border-radius: 10px; flex-shrink: 0;
    background: linear-gradient(135deg,#0d9e6a,#34d399);
    display: flex; align-items: center; justify-content: center;
    font-size: 14px; font-weight: 800; color: #fff;
}
.ins-sub-name { font-size: 13.5px; font-weight: 800; color: var(--ink); letter-spacing: -.2px; }
.ins-sub-co   { font-size: 11.5px; font-weight: 600; color: var(--ink3); margin-top: 1px; }
.ins-sub-meta { display: flex; align-items: center; gap: 10px; margin-top: 6px; }
.ins-sub-meta-item {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: 11.5px; font-weight: 500; color: var(--ink3);
}
.ins-sub-meta-item i { font-size: 10px; }
.ins-add-btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 7px 14px; border-radius: var(--r);
    background: var(--grn); color: #fff;
    font-size: 12px; font-weight: 700; font-family: 'Montserrat', sans-serif;
    border: none; cursor: pointer; text-decoration: none;
    transition: background .13s; white-space: nowrap;
}
.ins-add-btn:hover { background: #0a8559; color: #fff; }

/* ── TABLE ── */
.ins-tbl-wrap {
    background: var(--surf); border: 1px solid var(--bd);
    border-radius: 0 0 var(--rlg) var(--rlg);
    overflow: hidden;
}
.ins-tbl { width: 100%; border-collapse: collapse; }
.ins-tbl thead tr { background: #fafbfd; border-bottom: 2px solid var(--bd); }
.ins-tbl th {
    padding: 10px 18px; text-align: left;
    font-size: 10px; font-weight: 800; color: var(--ink3);
    text-transform: uppercase; letter-spacing: .8px; white-space: nowrap;
}
.ins-tbl th.r { text-align: right; }
.ins-tbl td { padding: 12px 18px; border-bottom: 1px solid var(--bd2); vertical-align: middle; }
.ins-tbl tbody tr:last-child td { border-bottom: none; }
.ins-tbl tbody tr { transition: background .1s; }
.ins-tbl tbody tr:hover td { background: var(--bg); }

/* ── FILE ROW ── */
.ins-file-row { display: flex; align-items: center; gap: 8px; margin-bottom: 5px; }
.ins-file-row:last-child { margin-bottom: 0; }
.ins-file-ico { color: var(--red); font-size: 13px; flex-shrink: 0; }
.ins-file-link {
    font-size: 12.5px; font-weight: 600; color: var(--blue);
    text-decoration: none; flex: 1; white-space: nowrap;
    overflow: hidden; text-overflow: ellipsis;
    max-width: 240px;
}
.ins-file-link:hover { text-decoration: underline; }
.ins-dl-btn {
    width: 26px; height: 26px; border-radius: 7px;
    display: flex; align-items: center; justify-content: center;
    font-size: 11px; border: 1px solid var(--bd);
    background: none; color: var(--ink3); cursor: pointer;
    transition: all .13s; text-decoration: none; flex-shrink: 0;
}
.ins-dl-btn:hover { background: var(--blt); border-color: var(--bbd); color: var(--blue); }

/* ── DATE & STATUS ── */
.ins-date { font-size: 12.5px; font-weight: 700; }
.ins-date.valid   { color: var(--grn); }
.ins-date.soon    { color: var(--amb); }
.ins-date.expired { color: var(--red); }

.ins-pill {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: 10.5px; font-weight: 800; padding: 3px 9px;
    border-radius: 6px; text-transform: uppercase; letter-spacing: .4px;
}
.ins-pill.valid   { background: var(--glt); color: var(--grn); border: 1px solid var(--gbd); }
.ins-pill.soon    { background: var(--alt); color: var(--amb); border: 1px solid var(--abd); }
.ins-pill.expired { background: var(--rlt); color: var(--red); border: 1px solid var(--rbd); }

/* ── NOTES ── */
.ins-notes {
    font-size: 12px; font-weight: 500; color: var(--ink2);
    max-width: 200px; white-space: nowrap;
    overflow: hidden; text-overflow: ellipsis;
}

/* ── ACTIONS ── */
.ins-acts { display: flex; align-items: center; justify-content: flex-end; gap: 4px; }
.ins-act {
    width: 30px; height: 30px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: 12px; border: 1px solid transparent;
    background: none; color: var(--ink3); cursor: pointer;
    transition: all .13s; text-decoration: none;
}
.ins-act:hover     { background: var(--bg); border-color: var(--bd); }
.ins-act.edit:hover{ background: var(--blt); border-color: var(--bbd); color: var(--blue); }
.ins-act.del:hover { background: var(--rlt); border-color: var(--rbd); color: var(--red); }

/* ── EMPTY ── */
.ins-empty {
    text-align: center; padding: 36px 24px;
    background: var(--surf); border: 1px solid var(--bd);
    border-radius: 0 0 var(--rlg) var(--rlg);
}
.ins-empty-icon {
    width: 52px; height: 52px; border-radius: 14px;
    background: var(--bg); border: 1px solid var(--bd);
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; color: var(--ink3); margin: 0 auto 12px;
}
.ins-empty-t { font-size: 13.5px; font-weight: 800; color: var(--ink); margin-bottom: 4px; }
.ins-empty-s { font-size: 12px; font-weight: 500; color: var(--ink3); margin-bottom: 14px; }

/* ── GLOBAL EMPTY ── */
.ins-global-empty {
    background: var(--surf); border: 1px solid var(--bd);
    border-radius: var(--rxl); padding: 64px 24px; text-align: center;
}
.ins-global-empty i { font-size: 28px; color: var(--ink3); opacity: .3; display: block; margin-bottom: 14px; }
.ins-global-empty-t { font-size: 14px; font-weight: 800; color: var(--ink); margin-bottom: 5px; }
.ins-global-empty-s { font-size: 12.5px; font-weight: 500; color: var(--ink3); }

/* ── SCROLLBAR ── */
::-webkit-scrollbar { width: 5px; height: 5px; }
::-webkit-scrollbar-track { background: var(--bg); }
::-webkit-scrollbar-thumb { background: #cdd0d8; border-radius: 9999px; }

@media (max-width: 768px) {
    .ins { padding: 16px; }
    .ins-hero { padding: 22px 20px; flex-direction: column; align-items: flex-start; }
    .ins-tbl th:nth-child(4), .ins-tbl td:nth-child(4) { display: none; }
}
</style>

<div class="ins">

    {{-- ── HERO ── --}}
    <div class="ins-hero">
        <div class="ins-hero-glow"></div>
        <div class="ins-hero-accent"></div>
        <div class="ins-hero-grid"></div>
        <div class="ins-hero-left">
            <div class="ins-hero-badge"><i class="fas fa-shield-alt"></i></div>
            <div>
                <div class="ins-hero-title">Insurance Management</div>
                <div class="ins-hero-sub">Track and manage subcontractor insurance documents</div>
            </div>
        </div>
        <div class="ins-hero-right">
            @php
                $totalIns    = $subcontractors->sum(fn($s) => $s->insurances->count());
                $expiredIns  = $subcontractors->sum(fn($s) => $s->insurances->filter(fn($i) => \Carbon\Carbon::parse($i->expires_at)->isPast())->count());
                $soonIns     = $subcontractors->sum(fn($s) => $s->insurances->filter(fn($i) => !$i->expires_at || (!Carbon\Carbon::parse($i->expires_at)->isPast() && Carbon\Carbon::parse($i->expires_at)->diffInDays(now()) <= 30))->count()) - $expiredIns;
            @endphp
            <div style="background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:12px;padding:10px 16px;text-align:center">
                <div style="font-size:20px;font-weight:800;color:#fff;line-height:1">{{ $totalIns }}</div>
                <div style="font-size:9.5px;color:rgba(255,255,255,.35);text-transform:uppercase;letter-spacing:.8px;margin-top:3px;font-weight:700">Records</div>
            </div>
            @if($expiredIns > 0)
            <div style="background:rgba(217,38,38,.15);border:1px solid rgba(217,38,38,.3);border-radius:12px;padding:10px 16px;text-align:center">
                <div style="font-size:20px;font-weight:800;color:#f87171;line-height:1">{{ $expiredIns }}</div>
                <div style="font-size:9.5px;color:rgba(248,113,113,.5);text-transform:uppercase;letter-spacing:.8px;margin-top:3px;font-weight:700">Expired</div>
            </div>
            @endif
        </div>
    </div>

    {{-- ── SEARCH ── --}}
    <div class="ins-search-wrap">
        <i class="fas fa-search ins-search-ico"></i>
        <input type="text" id="ins-search" class="ins-search-input"
               placeholder="Search by subcontractor name, company…"
               oninput="insSearch(this.value)">
        <span class="ins-search-count" id="ins-count"></span>
    </div>

    {{-- ── BLOCKS ── --}}
    @forelse($subcontractors as $sub)
    <div class="ins-block" data-search="{{ strtolower($sub->name.' '.($sub->last_name ?? '').' '.($sub->company_name ?? '')) }}">

        {{-- Head ── --}}
        <div class="ins-block-head">
            <div class="ins-block-head-l">
                <div class="ins-sub-av">{{ strtoupper(substr($sub->name,0,1)) }}</div>
                <div>
                    <div class="ins-sub-name">{{ $sub->name }} {{ $sub->last_name }}</div>
                    <div class="ins-sub-co">{{ $sub->company_name ?? '—' }}</div>
                    <div class="ins-sub-meta">
                        @if($sub->email)
                        <span class="ins-sub-meta-item"><i class="fas fa-envelope"></i> {{ $sub->email }}</span>
                        @endif
                        @if($sub->phone)
                        <span class="ins-sub-meta-item"><i class="fas fa-phone"></i> {{ $sub->phone }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <a href="{{ route('superadmin.subcontractors.insurances.create', $sub->id) }}" class="ins-add-btn">
                <i class="fas fa-plus" style="font-size:10px"></i>
                {{ $sub->insurances->isEmpty() ? 'Add Insurance' : 'Add More' }}
            </a>
        </div>

        {{-- Records ── --}}
        @if($sub->insurances->isNotEmpty())
        <div class="ins-tbl-wrap">
            <div style="overflow-x:auto">
                <table class="ins-tbl">
                    <thead>
                        <tr>
                            <th>Insurance Files</th>
                            <th>Expiration Date</th>
                            <th>Status</th>
                            <th>Notes</th>
                            <th class="r">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sub->insurances as $ins)
                        @php
                            $exp = \Carbon\Carbon::parse($ins->expires_at);
                            $isExpired  = $exp->isPast();
                            $expiresSoon = !$isExpired && $exp->diffInDays(now()) <= 30;
                            $statusClass = $isExpired ? 'expired' : ($expiresSoon ? 'soon' : 'valid');
                            $statusLabel = $isExpired ? 'Expired' : ($expiresSoon ? 'Expires Soon' : 'Valid');
                        @endphp
                        <tr>

                            {{-- Files ── --}}
                            <td>
                                @if(is_array($ins->file))
                                    @foreach($ins->file as $f)
                                        @if(isset($f['path']) && isset($f['original_name']))
                                        <div class="ins-file-row">
                                            <i class="fas fa-file-pdf ins-file-ico"></i>
                                            <a href="{{ Storage::url($f['path']) }}" target="_blank" class="ins-file-link">
                                                {{ $f['original_name'] }}
                                            </a>
                                            <a href="{{ Storage::url($f['path']) }}" download="{{ $f['original_name'] }}" class="ins-dl-btn" title="Download">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        </div>
                                        @endif
                                    @endforeach
                                @else
                                <div class="ins-file-row">
                                    <i class="fas fa-file-pdf ins-file-ico"></i>
                                    <a href="{{ Storage::url($ins->file) }}" target="_blank" class="ins-file-link">
                                        {{ basename($ins->file) }}
                                    </a>
                                    <a href="{{ Storage::url($ins->file) }}" download="{{ basename($ins->file) }}" class="ins-dl-btn" title="Download">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </div>
                                @endif
                            </td>

                            {{-- Date ── --}}
                            <td>
                                <span class="ins-date {{ $statusClass }}">
                                    {{ $exp->format('M d, Y') }}
                                </span>
                            </td>

                            {{-- Status ── --}}
                            <td>
                                <span class="ins-pill {{ $statusClass }}">
                                    <i class="fas fa-{{ $isExpired ? 'times-circle' : ($expiresSoon ? 'exclamation-circle' : 'check-circle') }}" style="font-size:8px"></i>
                                    {{ $statusLabel }}
                                </span>
                            </td>

                            {{-- Notes ── --}}
                            <td>
                                <div class="ins-notes" title="{{ $ins->notes ?? '' }}">
                                    {{ $ins->notes ?? '—' }}
                                </div>
                            </td>

                            {{-- Actions ── --}}
                            <td>
                                <div class="ins-acts">
                                    <a href="{{ route('superadmin.subcontractors.insurances.edit', [$sub->id, $ins->id]) }}"
                                       class="ins-act edit" title="Edit">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    <form method="POST"
                                          action="{{ route('superadmin.subcontractors.insurances.destroy', [$sub->id, $ins->id]) }}"
                                          class="ins-del-form" style="display:inline"
                                          data-name="{{ $sub->name }} {{ $sub->last_name }}">
                                        @csrf @method('DELETE')
                                        <button type="button" class="ins-act del" title="Delete"
                                                onclick="insDel(this.closest('form'))">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @else
        {{-- Empty per sub ── --}}
        <div class="ins-empty">
            <div class="ins-empty-icon"><i class="fas fa-folder-open"></i></div>
            <div class="ins-empty-t">No insurance records</div>
            <div class="ins-empty-s">Click "Add Insurance" to upload documents for this subcontractor</div>
        </div>
        @endif
    </div>

    @empty
    <div class="ins-global-empty">
        <i class="fas fa-users"></i>
        <div class="ins-global-empty-t">No subcontractors found</div>
        <div class="ins-global-empty-s">No subcontractors have been added to the system yet.</div>
    </div>
    @endforelse

</div>

<script>
/* ── SEARCH ── */
function insSearch(q) {
    const val   = q.trim().toLowerCase();
    const blocks = document.querySelectorAll('.ins-block');
    let shown = 0;
    const cnt = document.getElementById('ins-count');

    blocks.forEach(b => {
        const match = !val || b.dataset.search.includes(val);
        b.style.display = match ? '' : 'none';
        if (match) shown++;
    });

    if (val) {
        cnt.textContent = shown + ' result' + (shown !== 1 ? 's' : '');
        cnt.style.display = 'inline-flex';
    } else {
        cnt.style.display = 'none';
    }
}

/* ── DELETE ── */
function insDel(form) {
    const name = form.dataset.name || 'this record';
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Delete insurance record?',
            html: `<p style="font-family:Montserrat,sans-serif;color:#374151;font-size:14px;line-height:1.6">
                     The insurance record for <strong>${name}</strong> will be permanently deleted.
                   </p>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d92626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, delete',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
        }).then(r => { if (r.isConfirmed) form.submit(); });
    } else {
        if (confirm(`Delete insurance for ${name}?`)) form.submit();
    }
}
</script>

@endsection