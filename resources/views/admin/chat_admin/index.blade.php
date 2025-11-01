@extends('admin.layouts.superadmin')

@section('content')

@php
    $users = $users ?? collect();
    $firstUser = $users instanceof \Illuminate\Support\Collection ? $users->first() : (is_array($users) ? ($users[0] ?? null) : null);
    $fullName = trim(($firstUser->name ?? '').' '.($firstUser->last_name ?? '')) ?: 'Usuario';
    $avatarUrl = $firstUser->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($fullName).'&background=random';
    $hasUsers = !is_null($firstUser);
@endphp

<div class="container-fluid py-3 py-md-4">
  <div class="row">
    <div class="col-12">
      <div class="card chat-admin-card border-0 shadow-lg" style="height: 90vh;">
        {{-- Header Mejorado --}}
        <div class="card-header chat-admin-header text-white position-relative overflow-hidden">
          <div class="header-pattern"></div>
          <div class="d-flex justify-content-between align-items-center position-relative">
            <div class="d-flex align-items-center gap-3">
              <div class="d-flex align-items-center">
                <i class="fas fa-comments me-2 fs-5"></i>
                <h4 class="mb-0 fw-bold d-none d-md-block">Administrative Chat</h4>
                <h5 class="mb-0 fw-bold d-md-none">Chat</h5>
              </div>
              <span id="connection-status" class="badge connection-badge">
                <i class="fas fa-circle me-1 small"></i> <span class="d-none d-sm-inline">Connected</span>
              </span>
            </div>
            <div class="d-flex align-items-center gap-2">
              {{-- Botón para mostrar lista de usuarios en móvil --}}
              <button id="show-users-btn" type="button" class="btn btn-sm btn-light text-primary d-md-none">
                <i class="fas fa-users me-1"></i> <span>Contacts</span>
              </button>
              <button id="mark-read-btn" type="button" class="btn btn-mark-read d-none">
                <i class="fas fa-check-double me-1"></i> <span class="d-none d-sm-inline">Mark as read</span>
              </button>
            </div>
          </div>
        </div>

        <div class="card-body p-0 h-100">
          <div class="row g-0 h-100">
            {{-- Panel de usuarios mejorado --}}
            <aside id="users-sidebar" class="col-md-4 border-end bg-light h-100 mobile-users-sidebar">
              <div class="p-3 h-100 d-flex flex-column">
                {{-- Header móvil para lista de usuarios --}}
                <div class="d-flex justify-content-between align-items-center mb-3 d-md-none">
                  <h5 class="mb-0 fw-bold">Contacts</h5>
                  <button id="close-users-btn" type="button" class="btn-close" aria-label="Close"></button>
                </div>

                <div class="input-group input-group-search mb-3">
                  <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                  <input id="user-search" type="text" class="form-control border-start-0" placeholder="Search users..." @unless($hasUsers) disabled @endunless>
                </div>

                <div class="mb-3 d-none d-md-block">
                  <label for="receiver_id" class="form-label fw-semibold text-muted small">Select contact:</label>
                  <select id="receiver_id" class="form-select form-select-sm shadow-sm" @unless($hasUsers) disabled @endunless>
                    @forelse($users as $u)
                      <option value="{{ $u->id }}"
                        data-name="{{ $u->name }} {{ $u->last_name }}"
                        data-company="{{ $u->company_name }}"
                        data-avatar="{{ $u->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($u->name).'&background=random' }}"
                        title="{{ $u->name }} {{ $u->last_name }} — {{ $u->company_name }}"
                        @selected($firstUser && $u->id === $firstUser->id)>
                        {{ $u->name }} {{ $u->last_name }} - {{ $u->company_name }}
                      </option>
                    @empty
                      <option value="" disabled selected>No users available</option>
                    @endforelse
                  </select>
                </div>

                <div class="list-group list-group-flush user-list flex-grow-1" id="user-list" style="overflow-y: auto;">
                  @forelse($users as $u)
                    <a href="#"
                       class="list-group-item list-group-item-action user-list-item rounded mb-2 border-0 mobile-user-item"
                       data-id="{{ $u->id }}"
                       data-name="{{ $u->name }} {{ $u->last_name }}"
                       data-company="{{ $u->company_name }}"
                       data-avatar="{{ $u->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($u->name).'&background=random' }}"
                       title="{{ $u->name }} {{ $u->last_name }} — {{ $u->company_name }}">
                      <div class="d-flex align-items-center">
                        <div class="position-relative">
                          <img src="{{ $u->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($u->name).'&background=random' }}"
                               class="user-avatar rounded-circle me-3 flex-shrink-0" 
                               width="44" height="44" alt="avatar">
                          <span class="position-absolute bottom-0 end-0 bg-success border border-2 border-white rounded-circle"
                                style="width: 10px; height: 10px;"></span>
                        </div>
                        <div class="flex-grow-1 min-w-0">
                          <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="mb-0 fw-semibold text-truncate user-name">
                              {{ $u->name }} {{ $u->last_name }}
                            </h6>
                            <span class="badge bg-danger rounded-pill unread-badge d-none" data-unread-badge="{{ $u->id }}">0</span>
                          </div>
                          <small class="text-muted text-truncate d-block user-company">
                            {{ $u->company_name }}
                          </small>
                          <small class="text-muted user-last-message d-none d-md-block">Click to start conversation</small>
                        </div>
                      </div>
                    </a>
                  @empty
                    <div class="text-center py-5 text-muted">
                      <i class="fas fa-users mb-3 opacity-50" style="font-size: 2rem;"></i>
                      <p class="mb-0">No users available</p>
                    </div>
                  @endforelse
                </div>
              </div>
            </aside>

            {{-- Área de conversación mejorada --}}
            <section id="chat-section" class="col-md-8 h-100 mobile-chat-section">
              <div class="d-flex flex-column h-100">
                {{-- Encabezado del contacto actual --}}
                <div class="chat-header-current p-3 border-bottom bg-white d-flex align-items-center" style="flex-shrink: 0;">
                  {{-- Botón para volver a contactos en móvil --}}
                  <button id="back-to-users" type="button" class="btn btn-sm btn-outline-secondary rounded-circle me-2 d-md-none">
                    <i class="fas fa-arrow-left"></i>
                  </button>
                  <div class="position-relative">
                    <img id="current-avatar" src="{{ $avatarUrl }}" 
                         class="rounded-circle me-3 flex-shrink-0 border border-3 border-white shadow-sm" 
                         width="52" height="52" alt="avatar actual">
                    <span class="position-absolute bottom-0 end-0 bg-success border border-2 border-white rounded-circle"
                          style="width: 12px; height: 12px;"></span>
                  </div>
                  <div class="flex-grow-1 min-w-0">
                    <h5 id="current-contact" class="mb-0 fw-semibold text-dark peer-name"
                        title="{{ ($firstUser?->name ?? 'No contacts').' '.($firstUser?->last_name ?? '') }}">
                      {{ $firstUser?->name ?? 'No contacts' }} {{ $firstUser?->last_name ?? '' }}
                    </h5>
                    <small id="last-seen" class="text-muted d-flex align-items-center gap-1">
                      <i class="fas fa-circle text-success" style="font-size: 0.5rem;"></i>
                      {{ $hasUsers ? 'Online' : 'No users available' }}
                    </small>
                  </div>
                  <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary rounded-circle chat-actions-btn" 
                            type="button" data-bs-toggle="dropdown"
                            style="width: 36px; height: 36px;">
                      <i class="fas fa-ellipsis-v"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                      <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2 opacity-75"></i>View profile</a></li>
                      <li><a class="dropdown-item" href="#"><i class="fas fa-archive me-2 opacity-75"></i>Archive chat</a></li>
                      <li><hr class="dropdown-divider"></li>
                      <li><a class="dropdown-item text-danger" href="#"><i class="fas fa-ban me-2"></i>Block user</a></li>
                    </ul>
                  </div>
                </div>

                {{-- Caja de mensajes + badge Nuevos --}}
                <div class="position-relative flex-grow-1 d-flex flex-column min-h-0">
                  <div id="chat-box"
                       class="flex-grow-1 p-3 p-md-4 chat-messages-container"
                       role="log" aria-live="polite" aria-relevant="additions" 
                       aria-busy="{{ $hasUsers ? 'true' : 'false' }}"
                       style="overflow-y: auto; height: 0; min-height: 0;">
                    @if($hasUsers)
                      <div class="text-center text-muted py-5 d-flex flex-column align-items-center" id="chat-loading">
                        <div class="spinner-border text-primary mb-3" role="status" style="width: 2rem; height: 2rem;">
                          <span class="visually-hidden">Loading...</span>
                        </div>
                        <span class="text-muted">Loading conversation history...</span>
                      </div>
                    @else
                      <div class="text-center text-muted py-5 d-flex flex-column align-items-center justify-content-center h-100">
                        <i class="fas fa-comments mb-3 opacity-50" style="font-size: 3rem;"></i>
                        <h5 class="text-muted mb-2">No users available</h5>
                        <p class="text-muted opacity-75">There are no users to chat with at the moment.</p>
                      </div>
                    @endif
                  </div>

                  <button id="new-badge" type="button" 
                          class="btn btn-primary rounded-pill shadow-sm new-messages-badge border-0 d-none">
                    <i class="fas fa-chevron-down me-1"></i> New messages
                  </button>

                  <div id="typing-indicator" class="typing-indicator d-none">
                    <div class="d-flex align-items-center gap-2 bg-white rounded-pill px-3 py-2 shadow-sm border">
                      <div class="typing-dots">
                        <span></span>
                        <span></span>
                        <span></span>
                      </div>
                      <small class="text-muted">Typing...</small>
                    </div>
                  </div>
                </div>

                {{-- Composer mejorado --}}
                <div class="chat-composer p-3 border-top bg-white" style="flex-shrink: 0;">
                  <form class="input-group chat-input rounded-pill bg-light border-0 px-2" 
                        onsubmit="event.preventDefault(); sendMessage();">
                    <button type="button" class="btn btn-sm btn-link text-muted rounded-pill px-2">
                      <i class="fas fa-paperclip"></i>
                    </button>
                    <input type="text" id="message" class="form-control border-0 bg-transparent shadow-none"
                           placeholder="Type your message..." autocomplete="off" 
                           @unless($hasUsers) disabled @endunless>
                    <button type="button" class="btn btn-sm btn-link text-muted rounded-pill px-2">
                      <i class="far fa-smile"></i>
                    </button>
                    <button class="btn btn-primary rounded-pill px-3 d-flex align-items-center gap-2" 
                            type="submit" @unless($hasUsers) disabled @endunless>
                      <span class="d-none d-sm-inline">Send</span>
                      <i class="fas fa-paper-plane small"></i>
                    </button>
                  </form>
                  <small class="text-muted d-block mt-2 ms-2">
                    <i class="fas fa-info-circle me-1 opacity-75"></i> Press Enter to send
                  </small>
                </div>
              </div>
            </section>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
