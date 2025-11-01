@extends('layouts.app')

@section('content')
@php
    // Asegura que $users exista y haya al menos un admin
    $users = $users ?? collect();
    $firstUser = $users instanceof \Illuminate\Support\Collection ? $users->first() : (is_array($users) ? ($users[0] ?? null) : null);
    $fullName = trim(($firstUser->name ?? '').' '.($firstUser->last_name ?? '')) ?: 'Admin';
    $avatarUrl = $firstUser->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($fullName).'&background=random';
@endphp

<div class="container py-4">
  <div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
      <div class="d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
          <h4 class="mb-0">Chat with the Administrator</h4>
          <span id="connection-status" class="badge bg-success ms-2">Conectado</span>
        </div>
        <button id="mark-read-btn" type="button" class="btn btn-sm btn-light text-primary d-none">
          <i class="fas fa-check-double me-1"></i> Marcar leído
        </button>
      </div>
    </div>

    <div class="card-body pb-2">

      {{-- Selector oculto (compatibilidad con backend) --}}
      <div class="mb-3" style="display:none;">
        <label for="receiver_id" class="form-label fw-bold">Seleccionar contacto:</label>
        <select id="receiver_id" class="form-select shadow-sm">
          @foreach($users as $u)
            <option value="{{ $u->id }}"
              data-avatar="{{ $u->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($u->name).'&background=random' }}">
              {{ $u->name }} {{ $u->last_name }} - {{ $u->company_name }}
            </option>
          @endforeach
        </select>
      </div>

        {{-- Encabezado del contacto actual --}}
        <div class="d-flex align-items-center mb-3">
        <img id="current-avatar" src="{{ $avatarUrl }}" class="rounded-circle me-2 shadow-sm" width="40" height="40" alt="avatar del administrador">
        <div class="min-w-0">
            <h6 id="current-contact" class="mb-0 fw-semibold peer-name-ellipsis"
                title="{{ ($firstUser?->name ?? 'Sin administrador').' '.($firstUser?->last_name ?? '') }}">
            {{ $firstUser?->name ?? 'Sin administrador' }} {{ $firstUser?->last_name ?? '' }}
            </h6>
            <small id="last-seen" class="text-muted">{{ $firstUser ? 'Online' : 'Offline' }}</small>
        </div>
        </div>

        {{-- PANEL FLEX: mensajes (flex:1) + composer fijo al fondo --}}
        <div class="chat-panel position-relative">
        {{-- Área de mensajes --}}
        <div id="chat-box"
            class="chat-messages border rounded p-3 mb-3 bg-light"
            role="log" aria-live="polite" aria-relevant="additions" aria-busy="true">
            @if($firstUser)
            <div class="text-center text-muted py-4" id="chat-loading">
                <div class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></div>
                <span class="ms-2">Loading chat history...</span>
            </div>
            @else
            <div class="text-center text-muted py-4">No administrators available.</div>
            @endif
        </div>

        {{-- Badge “New” --}}
        <button id="new-badge" type="button" class="btn btn-sm btn-primary shadow new-badge">New</button>

        {{-- Indicador “escribiendo…” --}}
        <div id="typing-indicator" class="badge-typing" style="display:none;">Typing in...</div>

        {{-- Composer (ya NO sticky: queda fijo por el flex) --}}
        <form id="chat-form" class="chat-input input-group shadow-sm mt-2" autocomplete="off"
                onsubmit="event.preventDefault(); sendMessage();">
            <input type="text" id="message" class="form-control"
                placeholder="Write a message..." aria-label="Write a message..."
                @disabled(!$firstUser) />
            <button class="btn btn-primary" type="submit" id="send-btn" @disabled(!$firstUser)>
            <i class="fas fa-paper-plane"></i> Send
            </button>
        </form>
        <small class="text-muted d-block mt-1">Press Enter to send</small>
        </div>


    </div>
  </div>
</div>



