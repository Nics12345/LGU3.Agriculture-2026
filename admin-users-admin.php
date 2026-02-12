<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
  header('Content-Type: application/json');
  echo json_encode(["status" => "error", "message" => "Access denied."]);
  exit;
}

include 'db.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// ✅ Handle AJAX: Fetch Admin List
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

// ✅ Handle AJAX: Create Admin
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['fullname'], $_POST['email'], $_POST['password']) && !isset($_POST['id'])) {
  header('Content-Type: application/json');

  try {
    $fullname = trim($_POST['fullname']);
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);

    if ($fullname === '' || $email === '' || $password === '') {
      echo json_encode(["status" => "error", "message" => "All fields are required."]);
      exit;
    }

    // Check if email exists
    $check = $conn->prepare("SELECT id FROM admins WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();
    if ($check->num_rows > 0) {
      echo json_encode(["status" => "error", "field" => "email", "message" => "Email already exists."]);
      exit;
    }
    $check->close();

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO admins (fullname, email, password, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("sss", $fullname, $email, $hashedPassword);
    $stmt->execute();
    $stmt->close();

    echo json_encode(["status" => "success", "message" => "✅ New admin account created successfully!"]);
  } catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => "Server error: " . $e->getMessage()]);
  }
  exit;
}

// ✅ Handle AJAX: Update User
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
  header('Content-Type: application/json');

  try {
    $id       = intval($_POST['id']);
    $fullname = trim($_POST['fullname'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $phone    = trim($_POST['phone'] ?? '');
    $address  = trim($_POST['address'] ?? '');

    if ($fullname === '' || $email === '') {
      echo json_encode(["status" => "error", "message" => "Full name and email are required."]);
      exit;
    }

    // Update user details
    $stmt = $conn->prepare("UPDATE users SET fullname=?, email=?, phone=?, address=? WHERE id=?");
    $stmt->bind_param("ssssi", $fullname, $email, $phone, $address, $id);
    $stmt->execute();
    $stmt->close();

    // ✅ Update last_active timestamp
    $stmt = $conn->prepare("UPDATE users SET last_active = NOW() WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    echo json_encode(["status" => "success", "message" => "✅ User updated successfully!"]);
  } catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => "Server error: " . $e->getMessage()]);
  }
  exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User & Admin Management</title>
  <link rel="stylesheet" href="admin-dashboard.css">
  <script src="admin-dashboard.js?v=<?php echo time(); ?>" defer></script>
</head>
<body>

<h2>⚙️ Management Panel</h2>
<p>Switch between tabs to manage users or admins.</p>

<!-- Tab Navigation -->
<div class="tab-nav">
  <button class="tab-btn active" onclick="openTab('users-tab')">👥 Users</button>
  <button class="tab-btn" onclick="openTab('admins-tab')">🛠 Admins</button>
</div>

<!-- Users Tab -->
<div id="users-tab" class="tab-content active">
  <h3>👥 User Management</h3>
  <p>View and edit registered users. Users inactive for more than 1 week will be flagged.</p>

  <div class="user-table-wrapper">
    <table id="users-table">
      <thead>
            <tr>
                <th>👤 Full Name</th>
                <th>📧 Email</th>
                <th>📞 Phone</th>
                <th>🏠 Address</th>
                <th>📅 Created</th>
                <th>🕒 Last Active</th>
                <th>⏳ Status</th>
                <th>⚙️ Actions</th>
            </tr>
      </thead>
      <tbody>
        <?php
        $result = $conn->query("SELECT id, fullname, email, phone, address, created_at, last_active FROM users ORDER BY created_at DESC");
if ($result && $result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $createdDate = date("F j, Y", strtotime($row['created_at']));
    $lastActiveDisplay = $row['last_active'] ? date("F j, Y g:i A", strtotime($row['last_active'])) : "—";

    $lastActive = $row['last_active'] ? strtotime($row['last_active']) : strtotime($row['created_at']);
    $inactiveDays = (time() - $lastActive) / (60 * 60 * 24);
    $status = $inactiveDays > 7 ? "⚠️ Inactive > 1 week" : "✅ Active";

    echo "<tr>
            <td>" . htmlspecialchars($row['fullname']) . "</td>
            <td>" . htmlspecialchars($row['email']) . "</td>
            <td>" . htmlspecialchars($row['phone']) . "</td>
            <td>" . htmlspecialchars($row['address']) . "</td>
            <td>" . $createdDate . "</td>
            <td>" . $lastActiveDisplay . "</td>
            <td>" . $status . "</td>
            <td>
              <button class='btn btn-warning edit-btn'
                      data-id='" . $row['id'] . "'
                      data-name='" . htmlspecialchars($row['fullname']) . "'
                      data-email='" . htmlspecialchars($row['email']) . "'
                      data-phone='" . htmlspecialchars($row['phone']) . "'
                      data-address='" . htmlspecialchars($row['address']) . "'>✏️ Edit</button>
            </td>
          </tr>";
  }
} else {
  echo "<tr><td colspan='8' style='text-align:center;'>🚫 No users found</td></tr>";
}
        ?>
      </tbody>
    </table>
  </div>

  <!-- Edit Modal -->
  <div id="editModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">✏️ Edit User</div>
      <form id="editForm" class="form-table">
        <table>
          <tr><th>Full Name:</th><td><input type="text" id="edit-name" name="fullname" required></td></tr>
          <tr><th>Email:</th><td><input type="email" id="edit-email" name="email" required></td></tr>
          <tr><th>Phone:</th><td><input type="text" id="edit-phone" name="phone"></td></tr>
          <tr><th>Address:</th><td><input type="text" id="edit-address" name="address"></td></tr>
        </table>
        <input type="hidden" id="edit-id" name="id">
        <div class="modal-actions">
          <button type="button" onclick="closeModal('editModal')" class="btn btn-danger">Cancel</button>
          <button type="submit" class="btn btn-warning">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Admins Tab -->
<div id="admins-tab" class="tab-content">
  <h3>🛠 Admin Creation</h3>
  <p>View current admins and create new ones.</p>

  <!-- Admin List -->
  <div class="admin-list-container">
    <h4>👥 Current Admin Accounts</h4>
    <table id="admins-table">
      <thead>
        <tr><th>Full Name</th><th>Email</th><th>Date Created</th></tr>
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
    <h4>➕ Create New Admin</h4>
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
</div>

<div id="toast" class="toast"></div>

</body>
</html>