:root {
  --primary-color: #4361ee;
  --primary-light: #4a6ad1;
  --secondary-color: #6c757d;
  --success-color: #28a745;
  --danger-color: #dc3545;
  --light-bg: #f8f9fa;
  --dark-text: #212529;
  --border-color: #dee2e6;
  --shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
  --radius: 12px;
}

/* Tarjeta principal */
.chat-admin-card {
  border-radius: var(--radius);
  overflow: hidden;
}

/* Header */
.chat-admin-header {
  background: linear-gradient(135deg, var(--primary-color) 0%, #3a56d4 100%);
  border-bottom: none;
  padding: 1rem 1.25rem;
}

.header-pattern {
  position: absolute;
  top: 0;
  right: 0;
  width: 100%;
  height: 100%;
  opacity: 0.1;
  background-image: radial-gradient(circle, rgba(255,255,255,0.2) 1px, transparent 1px);
  background-size: 10px 10px;
}

.connection-badge {
  background-color: rgba(255, 255, 255, 0.2);
  color: rgba(255, 255, 255, 0.9);
  border: 1px solid rgba(255, 255, 255, 0.3);
  border-radius: 20px;
  padding: 6px 12px;
  font-size: 0.85rem;
  font-weight: 500;
}

.btn-mark-read {
  background: rgba(255, 255, 255, 0.2);
  color: white;
  border: 1px solid rgba(255, 255, 255, 0.3);
  border-radius: 20px;
  padding: 6px 12px;
  font-size: 0.85rem;
  transition: all 0.2s ease;
}

.btn-mark-read:hover {
  background: rgba(255, 255, 255, 0.3);
  transform: translateY(-1px);
}

/* Panel de usuarios */
.input-group-search {
  border-radius: 10px;
  overflow: hidden;
}

.input-group-search .form-control:focus {
  box-shadow: none;
  border-color: var(--primary-color);
}

.user-list {
  overflow-y: auto;
}

.user-list-item {
  padding: 12px 15px;
  transition: all 0.2s ease;
  border: none !important;
}

.user-list-item:hover, .user-list-item.active {
  background-color: var(--primary-light);
  transform: translateY(-1px);
  box-shadow: 0 2px 8px rgba(67, 97, 238, 0.15);
}

.user-list-item.active {
  border-left: 3px solid var(--primary-color) !important;
}

.user-avatar {
  object-fit: cover;
  border: 2px solid white;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}

.user-name {
  max-width: 160px;
}

.user-company {
  max-width: 200px;
}

.user-last-message {
  font-size: 0.75rem;
  max-width: 220px;
}

.unread-badge {
  font-size: 0.7rem;
  min-width: 20px;
  height: 20px;
  display: flex;
  align-items: center;
  justify-content: center;
}

/* Encabezado del chat actual */
.chat-header-current {
  position: relative;
}

.chat-header-current::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 0;
  width: 100%;
  height: 1px;
  background: linear-gradient(90deg, transparent, var(--border-color), transparent);
}

