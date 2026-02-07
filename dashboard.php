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
</head>
<body>
  <div class="topbar">
    <div class="topbar-left">
      <button class="menu-toggle" onclick="toggleSidebar()">☰</button>
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

  <div class="dashboard">
    <div class="sidebar" id="sidebar">
      <a href="chatbot-dashboard.php">🤖 Chatbot</a>
      <div class="sidebar">
<a href="#" onclick="loadSection('user-market-data.php')">📈 Market Data</a>
</div>
      <a href="#" onclick="loadSection('guides.php')">📘 Farming Guides</a>
      <a href="#" onclick="loadSection('pest.php')">🐛 Pest Control Guides</a>
      <a href="#" onclick="loadSection('weather.php')">🌤 Weather Forecast</a>
    </div>
      <div class="content" id="dashboard-content">
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['fullname']); ?>!</h2>
        <p>Select an option from the sidebar.</p>
      </div>
  </div>
  <!-- External JS -->
  <script src="dashboard.js?v=<?php echo time(); ?>" defer></script>
</body>
</html>