<!-- ================= CHAT TAB ================= -->

<div class="tab-pane fade show active" id="chat">

    <h4 class="mb-3">
    <i class="bi bi-chat-dots me-2"></i> Conversation
    </h4>


    <!-- CHAT MESSAGES -->
    <div id="chat-box" class="chat-container">

    @foreach($messages as $msg)

    @php
    $isSeller = isset($msg->team);
    $senderName = $isSeller ? $msg->team->name : ($msg->user->company_name ?? 'User');
    $isMine = $msg->user_id == auth()->id();

    $alignment = $isMine ? 'justify-content-end' : 'justify-content-start';
    $bubbleClass = $isMine ? 'chat-bubble-me' : 'chat-bubble-other';
    $timeAlign = $isMine ? 'text-end' : 'text-start';
    @endphp

    <div class="d-flex {{ $alignment }} mb-3 chat-row" id="message-{{ $msg->id }}">

    <div class="chat-bubble {{ $bubbleClass }}">

    <div class="chat-user">
    {{ $senderName }}
    </div>

    <div class="chat-message">
    {{ $msg->message }}
    </div>

    <div class="chat-time {{ $timeAlign }} d-flex align-items-center gap-2">

    <span>
    {{ $msg->created_at->format('d/m/Y H:i') }}
    </span>

    @if($isMine)
    <button
    class="btn btn-sm text-danger delete-message"
    data-id="{{ $msg->id }}"
    style="font-size:11px;padding:0;">
    <i class="bi bi-trash"></i>
    </button>
    @endif

    </div>

    </div>

    </div>

    @endforeach

    </div>


    <!-- CHAT INPUT -->
    <form id="chatForm" class="chat-input-wrapper">
    @csrf

    <input type="hidden" id="lead_id" value="{{ $lead->id }}">

    <div class="chat-input-group">

    <input
    type="text"
    id="message"
    class="form-control chat-input"
    placeholder="Type your message..."
    required
    >

    <button type="submit" class="btn btn-success chat-send-btn">
    <i class="bi bi-send"></i>
    </button>

    </div>

    </form>

</div>

<!-- ================= CHAT STYLE ================= -->

<style>

    /* CHAT CONTAINER */

    .chat-container{
    height:420px;
    overflow-y:auto;
    background:#f8fafc;
    padding:20px;
    border-radius:14px;
    border:1px solid #e6eaf0;
    display:flex;
    flex-direction:column;
    gap:6px;
    }


    /* MESSAGE ROW */

    .chat-row{
    animation:fadeIn .2s ease;
    }


    /* CHAT BUBBLE */

    .chat-bubble{
    max-width:70%;
    padding:12px 16px;
    border-radius:14px;
    position:relative;
    box-shadow:0 3px 10px rgba(0,0,0,0.05);
    }


    /* MY MESSAGE */

    .chat-bubble-me{
    background:#01032c;
    color:white;
    border-bottom-right-radius:5px;
    }


    /* OTHER MESSAGE */

    .chat-bubble-other{
    background:white;
    border:1px solid #e4e7eb;
    border-bottom-left-radius:5px;
    }


    /* USER NAME */

    .chat-user{
    font-weight:600;
    font-size:12px;
    margin-bottom:4px;
    opacity:.85;
    display:flex;
    align-items:center;
    gap:4px;
    }


    /* MESSAGE TEXT */

    .chat-message{
    font-size:14px;
    line-height:1.45;
    word-break:break-word;
    }


    /* TIME + DELETE */

    .chat-time{
    font-size:11px;
    opacity:.65;
    margin-top:6px;
    display:flex;
    align-items:center;
    gap:6px;
    }


    /* DELETE BUTTON */

    .delete-message{
    opacity:0;
    border:none;
    background:none;
    cursor:pointer;
    font-size:12px;
    transition:.2s;
    }

    /* color para mensajes propios (fondo azul) */

    .chat-bubble-me .delete-message{
    color:#ffffff;
    opacity:.6;
    }

    /* hover */

    .chat-bubble-me .delete-message:hover{
    color:#ff6b6b;
    opacity:1;
    transform:scale(1.1);
    }

    /* mensajes del cliente */

    .chat-bubble-other .delete-message{
    color:#35dc43;
    opacity:.6;
    }

    .chat-bubble-other .delete-message:hover{
    opacity:1;
    transform:scale(1.1);
    }

    /* INPUT AREA */

    .chat-input-wrapper{
    margin-top:18px;
    }


    /* INPUT GROUP */

    .chat-input-group{
    display:flex;
    gap:10px;
    }


    /* INPUT */

    .chat-input{
    border-radius:30px;
    padding:10px 18px;
    border:1px solid #dfe3e8;
    font-size:14px;
    transition:.2s;
    }

    .chat-input:focus{
    border-color:#0d6efd;
    box-shadow:0 0 0 3px rgba(13,110,253,0.15);
    }


    /* SEND BUTTON */

    .chat-send-btn{
    border-radius:30px;
    padding:8px 20px;
    display:flex;
    align-items:center;
    justify-content:center;
    transition:.2s;
    }

    .chat-send-btn:hover{
    transform:translateY(-1px);
    box-shadow:0 4px 10px rgba(0,0,0,0.1);
    }


    /* SCROLLBAR */

    .chat-container::-webkit-scrollbar{
    width:6px;
    }

    .chat-container::-webkit-scrollbar-thumb{
    background:#cfd4da;
    border-radius:20px;
    }

    .chat-container::-webkit-scrollbar-track{
    background:transparent;
    }


    /* MESSAGE APPEAR ANIMATION */

    @keyframes fadeIn{
    from{
    opacity:0;
    transform:translateY(6px);
    }
    to{
    opacity:1;
    transform:translateY(0);
    }
    }

</style>


