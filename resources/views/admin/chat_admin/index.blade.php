@extends('admin.layouts.superadmin')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <!-- Encabezado del chat -->
                <div class="card-header bg-gradient-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Chat Administrativo</h4>
                        <div id="connection-status" class="badge bg-success rounded-pill">
                            <i class="fas fa-circle me-1"></i> En línea
                        </div>
                    </div>
                </div>

                <!-- Cuerpo del chat -->
                <div class="card-body p-0">
                    <div class="row g-0">
                        <!-- Panel de usuarios (opcional) -->
                        <div class="col-md-4 d-none d-md-block border-end">
                            <div class="p-3">
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <input type="text" class="form-control" placeholder="Buscar usuario...">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="receiver_id" class="form-label fw-bold text-muted">Seleccionar contacto:</label>
                                    <select id="receiver_id" class="form-select shadow-sm">
                                        @foreach($users as $u)
                                            <option value="{{ $u->id }}" data-avatar="{{ $u->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($u->name).'&background=random' }}">
                                                {{ $u->name }} {{ $u->last_name }} - {{ $u->company_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="list-group list-group-flush user-list" style="max-height: 500px; overflow-y: auto;">
                                    @foreach($users as $u)
                                    <a href="#" class="list-group-item list-group-item-action user-item" data-id="{{ $u->id }}">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $u->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($u->name).'&background=random' }}" 
                                                 class="rounded-circle me-3" width="40" height="40">
                                            <div>
                                                <h6 class="mb-0">{{ $u->name }} {{ $u->last_name }}</h6>
                                                <small class="text-muted">{{ $u->company_name }}</small>
                                            </div>
                                        </div>
                                    </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        
                        <!-- Área de conversación -->
                        <div class="col-md-8">
                            <div class="d-flex flex-column" style="height: 600px;">
                                <!-- Información del contacto actual -->
                                <div class="p-3 border-bottom d-flex align-items-center">
                                    <img id="current-avatar" src="{{ $users[0]->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($users[0]->name).'&background=random' }}" 
                                         class="rounded-circle me-3" width="48" height="48">
                                    <div>
                                        <h5 id="current-contact" class="mb-0">{{ $users[0]->name }} {{ $users[0]->last_name }}</h5>
                                        <small id="last-seen" class="text-muted">En línea</small>
                                    </div>
                                </div>
                                
                                <!-- Caja de mensajes -->
                                <div id="chat-box" class="flex-grow-1 p-3" style="overflow-y: auto; background-color: #f8f9fa;">
                                    <div class="text-center text-muted py-4">
                                        <div class="spinner-border spinner-border-sm" role="status">
                                            <span class="visually-hidden">Cargando mensajes...</span>
                                        </div>
                                        Cargando historial de chat...
                                    </div>
                                </div>
                                
                                <!-- Área de escritura -->
                                <div class="p-3 border-top">
                                    <div class="input-group">
                                        <input type="text" id="message" class="form-control rounded-start" 
                                               placeholder="Escribe un mensaje..." autocomplete="off">
                                        <button class="btn btn-primary" onclick="sendMessage()">
                                            <i class="fas fa-paper-plane"></i> Enviar
                                        </button>
                                    </div>
                                    <small class="text-muted d-block mt-2">
                                        <i class="fas fa-info-circle me-1"></i> Presiona Enter para enviar
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Estilos personalizados */
    .chat-message {
        max-width: 70%;
        margin-bottom: 12px;
        padding: 10px 15px;
        border-radius: 18px;
        position: relative;
        word-wrap: break-word;
    }
    
    .message-sent {
        background-color: #007bff;
        color: white;
        margin-left: auto;
        border-bottom-right-radius: 5px;
    }
    
    .message-received {
        background-color: #e9ecef;
        color: #212529;
        margin-right: auto;
        border-bottom-left-radius: 5px;
    }
    
    .message-time {
        font-size: 0.75rem;
        opacity: 0.8;
        margin-top: 4px;
        display: block;
        text-align: right;
    }
    
    .user-item:hover {
        background-color: #f8f9fa;
    }
    
    .user-item.active {
        background-color: #e9f5ff;
    }
    
    #chat-box::-webkit-scrollbar {
        width: 8px;
    }
    
    #chat-box::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    
    #chat-box::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 4px;
    }
</style>

