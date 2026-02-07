<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: landing-page.php");
    exit;
}

include 'db.php'; 
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$user_id = $_SESSION['user_id'];
$message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname']);
    $email    = trim($_POST['email']);
    $phone    = trim($_POST['phone']);
    $address  = trim($_POST['address']);

    if (!empty($fullname) && !empty($email)) {
        $stmt = $conn->prepare("UPDATE users SET fullname=?, email=?, phone=?, address=? WHERE id=?");
        $stmt->bind_param("ssssi", $fullname, $email, $phone, $address, $user_id);
        $stmt->execute();

        $_SESSION['fullname'] = $fullname;
        $_SESSION['email']    = $email;

        header("Location: dashboard.php?updated=1");
        exit;
    } else {
        $message = "Full name and email are required.";
    }
}

$stmt = $conn->prepare("SELECT fullname, email, phone, address FROM users WHERE id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Profile</title>
  <link rel="stylesheet" href="dashboard.css?v=<?php echo time(); ?>">
  <style>
    .profile-card {
      max-width: 600px;
      margin: 40px auto;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 6px 18px rgba(0,0,0,0.1);
      padding: 30px;
      animation: fadeIn 0.4s ease;
    }
    .profile-card h2 {
      margin-top: 0;
      color: #4e73df;
      text-align: center;
      font-size: 24px;
      margin-bottom: 20px;
    }
    .profile-card table {
      width: 100%;
      border-collapse: collapse;
    }
    .profile-card th {
      text-align: right;
      padding: 12px;
      width: 150px;
      color: #555;
      font-weight: bold;
    }
    .profile-card td {
      padding: 12px;
    }
    .profile-card input {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 14px;
    }
    .profile-actions {
      text-align: center;
      margin-top: 25px;
    }
    .btn {
      padding: 10px 20px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-weight: bold;
      margin: 0 10px;
      transition: background 0.3s ease;
    }
    .btn-warning { background: #f1c40f; color: #333; }
    .btn-warning:hover { background: #d4ac0d; }
    .btn-danger { background: #e74c3c; color: #fff; }
    .btn-danger:hover { background: #c0392b; }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>
  <div class="topbar">
    <div class="topbar-left">
      <a href="dashboard.php" class="btn btn-warning">⬅ Back to Dashboard</a>
    </div>
    <div class="topbar-right">
      <button class="logout" onclick="window.location.href='logout.php'">Log Out</button>
    </div>
  </div>

  <div class="profile-card">
    <h2>✏️ Edit Profile</h2>
    <?php if ($message): ?>
      <p style="color: red; text-align:center;"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <form method="POST">
      <table>
        <tr>
          <th>Full Name:</th>
          <td><input type="text" name="fullname" value="<?php echo htmlspecialchars($user['fullname']); ?>" required></td>
        </tr>
        <tr>
          <th>Email:</th>
          <td><input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required></td>
        </tr>
        <tr>
          <th>Phone:</th>
          <td><input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>"></td>
        </tr>
        <tr>
          <th>Address:</th>
          <td><input type="text" name="address" value="<?php echo htmlspecialchars($user['address']); ?>"></td>
        </tr>
      </table>
      <div class="profile-actions">
        <button type="submit" class="btn btn-warning">Save Changes</button>
        <a href="dashboard.php" class="btn btn-danger">Cancel</a>
      </div>
    </form>
  </div>
</body>
</html>