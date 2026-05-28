<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chatbot AI</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }

        .chat-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .chat-header {
            background: #4CAF50;
            color: white;
            padding: 15px;
            text-align: center;
        }

        .chat-box {
            height: 400px;
            overflow-y: auto;
            padding: 20px;
            background: #f9f9f9;
        }

        .message {
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 5px;
            max-width: 80%;
        }

        .user-message {
            background: #e3f2fd;
            margin-left: auto;
            text-align: right;
        }

        .bot-message {
            background: #f5f5f5;
            margin-right: auto;
        }

        .chat-input {
            display: flex;
            padding: 15px;
            background: white;
            border-top: 1px solid #eee;
        }

        #msg {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-right: 10px;
        }

        #sendBtn {
            padding: 10px 20px;
            background: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }

        #sendBtn:hover {
            background: #45a049;
        }

        #sendBtn:disabled {
            background: #cccccc;
            cursor: not-allowed;
        }

        .loading {
            display: none;
            text-align: center;
            padding: 10px;
            color: #666;
        }

        .loading.show {
            display: block;
        }
    </style>
</head>
<body>

<div class="chat-container">
    <div class="chat-header">
        <h2>Chatbot Hỗ Trợ Khách Hàng</h2>
    </div>

    <div class="chat-box" id="box">
        <!-- Messages will be loaded here -->
    </div>

    <div class="loading" id="loading">
        <p> Đang xử lý...</p>
    </div>

    <div class="chat-input">
        <input type="text" id="msg" placeholder="Nhập câu hỏi của bạn..." onkeypress="handleKeyPress(event)">
        <button id="sendBtn" onclick="send()">Gửi</button>
    </div>
</div>

<script>
// Load chat history when page loads
document.addEventListener('DOMContentLoaded', function() {
    loadChatHistory();
});

function loadChatHistory() {
    fetch("/chat/messages", {
        method: "GET",
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json",
            "X-Requested-With": "XMLHttpRequest"
        }
    })
    .then(res => res.json())
    .then(data => {
        const box = document.getElementById('box');
        data.forEach(msg => {
            if (msg.sender === 'user') {
                box.innerHTML += `<div class="message user-message"><b>You:</b> ${msg.message}</div>`;
            } else {
                box.innerHTML += `<div class="message bot-message"><b>Bot:</b> ${msg.message}</div>`;
            }
        });
        box.scrollTop = box.scrollHeight;
    });
}

function send(){
    let msg = document.getElementById("msg").value;
    if (!msg.trim()) return;

    const box = document.getElementById('box');
    const sendBtn = document.getElementById('sendBtn');
    const loading = document.getElementById('loading');

    // Add user message
    box.innerHTML += `<div class="message user-message"><b>You:</b> ${msg}</div>`;
    box.scrollTop = box.scrollHeight;

    // Clear input
    document.getElementById("msg").value = "";

    // Show loading and disable button
    loading.classList.add('show');
    sendBtn.disabled = true;

    fetch("/chat/send", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json",
            "X-Requested-With": "XMLHttpRequest",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
        },
        body: JSON.stringify({ message: msg })
    })
    .then(res => res.json())
    .then(data => {
        // Hide loading
        loading.classList.remove('show');
        sendBtn.disabled = false;

        // Add bot message
        box.innerHTML += `<div class="message bot-message"><b>Bot:</b> ${data.bot_message.message}</div>`;
        box.scrollTop = box.scrollHeight;
    })
    .catch(error => {
        console.error('Error:', error);
        loading.classList.remove('show');
        sendBtn.disabled = false;
        box.innerHTML += `<div class="message bot-message" style="color: red;"><b>Bot:</b> Xin lỗi, có lỗi xảy ra. Vui lòng thử lại.</div>`;
        box.scrollTop = box.scrollHeight;
    });
}

function handleKeyPress(event) {
    if (event.key === 'Enter') {
        send();
    }
}
</script>

</body>
</html>