{{-- Estilos mejorados --}}
<style>
  /* ========= Paleta blanco & azul ========= */
  :root{
    --blue-500:#1e66ff;   --blue-600:#1558e6;
    --blue-050:#f2f6ff;   --blue-100:#e8f0ff;

    --chat-bg:#f7f8fa;
    --bubble-sent:linear-gradient(135deg,#2d7cff 0%, #1664ea 100%);
    --bubble-recv:#ffffff;

    --text:#0f172a;       --text-muted:#637085;
    --text-sent:#ffffff;  --text-recv:#1f2937;

    --border:#e6eaf1;
    --shadow-soft:0 6px 18px rgba(18,42,90,.07);

    --radius-xl:18px; --radius-2xl:22px;
    --space-1:.25rem; --space-2:.5rem; --space-3:.75rem; --space-4:1rem;
    --max-bubble:78vw; /* móvil */
  }
  @media (min-width:768px){ :root{ --max-bubble: 60%; } }
  @media (prefers-color-scheme: dark){
    :root{
      --chat-bg:#0b1220; --bubble-recv:#111827; --text:#e5e7eb; --text-recv:#e5e7eb;
      --border:#1f2a44; --text-muted:#94a3af; --shadow-soft:0 6px 18px rgba(0,0,0,.35);
      --blue-500:#4b7eff; --blue-600:#3b6df0; --blue-050:#0e1a33; --blue-100:#0f2246;
    }
  }

  /* ===== Encabezado ===== */
  .card-header.bg-primary{ background: linear-gradient(180deg,#0a3fb1 0%,#1558e6 100%) !important; }
  #connection-status.badge{ border:1px solid rgba(255,255,255,.35); }

  /* ===== Nombre visible (ellipsis correcto) ===== */
  .min-w-0{ min-width:0; }
  .peer-name-ellipsis{
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    max-width: 100%; color: #000000e3; /* header azul → nombre claro */
  }
  /* Si prefieres el nombre del peer oscuro, quita la línea de color y usa text-dark en el HTML */

  /* ===== Área de mensajes ===== */
  .chat-messages{
    height: 440px; background: var(--chat-bg);
    border:1px solid var(--border) !important; border-radius:14px;
    overflow-y:auto; scroll-behavior:smooth;
  }
  .chat-messages.bg-light{ background: var(--chat-bg) !important; }

  /* ===== Burbujas ===== */
  .message{
    max-width: var(--max-bubble);
    margin-bottom: var(--space-3);
    padding:.7rem 1rem .5rem;
    border-radius: var(--radius-xl);
    position: relative; word-wrap: break-word; line-height:1.35;
    box-shadow: var(--shadow-soft);
  }
  .message-sent{
    margin-left:auto; background: var(--bubble-sent);
    color: var(--text-sent); border-bottom-right-radius:8px;
  }
  .message-received{
    margin-right:auto; background: var(--bubble-recv);
    color: var(--text-recv); border:1px solid var(--border); border-bottom-left-radius:8px;
  }
  .message-time{ font-size:.72rem; color:var(--text-muted); text-align:right; margin-top:.35rem; }
  .message-sent .message-time{ color:rgba(255,255,255,.85); }

  /* ===== Composer ===== */
  .chat-input{
    border:1px solid var(--border); border-radius:12px;
    overflow:hidden; background:#fff; box-shadow:var(--shadow-soft);
  }
  .chat-input .form-control{ border:none!important; box-shadow:none!important; padding:.8rem .95rem; background:transparent; color:inherit; }
  .chat-input .btn{ border-left:1px solid var(--border); background:var(--blue-500); }
  .chat-input .btn:hover{ background:var(--blue-600); }

  /* ===== Badge “New” fijo y discreto ===== */
  .new-badge{
    position:absolute; right:12px; bottom:120px; display:none; z-index:10;
    background:var(--blue-500); border:none;
  }
  .new-badge:hover{ background:var(--blue-600); }

  /* ===== Typing ===== */
  .badge-typing{
    position:absolute; left:50%; transform:translateX(-50%);
    bottom:96px; background:#0f172a; color:#fff; border-radius:999px;
    padding:.25rem .6rem; font-size:.72rem; box-shadow:var(--shadow-soft);
  }
  @media (prefers-color-scheme: dark){ .badge-typing{ background:#1f2a44; } }

  /* ===== Scrollbar ===== */
  #chat-box::-webkit-scrollbar{ width:10px; }
  #chat-box::-webkit-scrollbar-track{ background:transparent; }
  #chat-box::-webkit-scrollbar-thumb{ background:#cbd5e1; border-radius:6px; }
  #chat-box::-webkit-scrollbar-thumb:hover{ background:#94a3b8; }
  @media (prefers-color-scheme: dark){
    #chat-box::-webkit-scrollbar-thumb{ background:#334155; }
    #chat-box::-webkit-scrollbar-thumb:hover{ background:#475569; }
  }

  /* ===== Animación ===== */
  .message-appear{ animation: msgIn .16s ease-out both; }
  @keyframes msgIn{ from{ transform:translateY(4px); opacity:0; } to{ transform:translateY(0); opacity:1; } }
</style>



{{-- Script: tiempo real con autoscroll inteligente + dedupe + fallback a polling --}}
<script>
  const receiverSelect   = document.getElementById('receiver_id');
  const chatBox          = document.getElementById('chat-box');
  const messageInput     = document.getElementById('message');
  const sendBtn          = document.getElementById('send-btn');
  const currentAvatar    = document.getElementById('current-avatar');
  const currentContact   = document.getElementById('current-contact');
  const lastSeen         = document.getElementById('last-seen');
  const connectionStatus = document.getElementById('connection-status');
  const newBadge         = document.getElementById('new-badge');
  const authUserId       = {{ auth()->id() }};

  let currentUserId = receiverSelect?.value || null;
  let lastId = 0;
  let pollTimer = null;

  // Echo
  let echoChannel = null;
  const usingEcho = !!(window.Echo && window.Echo.connector);

  // --- DEDUPE: ids ya pintados ---
  const renderedIds = new Set();
  function resetRendered(){ renderedIds.clear(); }

  // Utils
  function escapeHtml(str){ return (str ?? '').replace(/[&<>"']/g, s => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[s])); }
  function formatDate(d){
    const date = new Date(d), now = new Date();
    if (date.toDateString() === now.toDateString())
      return date.toLocaleTimeString([], {hour:'2-digit',minute:'2-digit'});
    if (date.getFullYear() === now.getFullYear())
      return date.toLocaleDateString([], {month:'short',day:'numeric'});
    return date.toLocaleDateString([], {year:'numeric',month:'short',day:'numeric'});
  }
  function isNearBottom(){
    const threshold = 60; // px
    return chatBox.scrollHeight - chatBox.scrollTop - chatBox.clientHeight <= threshold;
  }
  function scrollToBottom(force=false){
    if (force || isNearBottom()){
      chatBox.scrollTop = chatBox.scrollHeight;
      newBadge.style.display = 'none';
    } else {
      newBadge.style.display = 'inline-block';
    }
  }

  function appendMessage(m){
    if (!m || m.id == null) return;

    // DEDUPE por id
    if (renderedIds.has(m.id)) return;
    renderedIds.add(m.id);

    const isMe = m.sender_id === authUserId;
    const div = document.createElement('div');
    div.className = `message ${isMe ? 'message-sent' : 'message-received'} message-appear`;
    div.dataset.id = m.id;
    div.innerHTML = `
      <div>${escapeHtml(m.message)}</div>
      <small class="message-time">${formatDate(m.created_at)}</small>
    `;
    const shouldStick = isNearBottom();
    chatBox.appendChild(div);
    if (typeof m.id === 'number' && m.id > lastId) lastId = m.id;
    if (shouldStick || isMe) scrollToBottom(true); else scrollToBottom(false);
  }

  async function loadConversation(userId){
    currentUserId = userId;
    lastId = 0;
    resetRendered();
    chatBox.innerHTML = `
      <div class="text-center text-muted py-4">
        <div class="spinner-border spinner-border-sm" role="status"><span class="visually-hidden">Loading…</span></div>
        Loading chat history...
      </div>
    `;
    try{
      const res = await fetch(`/chat/${currentUserId}`, {headers:{'X-Requested-With':'XMLHttpRequest'}});
      if (!res.ok) throw new Error('load failed');
      const data = await res.json();
      const list    = Array.isArray(data) ? data : (data.messages || []);
      const srvLast = Array.isArray(data) ? (data.at(-1)?.id || 0) : (data.last_id || 0);

      chatBox.innerHTML = '';
      list.forEach(appendMessage);
      lastId = srvLast;

      // marca como leídos (admin -> user)
      await fetch(`/chat/${currentUserId}/read`, {
        method: 'POST',
        headers: {'X-CSRF-TOKEN':'{{ csrf_token() }}','X-Requested-With':'XMLHttpRequest'}
      });

      startRealtimeOrPoll();
    }catch(e){
      chatBox.innerHTML = `<div class="alert alert-danger">No se pudieron cargar los mensajes.</div>`;
      console.error(e);
    }
  }

  async function sendMessage(){
    const receiver_id = receiverSelect?.value;
    const message = messageInput.value.trim();
    if (!receiver_id || !message){ messageInput.focus(); return; }

    sendBtn.disabled = true;
    const original = sendBtn.innerHTML;
    sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';

    try{
      const res = await fetch('/chat/send', {
        method:'POST',
        headers:{
          'Content-Type':'application/json',
          'X-CSRF-TOKEN':'{{ csrf_token() }}',
          'X-Requested-With':'XMLHttpRequest',
          'Accept':'application/json'
        },
        body: JSON.stringify({receiver_id, message})
      });
      if (!res.ok) throw new Error('send failed');
      const created = await res.json();

      // SOLO append local (no recargar toda la conversación)
      appendMessage(created);
      messageInput.value = '';
      messageInput.focus();
    }catch(e){
      alert('No se pudo enviar el mensaje.');
      console.error(e);
    }finally{
      sendBtn.disabled = false;
      sendBtn.innerHTML = original;
    }
  }

  // -------- Realtime (Echo) o fallback Polling --------
  function startRealtimeOrPoll(){
    stopPoll();
    if (usingEcho){
      subscribeEcho();
    } else {
      startPoll();
    }
  }

  function subscribeEcho(){
    // desuscribir canal previo si existe
    if (echoChannel) {
      try { echoChannel.stopListening('.message.created'); } catch {}
      try {
        // algunos drivers usan 'private-' en el nombre; dejamos ambos por seguridad
        window.Echo.leave(`private-user.${authUserId}`);
        window.Echo.leave(`user.${authUserId}`);
      } catch {}
    }

    echoChannel = window.Echo.private(`user.${authUserId}`);
    echoChannel.listen('.message.created', (e) => {
      const payload = e.message ?? e;
      const s = payload.sender_id, r = payload.receiver_id;

      // solo si pertenece a la conversación abierta
      const belongs =
        (s === authUserId && r === Number(currentUserId)) ||
        (s === Number(currentUserId) && r === authUserId);
      if (!belongs) return;

      appendMessage(payload); // pasa por DEDUPE
    });
  }

  function startPoll(){
    stopPoll();
    const loop = async () => {
      if (!currentUserId) return;
      try{
        const res = await fetch(`/chat/${currentUserId}?after_id=${lastId}`, {headers:{'X-Requested-With':'XMLHttpRequest'}});
        if (res.ok){
          const data = await res.json();
          (data.messages || []).forEach(appendMessage); // DEDUPE
        }
      }catch(_){}
      pollTimer = setTimeout(loop, 5000);
    };
    loop();
  }

  function stopPoll(){ if (pollTimer) { clearTimeout(pollTimer); pollTimer = null; } }

  // UI
  sendBtn?.addEventListener('click', (e)=>{ e.preventDefault(); sendMessage(); });
  messageInput?.addEventListener('keypress', e => { if (e.key === 'Enter'){ e.preventDefault(); sendMessage(); } });
  newBadge?.addEventListener('click', () => scrollToBottom(true));

  receiverSelect?.addEventListener('change', () => {
    const opt = receiverSelect.options[receiverSelect.selectedIndex];
    currentAvatar.src = opt.dataset.avatar;
    currentContact.textContent = opt.text.split(' - ')[0];
    lastSeen.textContent = 'Online';
    loadConversation(receiverSelect.value);
  });

  document.addEventListener('visibilitychange', () => {
    if (document.hidden) stopPoll(); else startRealtimeOrPoll();
  });

  // Init
  window.addEventListener('DOMContentLoaded', () => {
    if (receiverSelect?.value){
      loadConversation(receiverSelect.value);
      connectionStatus.textContent = 'Connected';
      connectionStatus.className = 'badge bg-success';
    } else {
      chatBox.innerHTML = `<div class="text-center text-muted py-4">No hay administradores disponibles.</div>`;
      sendBtn?.setAttribute('disabled', 'disabled');
      messageInput?.setAttribute('disabled', 'disabled');
    }
  });
</script>

@endsection
