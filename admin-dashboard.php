<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
  header("Location: landing-page.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="admin-dashboard.css">
  <link rel="stylesheet" href="admin-guides-pest.css">
  <link rel="stylesheet" href="admin-guides-farm.css">
  <script src="admin-guides-farm.js"></script>
  <script src="admin-dashboard.js?v=<?php echo time(); ?>" defer></script>
  <script src="admin-guides-pest.js?v=<?php echo time(); ?>" defer></script>
</head>
<body>

  <div class="topbar">
    <div>Welcome, <?php echo htmlspecialchars($_SESSION['admin_name']); ?></div>
    <button class="logout" onclick="logout()">Log Out</button>
  </div>

  <div class="dashboard">
    <div class="sidebar">
      <a href="#" onclick="loadPage('admin-users-admin.php')">👥 User & Admin Management</a>
      <a href="#" onclick="toggleDropdown()">📚 Guides Management ▾</a>
      <a href="#" onclick="loadPage('admin-guides-pest.php')">🐛 Pest Guides</a>
      <a href="#" onclick="loadPage('admin-guides-farm.php')">🌾 Farm Guides</a>
    </div>

    <div class="content" id="admin-content">
      <div class="welcome-box">
        <h2>Welcome to the Admin Dashboard</h2>
        <p>Select an option from the sidebar to get started.</p>
      </div>
    </div>
  </div>

  <div id="toast" class="toast"></div>
</body>
</html>