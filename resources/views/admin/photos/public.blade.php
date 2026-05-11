<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Photo Gallery' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
        --ink:  #0f1117; --ink2: #3c4353; --ink3: #8c95a6;
        --bg:   #f4f5f8; --surf: #ffffff;
        --bd:   #e4e7ed; --bd2:  #eef0f4;
        --blue: #1855e0; --blt:  #eef2ff; --bbd:  #c7d4fb;
        --grn:  #0d9e6a; --glt:  #edfaf4; --gbd:  #9fe6c8;
        --r:    8px; --rlg: 13px; --rxl: 18px;
    }

    html, body {
        min-height: 100vh;
        font-family: 'Montserrat', sans-serif;
        background: var(--bg);
        color: var(--ink);
        font-size: 14px;
    }

    /* ── HEADER ── */
    .pg-header {
        position: sticky; top: 0; z-index: 100;
        background: var(--ink);
        border-bottom: 1px solid rgba(255,255,255,.07);
        padding: 0 28px;
    }
    .pg-header-inner {
        max-width: 1540px; margin: 0 auto;
        height: 62px;
        display: flex; align-items: center; justify-content: space-between;
        gap: 16px;
    }
    .pg-header-left { display: flex; align-items: center; gap: 12px; }
    .pg-header-icon {
        width: 36px; height: 36px; border-radius: 10px;
        background: rgba(24,85,224,.25); border: 1px solid rgba(24,85,224,.4);
        display: flex; align-items: center; justify-content: center;
        font-size: 14px; color: #8aadff; flex-shrink: 0;
    }
    .pg-header-title { font-size: 15px; font-weight: 800; color: #fff; letter-spacing: -.3px; }
    .pg-header-count {
        font-size: 11px; font-weight: 700; padding: 3px 9px;
        border-radius: 9999px; background: rgba(255,255,255,.08);
        border: 1px solid rgba(255,255,255,.1); color: rgba(255,255,255,.5);
    }
    .pg-header-right { display: flex; align-items: center; gap: 8px; flex-shrink: 0; }
    .pg-url-input {
        width: 260px; padding: 7px 12px;
        background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.1);
        border-radius: var(--r); font-size: 12px; font-weight: 500;
        font-family: 'Montserrat', sans-serif; color: rgba(255,255,255,.5);
        outline: none;
    }
    .pg-copy-btn {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 7px 14px; border-radius: var(--r);
        background: var(--blue); color: #fff;
        font-size: 12px; font-weight: 700; font-family: 'Montserrat', sans-serif;
        border: none; cursor: pointer; transition: background .13s; white-space: nowrap;
    }
    .pg-copy-btn:hover { background: #1344c2; }
    .pg-copy-btn.copied { background: var(--grn); }

    /* ── MAIN ── */
    .pg-main {
        max-width: 1540px; margin: 0 auto;
        padding: 28px 28px 48px;
    }

    /* ── EMPTY ── */
    .pg-empty {
        background: var(--surf); border: 1px solid var(--bd);
        border-radius: var(--rxl); padding: 72px 24px; text-align: center;
        max-width: 400px; margin: 0 auto;
    }
    .pg-empty-icon {
        width: 64px; height: 64px; border-radius: 16px;
        background: var(--bg); border: 1px solid var(--bd);
        display: flex; align-items: center; justify-content: center;
        font-size: 24px; color: var(--ink3); margin: 0 auto 16px;
    }
    .pg-empty-t { font-size: 15px; font-weight: 800; color: var(--ink); margin-bottom: 5px; }
    .pg-empty-s { font-size: 12.5px; font-weight: 500; color: var(--ink3); }

    /* ── GRID ── */
    .pg-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 12px;
    }

    /* ── PHOTO CARD ── */
    .pg-card {
        background: var(--surf); border: 1px solid var(--bd);
        border-radius: var(--rlg); overflow: hidden;
        transition: box-shadow .15s, border-color .15s, transform .15s;
        cursor: pointer;
    }
    .pg-card:hover {
        border-color: var(--blue);
        box-shadow: 0 4px 20px rgba(0,0,0,.1);
        transform: translateY(-2px);
    }
    .pg-card-thumb-wrap {
        position: relative; overflow: hidden;
        aspect-ratio: 1 / 1;
        background: var(--bg);
    }
    .pg-card-thumb {
        width: 100%; height: 100%; object-fit: cover; display: block;
        transition: transform .3s ease;
    }
    .pg-card:hover .pg-card-thumb { transform: scale(1.06); }
    .pg-card-overlay {
        position: absolute; inset: 0;
        background: rgba(0,0,0,0);
        display: flex; align-items: center; justify-content: center; gap: 6px;
        opacity: 0; transition: all .2s;
    }
    .pg-card:hover .pg-card-overlay { background: rgba(0,0,0,.35); opacity: 1; }
    .pg-card-ov-btn {
        width: 34px; height: 34px; border-radius: 50%;
        background: rgba(255,255,255,.95); border: none; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        font-size: 12px; color: var(--ink2); transition: all .13s;
    }
    .pg-card-ov-btn:hover { background: #fff; transform: scale(1.1); }
    .pg-card-ov-btn.grn:hover { background: var(--glt); color: var(--grn); }
    .pg-card-foot {
        padding: 8px 12px; display: flex; align-items: center; justify-content: space-between;
        border-top: 1px solid var(--bd2);
    }
    .pg-card-num { font-size: 11px; font-weight: 700; color: var(--ink2); }

    /* ── LIGHTBOX ── */
    .pg-lb {
        display: none; position: fixed; inset: 0; z-index: 9999;
        align-items: center; justify-content: center;
        background: rgba(0,0,0,.88);
        backdrop-filter: blur(6px);
    }
    .pg-lb.open { display: flex; }
    .pg-lb-box {
        position: relative;
        max-width: min(92vw, 900px);
        display: flex; flex-direction: column; align-items: center;
    }
    .pg-lb-img {
        max-width: 92vw; max-height: 82vh;
        object-fit: contain; border-radius: var(--rlg);
        box-shadow: 0 8px 48px rgba(0,0,0,.6); display: block;
    }
    .pg-lb-close {
        position: absolute; top: -44px; right: 0;
        width: 36px; height: 36px; border-radius: 10px;
        background: rgba(255,255,255,.1); border: 1px solid rgba(255,255,255,.15);
        color: rgba(255,255,255,.7); font-size: 14px;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; transition: all .13s;
    }
    .pg-lb-close:hover { background: rgba(255,255,255,.2); color: #fff; }
    .pg-lb-nav {
        position: absolute; top: 50%; transform: translateY(-50%);
        width: 42px; height: 42px; border-radius: 50%;
        background: rgba(255,255,255,.1); border: 1px solid rgba(255,255,255,.15);
        color: rgba(255,255,255,.8); font-size: 15px;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; transition: all .13s;
    }
    .pg-lb-nav:hover { background: rgba(255,255,255,.2); color: #fff; }
    .pg-lb-nav.prev { left: -58px; }
    .pg-lb-nav.next { right: -58px; }
    .pg-lb-bar {
        position: absolute; bottom: -44px;
        display: flex; align-items: center; gap: 12px;
    }
    .pg-lb-counter {
        font-size: 12px; font-weight: 700; color: rgba(255,255,255,.55);
        font-family: 'Montserrat', sans-serif;
    }
    .pg-lb-dl {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 6px 14px; border-radius: var(--r);
        background: rgba(255,255,255,.1); border: 1px solid rgba(255,255,255,.15);
        color: rgba(255,255,255,.7); font-size: 11.5px; font-weight: 700;
        font-family: 'Montserrat', sans-serif; cursor: pointer;
        transition: all .13s; text-decoration: none;
    }
    .pg-lb-dl:hover { background: rgba(255,255,255,.18); color: #fff; }

    /* ── FOOTER ── */
    .pg-footer {
        text-align: center; padding: 20px 24px 32px;
        font-size: 11.5px; font-weight: 600; color: var(--ink3);
    }
    .pg-footer a { color: var(--blue); text-decoration: none; }

    /* ── SCROLLBAR ── */
    ::-webkit-scrollbar { width: 5px; height: 5px; }
    ::-webkit-scrollbar-track { background: var(--bg); }
    ::-webkit-scrollbar-thumb { background: #cdd0d8; border-radius: 9999px; }

    @media (max-width: 768px) {
        .pg-header-inner { padding: 0; height: 56px; }
        .pg-url-input { display: none; }
        .pg-main { padding: 16px 16px 40px; }
        .pg-grid { grid-template-columns: repeat(auto-fill, minmax(140px,1fr)); gap: 8px; }
        .pg-lb-nav { display: none; }
        .pg-lb-bar { bottom: -40px; }
    }
    </style>
</head>
<body>

@php
    $raw   = $photos ?? $fotos ?? [];
    $items = [];
    foreach ($raw as $it) {
        if      (is_string($it))                       $items[] = $it;
        elseif  (is_array($it)  && isset($it['url']))  $items[] = $it['url'];
        elseif  (is_object($it) && isset($it->url))    $items[] = $it->url;
    }
    $total = count($items);
@endphp

{{-- ── HEADER ── --}}
<header class="pg-header">
    <div class="pg-header-inner">
        <div class="pg-header-left">
            <div class="pg-header-icon"><i class="fas fa-images"></i></div>
            <div class="pg-header-title">{{ $title ?? 'Photo Gallery' }}</div>
            <span class="pg-header-count">{{ $total }} {{ Str::plural('photo', $total) }}</span>
        </div>
        <div class="pg-header-right">
            <input id="pg-url" class="pg-url-input" readonly value="{{ request()->fullUrl() }}">
            <button type="button" class="pg-copy-btn" id="pg-copy-btn" onclick="pgCopy()">
                <i class="fas fa-copy" style="font-size:10px"></i> Copy link
            </button>
        </div>
    </div>
</header>

{{-- ── MAIN ── --}}
<main class="pg-main">

    @if(empty($items))
    <div class="pg-empty">
        <div class="pg-empty-icon"><i class="fas fa-camera"></i></div>
        <div class="pg-empty-t">No photos available</div>
        <div class="pg-empty-s">This gallery doesn't have any images yet.</div>
    </div>
    @else
    <div class="pg-grid">
        @foreach($items as $i => $u)
        @php
            $src = preg_match('#^https?://#i', $u) ? $u : asset('storage/'.$u);
        @endphp
        <div class="pg-card" onclick="pgLb({{ $i }})">
            <div class="pg-card-thumb-wrap">
                <img src="{{ $src }}" alt="Photo {{ $i+1 }}"
                     class="pg-card-thumb" loading="lazy" data-index="{{ $i }}">
                <div class="pg-card-overlay">
                    <button type="button" class="pg-card-ov-btn"
                            onclick="pgLb({{ $i }});event.stopPropagation()" title="View">
                        <i class="fas fa-expand"></i>
                    </button>
                    <button type="button" class="pg-card-ov-btn grn"
                            onclick="pgDl('{{ $src }}',{{ $i+1 }});event.stopPropagation()" title="Download">
                        <i class="fas fa-download"></i>
                    </button>
                </div>
            </div>
            <div class="pg-card-foot">
                <span class="pg-card-num">Photo #{{ $i+1 }}</span>
            </div>
        </div>
        @endforeach
    </div>
    @endif

</main>

{{-- ── FOOTER ── --}}
<footer class="pg-footer">
    &copy; {{ date('Y') }} Contracting Alliance Inc. · All rights reserved.
</footer>

{{-- ── LIGHTBOX ── --}}
<div class="pg-lb" id="pg-lb">
    <div class="pg-lb-box">
        <button class="pg-lb-close" onclick="pgLbClose()"><i class="fas fa-times"></i></button>
        <button class="pg-lb-nav prev" onclick="pgLbNav(-1)"><i class="fas fa-chevron-left"></i></button>
        <button class="pg-lb-nav next" onclick="pgLbNav(1)"><i class="fas fa-chevron-right"></i></button>
        <img id="pg-lb-img" class="pg-lb-img" src="" alt="">
        <div class="pg-lb-bar">
            <span class="pg-lb-counter" id="pg-lb-counter">1 / {{ $total }}</span>
            <a id="pg-lb-dl" href="#" download class="pg-lb-dl">
                <i class="fas fa-download" style="font-size:10px"></i> Download
            </a>
        </div>
    </div>
</div>

<script>
const pgPhotos = [
    @foreach($items as $u)
    @php $src = preg_match('#^https?://#i', $u) ? $u : asset('storage/'.$u); @endphp
    '{{ $src }}',
    @endforeach
];
let pgIdx = 0;

/* ── LIGHTBOX ── */
function pgLb(idx) {
    pgIdx = idx;
    document.getElementById('pg-lb-img').src = pgPhotos[pgIdx];
    document.getElementById('pg-lb-dl').href = pgPhotos[pgIdx];
    document.getElementById('pg-lb-dl').download = 'photo-' + (pgIdx+1) + '.jpg';
    document.getElementById('pg-lb-counter').textContent = (pgIdx+1) + ' / ' + pgPhotos.length;
    document.getElementById('pg-lb').classList.add('open');
    document.body.style.overflow = 'hidden';
}
function pgLbClose() {
    document.getElementById('pg-lb').classList.remove('open');
    document.body.style.overflow = '';
}
function pgLbNav(dir) {
    pgIdx = (pgIdx + dir + pgPhotos.length) % pgPhotos.length;
    document.getElementById('pg-lb-img').src = pgPhotos[pgIdx];
    document.getElementById('pg-lb-dl').href = pgPhotos[pgIdx];
    document.getElementById('pg-lb-dl').download = 'photo-' + (pgIdx+1) + '.jpg';
    document.getElementById('pg-lb-counter').textContent = (pgIdx+1) + ' / ' + pgPhotos.length;
}

/* ── DOWNLOAD ── */
function pgDl(url, num) {
    const a = document.createElement('a');
    a.href = url; a.download = 'photo-' + num + '.jpg';
    document.body.appendChild(a); a.click(); document.body.removeChild(a);
}

/* ── COPY LINK ── */
function pgCopy() {
    const inp = document.getElementById('pg-url');
    const btn = document.getElementById('pg-copy-btn');
    if (navigator.clipboard) {
        navigator.clipboard.writeText(inp.value);
    } else {
        inp.select(); document.execCommand('copy');
    }
    btn.innerHTML = '<i class="fas fa-check" style="font-size:10px"></i> Copied!';
    btn.classList.add('copied');
    setTimeout(() => {
        btn.innerHTML = '<i class="fas fa-copy" style="font-size:10px"></i> Copy link';
        btn.classList.remove('copied');
    }, 2000);
}

/* ── KEYBOARD + SWIPE ── */
document.addEventListener('keydown', e => {
    if (!document.getElementById('pg-lb').classList.contains('open')) return;
    if (e.key === 'Escape')      pgLbClose();
    if (e.key === 'ArrowLeft')   pgLbNav(-1);
    if (e.key === 'ArrowRight')  pgLbNav(1);
});

document.getElementById('pg-lb').addEventListener('click', function(e) {
    if (e.target === this) pgLbClose();
});

let tStart = 0;
document.addEventListener('touchstart', e => { tStart = e.changedTouches[0].screenX; });
document.addEventListener('touchend',   e => {
    if (!document.getElementById('pg-lb').classList.contains('open')) return;
    const diff = e.changedTouches[0].screenX - tStart;
    if (Math.abs(diff) > 50) pgLbNav(diff < 0 ? 1 : -1);
});
</script>

</body>
</html>