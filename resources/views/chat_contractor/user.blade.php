@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Chat with the Administrator</h4>
                <div id="connection-status" class="badge bg-success">Conectado</div>
            </div>
        </div>

        <div class="card-body">


            <div class="mb-3" style="display: none;">
            <!-- Contenido oculto -->
                <div class="mb-3">
                    <label for="receiver_id" class="form-label fw-bold">Seleccionar contacto:</label>
                    <select id="receiver_id" class="form-select shadow-sm">
                        @foreach($users as $u)
                            <option value="{{ $u->id }}" data-avatar="{{ $u->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($u->name).'&background=random' }}">
                                {{ $u->name }} {{ $u->last_name }} - {{ $u->company_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="d-flex align-items-center mb-3">
                <img id="current-avatar" src="{{ $users[0]->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($users[0]->name).'&background=random' }}" 
                     class="rounded-circle me-2" width="40" height="40">
                <div>
                    <h6 id="current-contact" class="mb-0 fw-bold">{{ $users[0]->name }} {{ $users[0]->last_name }}</h6>
                    <small id="last-seen" class="text-muted">In línea</small>
                </div>
            </div>

            <div id="chat-box" class="chat-messages border rounded p-3 mb-3 bg-light">
                <!-- Mensajes se cargarán aquí -->
                <div class="text-center text-muted py-4">
                    <div class="spinner-border spinner-border-sm" role="status">
                        <span class="visually-hidden">Loading messages...</span>
                    </div>
                    Loading chat history...
                </div>
            </div>

            <div class="input-group shadow-sm">
                <input type="text" id="message" class="form-control" 
                       placeholder="Escribe un mensaje..." autocomplete="off"
                       aria-label="Escribe un mensaje">
                <button class="btn btn-primary" id="send-btn" onclick="sendMessage()">
                    <i class="fas fa-paper-plane"></i> Send
                </button>
            </div>
            <small class="text-muted d-block mt-1">Press Enter to send</small>
        </div>
    </div>
</div>

<style>
    .chat-messages {
        height: 400px;
        overflow-y: auto;
        scroll-behavior: smooth;
        background-color: #f8f9fa;
    }
    
    .message {
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
    
    .message-received .message-time {
        color: #6c757d;
    }
    
    .message-sent .message-time {
        color: rgba(255, 255, 255, 0.8);
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
    
    #chat-box::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
</style>

<script>
    const receiverSelect = document.getElementById('receiver_id');
    const chatBox = document.getElementById('chat-box');
    const messageInput = document.getElementById('message');
    const sendBtn = document.getElementById('send-btn');
    const currentAvatar = document.getElementById('current-avatar');
    const currentContact = document.getElementById('current-contact');
    const lastSeen = document.getElementById('last-seen');
    const connectionStatus = document.getElementById('connection-status');
    const authUserId = {{ auth()->id() }};
    
    // Configuración de Echo para websockets (opcional)
    // window.Echo.private(`chat.${authUserId}`)
    //     .listen('NewMessage', (e) => {
    //         if (e.message.receiver_id == authUserId || e.message.sender_id == authUserId) {
    //             loadMessages(receiverSelect.value);
    //         }
    //     });
    
    // Manejar el evento de cambio de usuario
    receiverSelect.addEventListener('change', () => {
        const selectedOption = receiverSelect.options[receiverSelect.selectedIndex];
        currentAvatar.src = selectedOption.dataset.avatar;
        currentContact.textContent = selectedOption.text.split(' - ')[0];
        lastSeen.textContent = 'En línea'; // En una implementación real, esto vendría del servidor
        
        loadMessages(receiverSelect.value);
    });
    
    // Permitir enviar mensaje con Enter
    messageInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            sendMessage();
        }
    });
    
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
        
        fetch(`/chat/${userId}`)
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
                    msg.className = `message ${messageClass}`;
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
        
        // Deshabilitar temporalmente el botón
        sendBtn.disabled = true;
        sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enviando...';
        
        fetch('/chat/send', {
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
        })
        .finally(() => {
            sendBtn.disabled = false;
            sendBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Enviar';
        });
    }
    
    // Cargar mensajes iniciales
    window.addEventListener('DOMContentLoaded', () => {
        loadMessages(receiverSelect.value);
        messageInput.focus();
        
        // Simular estado de conexión (en una app real usarías websockets)
        setInterval(() => {
            connectionStatus.textContent = 'Conectado';
            connectionStatus.className = 'badge bg-success';
            
            setTimeout(() => {
                connectionStatus.textContent = 'Conectado';
                connectionStatus.className = 'badge bg-success';
            }, 5000);
        }, 30000);
    });
</script>
@endsection