.chat-actions-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.2s ease;
}

.chat-actions-btn:hover {
  background-color: var(--primary-light);
  border-color: var(--primary-color);
}

/* Área de mensajes - FIX CRUCIAL */
.chat-messages-container {
  background-color: var(--light-bg);
  background-image: 
    radial-gradient(circle at 100% 100%, rgba(67, 97, 238, 0.03) 0%, transparent 50%),
    radial-gradient(circle at 0% 0%, rgba(67, 97, 238, 0.03) 0%, transparent 50%);
  overflow-y: auto;
  display: flex;
  flex-direction: column;
  gap: 12px;
  /* Estas propiedades son clave para el scroll interno */
  flex: 1 1 auto;
  min-height: 0;
}

/* Mensajes individuales */
.chat-message {
  max-width: 85%;
  padding: 12px 16px;
  border-radius: 18px;
  position: relative;
  word-wrap: break-word;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
  animation: messageAppear 0.25s ease-out;
  flex-shrink: 0;
}

.message-sent {
  background: linear-gradient(135deg, var(--primary-color), #3a56d4);
  color: white;
  margin-left: auto;
  border-bottom-right-radius: 6px;
}

.message-received {
  background: white;
  color: var(--dark-text);
  margin-right: auto;
  border: 1px solid var(--border-color);
  border-bottom-left-radius: 6px;
}

.message-time {
  font-size: 0.75rem;
  opacity: 0.85;
  margin-top: 4px;
  display: block;
  text-align: right;
}

.message-sent .message-time {
  color: rgba(255, 255, 255, 0.85);
}

.message-received .message-time {
  color: var(--secondary-color);
}

@keyframes messageAppear {
  from { 
    opacity: 0; 
    transform: translateY(10px) scale(0.95); 
  }
  to { 
    opacity: 1; 
    transform: translateY(0) scale(1); 
  }
}

/* Badge de nuevos mensajes */
.new-messages-badge {
  position: absolute;
  left: 50%;
  transform: translateX(-50%);
  bottom: 100px;
  padding: 8px 16px;
  font-size: 0.85rem;
  font-weight: 500;
  z-index: 10;
  transition: all 0.2s ease;
}

.new-messages-badge:hover {
  transform: translateX(-50%) translateY(-2px);
  box-shadow: 0 6px 16px rgba(67, 97, 238, 0.4);
}

/* Indicador de typing */
.typing-indicator {
  position: absolute;
  left: 50%;
  transform: translateX(-50%);
  bottom: 90px;
  z-index: 5;
}

.typing-dots {
  display: flex;
  gap: 4px;
}

.typing-dots span {
  width: 6px;
  height: 6px;
  border-radius: 50%;
  background-color: var(--secondary-color);
  animation: typingAnimation 1.4s infinite ease-in-out;
}

.typing-dots span:nth-child(1) { animation-delay: -0.32s; }
.typing-dots span:nth-child(2) { animation-delay: -0.16s; }

@keyframes typingAnimation {
  0%, 80%, 100% { 
    transform: scale(0.8);
    opacity: 0.5;
  }
  40% { 
    transform: scale(1);
    opacity: 1;
  }
}

/* Composer */
.chat-composer {
  border-top: 1px solid var(--border-color);
}

.chat-input {
  transition: all 0.2s ease;
}

.chat-input:focus-within {
  box-shadow: 0 0 0 2px rgba(67, 97, 238, 0.1);
  background: white;
}

/* Scrollbars personalizados */
.chat-messages-container::-webkit-scrollbar {
  width: 6px;
}

.chat-messages-container::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 10px;
}

