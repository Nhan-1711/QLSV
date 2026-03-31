<style>
    #chat-widget-container {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 10000;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    #chat-button {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background-color: #0d6efd;
        color: white;
        border: none;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        cursor: pointer;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 24px;
        transition: transform 0.3s;
    }

    #chat-button:hover {
        transform: scale(1.1);
        background-color: #0b5ed7;
    }

    #chat-window {
        display: none;
        width: 350px;
        height: 500px;
        background-color: white;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        flex-direction: column;
        overflow: hidden;
        border: 1px solid #dee2e6;
        position: absolute;
        bottom: 70px;
        right: 0;
    }

    .chat-header {
        background-color: #0d6efd;
        color: white;
        padding: 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #0b5ed7;
    }

    .chat-header h5 {
        margin: 0;
        font-size: 16px;
    }

    .chat-body {
        flex: 1;
        padding: 15px;
        overflow-y: auto;
        background-color: #f8f9fa;
    }

    .chat-footer {
        padding: 10px;
        border-top: 1px solid #dee2e6;
        background-color: white;
        display: flex;
        gap: 10px;
    }

    .message {
        margin-bottom: 10px;
        max-width: 80%;
        padding: 10px 15px;
        border-radius: 15px;
        font-size: 14px;
        line-height: 1.4;
    }

    .message.user {
        background-color: #e9ecef;
        color: #333;
        align-self: flex-end;
        margin-left: auto;
        border-bottom-right-radius: 2px;
    }

    .message.bot {
        background-color: #0d6efd;
        color: white;
        align-self: flex-start;
        border-bottom-left-radius: 2px;
    }

    .loading-dots {
        display: inline-block;
        width: 50px;
        text-align: center;
    }

    .loading-dots span {
        display: inline-block;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background-color: white;
        animation: dot-flashing 1s infinite linear alternate;
        margin: 0 2px;
    }

    .loading-dots span:nth-child(1) {
        animation-delay: 0s;
    }

    .loading-dots span:nth-child(2) {
        animation-delay: 0.2s;
    }

    .loading-dots span:nth-child(3) {
        animation-delay: 0.4s;
    }

    @keyframes dot-flashing {
        0% {
            opacity: 1;
            transform: scale(1);
        }

        100% {
            opacity: 0.3;
            transform: scale(0.75);
        }
    }
</style>

<div id="chat-widget-container">
    <div id="chat-window">
        <div class="chat-header">
            <h5><i class="fas fa-robot me-2"></i>Trợ lý Ảo Sinh viên</h5>
            <button type="button" class="btn-close btn-close-white" id="close-chat"></button>
        </div>
        <div class="chat-body d-flex flex-column" id="chat-messages">
            <div class="message bot">
                Chào bạn! Mình có thể giúp gì cho bạn hôm nay?
                <br><br>
                {{-- <small class="text-white-50">Gợi ý: "Xem điểm", "Lịch học hôm nay", "Tôi có nợ môn nào không?"</small> --}}
            </div>
        </div>
        <div class="chat-footer">
            <input type="text" id="chat-input" class="form-control" placeholder="Nhập câu hỏi..." autocomplete="off">
            <button id="send-btn" class="btn btn-primary"><i class="fas fa-paper-plane"></i></button>
        </div>
    </div>
    <button id="chat-button">
        <i class="fas fa-comment-dots"></i>
    </button>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chatButton = document.getElementById('chat-button');
        const chatWindow = document.getElementById('chat-window');
        const closeChat = document.getElementById('close-chat');
        const chatInput = document.getElementById('chat-input');
        const sendBtn = document.getElementById('send-btn');
        const chatMessages = document.getElementById('chat-messages');

        // Toggle Chat Window
        chatButton.addEventListener('click', () => {
            chatWindow.style.display = chatWindow.style.display === 'flex' ? 'none' : 'flex';
            if (chatWindow.style.display === 'flex') {
                chatInput.focus();
            }
        });

        closeChat.addEventListener('click', () => {
            chatWindow.style.display = 'none';
        });

        // Send Message
        function sendMessage() {
            const message = chatInput.value.trim();
            if (message === '') return;

            // Add User Message
            addMessage(message, 'user');
            chatInput.value = '';

            // Show Loading
            const loadingId = addLoading();

            // Call API
            fetch("/chat/send", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        message: message
                    })
                })
                .then(response => response.json())
                .then(data => {
                    removeMessage(loadingId);
                    addMessage(data.response, 'bot', true); // true = allow HTML
                })
                .catch(error => {
                    removeMessage(loadingId);
                    addMessage('Xin lỗi, hệ thống đang bận. Vui lòng thử lại sau.', 'bot');
                    console.error(error);
                });
        }

        // Helper: Add Message
        function addMessage(text, sender, isHtml = false) {
            const div = document.createElement('div');
            div.className = `message ${sender}`;
            if (isHtml) {
                div.innerHTML = text;
            } else {
                div.textContent = text;
            }
            chatMessages.appendChild(div);
            chatMessages.scrollTop = chatMessages.scrollHeight;
            return div.id = 'msg-' + Date.now();
        }

        // Helper: Add Loading
        function addLoading() {
            const div = document.createElement('div');
            div.className = 'message bot';
            div.innerHTML = '<div class="loading-dots"><span></span><span></span><span></span></div>';
            const id = 'loading-' + Date.now();
            div.id = id;
            chatMessages.appendChild(div);
            return id;
        }

        // Helper: Remove Message
        function removeMessage(id) {
            const el = document.getElementById(id);
            if (el) el.remove();
        }

        // Event Listeners
        sendBtn.addEventListener('click', sendMessage);
        chatInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') sendMessage();
        });
    });
</script>