<script>
    const receiverSelect = document.getElementById('receiver_id');
    const chatBox = document.getElementById('chat-box');
    const messageInput = document.getElementById('message');
    const currentAvatar = document.getElementById('current-avatar');
    const currentContact = document.getElementById('current-contact');
    const lastSeen = document.getElementById('last-seen');
    const authUserId = {{ auth()->id() }};
    const userItems = document.querySelectorAll('.user-item');

    // Formatear fecha
    function formatDate(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        
        if (date.toDateString() === now.toDateString()) {
            return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        } else if (date.getFullYear() === now.getFullYear()) {
            return date.toLocaleDateString([], { month: 'short', day: 'numeric' });
        } else {
            return date.toLocaleDateString([], { year: 'numeric', month: 'short', day: 'numeric' });
        }
    }

    // Cargar mensajes
    function loadMessages(userId) {
        chatBox.innerHTML = `
            <div class="text-center text-muted py-4">
                <div class="spinner-border spinner-border-sm" role="status">
                    <span class="visually-hidden">Cargando mensajes...</span>
                </div>
                Cargando historial de chat...
            </div>
        `;
        
        fetch(`/superadmin/chat/${userId}`)
            .then(res => {
                if (!res.ok) throw new Error('Error al cargar mensajes');
                return res.json();
            })
            .then(data => {
                if (data.length === 0) {
                    chatBox.innerHTML = `
                        <div class="text-center text-muted py-4">
                            No hay mensajes aún. ¡Envía el primero!
                        </div>
                    `;
                    return;
                }
                
                chatBox.innerHTML = '';
                data.forEach(m => {
                    const isMe = m.sender_id === authUserId;
                    const messageClass = isMe ? 'message-sent' : 'message-received';
                    const time = formatDate(m.created_at);
                    
                    const msg = document.createElement('div');
                    msg.className = `chat-message ${messageClass}`;
                    msg.innerHTML = `
                        <div>${m.message}</div>
                        <small class="message-time">${time}</small>
                    `;
                    
                    chatBox.appendChild(msg);
                });
                
                chatBox.scrollTop = chatBox.scrollHeight;
            })
            .catch(err => {
                chatBox.innerHTML = `
                    <div class="alert alert-danger">
                        No se pudieron cargar los mensajes. Intenta recargar la página.
                    </div>
                `;
                console.error(err);
            });
    }

    // Enviar mensaje
    function sendMessage() {
        const receiver_id = receiverSelect.value;
        const message = messageInput.value.trim();
        
        if (message === '') {
            messageInput.focus();
            return;
        }
        
        fetch('/superadmin/chat/send', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ receiver_id, message })
        })
        .then(async res => {
            if (!res.ok) {
                const errorData = await res.json();
                throw new Error(errorData.message || 'Error al enviar');
            }
            return res.json();
        })
        .then(() => {
            messageInput.value = '';
            messageInput.focus();
            loadMessages(receiver_id);
        })
        .catch(err => {
            alert(`Error: ${err.message}`);
            console.error('Error enviando mensaje:', err);
        });
    }

    // Permitir enviar con Enter
    messageInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            sendMessage();
        }
    });

    // Cambiar de usuario
    receiverSelect.addEventListener('change', () => {
        const selectedOption = receiverSelect.options[receiverSelect.selectedIndex];
        currentAvatar.src = selectedOption.dataset.avatar;
        currentContact.textContent = selectedOption.text.split(' - ')[0];
        lastSeen.textContent = 'En línea';
        
        loadMessages(receiverSelect.value);
        
        // Resaltar usuario seleccionado en la lista
        userItems.forEach(item => {
            item.classList.remove('active');
            if (item.dataset.id === receiverSelect.value) {
                item.classList.add('active');
            }
        });
    });

    // Seleccionar usuario desde la lista
    userItems.forEach(item => {
        item.addEventListener('click', (e) => {
            e.preventDefault();
            receiverSelect.value = item.dataset.id;
            receiverSelect.dispatchEvent(new Event('change'));
        });
    });

    // Cargar mensajes iniciales
    window.addEventListener('DOMContentLoaded', () => {
        loadMessages(receiverSelect.value);
        messageInput.focus();
        
        // Resaltar el primer usuario por defecto
        if (userItems.length > 0) {
            userItems[0].classList.add('active');
        }
    });
</script>
@endsection