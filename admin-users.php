<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
  echo "<p style='color:red;'>Access denied.</p>";
  exit;
}

include 'db.php'; 
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
?>
<link rel="stylesheet" href="admin-dashboard.css">

<h2>👥 User Management</h2>
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
          
          // Calculate inactivity
          $lastActive = isset($row['last_active']) ? strtotime($row['last_active']) : strtotime($row['created_at']);
          $inactiveDays = (time() - $lastActive) / (60 * 60 * 24);
          $status = $inactiveDays > 7 ? "⚠️ Inactive > 1 week" : "✅ Active";

          echo "<tr>
                  <td>" . htmlspecialchars($row['fullname']) . "</td>
                  <td>" . htmlspecialchars($row['email']) . "</td>
                  <td>" . htmlspecialchars($row['phone']) . "</td>
                  <td>" . htmlspecialchars($row['address']) . "</td>
                  <td>" . $createdDate . "</td>
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
        echo "<tr><td colspan='7' style='text-align:center;'>🚫 No users found</td></tr>";
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
        <tr>
          <th>Full Name:</th>
          <td><input type="text" id="edit-name" name="fullname" required></td>
        </tr>
        <tr>
          <th>Email:</th>
          <td><input type="email" id="edit-email" name="email" required></td>
        </tr>
        <tr>
          <th>Phone:</th>
          <td><input type="text" id="edit-phone" name="phone"></td>
        </tr>
        <tr>
          <th>Address:</th>
          <td><input type="text" id="edit-address" name="address"></td>
        </tr>
      </table>
      <input type="hidden" id="edit-id" name="id">
      <div class="modal-actions">
        <button type="button" onclick="closeModal('editModal')" class="btn btn-danger">Cancel</button>
        <button type="submit" class="btn btn-warning">Save</button>
      </div>
    </form>
  </div>
</div>

<script src="admin-dashboard.js"></script>