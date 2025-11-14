<!-- Advanced Chatbot Widget V2 -->
<div id="chatbot-widget">
    <!-- Chat Button với Animation -->
    <button id="chatbot-toggle" class="chatbot-toggle" onclick="toggleChatbot()">
        <i class="fas fa-comments"></i>
        <span class="chatbot-badge" id="chatbot-badge">CHAT</span>
        <span class="pulse-ring"></span>
    </button>

    <!-- Chat Window -->
    <div id="chatbot-window" class="chatbot-window">
        <!-- Header với gradient và features -->
        <div class="chatbot-header">
            <div class="chatbot-header-info">
                <div class="chatbot-avatar">
                    <i class="fas fa-headset"></i>
                    <span class="avatar-status"></span>
                </div>
                <div>
                    <div class="chatbot-title">
                        Trợ Lý WowBox
                        <span class="ai-badge">💬</span>
                    </div>
                    <div class="chatbot-status">
                        <span class="status-dot"></span> 
                        <span id="bot-status-text">Đang online</span>
                    </div>
                </div>
            </div>
            <div class="header-actions">
                <button class="header-btn" onclick="clearChatHistory()" title="Xóa lịch sử">
                    <i class="fas fa-trash-alt"></i>
                </button>
                <button class="header-btn" onclick="toggleChatbot()" title="Thu nhỏ">
                    <i class="fas fa-minus"></i>
                </button>
                <button class="header-btn" onclick="toggleChatbot()" title="Đóng">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        <!-- Messages Container với Virtual Scrolling -->
        <div id="chatbot-messages" class="chatbot-messages">
            <!-- Welcome Message -->
            <div class="chatbot-message bot-message" data-message-id="welcome">
                <div class="message-avatar">
                    <i class="fas fa-headset"></i>
                </div>
                <div class="message-content">
                    <div class="message-text">
                        <strong>Xin chào! 👋</strong><br><br>
                        Tôi là <strong>Trợ Lý Chat</strong> của WowBox Shop với khả năng:<br><br>
                        🎯 <strong>Hiểu ngữ cảnh</strong> - Nhớ lịch sử chat<br>
                        🔍 <strong>Tìm kiếm thông minh</strong> - Gợi ý chính xác<br>
                        �️ <strong>Xem chi tiết nhanh</strong> - Click vào sản phẩm<br>
                        📦 <strong>Tra đơn hàng</strong> - Realtime tracking<br>
                        🎁 <strong>Ưu đãi cá nhân</strong> - Dựa trên sở thích<br><br>
                        Hỏi tôi bất cứ điều gì! 💬
                    </div>
                    <div class="message-time">{{ date('H:i') }}</div>
                </div>
            </div>
        </div>

        <!-- Smart Suggestions (Dynamic) -->
        <div id="smart-suggestions" class="smart-suggestions">
            <div class="suggestions-scroll">
                <button class="suggestion-chip" onclick="sendQuickReply('Gợi ý món ngon')">
                    <i class="fas fa-magic"></i> Gợi ý món
                </button>
                <button class="suggestion-chip" onclick="sendQuickReply('Có gì hot?')">
                    <i class="fas fa-fire"></i> Hot nhất
                </button>
                <button class="suggestion-chip" onclick="sendQuickReply('Khuyến mãi')">
                    <i class="fas fa-gift"></i> Ưu đãi
                </button>
                <button class="suggestion-chip" onclick="sendQuickReply('Đơn hàng của tôi')">
                    <i class="fas fa-box"></i> Đơn hàng
                </button>
                <button class="suggestion-chip" onclick="sendQuickReply('Giao hàng')">
                    <i class="fas fa-shipping-fast"></i> Giao hàng
                </button>
            </div>
        </div>

        <!-- Input Area với Voice & Emoji -->
        <div class="chatbot-input-container">
            <!-- Typing Indicator -->
            <div id="user-typing" class="user-typing-indicator" style="display: none;">
                <i class="fas fa-keyboard"></i> Bạn đang nhập...
            </div>
            
            <div class="chatbot-input">
                <button class="input-btn" onclick="toggleVoiceInput()" title="Nhập bằng giọng nói">
                    <i class="fas fa-microphone" id="voice-icon"></i>
                </button>
                
                <input type="text" 
                       id="chatbot-input-field" 
                       placeholder="Nhắn gì đó... (hoặc nhấn 🎤)"
                       onkeypress="handleChatbotKeypress(event)"
                       oninput="handleTyping()">
                
                <button class="input-btn" onclick="showEmojiPicker()" title="Emoji">
                    <i class="fas fa-smile"></i>
                </button>
                
                <button class="chatbot-send" onclick="sendChatbotMessage()" id="send-btn">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mini Chat Preview (Minimized State) -->
    <div id="chat-preview" class="chat-preview" style="display: none;">
        <div class="preview-content">
            <div class="preview-avatar">
                <i class="fas fa-headset"></i>
            </div>
            <div class="preview-text">
                <strong>WowBox Chat</strong>
                <p>Có thể giúp gì cho bạn? 😊</p>
            </div>
            <button onclick="toggleChatbot()" class="preview-close">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