.chat-messages-container::-webkit-scrollbar-thumb {
  background: #c1c1c1;
  border-radius: 10px;
}

.chat-messages-container::-webkit-scrollbar-thumb:hover {
  background: #a8a8a8;
}

.user-list::-webkit-scrollbar {
  width: 4px;
}

.user-list::-webkit-scrollbar-track {
  background: #f1f1f1;
}

.user-list::-webkit-scrollbar-thumb {
  background: #c1c1c1;
  border-radius: 10px;
}

/* ===== RESPONSIVE DESIGN ===== */

/* Móviles (hasta 767px) */
@media (max-width: 767.98px) {
  .mobile-users-sidebar {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1050;
    background: white;
    transform: translateX(-100%);
    transition: transform 0.3s ease;
  }
  
  .mobile-users-sidebar.active {
    transform: translateX(0);
  }
  
  .mobile-chat-section {
    width: 100%;
  }
  
  .chat-message {
    max-width: 90%;
    padding: 10px 14px;
  }
  
  .user-name {
    max-width: 120px;
  }
  
  .user-company {
    max-width: 160px;
  }
  
  .chat-admin-card {
    height: 95vh;
  }
  
  .chat-header-current {
    padding: 1rem;
  }
}

/* Tablets (768px a 991px) */
@media (min-width: 768px) and (max-width: 991.98px) {
  .chat-message {
    max-width: 80%;
  }
  
  .user-name {
    max-width: 140px;
  }
  
  .user-company {
    max-width: 180px;
  }
}

