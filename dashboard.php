<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: landing-page.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Dashboard</title>
  <!-- External CSS -->
  <link rel="stylesheet" href="dashboard.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="weather-notify.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="guides-soil.css?v=<?php echo time(); ?>">
</head>
<body>
  <div class="topbar">
    <div class="topbar-left">
      <button class="menu-toggle" onclick="toggleSidebar()">☰</button>
    </div>
    <div class="topbar-right">
  <!-- Notification Bell -->
  <div class="notification-dropdown">
    <span onclick="toggleNotifications()" class="notification-bell">
  🔔 <span id="notification-count" class="badge"></span>
</span>
    <div class="notification-menu" id="notification-menu">
      <h4>Notifications</h4>
      <ul id="notification-list">
        <!-- Notifications will be injected here -->
      </ul>
    </div>
  </div>

  <!-- Profile -->
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

  <div class="dashboard">
  <div class="sidebar" id="sidebar">
    <a href="chatbot-dashboard.php">🤖 Chatbot</a>
    <div class="sidebar">
    <a href="#" onclick="loadSection('user-market-data.php')">📈 Market Data</a>
    <a href="#" onclick="loadSection('guides.php')">📘 Farming Guides</a>
    <a href="#" onclick="loadSection('pest.php')">🐛 Pest Control Guides</a>
    <a href="#" onclick="loadSection('guides-soil.php')">🌱 Soil Analysis</a>
    <a href="#" onclick="loadSection('seeker.php')">🔎 Roboflow Seeker</a>
    <a href="#" onclick="loadSection('weather.php')">🌤 Weather Forecast</a>
    <a href="#" onclick="loadSection('weather-notify.php')">📢 Weather Notify</a>
  </div>
      </div>

  <div class="content" id="dashboard-content">
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['fullname']); ?>!</h2>
    <p>Select an option from the sidebar.</p>
  </div>
</div>
  <!-- External JS -->
  <script src="dashboard.js?v=<?php echo time(); ?>" defer></script>
<script src="seeker.js"></script>
<script src="weather-notify.js"></script>
<script src="guides-soil.js"></script>
</body>
</html>