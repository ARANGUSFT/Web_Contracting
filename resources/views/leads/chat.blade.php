@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Chat del Lead #{{ $lead_id }}</h5>
        </div>

        <div class="card-body">
            <!-- Contenedor del chat -->
            <div id="chat-box" class="border p-3 mb-3" style="height: 300px; overflow-y: auto; background: #f8f9fa;">
                <div id="messages"></div>
            </div>

            <!-- Input para escribir mensajes -->
            <div class="input-group">
                <input type="text" id="chat-message" class="form-control" placeholder="Escribe un mensaje..." required>
                <button class="btn btn-success" onclick="sendMessage()">Enviar</button>
            </div>
        </div>
    </div>
</div>

<script>
    let leadId = {{ $lead_id }};
    let currentUserId = {{ auth('web')->check() ? auth('web')->id() : 'null' }};
    let currentTeamId = {{ auth('team')->check() ? auth('team')->id() : 'null' }};

    function loadMessages() {
        fetch(`/leads/${leadId}/messages`)
            .then(response => response.json())
            .then(messages => {
                let messagesDiv = document.getElementById('messages');
                messagesDiv.innerHTML = '';
                messages.forEach(msg => {
                    let isOwnMessage = (currentUserId && msg.user_id == currentUserId) || (currentTeamId && msg.team_id == currentTeamId);
                    let senderName = msg.user ? `Admin (${msg.user.name})` : (msg.team ? `Vendedor (${msg.team.name})` : 'Yo');

                    messagesDiv.innerHTML += `
                        <div class="p-2 mb-2 ${isOwnMessage ? 'bg-primary text-white text-end' : 'bg-light text-start'}" style="border-radius: 8px;">
                            <strong>${senderName}:</strong> ${msg.message}
                        </div>
                    `;
                });
                document.getElementById('chat-box').scrollTop = document.getElementById('chat-box').scrollHeight;
            });
    }

    function sendMessage() {
        let message = document.getElementById('chat-message').value;
        if (message.trim() === '') return;

        fetch('/leads/messages', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ lead_id: leadId, message: message })
        }).then(() => {
            document.getElementById('chat-message').value = '';
            loadMessages();
        });
    }

    loadMessages();
    setInterval(loadMessages, 3000); // Recargar mensajes cada 3 segundos
</script>
@endsection
