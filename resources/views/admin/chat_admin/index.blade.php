@extends('admin.layouts.superadmin')
@section('title', 'Chat')

@section('content')

@php
    $users     = $users ?? collect();
    $firstUser = $users->first();
    $fullName  = trim(($firstUser->name ?? '').' '.($firstUser->last_name ?? '')) ?: 'Usuario';
    $avatarUrl = $firstUser->profile_photo
        ? asset('storage/'.$firstUser->profile_photo)
        : 'https://ui-avatars.com/api/?name='.urlencode($fullName).'&background=1855e0&color=fff&bold=true';
    $hasUsers  = !is_null($firstUser);
@endphp

<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
*, *::before, *::after { box-sizing: border-box; }

:root {
    --ink:  #0f1117; --ink2: #3c4353; --ink3: #8c95a6;
    --bg:   #f4f5f8; --surf: #ffffff;
    --bd:   #e4e7ed; --bd2:  #eef0f4;
    --blue: #1855e0; --blt:  #eef2ff; --bbd:  #c7d4fb;
    --grn:  #0d9e6a; --glt:  #edfaf4; --gbd:  #9fe6c8;
    --red:  #d92626; --rlt:  #fff0f0; --rbd:  #fbcfcf;
    --r:    8px; --rlg: 13px; --rxl: 18px;
    --sidebar-w: 280px;
}

/* ── PAGE WRAPPER ── */
.ch-page {
    font-family: 'Montserrat', sans-serif;
    padding: 28px 32px;
    height: calc(100vh - 62px); /* subtract header */
    display: flex;
    flex-direction: column;
}

/* ── CHAT SHELL ── */
.ch-shell {
    display: flex;
    flex: 1;
    border: 1px solid var(--bd);
    border-radius: var(--rxl);
    overflow: hidden;
    background: var(--surf);
    box-shadow: 0 2px 16px rgba(0,0,0,.06);
    min-height: 0;
}

/* ════════════════════════════════
   SIDEBAR
════════════════════════════════ */
.ch-sidebar {
    width: var(--sidebar-w);
    flex-shrink: 0;
    display: flex;
    flex-direction: column;
    border-right: 1px solid var(--bd);
    background: var(--surf);
}