</div>

<style>
    /* Advanced Styles V2 */
    #chatbot-widget {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 9999;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    .chatbot-toggle {
        width: 65px;
        height: 65px;
        border-radius: 50%;
        background: linear-gradient(135deg, #2E7D32 0%, #388E3C 100%);
        color: white;
        border: none;
        font-size: 1.7rem;
        cursor: pointer;
        box-shadow: 0 10px 30px rgba(46, 125, 50, 0.5);
        transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        position: relative;
        animation: chatbotFloat 3s ease-in-out infinite;
    }

    .chatbot-toggle:hover {
        transform: translateY(-5px) scale(1.05);
        box-shadow: 0 15px 40px rgba(46, 125, 50, 0.6);
    }

    @keyframes chatbotFloat {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }

    .pulse-ring {
        position: absolute;
        top: -5px;
        left: -5px;
        right: -5px;
        bottom: -5px;
        border: 3px solid #2E7D32;
        border-radius: 50%;
        animation: pulsateRing 2s ease-out infinite;
        opacity: 0;
    }

    @keyframes pulsateRing {
        0% {
            transform: scale(0.9);
            opacity: 1;
        }
        100% {
            transform: scale(1.3);
            opacity: 0;
        }
    }

    .chatbot-badge {
        position: absolute;
        top: -8px;
        right: -8px;
        background: linear-gradient(135deg, #FFE135 0%, #FFC107 100%);
        color: #2E7D32;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 0.7rem;
        font-weight: 700;
        border: 3px solid white;
        box-shadow: 0 4px 12px rgba(255, 225, 53, 0.4);
        animation: badgePulse 2s ease-in-out infinite;
    }

    @keyframes badgePulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }

    .chatbot-window {
        position: fixed;
        bottom: 100px;
        right: 20px;
        width: 420px;
        height: 650px;
        background: #ffffff;
        border-radius: 25px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        display: none;
        flex-direction: column;
        animation: slideUpFade 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        overflow: hidden;
        border: 1px solid rgba(46, 125, 50, 0.2);
    }

    @keyframes slideUpFade {
        from {
            opacity: 0;
            transform: translateY(30px) scale(0.9);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .chatbot-window.active {
        display: flex;
    }

    .chatbot-header {
        background: linear-gradient(135deg, #2E7D32 0%, #388E3C 100%);
        color: white;
        padding: 1.3rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 4px 20px rgba(46, 125, 50, 0.3);
    }

    .chatbot-header-info {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .chatbot-avatar {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #FFE135 0%, #FFF7A0 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #2E7D32;
        font-size: 1.6rem;
        position: relative;
        box-shadow: 0 4px 15px rgba(255, 225, 53, 0.5);
        animation: avatarGlow 2s ease-in-out infinite;
    }

    @keyframes avatarGlow {
        0%, 100% { box-shadow: 0 4px 15px rgba(255, 225, 53, 0.5); }
        50% { box-shadow: 0 4px 25px rgba(255, 225, 53, 0.8); }
    }

    .avatar-status {
        position: absolute;
        bottom: 2px;
        right: 2px;
        width: 14px;
        height: 14px;
        background: #4ade80;
        border: 3px solid white;
        border-radius: 50%;
        animation: statusBlink 2s infinite;
    }

    .chatbot-title {
        font-weight: 700;
        font-size: 1.15rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .ai-badge {
        font-size: 1rem;
        animation: aiFloat 2s ease-in-out infinite;
    }

    @keyframes aiFloat {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-3px); }
    }

    .chatbot-status {
        font-size: 0.85rem;
        opacity: 0.95;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-top: 0.2rem;
    }

    .status-dot {
        width: 9px;
        height: 9px;
        background: #4ade80;
        border-radius: 50%;
        animation: statusBlink 1.5s infinite;
        box-shadow: 0 0 10px #4ade80;
    }

    @keyframes statusBlink {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.4; }
    }

    .header-actions {
        display: flex;
        gap: 0.5rem;
    }

    .header-btn {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .header-btn:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: scale(1.1);
    }

    .chatbot-messages {
        flex: 1;
        overflow-y: auto;
        padding: 1.5rem;
        background: linear-gradient(180deg, #f8f9fa 0%, #ffffff 100%);
        scroll-behavior: smooth;
    }

    .chatbot-messages::-webkit-scrollbar {
        width: 6px;
    }

    .chatbot-messages::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .chatbot-messages::-webkit-scrollbar-thumb {
        background: linear-gradient(180deg, #2E7D32 0%, #388E3C 100%);
        border-radius: 10px;
    }

    .chatbot-message {
        display: flex;
        gap: 0.8rem;
        margin-bottom: 1.5rem;
        animation: messageSlide 0.4s ease;
    }

    @keyframes messageSlide {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .user-message {
        justify-content: flex-end;
        animation: messageSlideRight 0.4s ease;
    }

    @keyframes messageSlideRight {
        from {
            opacity: 0;
            transform: translateX(20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .user-message .message-avatar {
        order: 2;
        background: linear-gradient(135deg, #2E7D32 0%, #388E3C 100%);
        color: white;
    }

    .user-message .message-content {
        align-items: flex-end;
    }

    .user-message .message-text {
        background: linear-gradient(135deg, #2E7D32 0%, #388E3C 100%);
        color: white;
    }

    .message-avatar {
        width: 38px;
        height: 38px;
        background: linear-gradient(135deg, #FFE135 0%, #FFF7A0 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #2E7D32;
        font-size: 1.1rem;
        flex-shrink: 0;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
    }

    .message-content {
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
        max-width: 75%;
    }

    .message-text {
        background: white;
        padding: 1rem 1.2rem;
        border-radius: 18px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        line-height: 1.6;
        word-wrap: break-word;
        position: relative;
    }

    .message-text strong {
        color: #2E7D32;
    }

    .message-time {
        font-size: 0.75rem;
        color: #999;
        padding: 0 0.5rem;
        font-weight: 500;
    }

    .chatbot-product-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        cursor: pointer;
        margin-bottom: 1rem;
        border: 2px solid transparent;
        text-decoration: none;
        display: block;
    }

    .chatbot-product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(46, 125, 50, 0.3);
        border-color: #2E7D32;
    }

    .chatbot-product-image {
        width: 100%;
        height: 140px;
        object-fit: cover;
    }

    .chatbot-product-info {
        padding: 1rem;
    }

    .chatbot-product-name {
        font-weight: 600;
        color: #333;
        font-size: 1rem;
        margin-bottom: 0.5rem;
    }

    .chatbot-product-price {
        color: #2E7D32;
        font-weight: 700;
        font-size: 1.1rem;
    }

    .smart-suggestions {
        background: white;
        border-top: 1px solid #e9ecef;
        padding: 1rem;
    }

    .suggestions-scroll {
        display: flex;
        gap: 0.6rem;
        overflow-x: auto;
        padding-bottom: 0.5rem;
    }

    .suggestions-scroll::-webkit-scrollbar {
        height: 4px;
    }

    .suggestions-scroll::-webkit-scrollbar-thumb {
        background: linear-gradient(90deg, #2E7D32 0%, #388E3C 100%);
        border-radius: 10px;
    }

    .suggestion-chip {
        padding: 0.6rem 1.2rem;
        background: linear-gradient(135deg, #f0f8f0, #e8f5e9);
        border: 2px solid #2E7D32;
        color: #2E7D32;
        border-radius: 25px;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        white-space: nowrap;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .suggestion-chip:hover {
        background: linear-gradient(135deg, #2E7D32 0%, #388E3C 100%);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(46, 125, 50, 0.3);
    }

    .chatbot-input-container {
        background: white;
        border-top: 1px solid #e9ecef;
    }

    .user-typing-indicator {
        padding: 0.6rem 1.2rem;
        color: #2E7D32;
        font-size: 0.85rem;
        font-weight: 500;
    }

    .chatbot-input {
        padding: 1rem;
        display: flex;
        gap: 0.8rem;
        align-items: center;
    }

    .input-btn {
        background: transparent;
        border: none;
        color: #2E7D32;
        font-size: 1.3rem;
        cursor: pointer;
        transition: all 0.3s ease;
        padding: 0.5rem;
    }

    .input-btn:hover {
        transform: scale(1.2);
        color: #388E3C;
    }

    .input-btn.recording {
        color: #f5576c;
        animation: recordPulse 1s infinite;
    }

    @keyframes recordPulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.2); }
    }

    #chatbot-input-field {
        flex: 1;
        padding: 0.9rem 1.2rem;
        border: 2px solid #e0e0e0;
        border-radius: 25px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        background: white;
        color: #333;
    }

    #chatbot-input-field::placeholder {
        color: #999;
    }

    #chatbot-input-field:focus {
        outline: none;
        border-color: #2E7D32;
        background: white;
        color: #000;
        box-shadow: 0 0 0 4px rgba(46, 125, 50, 0.1);
    }

    .chatbot-send {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, #2E7D32 0%, #388E3C 100%);
        color: white;
        border: none;
        border-radius: 50%;
        font-size: 1.2rem;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(46, 125, 50, 0.3);
    }

    .chatbot-send:hover {
        transform: scale(1.1) rotate(15deg);
        box-shadow: 0 6px 20px rgba(46, 125, 50, 0.4);
    }

    .chatbot-send:active {
        transform: scale(0.95);
    }

    .typing-indicator {
        display: flex;
        gap: 0.4rem;
        padding: 1rem 1.2rem;
        background: white;
        border-radius: 18px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        width: fit-content;
    }

    .typing-dot {
        width: 10px;
        height: 10px;
        background: linear-gradient(135deg, #2E7D32 0%, #388E3C 100%);
        border-radius: 50%;
        animation: typingBounce 1.4s infinite;
    }

    .typing-dot:nth-child(2) {
        animation-delay: 0.2s;
    }

    .typing-dot:nth-child(3) {
        animation-delay: 0.4s;
    }

    @keyframes typingBounce {
        0%, 60%, 100% { transform: translateY(0); }
        30% { transform: translateY(-10px); }
    }

    /* Chat Preview (Minimized) */
    .chat-preview {
        position: fixed;
        bottom: 100px;
        right: 20px;
        background: white;
        padding: 1rem 1.5rem;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        animation: previewSlide 0.3s ease;
        max-width: 300px;
    }

    @keyframes previewSlide {
        from {
            opacity: 0;
            transform: translateX(20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .preview-content {
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    .preview-avatar {
        width: 45px;
        height: 45px;
        background: linear-gradient(135deg, #2E7D32 0%, #388E3C 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.3rem;
    }
    
    .preview-avatar i {
        font-size: 1.2rem;
    }

    .preview-text strong {
        color: #2E7D32;
        font-size: 0.95rem;
    }

    .preview-text p {
        margin: 0.3rem 0 0 0;
        color: #666;
        font-size: 0.85rem;
    }

    .preview-close {
        background: #f0f2f5;
        border: none;
        color: #666;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .preview-close:hover {
        background: #e4e6eb;
        transform: scale(1.1);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .chatbot-window {
            width: calc(100vw - 20px);
            height: calc(100vh - 120px);
            right: 10px;
            bottom: 90px;
            border-radius: 20px;
        }

        .message-content {
            max-width: 80%;
        }
    }

    /* Dark Mode Support */
    @media (prefers-color-scheme: dark) {
        .chatbot-window {
            background: #1a1a1a;
        }

        .chatbot-messages {
            background: linear-gradient(180deg, #2a2a2a 0%, #1a1a1a 100%);
        }

        .message-text {
            background: #2a2a2a;
            color: #e0e0e0;
        }

        #chatbot-input-field {
            background: #2a2a2a;
            color: #e0e0e0;
            border-color: #3a3a3a;
        }
    }
</style>

<script>
    let chatbotOpen = false;
    let chatContext = []; // Store chat history for context
    let sessionId = loadSessionId();
    let typingTimeout;
    let recognition; // Voice recognition
    let isRecording = false;

    // Load or generate session ID
    function loadSessionId() {
        let sid = localStorage.getItem('chatbot_session_id');
        if (!sid) {
            sid = 'session_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
            localStorage.setItem('chatbot_session_id', sid);
        }
        return sid;
    }

    // Load chat history from localStorage
    function loadChatHistory() {
        const savedContext = localStorage.getItem('chatbot_context');
        const savedMessages = localStorage.getItem('chatbot_messages');
        
        if (savedContext) {
            try {
                chatContext = JSON.parse(savedContext);
            } catch (e) {
                chatContext = [];
            }
        }
        
        if (savedMessages) {
            try {
                const messages = JSON.parse(savedMessages);
                const messagesDiv = document.getElementById('chatbot-messages');
                
                messages.forEach(msg => {
                    if (msg.type === 'products' && msg.products) {
                        addChatMessage(msg.message, 'bot', false);
                        addProductCards(msg.products, false);
                    } else {
                        addChatMessage(msg.message, msg.role, false);
                    }
                });
                
                // Scroll to bottom
                messagesDiv.scrollTop = messagesDiv.scrollHeight;
            } catch (e) {
                console.error('Error loading chat history:', e);
            }
        }
    }

    // Save chat history to localStorage
    function saveChatHistory() {
        try {
            localStorage.setItem('chatbot_context', JSON.stringify(chatContext));
            
            // Save visible messages (last 20 for performance)
            const messages = chatContext.slice(-20).map(ctx => ({
                message: ctx.message,
                role: ctx.role,
                type: ctx.type || 'text',
                products: ctx.products || null
            }));
            
            localStorage.setItem('chatbot_messages', JSON.stringify(messages));
        } catch (e) {
            console.error('Error saving chat history:', e);
        }
    }

    // Initialize Voice Recognition
    function initVoiceRecognition() {
        if ('webkitSpeechRecognition' in window || 'SpeechRecognition' in window) {
            const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
            recognition = new SpeechRecognition();
            recognition.lang = 'vi-VN';
            recognition.continuous = false;
            recognition.interimResults = false;

            recognition.onresult = function(event) {
                const transcript = event.results[0][0].transcript;
                document.getElementById('chatbot-input-field').value = transcript;
                sendChatbotMessage();
            };

            recognition.onerror = function(event) {
                console.error('Speech recognition error:', event.error);
                showCustomAlert('Lỗi nhận diện giọng nói. Vui lòng thử lại!', 'error');
            };

            recognition.onend = function() {
                isRecording = false;
                document.getElementById('voice-icon').classList.remove('recording');
            };
        }
    }

    // Toggle chatbot window
    function toggleChatbot() {
        chatbotOpen = !chatbotOpen;
        const window = document.getElementById('chatbot-window');
        const toggle = document.getElementById('chatbot-toggle');
        
        if (chatbotOpen) {
            window.classList.add('active');
            toggle.style.display = 'none';
            document.getElementById('chatbot-input-field').focus();
        } else {
            window.classList.remove('active');
            toggle.style.display = 'flex';
        }
    }

    // Send quick reply
    function sendQuickReply(message) {
        sendChatbotMessage(message);
    }

    // Handle typing indicator
    function handleTyping() {
        clearTimeout(typingTimeout);
        document.getElementById('user-typing').style.display = 'block';
        
        typingTimeout = setTimeout(() => {
            document.getElementById('user-typing').style.display = 'none';
        }, 1000);
    }

    // Handle enter key
    function handleChatbotKeypress(event) {
        if (event.key === 'Enter') {
            sendChatbotMessage();
        }
    }

    // Toggle voice input
    function toggleVoiceInput() {
        if (!recognition) {
            initVoiceRecognition();
        }

        if (isRecording) {
            recognition.stop();
            isRecording = false;
            document.getElementById('voice-icon').classList.remove('recording');
        } else {
            recognition.start();
            isRecording = true;
            document.getElementById('voice-icon').classList.add('recording');
            updateBotStatus('Đang nghe...', 'listening');
        }
    }

    // Show emoji picker (simple implementation)
    function showEmojiPicker() {
        const emojis = ['😊', '😋', '🍕', '🍔', '🍟', '🎁', '❤️', '👍', '🔥', '⭐'];
        const input = document.getElementById('chatbot-input-field');
        const randomEmoji = emojis[Math.floor(Math.random() * emojis.length)];
        input.value += randomEmoji;
        input.focus();
    }

    // Update bot status
    function updateBotStatus(text, state = 'online') {
        const statusText = document.getElementById('bot-status-text');
        statusText.textContent = text;
        
        setTimeout(() => {
            statusText.textContent = 'Đang online';
        }, 2000);
    }

    // Clear chat history
    function clearChatHistory() {
        showCustomConfirm('Bạn muốn xóa toàn bộ lịch sử chat?', function(result) {
            if (result) {
                const messagesDiv = document.getElementById('chatbot-messages');
                // Keep only welcome message
                const welcomeMsg = messagesDiv.querySelector('[data-message-id="welcome"]');
                messagesDiv.innerHTML = '';
                if (welcomeMsg) {
                    messagesDiv.appendChild(welcomeMsg);
                }
                chatContext = [];
                
                // Clear localStorage
                localStorage.removeItem('chatbot_context');
                localStorage.removeItem('chatbot_messages');
                
                showCustomAlert('Đã xóa lịch sử chat!', 'success');
            }
        });
    }

    // Send message to chatbot
    async function sendChatbotMessage(quickMessage = null) {
        const input = document.getElementById('chatbot-input-field');
        const message = quickMessage || input.value.trim();
        
        if (!message) return;
        
        // Add user message
        addChatMessage(message, 'user');
        input.value = '';
        document.getElementById('user-typing').style.display = 'none';
        
        // Add to context
        chatContext.push({
            role: 'user',
            message: message,
            timestamp: new Date().toISOString()
        });
        
        // Save to localStorage
        saveChatHistory();
        
        // Show typing indicator
        showTypingIndicator();
        updateBotStatus('Đang suy nghĩ...', 'thinking');
        
        try {
            const response = await fetch('{{ route("chatbot.message") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ 
                    message: message,
                    context: chatContext.slice(-5), // Send last 5 messages for context
                    sessionId: sessionId
                })
            });
            
            const data = await response.json();
            
            // Remove typing indicator
            removeTypingIndicator();
            updateBotStatus('Đang online', 'online');
            
            // Add bot response to context
            chatContext.push({
                role: 'bot',
                message: data.message,
                type: data.type,
                products: data.products || null,
                timestamp: new Date().toISOString()
            });
            
            // Save to localStorage
            saveChatHistory();
            
            // Handle response
            if (data.type === 'text') {
                addChatMessage(data.message, 'bot');
                
                // Handle special actions
                if (data.action === 'login') {
                    setTimeout(() => {
                        window.location.href = '{{ route("dangnhap") }}';
                    }, 2000);
                }
            } else if (data.type === 'products') {
                addChatMessage(data.message, 'bot');
                addProductCards(data.products);
            }
            
            // Update smart suggestions if provided
            if (data.suggestions && data.suggestions.length > 0) {
                updateSmartSuggestions(data.suggestions);
            }
            
        } catch (error) {
            console.error('Chatbot error:', error);
            removeTypingIndicator();
            addChatMessage('😔 Xin lỗi, có lỗi xảy ra. Vui lòng thử lại sau!', 'bot');
            updateBotStatus('Đang online', 'online');
        }
    }

    // Add chat message
    function addChatMessage(text, type, shouldScroll = true) {
        const messagesDiv = document.getElementById('chatbot-messages');
        const messageDiv = document.createElement('div');
        messageDiv.className = `chatbot-message ${type}-message`;
        
        const now = new Date();
        const time = now.getHours().toString().padStart(2, '0') + ':' + 
                    now.getMinutes().toString().padStart(2, '0');
        
        messageDiv.innerHTML = `
            <div class="message-avatar">
                <i class="fas fa-${type === 'bot' ? 'headset' : 'user'}"></i>
            </div>
            <div class="message-content">
                <div class="message-text">${text.replace(/\n/g, '<br>')}</div>
                <div class="message-time">${time}</div>
            </div>
        `;
        
        messagesDiv.appendChild(messageDiv);
        if (shouldScroll) {
            messagesDiv.scrollTop = messagesDiv.scrollHeight;
        }
    }

    // Add product cards
    function addProductCards(products, shouldScroll = true) {
        const messagesDiv = document.getElementById('chatbot-messages');
        
        products.forEach(product => {
            const cardDiv = document.createElement('div');
            cardDiv.className = 'chatbot-message bot-message';
            cardDiv.innerHTML = `
                <div class="message-avatar">
                    <i class="fas fa-headset"></i>
                </div>
                <div class="message-content" style="max-width: 85%;">
                    <a href="${product.url}" class="chatbot-product-card">
                        <img src="${product.image}" alt="${product.name}" class="chatbot-product-image" 
                             onerror="this.src='{{ asset('images/products/default-product.png') }}'">
                        <div class="chatbot-product-info">
                            <div class="chatbot-product-name">${product.name}</div>
                            <div class="chatbot-product-price">${product.price_range}</div>
                        </div>
                    </a>
                </div>
            `;
            messagesDiv.appendChild(cardDiv);
        });
        
        if (shouldScroll) {
            messagesDiv.scrollTop = messagesDiv.scrollHeight;
        }
    }

    // Show typing indicator
    function showTypingIndicator() {
        const messagesDiv = document.getElementById('chatbot-messages');
        const typingDiv = document.createElement('div');
        typingDiv.id = 'typing-indicator';
        typingDiv.className = 'chatbot-message bot-message';
        typingDiv.innerHTML = `
            <div class="message-avatar">
                <i class="fas fa-headset"></i>
            </div>
            <div class="typing-indicator">
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
            </div>
        `;
        messagesDiv.appendChild(typingDiv);
        messagesDiv.scrollTop = messagesDiv.scrollHeight;
    }

    // Remove typing indicator
    function removeTypingIndicator() {
        const typingDiv = document.getElementById('typing-indicator');
        if (typingDiv) {
            typingDiv.remove();
        }
    }

    // Update smart suggestions
    function updateSmartSuggestions(suggestions) {
        const suggestionsDiv = document.querySelector('.suggestions-scroll');
        suggestionsDiv.innerHTML = '';
        
        suggestions.forEach(suggestion => {
            const chip = document.createElement('button');
            chip.className = 'suggestion-chip';
            chip.onclick = () => sendQuickReply(suggestion);
            chip.innerHTML = `<i class="fas fa-lightbulb"></i> ${suggestion}`;
            suggestionsDiv.appendChild(chip);
        });
    }

    // Play success sound (optional)
    function playSuccessSound() {
        const audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBCx+zPDaizAHGGe76+aYRAwPU6jn77NjGwU7k9n0yHgtBSh+z/DTglMFFGS96+aVRw0NSKPq86hWEgxJof/zvmsiBC1+zvDajC8IGme67O aVRAwOUqjn8LNjGgU7k9j0yHgtBSh+z/DSglMFFGS96+aVRw0NSKPq86hWEgxJof/zvmsiBC1+zvDajC8IGme67OaVRAwOUqjn8LNjGgU7k9j0yHgtBSh+z/DSglMFFGS96+aVRw0NSKPq86hWEgxJof/zvmsiBC1+zvDajC8IGme67OaVRAwOUqjn8LNjGg');
        audio.volume = 0.3;
        audio.play().catch(() => {});
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        initVoiceRecognition();
        
        // Load chat history from localStorage
        loadChatHistory();
        
        // Show welcome animation
        setTimeout(() => {
            const badge = document.getElementById('chatbot-badge');
            badge.textContent = 'NEW';
            setTimeout(() => {
                badge.textContent = 'CHAT';
            }, 3000);
        }, 1000);
    });
</script>
