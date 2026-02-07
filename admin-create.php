<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Content-Type: application/json');
    echo json_encode(["status" => "error", "message" => "Access denied."]);
    exit;
}

include 'db.php'; // $conn = new mysqli("localhost","root","","lgu3_platform");
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Handle AJAX request to fetch admin list
if (isset($_GET['list']) && $_GET['list'] === '1') {
    header('Content-Type: application/json');
    $rows = [];
    $result = $conn->query("SELECT fullname, email, created_at FROM admins ORDER BY created_at DESC");
    while ($row = $result->fetch_assoc()) {
        $rows[] = [
            "fullname" => htmlspecialchars($row['fullname']),
            "email" => htmlspecialchars($row['email']),
            "created_at" => date("F j, Y", strtotime($row['created_at']))
        ];
    }
    echo json_encode($rows);
    exit;
}

// Handle form submission (AJAX POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    try {
        $fullname = trim($_POST['fullname'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if ($fullname === '' || $email === '' || $password === '') {
            echo json_encode([
                "status" => "error",
                "field" => "",
                "message" => "All fields are required."
            ]);
            exit;
        }

        // Check if email already exists
        $check = $conn->prepare("SELECT id FROM admins WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();
        if ($check->num_rows > 0) {
            echo json_encode([
                "status" => "error",
                "field" => "email",
                "message" => "Email already exists."
            ]);
            exit;
        }
        $check->close();

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO admins (fullname, email, password, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("sss", $fullname, $email, $hashedPassword);
        $stmt->execute();
        $stmt->close();

        echo json_encode([
            "status" => "success",
            "message" => "✅ New admin account created successfully!"
        ]);
    } catch (Exception $e) {
        echo json_encode([
            "status" => "error",
            "field" => "",
            "message" => "Server error: " . $e->getMessage()
        ]);
    }

    exit; // stop here, no HTML after JSON
}

// If not POST, render the HTML page
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Create Admin</title>
  <link rel="stylesheet" href="admin-dashboard.css">
  <script src="admin-dashboard.js?v=<?php echo time(); ?>" defer></script>
</head>
<body>
<div class="center-wrapper">
  <!-- Admin List Section -->
  <div class="admin-list-container">
    <h2>👥 Current Admin Accounts</h2>
    <table id="admins-table">
      <thead>
        <tr>
          <th>Full Name</th>
          <th>Email</th>
          <th>Date Created</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $result = $conn->query("SELECT fullname, email, created_at FROM admins ORDER BY created_at DESC");
        while ($row = $result->fetch_assoc()) {
            $date = date("F j, Y", strtotime($row['created_at']));
            echo "<tr>
                    <td>" . htmlspecialchars($row['fullname']) . "</td>
                    <td>" . htmlspecialchars($row['email']) . "</td>
                    <td>" . $date . "</td>
                  </tr>";
        }
        ?>
      </tbody>
    </table>
  </div>

<!-- Admin Creation Form -->
<div class="admin-form-container">
  <h2>🛠 Create New Admin</h2>
  <p>Fill out the form below to add a new admin account.</p>

  <form id="create-admin-form" method="post">
  <table class="form-table">
    <tr>
      <th>Full Name:</th>
      <td>
        <input type="text" name="fullname" id="fullname" required>
        <div class="error-message" id="fullname-error"></div>
      </td>
    </tr>
    <tr>
      <th>Email:</th>
      <td>
        <input type="email" name="email" id="email" required>
        <div class="error-message" id="email-error"></div>
      </td>
    </tr>
    <tr>
      <th>Password:</th>
      <td>
        <input type="password" name="password" id="password" required>
        <div class="error-message" id="password-error"></div>
      </td>
    </tr>
    <tr>
      <td colspan="2" style="text-align:center;">
        <button type="submit">Create Admin</button>
      </td>
    </tr>
  </table>
</form>
</div>

<div id="toast" class="toast"></div>
</body>
</html>