<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "<p style='color:red;'>Access denied.</p>";
    exit;
}
include 'db.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Handle auto severe weather alerts pushed from JS
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title'])) {
    $title = trim($_POST['title']);
    $description = trim($_POST['description'] ?? '');

    // Insert into weather_notifications table
    $stmt = $conn->prepare("INSERT INTO weather_notifications (title, description) VALUES (?, ?)");
    $stmt->bind_param("ss", $title, $description);
    $stmt->execute();
    $stmt->close();

    // Also insert into global notifications table so bell dropdown shows it
    $msg  = $title . " - " . $description;
    $link = "weather-notify.php";
    $nstmt = $conn->prepare("INSERT INTO notifications (message, link) VALUES (?, ?)");
    $nstmt->bind_param("ss", $msg, $link);
    $nstmt->execute();
    $nstmt->close();

    echo json_encode(["status"=>"success","message"=>"Weather notification added"]);
    exit;
}
?>

<link rel="stylesheet" href="weather-notify.css">

<div class="notify-container">
  <h2>📢 Local Weather Forecast</h2>

  <!-- Auto Weather Forecast -->
  <div id="weather-forecast">
    <p>Loading local weather forecast...</p>
  </div>

  <!-- Existing Severe Weather Alerts -->
<div class="notify-cards">
  <?php
  $notifs = $conn->query("SELECT id,title,description FROM weather_notifications ORDER BY created_at DESC LIMIT 10");
  if ($notifs->num_rows > 0) {
      while ($notif = $notifs->fetch_assoc()) {
          $isSevere = strpos($notif['title'], 'Severe Weather Alert') !== false;
          $class = $isSevere ? 'notify-card severe' : 'notify-card';
          echo "<div class='{$class}'>";
          echo "<h3>".htmlspecialchars($notif['title'])."</h3>";
          echo "<p>".nl2br(htmlspecialchars($notif['description']))."</p>";
          echo "</div>";
      }
  } else {
      echo "<p class='no-notify'>🚫 No severe weather alerts yet.</p>";
  }
  ?>
</div>
</div>

<script src="weather-notify.js"></script>
