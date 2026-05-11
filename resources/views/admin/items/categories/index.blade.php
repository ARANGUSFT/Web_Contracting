@extends('admin.layouts.superadmin')
@section('title', 'Item Categories')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
*, *::before, *::after { box-sizing: border-box; }
.cat { font-family: 'Montserrat', sans-serif; padding: 28px 32px; }

:root {
    --ink:  #0f1117; --ink2: #3c4353; --ink3: #8c95a6;
    --bg:   #f4f5f8; --surf: #ffffff;
    --bd:   #e4e7ed; --bd2:  #eef0f4;
    --blue: #1855e0; --blt:  #eef2ff; --bbd:  #c7d4fb;
    --grn:  #0d9e6a; --glt:  #edfaf4; --gbd:  #9fe6c8;
    --red:  #d92626; --rlt:  #fff0f0; --rbd:  #fbcfcf;
    --amb:  #d97706; --alt:  #fffbeb; --abd:  #fde68a;
    --r: 8px; --rlg: 13px; --rxl: 18px;
}

/* ── HERO ── */
.cat-hero {
    position: relative; border-radius: var(--rxl);
    padding: 30px 40px; margin-bottom: 24px;
    display: flex; align-items: center; justify-content: space-between;
    gap: 20px; background: var(--ink); overflow: hidden;
}
.cat-hero::before {
    content: ''; position: absolute; inset: 0; pointer-events: none;
    background-image: linear-gradient(rgba(255,255,255,.025) 1px,transparent 1px),
                      linear-gradient(90deg,rgba(255,255,255,.025) 1px,transparent 1px);
    background-size: 48px 48px;
}
.cat-hero::after {
    content: ''; position: absolute; left:0; top:0; bottom:0; width:4px;
    background: linear-gradient(180deg,#fdba74,#d97706 60%,transparent);
    border-radius: 0 2px 2px 0;
}
.cat-glow {
    position: absolute; right:-60px; top:-60px; width:560px; height:300px;
    background: radial-gradient(ellipse,rgba(217,119,6,.3) 0%,transparent 70%);
    pointer-events: none;
}
.cat-hero-l { position:relative; display:flex; align-items:center; gap:16px; }
.cat-hero-icon {
    width:52px; height:52px; border-radius:14px; flex-shrink:0;
    background:rgba(217,119,6,.2); border:1px solid rgba(217,119,6,.35);
    display:flex; align-items:center; justify-content:center; font-size:20px; color:#fdba74;
}
.cat-hero-title { font-size:22px; font-weight:800; color:#fff; letter-spacing:-.5px; line-height:1; }
.cat-hero-sub   { font-size:12.5px; font-weight:600; color:rgba(255,255,255,.38); margin-top:6px; }
.cat-hero-r { position:relative; display:flex; align-items:center; gap:12px; }
.cat-stat-chip {
    background:rgba(255,255,255,.07); border:1px solid rgba(255,255,255,.1);
    border-radius:12px; padding:10px 18px; text-align:center;
}
.cat-stat-n { font-size:22px; font-weight:800; color:#fff; line-height:1; letter-spacing:-.5px; }
.cat-stat-l { font-size:9.5px; color:rgba(255,255,255,.35); text-transform:uppercase; letter-spacing:.8px; margin-top:3px; font-weight:700; }
.cat-new-btn {
    display:inline-flex; align-items:center; gap:7px;
    padding:11px 20px; border-radius:var(--rlg);
    background:var(--amb); color:#fff;
    font-size:13px; font-weight:700; font-family:'Montserrat',sans-serif;
    border:none; text-decoration:none; transition:background .13s; white-space:nowrap;
    box-shadow: 0 2px 10px rgba(217,119,6,.4);
}
.cat-new-btn:hover { background:#b45309; color:#fff; }

/* ── STATS ROW ── */
.cat-stats {
    display: grid; grid-template-columns: repeat(3, 1fr);
    gap: 12px; margin-bottom: 20px;
}
.cat-stat-card {
    background: var(--surf); border: 1px solid var(--bd);
    border-radius: var(--rlg); padding: 16px 20px;
    display: flex; align-items: center; gap: 14px;
}
.cat-stat-card-icon {
    width: 42px; height: 42px; border-radius: 11px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center; font-size: 16px;
}
.cat-stat-card-icon.amb { background: var(--alt); color: var(--amb); }
.cat-stat-card-icon.grn { background: var(--glt); color: var(--grn); }
.cat-stat-card-icon.ink { background: var(--bg);  color: var(--ink3); }
.cat-stat-card-val { font-size: 24px; font-weight: 800; color: var(--ink); line-height: 1; letter-spacing: -.5px; }
.cat-stat-card-lbl { font-size: 11px; font-weight: 600; color: var(--ink3); margin-top: 2px; }

/* ── TABLE CARD ── */
.cat-card {
    background: var(--surf); border: 1px solid var(--bd);
    border-radius: var(--rxl); overflow: hidden;
}
.cat-card-head {
    display: flex; align-items: center; justify-content: space-between;
    padding: 16px 24px; border-bottom: 1px solid var(--bd2);
    background: linear-gradient(to right, var(--surf), #fafbfd);
}
.cat-card-head-l { display: flex; align-items: center; gap: 10px; }
.cat-card-title  { font-size: 13px; font-weight: 800; color: var(--ink); letter-spacing: -.2px; }
.cat-badge {
    font-size: 11px; font-weight: 700; padding: 3px 10px;
    border-radius: 9999px; background: var(--alt);
    color: var(--amb); border: 1px solid var(--abd);
}

/* ── SEARCH ── */
.cat-search-wrap {
    display: flex; align-items: center; gap: 7px;
    background: var(--bg); border: 1px solid var(--bd);
    border-radius: var(--r); padding: 7px 12px; min-width: 240px;
}
.cat-search-input {
    border: none; outline: none; background: transparent;
    font-size: 12.5px; font-weight: 500; font-family: 'Montserrat', sans-serif;
    color: var(--ink); width: 100%;
}
.cat-search-input::placeholder { color: var(--ink3); }

/* ── TABLE ── */
.cat-tbl { width: 100%; border-collapse: collapse; }
.cat-tbl thead tr { background: #fafbfd; border-bottom: 2px solid var(--bd); }
.cat-tbl th {
    padding: 11px 24px; text-align: left;
    font-size: 10px; font-weight: 800; color: var(--ink3);
    text-transform: uppercase; letter-spacing: .8px; white-space: nowrap;
}
.cat-tbl th.c { text-align: center; }
.cat-tbl th.r { text-align: right; }
.cat-tbl td   { padding: 14px 24px; border-bottom: 1px solid var(--bd2); vertical-align: middle; }
.cat-tbl tbody tr:last-child td { border-bottom: none; }
.cat-tbl tbody tr { transition: background .1s; }
.cat-tbl tbody tr:hover td { background: #fafbfd; }

.cat-row-num  { font-size: 11.5px; font-weight: 700; color: var(--ink3); }
.cat-row-name { font-size: 13.5px; font-weight: 700; color: var(--ink); }
.cat-row-desc { font-size: 11.5px; font-weight: 500; color: var(--ink3); margin-top: 2px; }

.cat-pill {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: 10.5px; font-weight: 800; padding: 4px 10px;
    border-radius: 6px; text-transform: uppercase; letter-spacing: .4px;
}
.cat-pill.active   { background: var(--glt); color: var(--grn); border: 1px solid var(--gbd); }
.cat-pill.inactive { background: var(--bg);  color: var(--ink3); border: 1px solid var(--bd); }

.cat-acts { display: flex; align-items: center; justify-content: flex-end; gap: 4px; }
.cat-act {
    width: 32px; height: 32px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: 12px; border: 1px solid transparent;
    background: none; color: var(--ink3); cursor: pointer;
    transition: all .13s; text-decoration: none;
}
.cat-act:hover       { background: var(--bg); border-color: var(--bd); }
.cat-act.edit:hover  { background: var(--blt); border-color: var(--bbd); color: var(--blue); }
.cat-act.del:hover   { background: var(--rlt); border-color: var(--rbd); color: var(--red); }

/* ── EMPTY ── */
.cat-empty { text-align: center; padding: 72px 24px; }
.cat-empty-icon {
    width: 64px; height: 64px; border-radius: 16px;
    background: var(--bg); border: 1px solid var(--bd);
    display: flex; align-items: center; justify-content: center;
    font-size: 24px; color: var(--ink3); margin: 0 auto 16px;
}
.cat-empty-t { font-size: 15px; font-weight: 800; color: var(--ink); margin-bottom: 5px; }
.cat-empty-s { font-size: 12.5px; font-weight: 500; color: var(--ink3); margin-bottom: 20px; }

@media (max-width: 900px)  { .cat-stats { grid-template-columns: 1fr 1fr; } }
@media (max-width: 640px)  {
    .cat { padding: 16px; }
    .cat-hero { padding: 22px 20px; flex-direction: column; align-items: flex-start; }
    .cat-stats { grid-template-columns: 1fr; }
    .cat-tbl th:nth-child(3), .cat-tbl td:nth-child(3) { display: none; }
}
</style>

@php
    $total    = $categories->count();
    $active   = $categories->where('is_active', true)->count();
    $inactive = $total - $active;
@endphp

<div class="cat">

    {{-- ── HERO ── --}}
    <div class="cat-hero">
        <div class="cat-glow"></div>
        <div class="cat-hero-l">
            <div class="cat-hero-icon"><i class="fas fa-tags"></i></div>
            <div>
                <div class="cat-hero-title">Item Categories</div>
                <div class="cat-hero-sub">Organize and manage your item categories</div>
            </div>
        </div>
        <div class="cat-hero-r">
            <div class="cat-stat-chip">
                <div class="cat-stat-n">{{ $total }}</div>
                <div class="cat-stat-l">Total</div>
            </div>
            <div class="cat-stat-chip">
                <div class="cat-stat-n" style="color:#34d399">{{ $active }}</div>
                <div class="cat-stat-l">Active</div>
            </div>
            <a href="{{ route('superadmin.item-categories.create') }}" class="cat-new-btn">
                <i class="fas fa-plus" style="font-size:11px"></i> New Category
            </a>
        </div>
    </div>

    {{-- ── STATS ROW ── --}}
    <div class="cat-stats">
        <div class="cat-stat-card">
            <div class="cat-stat-card-icon amb"><i class="fas fa-tags"></i></div>
            <div>
                <div class="cat-stat-card-val">{{ $total }}</div>
                <div class="cat-stat-card-lbl">Total Categories</div>
            </div>
        </div>
        <div class="cat-stat-card">
            <div class="cat-stat-card-icon grn"><i class="fas fa-check-circle"></i></div>
            <div>
                <div class="cat-stat-card-val">{{ $active }}</div>
                <div class="cat-stat-card-lbl">Active</div>
            </div>
        </div>
        <div class="cat-stat-card">
            <div class="cat-stat-card-icon ink"><i class="fas fa-minus-circle"></i></div>
            <div>
                <div class="cat-stat-card-val">{{ $inactive }}</div>
                <div class="cat-stat-card-lbl">Inactive</div>
            </div>
        </div>
    </div>

    {{-- ── TABLE CARD ── --}}
    <div class="cat-card">
        <div class="cat-card-head">
            <div class="cat-card-head-l">
                <i class="fas fa-list" style="font-size:14px;color:var(--ink3)"></i>
                <span class="cat-card-title">All Categories</span>
                <span class="cat-badge">{{ $total }}</span>
            </div>
            <div class="cat-search-wrap">
                <i class="fas fa-search" style="font-size:12px;color:var(--ink3)"></i>
                <input type="text" class="cat-search-input" placeholder="Search categories…"
                       oninput="catSearch(this.value)">
            </div>
        </div>

        @if($categories->isEmpty())
        <div class="cat-empty">
            <div class="cat-empty-icon"><i class="fas fa-tags"></i></div>
            <div class="cat-empty-t">No categories yet</div>
            <div class="cat-empty-s">Create your first category to start organizing items.</div>
            <a href="{{ route('superadmin.item-categories.create') }}" class="cat-new-btn">
                <i class="fas fa-plus" style="font-size:11px"></i> New Category
            </a>
        </div>
        @else
        <div style="overflow-x:auto">
            <table class="cat-tbl" id="cat-tbl">
                <thead>
                    <tr>
                        <th style="width:48px">#</th>
                        <th>Name</th>
                        <th class="c">Status</th>
                        <th class="r">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $i => $cat)
                    <tr data-search="{{ strtolower($cat->name) }}">
                        <td><span class="cat-row-num">{{ $i + 1 }}</span></td>
                        <td>
                            <div class="cat-row-name">{{ $cat->name }}</div>
                            @if($cat->description)
                            <div class="cat-row-desc">{{ Str::limit($cat->description, 60) }}</div>
                            @endif
                        </td>
                        <td style="text-align:center">
                            <span class="cat-pill {{ $cat->is_active ? 'active' : 'inactive' }}">
                                <i class="fas fa-{{ $cat->is_active ? 'check-circle' : 'minus-circle' }}" style="font-size:8px"></i>
                                {{ $cat->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <div class="cat-acts">
                                <a href="{{ route('superadmin.item-categories.edit', $cat) }}"
                                   class="cat-act edit" title="Edit">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <form method="POST"
                                      action="{{ route('superadmin.item-categories.destroy', $cat) }}"
                                      style="display:inline">
                                    @csrf @method('DELETE')
                                    <button type="button" class="cat-act del" title="Delete"
                                            onclick="catDel(this.closest('form'), '{{ $cat->name }}')">
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
        @endif
    </div>

</div>

<script>
function catSearch(q) {
    const val = q.trim().toLowerCase();
    document.querySelectorAll('#cat-tbl tbody tr').forEach(r => {
        r.style.display = !val || r.dataset.search.includes(val) ? '' : 'none';
    });
}

function catDel(form, name) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Delete category?',
            html: `<p style="font-family:Montserrat,sans-serif;color:#374151;font-size:14px;line-height:1.6">
                     <strong>${name}</strong> will be permanently deleted.
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
        if (confirm(`Delete "${name}"?`)) form.submit();
    }
}
</script>

@endsection