<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: landing-page.php");
    exit;
}
require_once "db.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Chatbot</title>
  <link rel="stylesheet" href="dashboard.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="chatbot.css?v=<?php echo time(); ?>">
</head>
<body>
  <div class="topbar">
    <div class="topbar-left">
      <a href="dashboard.php" class="back-btn">⬅ Back to Dashboard</a>
    </div>
    <div class="topbar-right">
      <div class="profile-dropdown">
        <span onclick="toggleProfile()" class="profile-name">
          <?php echo htmlspecialchars($_SESSION['fullname']); ?> ▼
        </span>
        <div class="profile-menu" id="profile-menu">
          <p><strong>Name:</strong> <?php echo htmlspecialchars($_SESSION['fullname']); ?></p>
          <p><strong>Email:</strong> <?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : 'Not available'; ?></p>
          <a href="edit-profile.php">Edit Profile</a>
        </div>
      </div>
      <button class="logout" onclick="logout()">Log Out</button>
    </div>
  </div>

  <div class="chatbot-dashboard">
    <!-- Sidebar -->
    <div class="chat-sidebar">
      <div class="chat-search">
        <input type="text" id="chat-search" placeholder="Search chats...">
      </div>

      <h3>Your Chats</h3>
      <ul id="chat-list">
        <?php
        $stmt = $conn->prepare("SELECT id, title FROM conversations WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($conv = $result->fetch_assoc()) {
          $active = (isset($_GET['conv_id']) && $_GET['conv_id'] == $conv['id']) ? "active" : "";
          echo '<li class="'.$active.'">
                  <a href="chatbot-dashboard.php?conv_id='.$conv['id'].'">'.htmlspecialchars($conv['title']).'</a>
                  <div class="chat-options">
                    <button class="chat-options-btn">⋮</button>
                    <div class="chat-options-menu">
                      <button class="rename-chat" data-id="'.$conv['id'].'">Rename</button>
                      <button class="pin-chat" data-id="'.$conv['id'].'">Pin</button>
                      <button class="delete-chat" data-id="'.$conv['id'].'">Delete</button>
                    </div>
                  </div>
                </li>';
        }
        ?>
      </ul>
      <button onclick="newChat()">+ New Chat</button>
    </div>

    <!-- Main Chat Area -->
    <div class="chat-main">
      <!-- Splash screen -->
      <div id="chat-splash" class="chat-splash">
        <img src="LOGO.jpg" alt="LGU3 Logo" class="splash-logo">
        <h1 class="splash-title">LGU3</h1>
      </div>

      <div id="chat-messages" class="chat-messages">
  <?php
  if (isset($_GET['conv_id'])) {
      $stmt = $conn->prepare("SELECT role, content, image_path FROM messages WHERE conversation_id = ? ORDER BY created_at ASC");
      $stmt->bind_param("i", $_GET['conv_id']);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result->num_rows === 0) {
          // Show splash if conversation exists but has no messages yet
          echo '<div id="chat-splash" class="chat-splash">
                  <img src="LOGO.jpg" alt="LGU3 Logo" class="splash-logo">
                  <h1 class="splash-title">LGU3</h1>
                  <p>Select or start a chat.</p>
                </div>';
      } else {
          while ($msg = $result->fetch_assoc()) {
              $class = $msg['role'] === 'user' ? 'user-message' : 'bot-message';
              echo '<div class="message '.$class.'">'.htmlspecialchars($msg['content']).'</div>';
              if ($msg['image_path']) {
                  echo '<img src="'.htmlspecialchars($msg['image_path']).'" class="'.$class.'">';
              }
          }
      }
  } else {
      // No conv_id at all → show splash
      echo '<div id="chat-splash" class="chat-splash">
              <img src="LOGO.jpg" alt="LGU3 Logo" class="splash-logo">
              <h1 class="splash-title">LGU3</h1>
              <p>Select or start a chat.</p>
            </div>';
  }
  ?>
</div>

      <!-- Chat input -->
<form id="chat-form" enctype="multipart/form-data">
  <input type="hidden" name="conv_id" value="<?php echo $_GET['conv_id'] ?? ''; ?>">

  <!-- File upload styled as icon -->
  <label for="file-upload" class="file-upload">+</label>
  <input id="file-upload" type="file" name="image">

  <!-- Preview area -->
  <div id="file-preview"></div>

  <input type="text" name="prompt" placeholder="Type a message..." required>
  <button type="submit">Send</button>
</form>
    </div>
  </div>
    <!-- Rename Modal -->
<div id="rename-modal" class="modal-overlay">
  <div class="modal">
    <h2>Rename Conversation</h2>
    <input type="text" id="rename-input" placeholder="New name...">
    <div class="modal-buttons">
      <button class="confirm" id="rename-confirm">Save</button>
      <button class="cancel" id="rename-cancel">Cancel</button>
    </div>
  </div>
</div>

<!-- Delete Modal -->
<div id="delete-modal" class="modal-overlay">
  <div class="modal">
    <h2>Delete Conversation?</h2>
    <p>This action cannot be undone.</p>
    <div class="modal-buttons">
      <button class="confirm" id="delete-confirm">Delete</button>
      <button class="cancel" id="delete-cancel">Cancel</button>
    </div>
  </div>
</div>

  <script src="dashboard.js?v=<?php echo time(); ?>" defer></script>
  <script src="chatbot.js?v=<?php echo time(); ?>" defer></script>
</body>
</html>