function initChat() {
    let ws;
    let nickname = "";

    // Close previous WebSocket connection if exists
    if (window.chatSocket && window.chatSocket.readyState === WebSocket.OPEN) {
        window.chatSocket.close();
    }

    function connect() {
        ws = new WebSocket("ws://localhost:8080");

        ws.onopen = () => {
            console.log("Connected to chat server.");
            document.getElementById("send-button").disabled = false;
        };

        ws.onmessage = (event) => {
            const data = JSON.parse(event.data);

            if (data.type === "user_count") {
                document.getElementById("active-users").innerText = data.count;
                return;
            }

            const chat = document.getElementById("chat");
            chat.innerHTML += `<p class="mb-1"><strong>[${data.timestamp}] ${data.nickname}:</strong> ${data.message}</p>`;
            chat.scrollTop = chat.scrollHeight;
        };

        ws.onclose = () => {
            console.log("Disconnected. Reconnecting in 3s...");
            document.getElementById("send-button").disabled = true;
            setTimeout(connect, 3000);
        };
    }

    connect();

    // Save the current WebSocket as global
    window.chatSocket = ws;

    // Remove old listeners and reattach "Send" button click handler
    const sendBtn = document.getElementById("send-button");
    sendBtn.replaceWith(sendBtn.cloneNode(true));
    const newSendBtn = document.getElementById("send-button");

    newSendBtn.addEventListener("click", () => {
        if (!nickname) {
            nickname = document.getElementById("nickname").value.trim() || "Anonymous";
            if (ws.readyState === WebSocket.OPEN) {
                ws.send(JSON.stringify({ type: "set_nickname", nickname }));
            }
        }

        const msg = document.getElementById("message").value;
        if (msg && ws.readyState === WebSocket.OPEN) {
            ws.send(JSON.stringify({ message: msg }));
            document.getElementById("message").value = '';
        }
    });
}

// Expose initChat globally so spa.js can call it
window.initChat = initChat;
