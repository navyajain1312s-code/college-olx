<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit();
}

$userId = $_SESSION['user_id'];
$username = $_SESSION['username'] ?? 'Seller';
$email = $_SESSION['email'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Messages - UniMart</title>
  <link rel="stylesheet" href="chat.css">
  <style>
    /* Admin-specific WhatsApp-style layout */
    body {
      background: #e5ddd5;
      min-height: 100vh;
      padding: 0;
      margin: 0;
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Helvetica', 'Arial', sans-serif;
    }

    .admin-container {
      max-width: 1400px;
      height: 100vh;
      margin: 0 auto;
      display: flex;
      flex-direction: column;
      background: white;
    }

    /* Header */
    .admin-header {
      background: #075e54;
      color: white;
      padding: 16px 24px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .admin-header h1 {
      margin: 0;
      font-size: 20px;
      font-weight: 500;
    }

    .auth-area {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .auth-area strong {
      font-weight: 400;
      font-size: 14px;
    }

    .btn {
      padding: 8px 16px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-weight: 500;
      transition: all 0.2s;
      font-size: 13px;
      text-decoration: none;
      display: inline-block;
    }

    .btn-primary {
      background: white;
      color: #075e54;
    }

    .btn-primary:hover {
      background: #f0f0f0;
    }

    .btn-secondary {
      background: rgba(255, 255, 255, 0.2);
      color: white;
      border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .btn-secondary:hover {
      background: rgba(255, 255, 255, 0.3);
    }

    /* Main Layout: Sidebar + Chat */
    .admin-main {
      display: flex;
      flex: 1;
      overflow: hidden;
    }

    /* Left Sidebar - Contacts List */
    .contacts-sidebar {
      width: 350px;
      background: white;
      border-right: 1px solid #e0e0e0;
      display: flex;
      flex-direction: column;
      overflow: hidden;
    }

    .sidebar-header {
      padding: 12px 16px;
      background: #f5f5f5;
      border-bottom: 1px solid #e0e0e0;
    }

    .sidebar-header h2 {
      margin: 0;
      font-size: 16px;
      font-weight: 500;
      color: #333;
    }

    .contacts-list {
      flex: 1;
      overflow-y: auto;
      background: white;
    }

    .contact-item {
      padding: 12px 16px;
      border-bottom: 1px solid #f0f0f0;
      cursor: pointer;
      transition: background 0.2s;
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .contact-item:hover {
      background: #f5f5f5;
    }

    .contact-item.active {
      background: #e8f5e9;
    }

    .contact-avatar {
      width: 48px;
      height: 48px;
      border-radius: 50%;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-weight: 600;
      font-size: 18px;
      flex-shrink: 0;
    }

    .contact-info {
      flex: 1;
      min-width: 0;
    }

    .contact-name {
      font-weight: 500;
      font-size: 15px;
      color: #111;
      margin-bottom: 4px;
    }

    .contact-preview {
      font-size: 13px;
      color: #667781;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .contact-meta {
      display: flex;
      flex-direction: column;
      align-items: flex-end;
      gap: 4px;
      flex-shrink: 0;
    }

    .contact-time {
      font-size: 12px;
      color: #667781;
    }

    .contact-badge {
      background: #25d366;
      color: white;
      border-radius: 12px;
      padding: 2px 8px;
      font-size: 11px;
      font-weight: 600;
      min-width: 20px;
      text-align: center;
    }

    .empty-contacts {
      padding: 60px 20px;
      text-align: center;
      color: #667781;
    }

    .empty-contacts svg {
      width: 64px;
      height: 64px;
      margin-bottom: 16px;
      opacity: 0.3;
    }

    /* Right Panel - Chat Area */
    .chat-panel {
      flex: 1;
      display: flex;
      flex-direction: column;
      background: #e5ddd5;
      position: relative;
    }

    /* Chat Background Pattern */
    .chat-panel::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZGVmcz48cGF0dGVybiBpZD0iYSIgcGF0dGVyblVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgd2lkdGg9IjEwMCIgaGVpZ2h0PSIxMDAiPjxwYXRoIGQ9Ik0wIDBoMTAwdjEwMEgweiIgZmlsbD0iI2U1ZGRkNSIvPjxwYXRoIGQ9Ik01MCAwdjEwMCIgc3Ryb2tlPSIjZDFkN2RiIiBzdHJva2Utd2lkdGg9IjEiIG9wYWNpdHk9Ii4xIi8+PHBhdGggZD0iTTAgNTBoMTAwIiBzdHJva2U9IiNkMWQ3ZGIiIHN0cm9rZS13aWR0aD0iMSIgb3BhY2l0eT0iLjEiLz48L3BhdHRlcm4+PC9kZWZzPjxyZWN0IHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiIGZpbGw9InVybCgjYSkiLz48L3N2Zz4=');
      opacity: 0.4;
      pointer-events: none;
    }

    .chat-header {
      background: #f5f5f5;
      padding: 12px 20px;
      border-bottom: 1px solid #e0e0e0;
      display: flex;
      align-items: center;
      gap: 12px;
      position: relative;
      z-index: 1;
    }

    .chat-header-avatar {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-weight: 600;
      font-size: 16px;
    }

    .chat-header-info h3 {
      margin: 0;
      font-size: 16px;
      font-weight: 500;
      color: #111;
    }

    .chat-header-info p {
      margin: 2px 0 0 0;
      font-size: 13px;
      color: #667781;
    }

    .messages-container {
      flex: 1;
      overflow-y: auto;
      padding: 20px;
      position: relative;
      z-index: 1;
    }

    .message-date-divider {
      text-align: center;
      margin: 20px 0;
    }

    .message-date-divider span {
      background: rgba(255, 255, 255, 0.9);
      padding: 6px 12px;
      border-radius: 8px;
      font-size: 12px;
      color: #667781;
      box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    .message-item {
      margin-bottom: 12px;
      display: flex;
      gap: 8px;
    }

    .message-item.client {
      justify-content: flex-start;
    }

    .message-item.seller {
      justify-content: flex-end;
    }

    .message-bubble {
      max-width: 65%;
      padding: 8px 12px;
      border-radius: 8px;
      position: relative;
      box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    .message-item.client .message-bubble {
      background: white;
      border-top-left-radius: 0;
    }

    .message-item.seller .message-bubble {
      background: #d9fdd3;
      border-top-right-radius: 0;
    }

    .message-sender {
      font-weight: 600;
      font-size: 13px;
      color: #075e54;
      margin-bottom: 4px;
    }

    .message-text {
      color: #111;
      line-height: 1.4;
      font-size: 14px;
      white-space: pre-wrap;
      word-wrap: break-word;
    }

    .message-time {
      font-size: 11px;
      color: #667781;
      text-align: right;
      margin-top: 4px;
    }

    .empty-chat {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      height: 100%;
      color: #667781;
      position: relative;
      z-index: 1;
    }

    .empty-chat svg {
      width: 120px;
      height: 120px;
      margin-bottom: 24px;
      opacity: 0.3;
    }

    .empty-chat h3 {
      margin: 0 0 8px 0;
      font-size: 24px;
      font-weight: 400;
    }

    .empty-chat p {
      margin: 0;
      font-size: 14px;
    }

    /* Reply Section */
    .reply-section {
      background: #f5f5f5;
      padding: 12px 20px;
      border-top: 1px solid #e0e0e0;
      display: flex;
      gap: 12px;
      align-items: center;
      position: relative;
      z-index: 1;
    }

    .reply-section input {
      flex: 1;
      padding: 12px 16px;
      border: 1px solid #ddd;
      border-radius: 24px;
      font-size: 15px;
      background: white;
      transition: border-color 0.2s;
    }

    .reply-section input:focus {
      outline: none;
      border-color: #075e54;
    }

    .reply-section input:disabled {
      background: #f0f0f0;
      cursor: not-allowed;
    }

    .btn-send {
      padding: 12px 24px;
      background: #075e54;
      color: white;
      border: none;
      border-radius: 24px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.2s;
      font-size: 14px;
    }

    .btn-send:hover:not(:disabled) {
      background: #064e47;
    }

    .btn-send:disabled {
      opacity: 0.5;
      cursor: not-allowed;
    }

    .status-badge {
      position: fixed;
      bottom: 20px;
      right: 20px;
      padding: 12px 20px;
      background: rgba(0, 0, 0, 0.8);
      color: white;
      border-radius: 8px;
      font-size: 13px;
      z-index: 1000;
      transition: all 0.3s;
      display: none;
    }

    .status-badge.warning {
      background: rgba(220, 38, 38, 0.9);
    }

    .status-badge.show {
      display: block;
    }

    /* Scrollbar Styling */
    .contacts-list::-webkit-scrollbar,
    .messages-container::-webkit-scrollbar {
      width: 6px;
    }

    .contacts-list::-webkit-scrollbar-track,
    .messages-container::-webkit-scrollbar-track {
      background: transparent;
    }

    .contacts-list::-webkit-scrollbar-thumb,
    .messages-container::-webkit-scrollbar-thumb {
      background: rgba(0, 0, 0, 0.2);
      border-radius: 3px;
    }

    .contacts-list::-webkit-scrollbar-thumb:hover,
    .messages-container::-webkit-scrollbar-thumb:hover {
      background: rgba(0, 0, 0, 0.3);
    }

    @media (max-width: 768px) {
      .contacts-sidebar {
        width: 100%;
        display: none;
      }

      .contacts-sidebar.mobile-show {
        display: flex;
      }

      .chat-panel {
        display: none;
      }

      .chat-panel.mobile-show {
        display: flex;
      }
    }
  </style>
</head>

<body>
  <div class="admin-container">
    <header class="admin-header">
      <h1>💬 My Messages</h1>
      <div class="auth-area" id="authArea">
        <span>Signed in as <strong><?php echo htmlspecialchars($username); ?></strong></span>
        <a href="1.php" class="btn btn-primary">← Back to Marketplace</a>
        <a href="logout.php" class="btn btn-secondary">Logout</a>
      </div>
      <div id="chatStatusArea" style="display:flex;align-items:center;gap:10px;margin-left:15px;"></div>
    </header>

    <main class="admin-main">
      <!-- Left Sidebar: Contacts List -->
      <aside class="contacts-sidebar">
        <div class="sidebar-header">
          <h2>Conversations</h2>
        </div>
        <div class="contacts-list" id="contactsList">
          <div class="empty-contacts">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
              </path>
            </svg>
            <p>No conversations yet</p>
          </div>
        </div>
      </aside>

      <!-- Right Panel: Chat Area -->
      <section class="chat-panel">
        <div class="empty-chat" id="emptyChat">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
            </path>
          </svg>
          <h3>Welcome to Your Messages</h3>
          <p>Select a conversation from the sidebar to start messaging</p>
        </div>

        <div class="chat-header" id="chatHeader" style="display: none;">
          <div class="chat-header-avatar" id="currentAvatar">J</div>
          <div class="chat-header-info">
            <h3 id="currentChatName">John Buyer</h3>
            <p id="currentChatStatus">Click to view contact info</p>
          </div>
        </div>

        <div class="messages-container" id="messages" style="display: none;">
          <!-- Messages will be inserted here -->
        </div>

        <div class="reply-section" id="replySection" style="display: none;">
          <input id="replyInput" type="text" placeholder="Type a message..." maxlength="1000">
          <button id="sendBtn" class="btn-send">Send</button>
        </div>
      </section>
    </main>
  </div>

  <div id="statusBanner" class="status-badge"></div>

  <!-- Pass PHP session data to JavaScript -->
  <script>
    // Session data from PHP
    window.SELLER_USER_ID = <?php echo $userId; ?>;
    window.SELLER_USERNAME = "<?php echo addslashes($username); ?>";
  </script>

  <script type="module" src="admin.js"></script>
</body>

</html>