/* Utilidades */
.min-w-0 {
  min-width: 0;
}

.min-h-0 {
  min-height: 0;
}

.peer-name {
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  max-width: 100%;
  line-height: 1.2;
}

/* Clase para contenedores flex que necesitan scroll interno */
.flex-scroll-container {
  flex: 1 1 auto;
  min-height: 0;
  overflow-y: auto;
}

/* Estados de visibilidad para móviles */
.mobile-users-sidebar:not(.active) {
  display: none;
}

@media (min-width: 768px) {
  .mobile-users-sidebar {
    display: block !important;
    transform: none !important;
  }
}
</style>

<script>
  // --- DOM ---
  const receiverSelect   = document.getElementById('receiver_id');
  const chatBox          = document.getElementById('chat-box');
  const messageInput     = document.getElementById('message');
  const currentAvatar    = document.getElementById('current-avatar');
  const currentContact   = document.getElementById('current-contact');
  const lastSeen         = document.getElementById('last-seen');
  const userList         = document.getElementById('user-list');
  const userSearch       = document.getElementById('user-search');
  const authUserId       = {{ auth()->id() }};

  // Elementos de navegación móvil
  const usersSidebar = document.getElementById('users-sidebar');
  const chatSection = document.getElementById('chat-section');
  const showUsersBtn = document.getElementById('show-users-btn');
  const closeUsersBtn = document.getElementById('close-users-btn');
  const backToUsersBtn = document.getElementById('back-to-users');

  // Badge "New messages"
  const newBadge = document.getElementById('new-badge');

  // --- Estado ---
  let currentUserId = receiverSelect?.value || null;
  let lastId = 0;
  let pollTimer = null;

  // Echo
  let echoChannel = null;
  const usingEcho = !!(window.Echo && window.Echo.connector);

  // DEDUPE
  const renderedIds = new Set();
  function resetRendered(){ renderedIds.clear(); }

  // --- Navegación móvil ---
  function showUsersSidebar() {
    usersSidebar.classList.add('active');
  }

  function hideUsersSidebar() {
    usersSidebar.classList.remove('active');
  }

  function showChatSection() {
    chatSection.style.display = 'block';
    hideUsersSidebar();
  }

  // Event listeners para navegación móvil
  if (showUsersBtn) {
    showUsersBtn.addEventListener('click', showUsersSidebar);
  }

  if (closeUsersBtn) {
    closeUsersBtn.addEventListener('click', hideUsersSidebar);
  }

  if (backToUsersBtn) {
    backToUsersBtn.addEventListener('click', showUsersSidebar);
  }

  // --- Utils ---
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
    const threshold = 60;
    return chatBox.scrollHeight - chatBox.scrollTop - chatBox.clientHeight <= threshold;
  }
  function scrollToBottom(force=false){
    if (force || isNearBottom()) { 
      chatBox.scrollTop = chatBox.scrollHeight; 
      newBadge.classList.add('d-none');
    } else { 
      newBadge.classList.remove('d-none');
    }
  }
  function setActiveUserInList(id){
    if (!userList) return;
    userList.querySelectorAll('.user-list-item').forEach(a => {
      a.classList.toggle('active', a.dataset.id === String(id));
    });
  }
  function syncHeaderFromSelect(){
    const opt = receiverSelect.options[receiverSelect.selectedIndex];
    if (!opt) return;
    currentAvatar.src = opt.dataset.avatar;
    currentContact.textContent = (opt.dataset.name || opt.text).split(' - ')[0];
    lastSeen.textContent = 'Online';
  }

  // --- Render mensaje (con DEDUPE) ---
  function appendMessage(m){
    if (!m || m.id == null) return;
    if (renderedIds.has(m.id)) return; // dedupe
    renderedIds.add(m.id);

    const isMe = m.sender_id === authUserId;
    const msg = document.createElement('div');
    msg.className = `chat-message ${isMe ? 'message-sent' : 'message-received'}`;
    msg.dataset.id = m.id;
    msg.innerHTML = `
      <div>${escapeHtml(m.message)}</div>
      <small class="message-time">${formatDate(m.created_at)}</small>
    `;
    const stick = isNearBottom();
    chatBox.appendChild(msg);
    if (typeof m.id === 'number' && m.id > lastId) lastId = m.id;
    if (stick || isMe) scrollToBottom(true); else scrollToBottom(false);
  }

  // --- Cargar conversación ---
  async function loadConversation(userId){
    currentUserId = userId;
    lastId = 0;
    resetRendered();
    setActiveUserInList(userId);

    chatBox.innerHTML = `
      <div class="text-center text-muted py-5 d-flex flex-column align-items-center">
        <div class="spinner-border text-primary mb-3" role="status" style="width: 2rem; height: 2rem;">
          <span class="visually-hidden">Loading...</span>
        </div>
        <span class="text-muted">Loading conversation history...</span>
      </div>
    `;
    try{
      const res = await fetch(`/superadmin/chat/${currentUserId}`, {headers:{'X-Requested-With':'XMLHttpRequest'}});
      if (!res.ok) throw new Error('load failed');
      const data = await res.json();
      const list = Array.isArray(data) ? data : (data.messages || []);
      const srvLast = Array.isArray(data) ? (data.at(-1)?.id || 0) : (data.last_id || 0);

      chatBox.innerHTML = '';
      list.forEach(appendMessage);
      lastId = srvLast;

      // marcar leídos (user -> admin)
      await fetch(`/superadmin/chat/${currentUserId}/read`, {
        method: 'POST',
        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}','X-Requested-With':'XMLHttpRequest'}
      });

      // En móviles, mostrar la sección de chat después de cargar
      if (window.innerWidth < 768) {
        showChatSection();
      }

      startRealtimeOrPoll();
    }catch(e){
      chatBox.innerHTML = `<div class="alert alert-danger">Could not load messages.</div>`;
      console.error(e);
    }
  }

  // --- Enviar ---
  async function sendMessage(){
    const receiver_id = receiverSelect.value;
    const message = messageInput.value.trim();
    if (!receiver_id || !message) { messageInput.focus(); return; }

    try{
      const res = await fetch('/superadmin/chat/send', {
        method:'POST',
        headers:{
          'Content-Type':'application/json',
          'X-CSRF-TOKEN':'{{ csrf_token() }}',
          'X-Requested-With':'XMLHttpRequest',
          'Accept':'application/json'
        },
        body: JSON.stringify({receiver_id, message})
      });
      if (!res.ok) throw new Error('Send error');
      const created = await res.json();
      appendMessage(created);
      messageInput.value = '';
      messageInput.focus();
    }catch(e){ console.error(e); alert('Could not send message.'); }
  }

  // --- Echo o Poll ---
  function startRealtimeOrPoll(){
    stopPoll();
    if (usingEcho) subscribeEcho(); else startPoll();
  }

  function subscribeEcho(){
    if (echoChannel) {
      try { echoChannel.stopListening('.message.created'); } catch {}
      try { window.Echo.leave(`private-user.${authUserId}`); window.Echo.leave(`user.${authUserId}`); } catch {}
    }
    echoChannel = window.Echo.private(`user.${authUserId}`);
    echoChannel.listen('.message.created', (e) => {
      const payload = e.message ?? e;
      const s = payload.sender_id, r = payload.receiver_id;
      const belongs =
        (s === authUserId && r === Number(currentUserId)) ||
        (s === Number(currentUserId) && r === authUserId);
      if (!belongs) return;
      appendMessage(payload);
    });
  }

  function startPoll(){
    stopPoll();
    const loop = async () => {
      try{
        const res = await fetch(`/superadmin/chat/${currentUserId}?after_id=${lastId}`, {headers:{'X-Requested-With':'XMLHttpRequest'}});
        if (res.ok){
          const data = await res.json();
          (data.messages || []).forEach(appendMessage);
        }
      }catch(e){}
      pollTimer = setTimeout(loop, 5000);
    };
    loop();
  }
  function stopPoll(){ if (pollTimer) clearTimeout(pollTimer); pollTimer = null; }

  // --- UI events ---
  // Enter para enviar
  messageInput.addEventListener('keypress', e => { if (e.key === 'Enter'){ e.preventDefault(); sendMessage(); } });

  // Cambio desde el SELECT
  receiverSelect.addEventListener('change', () => {
    syncHeaderFromSelect();
    loadConversation(receiverSelect.value);
  });

  // Click en la lista de usuarios (delegación de eventos)
  if (userList) {
    userList.addEventListener('click', (ev) => {
      const item = ev.target.closest('.user-list-item');
      if (!item) return;
      ev.preventDefault();
      const id = item.dataset.id;
      // sincroniza <select> y header
      if (receiverSelect) {
        receiverSelect.value = id;
        syncHeaderFromSelect();
      } else {
        // si por alguna razón no hay <select>, toma datos del item
        currentAvatar.src = item.dataset.avatar;
        currentContact.textContent = item.dataset.name;
      }
      loadConversation(id);
    });
  }

  // Filtro de usuarios
  userSearch?.addEventListener('input', () => {
    const q = userSearch.value.toLowerCase().trim();
    userList.querySelectorAll('.user-list-item').forEach(a => {
      const txt = (a.dataset.name + ' ' + a.dataset.company).toLowerCase();
      a.style.display = txt.includes(q) ? '' : 'none';
    });
  });

  document.addEventListener('visibilitychange', () => {
    if (document.hidden) stopPoll(); else startRealtimeOrPoll();
  });

  // init
  window.addEventListener('DOMContentLoaded', () => {
    if (receiverSelect?.value) {
      syncHeaderFromSelect();
      loadConversation(receiverSelect.value);
      setActiveUserInList(receiverSelect.value);
    }

    // Inicializar estado para móviles
    if (window.innerWidth < 768) {
      usersSidebar.classList.add('active');
    }
  });

  // Manejar cambios de tamaño de ventana
  window.addEventListener('resize', () => {
    if (window.innerWidth >= 768) {
      // En desktop, asegurar que ambos paneles sean visibles
      usersSidebar.style.display = 'block';
      usersSidebar.classList.remove('active');
      chatSection.style.display = 'block';
    } else {
      // En móvil, mostrar solo la lista de usuarios inicialmente
      usersSidebar.classList.add('active');
    }
  });
</script>

@endsection