/* Sidebar Header */
.ch-sidebar-head {
    padding: 16px 18px;
    border-bottom: 1px solid var(--bd2);
    background: linear-gradient(to right, var(--surf), #fafbfd);
    flex-shrink: 0;
}
.ch-sidebar-title { font-size: 13px; font-weight: 800; color: var(--ink); letter-spacing: -.2px; }
.ch-sidebar-sub   { font-size: 11px; font-weight: 600; color: var(--ink3); margin-top: 1px; }

/* Search */
.ch-search-wrap {
    display: flex; align-items: center; gap: 7px;
    padding: 12px 14px; border-bottom: 1px solid var(--bd2);
    flex-shrink: 0;
}
.ch-search-ico { font-size: 12px; color: var(--ink3); flex-shrink: 0; }
.ch-search-input {
    flex: 1; border: none; outline: none;
    font-size: 12.5px; font-weight: 500;
    font-family: 'Montserrat', sans-serif;
    color: var(--ink); background: transparent;
}
.ch-search-input::placeholder { color: var(--ink3); }

/* User list */
.ch-user-list {
    flex: 1; overflow-y: auto;
    scrollbar-width: thin; scrollbar-color: #cdd0d8 var(--bg);
    padding: 8px;
}
.ch-user-list::-webkit-scrollbar { width: 4px; }
.ch-user-list::-webkit-scrollbar-thumb { background: #cdd0d8; border-radius: 9999px; }

.ch-user-item {
    display: flex; align-items: center; gap: 10px;
    padding: 10px 12px; border-radius: var(--rlg);
    cursor: pointer; transition: all .13s;
    margin-bottom: 2px; text-decoration: none;
    border: 1px solid transparent;
}
.ch-user-item:hover  { background: var(--blt); border-color: var(--bbd); }
.ch-user-item.active { background: var(--blt); border-color: var(--blue); }
.ch-user-item.active .ch-user-name { color: var(--blue); }

.ch-user-av {
    position: relative; flex-shrink: 0;
}
.ch-user-av img {
    width: 40px; height: 40px; border-radius: 11px;
    object-fit: cover; display: block;
    border: 2px solid var(--bd2);
}
.ch-user-av-fallback {
    width: 40px; height: 40px; border-radius: 11px;
    background: linear-gradient(135deg,#1855e0,#5b8af7);
    display: flex; align-items: center; justify-content: center;
    font-size: 14px; font-weight: 800; color: #fff;
    border: 2px solid var(--bd2);
}
.ch-user-dot {
    position: absolute; bottom: -1px; right: -1px;
    width: 10px; height: 10px; border-radius: 50%;
    background: var(--grn); border: 2px solid var(--surf);
}

.ch-user-info { flex: 1; min-width: 0; }
.ch-user-name { font-size: 12.5px; font-weight: 700; color: var(--ink); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.ch-user-co   { font-size: 11px; font-weight: 500; color: var(--ink3); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-top: 1px; }
.ch-unread {
    font-size: 9.5px; font-weight: 800; min-width: 18px; height: 18px;
    border-radius: 9999px; background: var(--red); color: #fff;
    display: flex; align-items: center; justify-content: center;
    padding: 0 4px;
}

.ch-empty-users {
    text-align: center; padding: 40px 16px; color: var(--ink3);
}
.ch-empty-users i { font-size: 24px; opacity: .3; display: block; margin-bottom: 10px; }
.ch-empty-users p { font-size: 12.5px; font-weight: 600; }

/* ════════════════════════════════
   CHAT AREA
════════════════════════════════ */
.ch-main {
    flex: 1; display: flex; flex-direction: column; min-width: 0; min-height: 0;
}

/* Chat Header */
.ch-conv-head {
    display: flex; align-items: center; gap: 12px;
    padding: 12px 20px; border-bottom: 1px solid var(--bd2);
    background: var(--surf); flex-shrink: 0;
}
.ch-conv-av {
    position: relative; flex-shrink: 0;
}
.ch-conv-av img {
    width: 42px; height: 42px; border-radius: 12px;
    object-fit: cover; border: 2px solid var(--bd);
}
.ch-conv-av-fallback {
    width: 42px; height: 42px; border-radius: 12px;
    background: linear-gradient(135deg,#1855e0,#5b8af7);
    display: flex; align-items: center; justify-content: center;
    font-size: 14px; font-weight: 800; color: #fff;
}
.ch-conv-dot {
    position: absolute; bottom: -1px; right: -1px;
    width: 11px; height: 11px; border-radius: 50%;
    background: var(--grn); border: 2px solid var(--surf);
}
.ch-conv-name { font-size: 14px; font-weight: 800; color: var(--ink); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.ch-conv-status { font-size: 11px; font-weight: 600; color: var(--grn); display: flex; align-items: center; gap: 4px; margin-top: 1px; }
.ch-conv-status i { font-size: 7px; }
.ch-conn-badge {
    margin-left: auto; flex-shrink: 0;
    font-size: 10.5px; font-weight: 700; padding: 4px 10px;
    border-radius: 9999px; background: var(--glt);
    color: var(--grn); border: 1px solid var(--gbd);
    display: flex; align-items: center; gap: 5px;
}
.ch-conn-badge i { font-size: 7px; }

/* Messages Box */
.ch-messages {
    flex: 1; overflow-y: auto; padding: 20px;
    background: var(--bg); min-height: 0;
    display: flex; flex-direction: column; gap: 10px;
    scrollbar-width: thin; scrollbar-color: #cdd0d8 var(--bg);
}
.ch-messages::-webkit-scrollbar { width: 5px; }
.ch-messages::-webkit-scrollbar-thumb { background: #cdd0d8; border-radius: 9999px; }

/* Loading */
.ch-loading {
    text-align: center; padding: 40px 16px; color: var(--ink3);
    display: flex; flex-direction: column; align-items: center; gap: 12px;
}
.ch-spinner {
    width: 28px; height: 28px; border-radius: 50%;
    border: 3px solid var(--bbd); border-top-color: var(--blue);
    animation: spin .7s linear infinite; flex-shrink: 0;
}
@keyframes spin { to { transform: rotate(360deg); } }

/* Empty chat state */
.ch-no-user {
    flex: 1; display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    color: var(--ink3); padding: 40px;
}
.ch-no-user i { font-size: 32px; opacity: .25; margin-bottom: 14px; }
.ch-no-user-t { font-size: 14px; font-weight: 800; color: var(--ink); margin-bottom: 5px; }
.ch-no-user-s { font-size: 12.5px; font-weight: 500; color: var(--ink3); }

/* Bubbles */
.ch-msg {
    max-width: 72%; padding: 11px 15px;
    border-radius: 16px; position: relative;
    word-break: break-word;
    animation: msgIn .2s ease-out;
    font-size: 13px; font-weight: 500; line-height: 1.5;
    flex-shrink: 0;
}
@keyframes msgIn { from { opacity:0; transform:translateY(6px); } to { opacity:1; } }

.ch-msg.sent {
    background: var(--blue); color: #fff;
    margin-left: auto; border-bottom-right-radius: 5px;
    box-shadow: 0 2px 8px rgba(24,85,224,.25);
}
.ch-msg.recv {
    background: var(--surf); color: var(--ink2);
    margin-right: auto; border-bottom-left-radius: 5px;
    border: 1px solid var(--bd);
    box-shadow: 0 1px 4px rgba(0,0,0,.05);
}
.ch-msg-time {
    font-size: 10px; font-weight: 600; display: block; margin-top: 4px;
    text-align: right;
}
.ch-msg.sent .ch-msg-time { color: rgba(255,255,255,.65); }
.ch-msg.recv .ch-msg-time { color: var(--ink3); }

/* New messages badge */
.ch-new-badge {
    position: absolute; left: 50%; transform: translateX(-50%);
    bottom: 80px; z-index: 10;
    display: none; align-items: center; gap: 5px;
    padding: 7px 16px; border-radius: 9999px;
    background: var(--blue); color: #fff;
    font-size: 12px; font-weight: 700;
    font-family: 'Montserrat', sans-serif;
    border: none; cursor: pointer;
    box-shadow: 0 4px 14px rgba(24,85,224,.35);
    transition: transform .15s;
}
.ch-new-badge:hover { transform: translateX(-50%) translateY(-2px); }
.ch-msgs-wrap {
    flex: 1; display: flex; flex-direction: column; min-height: 0; position: relative;
}

/* Typing indicator */
.ch-typing {
    display: none; padding: 0 20px 8px;
    background: var(--bg); flex-shrink: 0;
}
.ch-typing-inner {
    display: inline-flex; align-items: center; gap: 8px;
    background: var(--surf); border: 1px solid var(--bd);
    border-radius: 12px; padding: 7px 12px;
}
.ch-typing-dots { display: flex; gap: 3px; }
.ch-typing-dots span {
    width: 5px; height: 5px; border-radius: 50%; background: var(--ink3);
    animation: typDot 1.4s infinite ease-in-out;
}
.ch-typing-dots span:nth-child(1) { animation-delay: -.32s; }
.ch-typing-dots span:nth-child(2) { animation-delay: -.16s; }
@keyframes typDot {
    0%,80%,100% { transform:scale(.7); opacity:.4; }
    40%          { transform:scale(1);  opacity:1; }
}
.ch-typing-lbl { font-size: 11.5px; font-weight: 600; color: var(--ink3); }

/* Composer */
.ch-composer {
    padding: 12px 18px; border-top: 1px solid var(--bd2);
    background: var(--surf); flex-shrink: 0;
}
.ch-composer-inner {
    display: flex; align-items: center; gap: 10px;
    background: var(--bg); border: 1px solid var(--bd);
    border-radius: 9999px; padding: 6px 8px 6px 16px;
    transition: border-color .15s, box-shadow .15s;
}
.ch-composer-inner:focus-within {
    border-color: var(--blue); box-shadow: 0 0 0 3px rgba(24,85,224,.09);
}
.ch-msg-input {
    flex: 1; border: none; outline: none; background: transparent;
    font-size: 13px; font-weight: 500; font-family: 'Montserrat', sans-serif;
    color: var(--ink);
}
.ch-msg-input::placeholder { color: var(--ink3); }
.ch-send-btn {
    width: 36px; height: 36px; border-radius: 50%; flex-shrink: 0;
    background: var(--blue); border: none; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 13px; transition: background .13s;
}
.ch-send-btn:hover   { background: #1344c2; }
.ch-send-btn:disabled{ background: var(--bd); cursor: not-allowed; }
.ch-composer-hint { font-size: 10.5px; font-weight: 600; color: var(--ink3); margin-top: 6px; padding-left: 4px; }

/* ── MOBILE ── */
@media (max-width: 768px) {
    .ch-page { padding: 0; height: calc(100vh - 56px); }
    .ch-shell { border-radius: 0; border: none; }
    .ch-sidebar { position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 50; transform: translateX(-100%); transition: transform .25s ease; }
    .ch-sidebar.open { transform: translateX(0); }
    .ch-mobile-back { display: flex; }
}
@media (min-width: 769px) {
    .ch-mobile-show-btn,
    .ch-mobile-back { display: none !important; }
}
.ch-mobile-back {
    display: none;
    width: 32px; height: 32px; border-radius: 8px;
    background: var(--bg); border: 1px solid var(--bd);
    align-items: center; justify-content: center;
    color: var(--ink2); font-size: 13px; cursor: pointer;
    transition: all .13s; flex-shrink: 0;
}
.ch-mobile-back:hover { background: var(--blt); color: var(--blue); }
.ch-mobile-show-btn {
    display: none; align-items: center; gap: 5px;
    padding: 6px 12px; border-radius: var(--r);
    background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.12);
    color: rgba(255,255,255,.6); font-size: 12px; font-weight: 600;
    font-family: 'Montserrat', sans-serif; cursor: pointer;
}
</style>

<div class="ch-page">
<div class="ch-shell">

    {{-- ════════ SIDEBAR ════════ --}}
    <aside class="ch-sidebar" id="ch-sidebar">

        <div class="ch-sidebar-head">
            <div style="display:flex;align-items:center;justify-content:space-between">
                <div>
                    <div class="ch-sidebar-title">Contacts</div>
                    <div class="ch-sidebar-sub">{{ $users->count() }} {{ Str::plural('user', $users->count()) }}</div>
                </div>
                {{-- Mobile close --}}
                <button type="button" style="background:none;border:none;cursor:pointer;color:var(--ink3);font-size:16px;display:none" id="ch-close-sidebar" class="ch-mobile-show-btn" onclick="chCloseSidebar()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        <div class="ch-search-wrap">
            <i class="fas fa-search ch-search-ico"></i>
            <input type="text" id="ch-search" class="ch-search-input"
                   placeholder="Search contacts…" oninput="chSearch(this.value)"
                   {{ !$hasUsers ? 'disabled' : '' }}>
        </div>

        <div class="ch-user-list" id="ch-user-list">
            @forelse($users as $u)
            @php
                $uName = trim($u->name.' '.($u->last_name ?? ''));
                $uAv = $u->profile_photo
                    ? asset('storage/'.$u->profile_photo)
                    : 'https://ui-avatars.com/api/?name='.urlencode($uName).'&background=1855e0&color=fff&bold=true';
            @endphp
            <a href="#" class="ch-user-item {{ $loop->first ? 'active' : '' }}"
               data-id="{{ $u->id }}"
               data-name="{{ $uName }}"
               data-company="{{ $u->company_name ?? '' }}"
               data-avatar="{{ $uAv }}"
               onclick="chSelectUser(this); return false;">
                <div class="ch-user-av">
                    <img src="{{ $uAv }}" alt="{{ $uName }}" onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                    <div class="ch-user-av-fallback" style="display:none">{{ strtoupper(substr($uName,0,1)) }}</div>
                    <span class="ch-user-dot"></span>
                </div>
                <div class="ch-user-info">
                    <div class="ch-user-name">{{ $uName }}</div>
                    <div class="ch-user-co">{{ $u->company_name ?? '' }}</div>
                </div>
                <span class="ch-unread d-none" data-unread-badge="{{ $u->id }}">0</span>
            </a>
            @empty
            <div class="ch-empty-users">
                <i class="fas fa-users"></i>
                <p>No users available</p>
            </div>
            @endforelse
        </div>
    </aside>

    {{-- ════════ MAIN ════════ --}}
    <div class="ch-main">

        {{-- Conv Header --}}
        <div class="ch-conv-head">
            <button type="button" class="ch-mobile-back" id="ch-back" onclick="chOpenSidebar()">
                <i class="fas fa-arrow-left"></i>
            </button>

            <div class="ch-conv-av">
                <img id="ch-head-img" src="{{ $avatarUrl }}" alt="{{ $fullName }}"
                     onerror="this.style.display='none';document.getElementById('ch-head-fallback').style.display='flex'">
                <div class="ch-conv-av-fallback" id="ch-head-fallback" style="display:none">
                    {{ strtoupper(substr($fullName,0,1)) }}
                </div>
                <span class="ch-conv-dot"></span>
            </div>

            <div style="flex:1;min-width:0">
                <div class="ch-conv-name" id="ch-head-name">{{ $firstUser ? $fullName : 'No contacts' }}</div>
                <div class="ch-conv-status">
                    <i class="fas fa-circle"></i>
                    <span id="ch-head-status">{{ $hasUsers ? 'Online' : 'No users available' }}</span>
                </div>
            </div>

            <span class="ch-conn-badge" id="ch-conn-badge">
                <i class="fas fa-circle"></i> Connected
            </span>
        </div>

        {{-- Messages --}}
        <div class="ch-msgs-wrap">
            <div class="ch-messages" id="ch-box" role="log" aria-live="polite">
                @if($hasUsers)
                <div class="ch-loading" id="ch-loading">
                    <div class="ch-spinner"></div>
                    <span style="font-size:12.5px;font-weight:600">Loading conversation…</span>
                </div>
                @else
                <div class="ch-no-user">
                    <i class="fas fa-comments"></i>
                    <div class="ch-no-user-t">No users available</div>
                    <div class="ch-no-user-s">There are no users to chat with at the moment.</div>
                </div>
                @endif
            </div>

            <button type="button" class="ch-new-badge" id="ch-new-badge" onclick="chScrollBottom(true)">
                <i class="fas fa-chevron-down"></i> New messages
            </button>
        </div>

        {{-- Typing --}}
        <div class="ch-typing" id="ch-typing">
            <div class="ch-typing-inner">
                <div class="ch-typing-dots"><span></span><span></span><span></span></div>
                <span class="ch-typing-lbl">Typing…</span>
            </div>
        </div>

        {{-- Composer --}}
        <div class="ch-composer">
            <div class="ch-composer-inner">
                <input type="text" id="ch-input" class="ch-msg-input"
                       placeholder="Type your message…" autocomplete="off"
                       {{ !$hasUsers ? 'disabled' : '' }}>
                <button type="button" class="ch-send-btn" id="ch-send"
                        onclick="chSend()" {{ !$hasUsers ? 'disabled' : '' }}>
                    <i class="fas fa-paper-plane" style="font-size:12px"></i>
                </button>
            </div>
            <div class="ch-composer-hint">
                <i class="fas fa-info-circle" style="margin-right:4px"></i>Press Enter to send
            </div>
        </div>

    </div>
</div>
</div>

{{-- Hidden select for receiver --}}
<select id="receiver_id" style="display:none" {{ !$hasUsers ? 'disabled' : '' }}>
    @forelse($users as $u)
    <option value="{{ $u->id }}"
        data-name="{{ trim($u->name.' '.($u->last_name ?? '')) }}"
        data-company="{{ $u->company_name ?? '' }}"
        data-avatar="{{ $u->profile_photo ? asset('storage/'.$u->profile_photo) : 'https://ui-avatars.com/api/?name='.urlencode($u->name).'&background=1855e0&color=fff&bold=true' }}"
        @selected($firstUser && $u->id === $firstUser->id)>
        {{ $u->name }} {{ $u->last_name ?? '' }}
    </option>
    @empty
    <option disabled selected>No users</option>
    @endforelse
</select>

<script>
/* ════════════════════════════════
   STATE
════════════════════════════════ */
const authUserId    = {{ auth()->id() }};
const receiverSel   = document.getElementById('receiver_id');
const chBox         = document.getElementById('ch-box');
const chInput       = document.getElementById('ch-input');
const chHeadName    = document.getElementById('ch-head-name');
const chHeadImg     = document.getElementById('ch-head-img');
const chHeadFallbk  = document.getElementById('ch-head-fallback');
const chNewBadge    = document.getElementById('ch-new-badge');
const chSidebar     = document.getElementById('ch-sidebar');

let currentUserId = receiverSel?.value || null;
let lastId        = 0;
let pollTimer     = null;
const rendered    = new Set();

const usingEcho = !!(window.Echo && window.Echo.connector);
let echoChannel = null;

/* ════════════════════════════════
   UTILS
════════════════════════════════ */
function esc(s){ return (s ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c])); }

function fmtDate(d){
    const dt = new Date(d), now = new Date();
    if (dt.toDateString() === now.toDateString())
        return dt.toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'});
    return dt.toLocaleDateString([], {month:'short', day:'numeric'});
}

function nearBottom(){
    return chBox.scrollHeight - chBox.scrollTop - chBox.clientHeight <= 80;
}

function chScrollBottom(force=false){
    if (force || nearBottom()){
        chBox.scrollTop = chBox.scrollHeight;
        chNewBadge.style.display = 'none';
    } else {
        chNewBadge.style.display = 'flex';
    }
}

/* ════════════════════════════════
   RENDER MESSAGE
════════════════════════════════ */
function appendMsg(m){
    if (!m || m.id == null || rendered.has(m.id)) return;
    rendered.add(m.id);
    const isMe = m.sender_id === authUserId;
    const div  = document.createElement('div');
    div.className = `ch-msg ${isMe ? 'sent' : 'recv'}`;
    div.dataset.id = m.id;
    div.innerHTML  = `${esc(m.message)}<small class="ch-msg-time">${fmtDate(m.created_at)}</small>`;
    const stick = nearBottom();
    chBox.appendChild(div);
    if (m.id > lastId) lastId = m.id;
    if (stick || isMe) chScrollBottom(true); else chScrollBottom(false);
}

/* ════════════════════════════════
   LOAD CONVERSATION
════════════════════════════════ */
async function chLoad(userId){
    currentUserId = userId;
    lastId = 0; rendered.clear();

    chBox.innerHTML = `<div class="ch-loading" id="ch-loading"><div class="ch-spinner"></div><span style="font-size:12.5px;font-weight:600">Loading conversation…</span></div>`;

    try {
        const res = await fetch(`/superadmin/chat/${userId}`, {headers:{'X-Requested-With':'XMLHttpRequest'}});
        if (!res.ok) throw new Error();
        const data = await res.json();
        const list = Array.isArray(data) ? data : (data.messages ?? []);

        chBox.innerHTML = '';
        if (!list.length) {
            chBox.innerHTML = `<div class="ch-no-user" style="flex:0;margin:auto"><i class="fas fa-comment-slash"></i><div class="ch-no-user-t">No messages yet</div><div class="ch-no-user-s">Start the conversation below</div></div>`;
        } else {
            list.forEach(appendMsg);
        }

        await fetch(`/superadmin/chat/${userId}/read`, {
            method:'POST',
            headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','X-Requested-With':'XMLHttpRequest'}
        });

        if (window.innerWidth < 769) chCloseSidebar();
        chStartRealtime();

    } catch(e) {
        chBox.innerHTML = `<div style="padding:20px;text-align:center;color:var(--red);font-size:13px;font-weight:600"><i class="fas fa-exclamation-circle"></i> Could not load messages.</div>`;
    }
}

/* ════════════════════════════════
   SEND
════════════════════════════════ */
async function chSend(){
    const rid = receiverSel?.value;
    const msg = chInput.value.trim();
    if (!rid || !msg) { chInput.focus(); return; }

    chInput.value = '';
    try {
        const res = await fetch('/superadmin/chat/send', {
            method:'POST',
            headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}','X-Requested-With':'XMLHttpRequest','Accept':'application/json'},
            body: JSON.stringify({receiver_id:rid, message:msg})
        });
        if (!res.ok) throw new Error();
        appendMsg(await res.json());
        chInput.focus();
    } catch(e) { chInput.value = msg; alert('Could not send message.'); }
}

/* ════════════════════════════════
   REALTIME / POLL
════════════════════════════════ */
function chStartRealtime(){
    chStopPoll();
    if (usingEcho) chSubscribeEcho(); else chStartPoll();
}

function chSubscribeEcho(){
    try { echoChannel?.stopListening('.message.created'); window.Echo.leave(`user.${authUserId}`); } catch{}
    echoChannel = window.Echo.private(`user.${authUserId}`);
    echoChannel.listen('.message.created', e => {
        const p = e.message ?? e;
        const s = p.sender_id, r = p.receiver_id;
        if ((s===authUserId && r===Number(currentUserId)) || (s===Number(currentUserId) && r===authUserId))
            appendMsg(p);
    });
}

function chStartPoll(){
    chStopPoll();
    const loop = async () => {
        try {
            const res = await fetch(`/superadmin/chat/${currentUserId}?after_id=${lastId}`, {headers:{'X-Requested-With':'XMLHttpRequest'}});
            if (res.ok) { const d = await res.json(); (d.messages ?? []).forEach(appendMsg); }
        } catch {}
        pollTimer = setTimeout(loop, 5000);
    };
    loop();
}
function chStopPoll(){ clearTimeout(pollTimer); pollTimer = null; }

/* ════════════════════════════════
   USER SELECTION
════════════════════════════════ */
function chSelectUser(el){
    const id = el.dataset.id;
    document.querySelectorAll('.ch-user-item').forEach(a => a.classList.remove('active'));
    el.classList.add('active');

    if (receiverSel) receiverSel.value = id;

    // update header
    const name = el.dataset.name;
    const av   = el.dataset.avatar;
    chHeadName.textContent = name;
    chHeadImg.src = av;
    chHeadImg.style.display = '';
    chHeadFallbk.style.display = 'none';
    chHeadImg.onerror = () => { chHeadImg.style.display='none'; chHeadFallbk.textContent = name[0].toUpperCase(); chHeadFallbk.style.display='flex'; };

    chLoad(id);
}

/* ════════════════════════════════
   SEARCH
════════════════════════════════ */
function chSearch(q){
    const val = q.trim().toLowerCase();
    document.querySelectorAll('.ch-user-item').forEach(a => {
        const match = !val || (a.dataset.name + ' ' + a.dataset.company).toLowerCase().includes(val);
        a.style.display = match ? '' : 'none';
    });
}

/* ════════════════════════════════
   MOBILE SIDEBAR
════════════════════════════════ */
function chOpenSidebar()  { chSidebar.classList.add('open'); }
function chCloseSidebar() { chSidebar.classList.remove('open'); }

/* ════════════════════════════════
   EVENTS
════════════════════════════════ */
chInput.addEventListener('keypress', e => { if (e.key === 'Enter') { e.preventDefault(); chSend(); } });

document.addEventListener('visibilitychange', () => {
    if (document.hidden) chStopPoll(); else if (currentUserId) chStartRealtime();
});

/* ════════════════════════════════
   INIT
════════════════════════════════ */
document.addEventListener('DOMContentLoaded', () => {
    if (receiverSel?.value) {
        currentUserId = receiverSel.value;
        chLoad(currentUserId);
    }
    if (window.innerWidth < 769) chOpenSidebar();
});
</script>

@endsection